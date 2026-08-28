<?php

namespace App\Models;

use App\Enums\BannerSlot;
use App\Traits\ImportsLegacyMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AdBanner extends Model implements HasMedia
{
    use HasFactory, ImportsLegacyMedia, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slot',
        'image', // Stored media path
        'link',
        'alt_text',
        'is_active',
        'open_new_tab',
        'order',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'slot' => BannerSlot::class,
        'is_active' => 'boolean',
        'open_new_tab' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Scope to get active banners for a slot.
     */
    public function scopeForSlot(Builder $query, BannerSlot $slot): Builder
    {
        return $query->where('slot', $slot)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>=', now());
            })
            ->orderBy('order');
    }

    /**
     * Get image URL.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->hasMedia('featured')) {
            return $this->getFirstMediaUrl('featured');
        }

        if ($this->image && (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://'))) {
            return $this->image;
        }

        return $this->image ? asset($this->image) : asset('images/ad-placeholder.jpg');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')->singleFile();
    }
}
