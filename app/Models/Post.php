<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'summary',
        'content',
        'featured_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    // ==================== SLUG ====================

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(100)
            ->doNotGenerateSlugsOnUpdate();
    }

    // ==================== MEDIA ====================

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')
            ->singleFile()
            ->useFallbackUrl(asset('images/placeholder.jpg'));

        $this->addMediaCollection('og_image')
            ->singleFile()
            ->useFallbackUrl(asset('images/og-placeholder.jpg'));
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)->height(200)->sharpen(10)
            ->performOnCollections('featured_image');

        $this->addMediaConversion('medium')
            ->width(800)->height(600)
            ->performOnCollections('featured_image');

        $this->addMediaConversion('og')
            ->width(1200)->height(630)
            ->performOnCollections('og_image', 'featured_image');
    }

    // ==================== ACCESSORS ====================

    public function getFeaturedImageUrlAttribute(): string
    {
        $media = $this->getFirstMediaUrl('featured_image');
        if ($media) return $media;
        if ($this->featured_image) return asset($this->featured_image);
        return asset('images/placeholder.jpg');
    }

    public function getOgImageUrlAttribute(): string
    {
        $media = $this->getFirstMediaUrl('og_image', 'og');
        if ($media) return $media;
        if ($this->og_image) return asset($this->og_image);
        return $this->featured_image_url;
    }

    // ==================== SCOPES ====================

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->whereNotNull('published_at');
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeWithTag($query, $tagSlug)
    {
        return $query->whereHas('tags', fn($q) => $q->where('slug', $tagSlug));
    }
}
