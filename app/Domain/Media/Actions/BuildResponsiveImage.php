<?php

namespace App\Domain\Media\Actions;

use Awcodes\Curator\Facades\Curator;
use Awcodes\Curator\Glide\GlideBuilder;
use Awcodes\Curator\Models\Media;

/**
 * WebP versions of a library image, sized for the frame that shows it.
 * The uploaded original is kept as it is; Glide creates and caches each size on first request.
 */
final class BuildResponsiveImage
{
    /** High enough that text in website screenshots stays crisp. */
    private const QUALITY = 90;

    /**
     * @param  array<int, int>  $widths  wanted widths; none is larger than the original
     * @param  float|null  $ratio  width / height to crop to; null keeps the whole image
     * @return array{src: string, srcset: string|null}
     */
    public function execute(Media $media, array $widths, ?float $ratio = null): array
    {
        // SVGs and records without a known size are served as uploaded.
        if (! Curator::isResizable($media->ext) || ! $media->width) {
            return ['src' => (string) $media->url, 'srcset' => null];
        }

        $largest = min($media->width, max($widths));

        $urls = collect($widths)
            ->filter(fn (int $width): bool => $width < $largest)
            ->push($largest)
            ->sort()
            ->mapWithKeys(function (int $width) use ($media, $ratio): array {
                $image = GlideBuilder::make()->width($width)->format('webp')->quality(self::QUALITY)->fit($ratio ? 'crop' : 'max');

                if ($ratio) {
                    $image->height((int) round($width / $ratio));
                }

                return [$width => $image->toUrl($media->path)];
            });

        return [
            'src' => $urls->last(),
            'srcset' => $urls->count() > 1 ? $urls->map(fn (string $url, int $width): string => $url.' '.$width.'w')->implode(', ') : null,
        ];
    }
}
