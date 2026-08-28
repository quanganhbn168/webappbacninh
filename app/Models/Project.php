<?php

namespace App\Models;

use App\Traits\ImportsLegacyMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Project extends Model implements HasMedia
{
    use HasSlug, ImportsLegacyMedia, InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'project_category_id',
        'code',
        'description',
        'image', // Stored media path
        'link',
        'category',
        'industry',
        'year',
        'excerpt',
        'client',
        'duration',
        'website_type',
        'challenge',
        'solution',
        'gallery',
        'results',
        'deliverables',
        'technologies',
        'data',
        'is_featured',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'gallery' => 'array',
        'results' => 'array',
        'deliverables' => 'array',
        'technologies' => 'array',
        'data' => 'array',
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
     * Get the image URL.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->hasMedia('featured')) {
            return $this->getFirstMediaUrl('featured');
        }

        return $this->image ? asset($this->image) : asset('images/project-placeholder.jpg');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')->singleFile();
        $this->addMediaCollection('gallery');
    }
}
