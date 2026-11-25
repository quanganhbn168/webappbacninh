<?php

namespace App\Models;

use App\Enums\PricingGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Every price of the site's services. A plan shows in the shared table (/bang-gia,
 * home…), on the page of the service it belongs to, or both.
 */
class PricingPlan extends Model
{
    protected $fillable = [
        'group',
        'show_in_table',
        'priceable_type',
        'priceable_id',
        'name',
        'icon',
        'price_prefix',
        'price',
        'price_suffix',
        'summary',
        'features',
        'is_featured',
        'badge',
        'cta_label',
        'order',
        'is_active',
    ];

    protected $casts = [
        'group' => PricingGroup::class,
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'show_in_table' => 'boolean',
        'order' => 'integer',
    ];

    /** The service or operation service whose page shows this plan. */
    public function priceable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('order')->orderBy('id');
    }

    /** Plans of the shared price table. */
    public function scopeInTable(Builder $query): Builder
    {
        return $query->active()->where('show_in_table', true)->whereNotNull('group');
    }

    public function scopeInGroup(Builder $query, PricingGroup $group): Builder
    {
        return $query->inTable()->where('group', $group);
    }

    public function priceText(): string
    {
        return trim(implode(' ', array_filter([$this->price_prefix, $this->price.$this->price_suffix])));
    }
}
