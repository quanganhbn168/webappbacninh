<?php

namespace App\Traits;

use App\Models\PricingPlan;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Shared by website services and operation services: Curator images,
 * display fallbacks and the ordering scopes used by the public site.
 */
trait HasServiceContent
{
    public static function bootHasServiceContent(): void
    {
        // Plans of a deleted service go with it; those also in the shared table stay there.
        static::deleted(function (self $service): void {
            $service->pricingPlans()->where('show_in_table', false)->delete();
            $service->pricingPlans()->update(['priceable_type' => null, 'priceable_id' => null]);
        });
    }

    public function pricingPlans(): MorphMany
    {
        return $this->morphMany(PricingPlan::class, 'priceable');
    }

    /** The plans shown on the service page, the first one giving "Giá từ". */
    public function plans(): MorphMany
    {
        return $this->pricingPlans()->where('is_active', true)->orderBy('order')->orderBy('id');
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
