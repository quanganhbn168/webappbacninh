<?php

namespace App\Models;

use App\Enums\ProjectCategory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Project extends Model
{
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image', // LFM path
        'link',
        'category',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'category' => ProjectCategory::class,
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
     * Get the image URL.
     */
    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset($this->image) : asset('images/project-placeholder.jpg');
    }
}
