<?php

namespace App\Models;

use App\Traits\HasSlug;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostCategory extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'image_id',
        'og_media_id',
        'meta_title',
        'meta_description',
        'slug',
        'description',
        'color',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ==================== SLUG ====================
    // Removed Spatie implementation in favor of centralized system

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function ogMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'og_media_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    protected static function booted()
    {
        static::deleting(function ($category) {
            if ($category->posts()->exists()) {
                throw new \Exception('Không thể xóa danh mục này vì vẫn còn bài viết thuộc danh mục.');
            }
        });
    }
}
