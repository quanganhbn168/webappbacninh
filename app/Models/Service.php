<?php

namespace App\Models;

use App\Traits\HasSlug;
use App\Traits\ImportsLegacyMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use HasFactory, HasSlug, ImportsLegacyMedia, InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'icon',
        'description',
        'content',
        'order',
        'is_active',
        'menu_key', 'eyebrow', 'highlight', 'image', 'secondary_image', 'price_from',
        'timeline', 'meta_title', 'meta_description', 'data',
        'service_category_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'data' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->hasMedia('featured')) {
            return $this->getFirstMediaUrl('featured');
        }

        return $this->image ? asset($this->image) : asset('frontend/assets/images/hero-industrial.webp');
    }

    public function getSecondaryImageUrlAttribute(): string
    {
        if ($this->hasMedia('gallery')) {
            return $this->getFirstMediaUrl('gallery');
        }

        return $this->secondary_image ? asset($this->secondary_image) : $this->image_url;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')->singleFile();
        $this->addMediaCollection('gallery');
    }
}
