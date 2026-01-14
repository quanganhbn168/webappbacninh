<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Project extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'link',
        'category',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->orderBy('order');
    }

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')
            ->singleFile();
    }

    /**
     * Register media conversions.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(300)
            ->sharpen(10);
    }

    /**
     * Get the featured image URL.
     */
    public function getImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('featured_image', 'thumb') 
            ?: asset('images/project-placeholder.jpg');
    }
}
