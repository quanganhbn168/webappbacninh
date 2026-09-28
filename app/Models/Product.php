<?php

namespace App\Models;

use App\Enums\ProductGroup;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Packaged products shown on /san-pham and the home page.
 */
class Product extends Model
{
    protected $fillable = [
        'name',
        'group',
        'image_id',
        'summary',
        'tags',
        'detail_url',
        'demo_url',
        'is_featured',
        'order',
        'is_active',
    ];

    protected $casts = [
        'group' => ProductGroup::class,
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function scopeForCatalog(Builder $query): Builder
    {
        return $query->where('is_active', true)->with('image')->orderByDesc('is_featured')->orderBy('order')->orderBy('id');
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->image?->url ?? asset('images/placeholder.svg'));
    }

    protected function groupLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->group?->label() ?? '');
    }
}
