<?php

namespace App\Models;

use App\Traits\ImportsLegacyMedia;
use Illuminate\Support\Str;
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

        return $this->image ? asset($this->image) : asset('images/placeholder.svg');
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

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_template_category');
    }

    /** Finds an industry by name, or creates it with a slug no other public page uses. */
    public static function findOrCreateByName(string $name): self
    {
        $name = trim($name);

        if ($existing = self::query()->where('name', $name)->first()) {
            return $existing;
        }

        $base = Str::slug($name) ?: 'nganh';
        $slug = $base;

        for ($i = 2; self::query()->where('slug', $slug)->exists() || Slug::query()->where('key', $slug)->exists(); $i++) {
            $slug = $i === 2 ? 'nganh-'.$base : 'nganh-'.$base.'-'.$i;
        }

        return self::query()->create(['name' => $name, 'slug' => $slug]);
    }

    protected static function booted()
    {
        static::deleting(function ($category) {
            if ($category->templates()->exists() || $category->projects()->exists()) {
                throw new \Exception('Không thể xóa ngành này vì vẫn còn giao diện hoặc dự án đang dùng.');
            }
        });
    }
}
