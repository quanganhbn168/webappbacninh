<?php

namespace App\Models;

use App\Traits\HasSlug;
use App\Traits\ImportsLegacyMedia;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use HasFactory, HasSlug, ImportsLegacyMedia, InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'summary',
        'content',
        'featured_image',
        'featured_media_id',
        'curator_managed',
        'og_media_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'is_published',
        'published_at',
        'read_time',
        'is_featured',
        'data',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'curator_managed' => 'boolean',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'data' => 'array',
    ];

    // ==================== RELATIONSHIPS ====================

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function featuredMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_media_id');
    }

    public function ogMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'og_media_id');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    // ==================== SLUG ====================
    // Removed Spatie implementation in favor of centralized system

    // ==================== ACCESSORS ====================

    public function getFeaturedImageUrlAttribute(): string
    {
        if ($this->featuredMedia) {
            return $this->featuredMedia->url;
        }
        if ($this->curator_managed) {
            return asset('images/placeholder.jpg');
        }
        if ($this->hasMedia('featured')) {
            return $this->getFirstMediaUrl('featured');
        }
        if ($this->featured_image) {
            return asset($this->featured_image);
        }

        return asset('images/placeholder.jpg');
    }

    public function getOgImageUrlAttribute(): string
    {
        if ($this->ogMedia) {
            return $this->ogMedia->url;
        }
        if ($this->curator_managed) {
            return $this->featured_image_url;
        }
        if ($this->hasMedia('og')) {
            return $this->getFirstMediaUrl('og');
        }

        if ($this->og_image) {
            return asset($this->og_image);
        }

        return $this->featured_image_url;
    }

    // ==================== SCOPES ====================

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->where('published_at', '<=', now());
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeWithTag($query, $tagSlug)
    {
        return $query->whereHas('tags', fn ($q) => $q->where('slug', $tagSlug));
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')->singleFile();
        $this->addMediaCollection('og')->singleFile();
        $this->addMediaCollection('content');
    }
}
