<?php

namespace App\Traits;

use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Shared by website services and operation services: Curator images,
 * display fallbacks and the ordering scopes used by the public site.
 */
trait HasServiceContent
{
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
        return Attribute::get(fn (): string => $this->image?->url ?? frontend_asset('assets/images/hero-industrial.webp'));
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
        return Attribute::get(fn (): string => $this->icon ?: 'fa-solid fa-layer-group');
    }

    protected function priceLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->price_from ?: 'Liên hệ để tư vấn');
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
