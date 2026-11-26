<?php

namespace App\Models;

use App\Domain\Media\Actions\BuildResponsiveImage;
use App\Enums\LandingTemplate;
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
        'demo_layout',
        'demo',
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
        'demo_layout' => LandingTemplate::class,
        'demo' => 'array',
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

    /** The device preview of the demo, or null when the design has none. */
    protected function previewUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->demo_source ? route('themes.demo', $this->slug) : null);
    }

    /** What the preview frame loads: our own demo when a layout is chosen, otherwise the outside demo link. */
    protected function demoSource(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->demo_layout ? route('themes.demo.frame', $this->slug) : ($this->demo_url ?: null));
    }

    /**
     * Resized copies of the cover; the uploaded screenshot can be many megabytes.
     *
     * @return Attribute<array{src: string, srcset: string|null}, never>
     */
    protected function cover(): Attribute
    {
        return Attribute::get(fn (): array => $this->image
            ? app(BuildResponsiveImage::class)->execute($this->image, [480, 800, 1200])
            : ['src' => $this->image_url, 'srcset' => null])->shouldCache();
    }

    /**
     * The detail page slider: the gallery in its saved
     * order, or the cover alone. Each slide has a 3:2 crop for its thumbnail.
     *
     * @return Attribute<array<int, array{src: string, srcset: string|null, thumb: string}>, never>
     */
    protected function slides(): Attribute
    {
        return Attribute::get(function (): array {
            $images = app(BuildResponsiveImage::class);
            $media = $this->gallery_media->isNotEmpty() ? $this->gallery_media : collect([$this->image])->filter();

            if ($media->isEmpty()) {
                return [['src' => $this->image_url, 'srcset' => null, 'thumb' => $this->image_url]];
            }

            return $media->map(fn (Media $image): array => $images->execute($image, [800, 1200, 1600])
                + ['thumb' => $images->execute($image, [240], 3 / 2)['src']])->values()->all();
        })->shouldCache();
    }

    protected function priceLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->effective_price ? money($this->effective_price) : 'Liên hệ');
    }

    /** What the design costs now: the sale price when it really is lower, otherwise the price. Null means "ask us". */
    protected function effectivePrice(): Attribute
    {
        return Attribute::get(fn (): ?int => $this->old_price ? $this->sale_price : ($this->price ?: ($this->sale_price ?: null)));
    }

    /** The price to strike through, only while there is a discount. */
    protected function oldPrice(): Attribute
    {
        return Attribute::get(fn (): ?int => $this->sale_price && $this->price && $this->sale_price < $this->price ? $this->price : null);
    }

    /** The demo as an unsaved landing page, so it renders with the same layout views as a real one. */
    public function demoPage(): ?LandingPage
    {
        if (! $this->demo_layout) {
            return null;
        }

        $sample = $this->demo_layout->sample();
        $page = new LandingPage([
            'slug' => $this->slug,
            'template' => $this->demo_layout,
            'name' => $this->name,
            'noindex' => true,
            'meta_title' => 'Demo '.$this->name,
            'meta_description' => $this->description,
            'price' => (int) data_get($this->demo, 'price', $sample['price']),
            'compare_price' => data_get($this->demo, 'compare_price', $sample['compare_price']),
            'content' => data_get($this->demo, 'content', []),
        ]);
        $page->isDemo = true;
        $page->publicUrl = route('themes.demo.frame', $this->slug);

        return $page;
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
        return Attribute::get(fn (): string => $this->image?->url ?? asset('images/project-corporate.webp'));
    }

    /**
     * Sort weight for the "Nổi bật" order in the public catalog.
     */
    protected function featuredScore(): Attribute
    {
        return Attribute::get(fn (): int => ($this->is_featured ? 1000 : 0) - (int) $this->order);
    }
}
