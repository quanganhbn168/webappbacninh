<?php

namespace App\Models;

use App\Traits\HasServiceContent;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class OperationService extends Model
{
    use HasServiceContent;

    protected $fillable = [
        'title',
        'slug',
        'menu_key',
        'eyebrow',
        'highlight',
        'icon',
        'description',
        'image_id',
        'secondary_image_id',
        'price_from',
        'cadence',
        'cta',
        'need_value',
        'audiences',
        'scope',
        'deliverables',
        'process',
        'packages',
        'faqs',
        'meta_title',
        'meta_description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'audiences' => 'array',
        'scope' => 'array',
        'deliverables' => 'array',
        'process' => 'array',
        'packages' => 'array',
        'faqs' => 'array',
    ];

    protected function url(): Attribute
    {
        return Attribute::get(fn (): string => route('operations.show', $this->slug));
    }

    protected function cadenceLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->cadence ?: 'Theo gói hoặc theo tháng');
    }
}
