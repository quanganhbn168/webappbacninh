<?php

namespace App\Models;

use App\Traits\ImportsLegacyMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TemplateCategory extends Model implements HasMedia
{
    use HasFactory, ImportsLegacyMedia, InteractsWithMedia;
    use \App\Traits\HasSlug;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'image',
        'og_image',
        'meta_title',
        'meta_description',
        'description',
        'content',
    ];

    public function parent()
    {
        return $this->belongsTo(TemplateCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(TemplateCategory::class, 'parent_id');
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->hasMedia('featured')) {
            return $this->getFirstMediaUrl('featured');
        }

        return $this->image ? asset($this->image) : asset('images/no-image.jpg');
    }

    public function getOgImageUrlAttribute(): string
    {
        if ($this->hasMedia('og')) {
            return $this->getFirstMediaUrl('og');
        }

        return $this->og_image ? asset($this->og_image) : $this->image_url;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')->singleFile();
        $this->addMediaCollection('og')->singleFile();
    }

    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    protected static function booted()
    {
        static::deleting(function ($category) {
            if ($category->templates()->exists()) {
                throw new \Exception('Không thể xóa danh mục này vì vẫn còn giao diện thuộc danh mục.');
            }
        });
    }
}
