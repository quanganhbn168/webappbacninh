<?php

namespace App\Traits;

use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * A `gallery` JSON column holding ordered Curator media ids, falling back
 * to the model's `image_url` when the gallery is empty.
 */
trait HasCuratorGallery
{
    protected function galleryUrls(): Attribute
    {
        return Attribute::get(function (): array {
            $ids = array_values(array_filter($this->gallery ?? []));
            $media = $ids === [] ? collect() : Media::query()->whereKey($ids)->get()->keyBy('id');
            $urls = collect($ids)->map(fn (int|string $id): ?string => $media->get((int) $id)?->url)->filter()->values()->all();

            return $urls !== [] ? $urls : [$this->image_url];
        })->shouldCache();
    }
}
