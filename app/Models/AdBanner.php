<?php

namespace App\Models;

use App\Enums\BannerSlot;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slot',
        'image_id',
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

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    /**
     * Active, scheduled banners for a slot.
     */
    public function scopeForSlot(Builder $query, BannerSlot $slot): Builder
    {
        return $query->where('slot', $slot)
            ->where('is_active', true)
            ->whereNotNull('image_id')
            ->where(fn (Builder $query) => $query->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn (Builder $query) => $query->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->orderBy('order');
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->image?->url ?? asset('images/placeholder.svg'));
    }
}
