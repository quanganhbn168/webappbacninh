<?php

namespace App\Models;

use App\Enums\PricingGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Every price of the site's services. A plan shows in the shared table (/bang-gia,
 * home…), on the pages of the services that picked it, or both.
 */
class PricingPlan extends Model
{
    protected $fillable = [
        'group',
        'show_in_table',
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

    public function services(): MorphToMany
    {
        return $this->morphedByMany(Service::class, 'priceable');
    }

    public function operationServices(): MorphToMany
    {
        return $this->morphedByMany(OperationService::class, 'priceable');
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
