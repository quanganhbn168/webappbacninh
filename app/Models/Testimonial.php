<?php

namespace App\Models;

use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'role',
        'quote',
        'avatar_id',
        'rating',
        'order',
        'is_active',
    ];

    protected $casts = [
        'rating' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'avatar_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->with('avatar')->orderBy('order')->orderBy('id');
    }
}
