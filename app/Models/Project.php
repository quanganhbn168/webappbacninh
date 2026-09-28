<?php

namespace App\Models;

use App\Traits\HasCuratorGallery;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Project extends Model
{
    use HasCuratorGallery;
    use HasSlug;

    protected $fillable = [
        'project_category_id',
        'code',
        'title',
        'slug',
        'image_id',
        'gallery',
        'excerpt',
        'link',
        'industry',
        'year',
        'client',
        'duration',
        'website_type',
        'challenge',
        'solution',
        'results',
        'deliverables',
        'technologies',
        'meta_title',
        'meta_description',
        'is_featured',
        'is_active',
        'order',
    ];

    protected $casts = [
        'gallery' => 'array',
        'results' => 'array',
        'deliverables' => 'array',
        'technologies' => 'array',
        'year' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForCatalog(Builder $query): Builder
    {
        return $query->active()->with(['category', 'image'])->orderByDesc('is_featured')->orderBy('order')->orderBy('id');
    }

    /**
     * Other active projects, same group first, then featured ones.
     *
     * @return Collection<int, self>
     */
    public function related(int $limit = 3): Collection
    {
        return self::query()->forCatalog()->whereKeyNot($this->getKey())->get()
            ->sortByDesc(fn (self $project): int => ($project->project_category_id === $this->project_category_id ? 2 : 0) + (int) $project->is_featured)
            ->take($limit)
            ->values();
    }

    protected function url(): Attribute
    {
        return Attribute::get(fn (): string => route('projects.show', $this->slug));
    }

    protected function categorySlug(): Attribute
    {
        return Attribute::get(fn (): string => $this->category?->slug ?? 'du-an');
    }

    protected function categoryLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->category?->name ?? 'Dự án');
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->image?->url ?? frontend_asset('assets/images/project-corporate.webp'));
    }
}
