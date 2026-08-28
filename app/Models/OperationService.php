<?php

namespace App\Models;

use App\Traits\ImportsLegacyMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class OperationService extends Model implements HasMedia
{
    use ImportsLegacyMedia, InteractsWithMedia;

    protected $guarded = [];

    protected $casts = ['data' => 'array', 'is_active' => 'boolean'];

    public function getImageUrlAttribute(): string
    {
        if ($this->hasMedia('featured')) {
            return $this->getFirstMediaUrl('featured');
        }

        return site_asset_url($this->image, 'images/no-image.jpg');
    }

    public function getSecondaryImageUrlAttribute(): string
    {
        if ($this->hasMedia('gallery')) {
            return $this->getFirstMediaUrl('gallery');
        }

        return $this->secondary_image ? site_asset_url($this->secondary_image) : $this->image_url;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')->singleFile();
        $this->addMediaCollection('gallery');
    }
}
