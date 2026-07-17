<?php

namespace App\Models;

use App\Traits\ImportsLegacyMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Template extends Model implements HasMedia
{
    use \App\Traits\HasSlug;
    use HasFactory;
    use ImportsLegacyMedia;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'category', // Legacy
        'template_category_id', // New Relation
        'demo_url',
        'is_premium',
        'price',
        'sale_price',
        'is_free',
        'order',
        'is_active',
        'is_active',
        // 'tags', // Removed
        'content',
        'code', 'type', 'industry', 'year', 'description', 'badge', 'duration', 'data', 'is_featured',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'is_free' => 'boolean',
        'is_active' => 'boolean',
        // 'tags' => 'array', // Removed
        'price' => 'decimal:0',
        'sale_price' => 'decimal:0',
        'data' => 'array',
        'is_featured' => 'boolean',
    ];

    public function templateCategory()
    {
        return $this->belongsTo(TemplateCategory::class, 'template_category_id');
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    public function getImageUrlAttribute()
    {
        if ($this->hasMedia('featured')) {
            return $this->getFirstMediaUrl('featured');
        }
        if ($this->image) {
            // Check if it's a storage path (new method)
            if (str_starts_with($this->image, 'storage/')) {
                return asset($this->image);
            }
            // Check legacy path
            if (file_exists(public_path($this->image))) {
                return asset($this->image);
            }
        }

        return asset('images/no-image.jpg'); // Fallback
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')->singleFile();
        $this->addMediaCollection('gallery');
    }
}
