<?php

namespace App\Models;

use App\Traits\HasServiceContent;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

    protected $attributes = [
        'is_landing' => false,
    ];

    protected $casts = [
        'is_landing' => 'boolean',
        'is_active' => 'boolean',
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
     * Landing services have a designed page under /thiet-ke-website; the
     * others are simple pages served from the site root.
     */
    protected function url(): Attribute
    {
        return Attribute::get(fn (): string => $this->is_landing
            ? route('services.show', $this->slug)
            : route('slug.handle', $this->slug));
    }

    protected function timelineLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->timeline ?: 'Liên hệ để tư vấn');
    }
}
