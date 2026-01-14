<?php

namespace App\Models;

use App\Enums\BannerSlot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AdBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slot',
        'image', // LFM path
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
        return $this->image ? asset($this->image) : asset('images/ad-placeholder.jpg');
    }
}
