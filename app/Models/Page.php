<?php

namespace App\Models;

use App\Domain\Pages\SitePages;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

/**
 * SEO and hero banner of a fixed site page (see App\Domain\Pages\SitePages).
 * Page content lives in the page's Blade view.
 */
class Page extends Model
{
    protected $fillable = [
        'key',
        'title',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image_id',
        'noindex',
        'banner_eyebrow',
        'banner_title',
        'banner_highlight',
        'banner_subtitle',
        'banner_image_id',
    ];

    protected $casts = [
        'noindex' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn (self $page) => Cache::forget(self::cacheKey($page->key)));
        static::deleted(fn (self $page) => Cache::forget(self::cacheKey($page->key)));
    }

    /**
     * The stored page for a key, or an unsaved one with the default title.
     */
    public static function for(string $key): self
    {
        $page = Cache::rememberForever(self::cacheKey($key), fn (): ?self => self::query()->with(['ogImage', 'bannerImage'])->where('key', $key)->first());

        return $page ?? new self(['key' => $key, 'title' => SitePages::title($key)]);
    }

    public static function cacheKey(string $key): string
    {
        return 'site-page.'.$key;
    }

    public function ogImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'og_image_id');
    }

    public function bannerImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'banner_image_id');
    }

    protected function url(): Attribute
    {
        return Attribute::get(fn (): ?string => SitePages::url((string) $this->key));
    }

    protected function bannerImageUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->bannerImage?->url);
    }
}
