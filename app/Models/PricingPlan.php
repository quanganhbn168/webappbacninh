<?php

namespace App\Models;

use App\Enums\PricingGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Reference price plans shown on /bang-gia and the home page.
 */
class PricingPlan extends Model
{
    protected $fillable = [
        'group',
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
        'order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('order')->orderBy('id');
    }

    public function scopeInGroup(Builder $query, PricingGroup $group): Builder
    {
        return $query->active()->where('group', $group);
    }

    public function priceText(): string
    {
        return trim(implode(' ', array_filter([$this->price_prefix, $this->price.$this->price_suffix])));
    }
}
