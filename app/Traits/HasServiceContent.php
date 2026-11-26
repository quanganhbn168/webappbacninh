<?php

namespace App\Traits;

use App\Domain\Media\Actions\BuildResponsiveImage;
use App\Models\PricingPlan;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Shared by website services and operation services: Curator images,
 * display fallbacks and the ordering scopes used by the public site.
 */
trait HasServiceContent
{
    public static function bootHasServiceContent(): void
    {
        // The plans stay in the price table: other services may use them.
        static::deleted(fn (self $service) => $service->pricingPlans()->detach());
    }

    /** The plans of the price table this service picked. */
    public function pricingPlans(): MorphToMany
    {
        return $this->morphToMany(PricingPlan::class, 'priceable');
    }

    /** The plans shown on the service page, in price table order; the first one gives "Giá từ". */
    public function plans(): MorphToMany
    {
        return $this->pricingPlans()->where('pricing_plans.is_active', true)
            ->orderBy('pricing_plans.order')->orderBy('pricing_plans.id');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function secondaryImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'secondary_image_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('id');
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->image?->url ?? asset('images/hero-industrial.webp'));
    }

    protected function secondaryImageUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->secondaryImage?->url ?? $this->image_url);
    }

    /** The second picture of the page in sizes for its frame; the cover when there is none. */
    protected function secondaryCover(): Attribute
    {
        return Attribute::get(fn (): array => $this->secondaryImage
            ? app(BuildResponsiveImage::class)->execute($this->secondaryImage, [480, 800, 1200])
            : $this->cover)->shouldCache();
    }

    protected function eyebrowLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->eyebrow ?: mb_strtoupper((string) $this->title));
    }

    protected function iconClass(): Attribute
    {
        return Attribute::get(fn (): string => $this->icon ?: 'layers');
    }

    protected function priceLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->plans->first()?->priceText() ?: 'Liên hệ để tư vấn');
    }

    protected function ctaLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->cta ?: 'Nhận tư vấn');
    }

    protected function needLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->need_value ?: (string) $this->title);
    }
}
