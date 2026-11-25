<?php

namespace App\Models;

use App\Enums\ServiceSection;
use App\Traits\HasServiceContent;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;
    use HasServiceContent;
    use HasSlug;

    protected $fillable = [
        'service_category_id',
        'title',
        'slug',
        'is_landing',
        'section',
        'menu_key',
        'eyebrow',
        'highlight',
        'icon',
        'description',
        'content',
        'image_id',
        'secondary_image_id',
        'price_from',
        'timeline',
        'cta',
        'need_value',
        'audiences',
        'problems',
        'pages',
        'features',
        'packages',
        'faqs',
        'meta_title',
        'meta_description',
        'order',
        'is_active',
        'show_in_grid',
        'card_title',
        'card_text',
    ];

    protected $attributes = [
        'is_landing' => false,
        'section' => 'website',
    ];

    protected $casts = [
        'is_landing' => 'boolean',
        'section' => ServiceSection::class,
        'is_active' => 'boolean',
        'show_in_grid' => 'boolean',
        'audiences' => 'array',
        'problems' => 'array',
        'pages' => 'array',
        'features' => 'array',
        'packages' => 'array',
        'faqs' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    /**
     * Landing services have a designed page under /thiet-ke-website or /dich-vu
     * (by section); the others are simple pages served from the site root.
     */
    protected function url(): Attribute
    {
        return Attribute::get(fn (): string => $this->is_landing
            ? route($this->section->routeName(), $this->slug)
            : route('slug.handle', $this->slug));
    }

    /** Services shown as cards on the home and services pages. */
    public function scopeInGrid(Builder $query): Builder
    {
        return $query->active()->where('show_in_grid', true)->ordered();
    }

    protected function gridTitle(): Attribute
    {
        return Attribute::get(fn (): string => $this->card_title ?: (string) $this->title);
    }

    protected function gridText(): Attribute
    {
        return Attribute::get(fn (): string => $this->card_text ?: Str::limit((string) $this->description, 60));
    }

    protected function timelineLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->timeline ?: 'Liên hệ để tư vấn');
    }
}
