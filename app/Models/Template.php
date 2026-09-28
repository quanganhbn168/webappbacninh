<?php

namespace App\Models;

use App\Enums\TemplateType;
use App\Traits\HasCuratorGallery;
use App\Traits\HasSlug;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Template extends Model
{
    use HasCuratorGallery;
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'template_category_id',
        'code',
        'name',
        'slug',
        'type',
        'image_id',
        'gallery',
        'demo_url',
        'price',
        'sale_price',
        'year',
        'description',
        'badge',
        'duration',
        'tags',
        'audiences',
        'pages',
        'included_features',
        'customizations',
        'meta_title',
        'meta_description',
        'is_featured',
        'order',
        'is_active',
    ];

    protected $casts = [
        'type' => TemplateType::class,
        'gallery' => 'array',
        'tags' => 'array',
        'audiences' => 'array',
        'pages' => 'array',
        'included_features' => 'array',
        'customizations' => 'array',
        'price' => 'integer',
        'sale_price' => 'integer',
        'year' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TemplateCategory::class, 'template_category_id');
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(ThemeFeature::class, 'template_theme_feature');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('id');
    }

    public function scopeForCatalog(Builder $query): Builder
    {
        return $query->active()->ordered()->with(['category', 'features', 'image']);
    }

    /**
     * Other active templates, same industry first, then featured ones.
     *
     * @return Collection<int, self>
     */
    public function related(int $limit = 3): Collection
    {
        return self::query()->forCatalog()->whereKeyNot($this->getKey())->get()
            ->sortByDesc(fn (self $template): int => ($template->template_category_id === $this->template_category_id ? 2 : 0) + (int) $template->is_featured)
            ->take($limit)
            ->values();
    }

    protected function url(): Attribute
    {
        return Attribute::get(fn (): string => route('themes.show', $this->slug));
    }

    protected function typeLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->type?->label() ?? TemplateType::Service->label());
    }

    protected function industrySlug(): Attribute
    {
        return Attribute::get(fn (): string => $this->category?->slug ?? 'khac');
    }

    protected function industryLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->category?->name ?? 'Khác');
    }

    protected function featureKeys(): Attribute
    {
        return Attribute::get(fn (): array => $this->features->pluck('slug')->all());
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->image?->url ?? frontend_asset('assets/images/project-corporate.webp'));
    }

    /**
     * Sort weight for the "Nổi bật" order in the public catalog.
     */
    protected function featuredScore(): Attribute
    {
        return Attribute::get(fn (): int => ($this->is_featured ? 1000 : 0) - (int) $this->order);
    }
}
