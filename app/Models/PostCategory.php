<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasSlug;

class PostCategory extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
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
