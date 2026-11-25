<?php

namespace App\Traits;

use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Collection;

/**
 * A `gallery` JSON column holding ordered Curator media ids, falling back
 * to the model's `image_url` when the gallery is empty.
 */
trait HasCuratorGallery
{
    /** The gallery's library images in their saved order. */
    protected function galleryMedia(): Attribute
    {
        return Attribute::get(function (): Collection {
            $ids = array_values(array_filter($this->gallery ?? []));
            $media = $ids === [] ? collect() : Media::query()->whereKey($ids)->get()->keyBy('id');

            return collect($ids)->map(fn (int|string $id): ?Media => $media->get((int) $id))->filter()->values();
        })->shouldCache();
    }

    protected function galleryUrls(): Attribute
    {
        return Attribute::get(function (): array {
            $urls = $this->gallery_media->map(fn (Media $media): ?string => $media->url)->filter()->values()->all();

            return $urls !== [] ? $urls : [$this->image_url];
        })->shouldCache();
    }
}
