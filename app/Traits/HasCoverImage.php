<?php

namespace App\Traits;

use App\Domain\Media\Actions\BuildResponsiveImage;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * The card and banner picture of a record, in sizes for those frames, so a phone does not
 * download the uploaded original. The model provides image_url for the picture-less case.
 */
trait HasCoverImage
{
    /** @return Attribute<array{src: string, srcset: string|null}, never> */
    protected function cover(): Attribute
    {
        return Attribute::get(function (): array {
            $media = $this->coverMedia();

            return $media
                ? app(BuildResponsiveImage::class)->execute($media, [480, 800, 1200])
                : ['src' => $this->image_url, 'srcset' => null];
        })->shouldCache();
    }

    protected function coverMedia(): ?Media
    {
        return $this->image;
    }
}
