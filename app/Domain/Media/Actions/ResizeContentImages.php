<?php

namespace App\Domain\Media\Actions;

use Awcodes\Curator\Models\Media;

/**
 * Pictures placed inside a text written in the editor point at the uploaded original. This
 * serves them like every other picture of the site: WebP in sizes for the text column.
 */
final class ResizeContentImages
{
    private const WIDTHS = [480, 800, 1200];

    private const SIZES = '(min-width: 992px) 832px, 100vw';

    public function __construct(private readonly BuildResponsiveImage $images) {}

    /** @param  string  $html  already sanitized */
    public function execute(string $html): string
    {
        return (string) preg_replace_callback('/<img\b[^>]*>/i', function (array $match): string {
            $tag = $match[0];

            if (! preg_match('/\ssrc="([^"]+)"/i', $tag, $source)) {
                return $tag;
            }

            $path = (string) parse_url(html_entity_decode($source[1]), PHP_URL_PATH);
            $media = str_contains($path, '/storage/') ? Media::query()->where('path', rawurldecode((string) str($path)->after('/storage/')))->first() : null;

            if (! $media) {
                return $tag;
            }

            $image = $this->images->execute($media, self::WIDTHS);
            $attributes = ' src="'.e($image['src']).'"'.($image['srcset'] ? ' srcset="'.e($image['srcset']).'" sizes="'.self::SIZES.'"' : '');
            $tag = str_replace($source[0], $attributes, $tag);

            return stripos($tag, ' loading=') === false ? preg_replace('/^<img/i', '<img loading="lazy"', $tag) : $tag;
        }, $html);
    }
}
