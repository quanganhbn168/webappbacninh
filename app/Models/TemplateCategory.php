<?php

namespace App\Models;

use App\Domain\Media\Actions\ResizeContentImages;
use App\Traits\HasFaqs;
use App\Traits\HasSlug;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TemplateCategory extends Model
{
    use HasFactory, HasFaqs;
    use HasSlug;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'meta_title',
        'meta_description',
        'description',
        'content',
        'faqs',
        'banner_image_id',
        'is_published',
    ];

    protected $casts = [
        'faqs' => 'array',
        'is_published' => 'boolean',
    ];

    public function bannerImage()
    {
        return $this->belongsTo(Media::class, 'banner_image_id');
    }

    /** The industry's own page: /mau-website/{slug}. */
    protected function url(): Attribute
    {
        return Attribute::get(fn (): string => route('themes.industry', $this->slug));
    }

    /** Sanitized body of the industry page. */
    protected function bodyHtml(): Attribute
    {
        return Attribute::get(fn (): string => app(ResizeContentImages::class)->execute((string) str((string) $this->content)->sanitizeHtml()));
    }

    /**
     * Pages Google may list: turned on in the admin, with their own text and at least one design.
     * Anything thinner stays reachable but is kept out of search.
     */
    public function scopeIndexable(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where('description', '!=', '')
            ->where('content', '!=', '')
            ->whereHas('templates', fn (Builder $templates) => $templates->where('is_active', true));
    }

    public function isIndexable(): bool
    {
        return self::query()->indexable()->whereKey($this->getKey())->exists();
    }

    public function parent()
    {
        return $this->belongsTo(TemplateCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(TemplateCategory::class, 'parent_id');
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
