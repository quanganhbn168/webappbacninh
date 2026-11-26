<?php

namespace App\Models;

use App\Domain\Content\EstimateReadingTime;
use App\Domain\Media\Actions\ResizeContentImages;
use App\Traits\HasCoverImage;
use App\Traits\HasFaqs;
use App\Traits\HasSlug;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Post extends Model
{
    use HasCoverImage;
    use HasFactory, HasFaqs, HasSlug;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'summary',
        'content',
        'faqs',
        'featured_media_id',
        'og_media_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_published',
        'published_at',
        'is_featured',
        'data',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'data' => 'array',
        'faqs' => 'array',
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

    // ==================== ACCESSORS ====================

    protected function url(): Attribute
    {
        return Attribute::get(fn (): string => route('slug.handle', $this->slug));
    }

    protected function categorySlug(): Attribute
    {
        return Attribute::get(fn (): string => $this->category?->slug ?? 'kien-thuc');
    }

    protected function categoryLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->category?->name ?? 'Kiến thức');
    }

    protected function excerpt(): Attribute
    {
        return Attribute::get(fn (): string => (string) $this->summary);
    }

    protected function intro(): Attribute
    {
        return Attribute::get(fn (): string => (string) data_get($this->data, 'intro', $this->summary));
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->featured_image_url);
    }

    protected function publishedLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->published_at?->format('d/m/Y') ?? '');
    }

    protected function readTimeLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->read_time.' phút đọc');
    }

    /**
     * Sanitized body with an anchor on every h2, for the table of contents.
     */
    protected function bodyHtml(): Attribute
    {
        return Attribute::get(function (): string {
            $index = 0;
            $html = app(ResizeContentImages::class)->execute((string) str((string) $this->content)->sanitizeHtml());

            return (string) preg_replace_callback('/<h2(\s[^>]*)?>/i', function (array $match) use (&$index): string {
                $index++;

                return '<h2 id="section-'.$index.'"'.preg_replace('/\sid="[^"]*"/i', '', $match[1] ?? '').'>';
            }, $html);
        })->shouldCache();
    }

    /**
     * @return array<int, string> h2 headings keyed by their anchor number
     */
    public function tableOfContents(): array
    {
        preg_match_all('/<h2[^>]*>(.*?)<\/h2>/is', $this->body_html, $matches);

        return collect($matches[1])->map(fn (string $heading): string => trim(html_entity_decode(strip_tags($heading))))
            ->filter()->mapWithKeys(fn (string $heading, int $index): array => [$index + 1 => $heading])->all();
    }

    /**
     * @return Collection<int, self>
     */
    public function related(int $limit = 3): Collection
    {
        return self::query()->forCatalog()->whereKeyNot($this->getKey())->get()
            ->sortByDesc(fn (self $post): int => ($post->category_id === $this->category_id ? 2 : 0) + (int) $post->is_featured)
            ->take($limit)
            ->values();
    }

    public function getReadTimeAttribute(): int
    {
        return app(EstimateReadingTime::class)->execute($this->content);
    }

    protected function coverMedia(): ?Media
    {
        return $this->featuredMedia;
    }

    public function getFeaturedImageUrlAttribute(): string
    {
        return $this->featuredMedia?->url ?? asset('images/placeholder.svg');
    }

    public function getOgImageUrlAttribute(): string
    {
        return $this->ogMedia?->url ?? $this->featured_image_url;
    }

    // ==================== SCOPES ====================

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->where('published_at', '<=', now());
    }

    public function scopeForCatalog(Builder $query): Builder
    {
        return $query->published()->with(['category', 'featuredMedia'])->orderByDesc('published_at');
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
