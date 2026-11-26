<?php

namespace App\Domain\Seo;

use App\Models\Post;
use Awcodes\Curator\Models\Media;
use DOMElement;
use Illuminate\Support\Collection;

/** What the rules read: the article as typed and the page it produces. */
final class SeoContext
{
    public readonly string $keyword;

    public readonly string $text;

    public readonly int $words;

    /** @var Collection<int, array{src: string, alt: string, media: Media|null, local: bool}>|null */
    private ?Collection $images = null;

    public function __construct(public readonly Post $post, public readonly ParsedPage $page)
    {
        $this->keyword = trim((string) data_get($post->data, 'focus_keyword'));
        $this->text = $page->articleText();
        $this->words = SeoText::countWords($this->text);
    }

    /** The title typed for search results, without the site name added to it. */
    public function seoTitle(): string
    {
        return trim((string) ($this->post->meta_title ?: $this->post->title));
    }

    /**
     * Pictures placed in the text, as written in the editor, matched to the library.
     *
     * @return Collection<int, array{src: string, alt: string, media: Media|null, local: bool}>
     */
    public function contentImages(): Collection
    {
        return $this->images ??= collect((new ParsedPage('<div>'.$this->post->content.'</div>'))->elements('//img'))
            ->map(function (DOMElement $image): array {
                $src = $image->getAttribute('src');
                $local = self::isOwnAddress($src);
                $path = (string) parse_url($src, PHP_URL_PATH);
                $file = $local && preg_match('#/(?:storage|curator)/(.+)$#', $path, $match) ? rawurldecode($match[1]) : '';

                return [
                    'src' => $src,
                    'alt' => trim($image->getAttribute('alt')),
                    'media' => $file !== '' ? Media::query()->where('path', $file)->first() : null,
                    'local' => $local,
                ];
            });
    }

    /** @return Collection<int, Media> every library picture the article shows */
    public function libraryImages(): Collection
    {
        return $this->contentImages()->pluck('media')->prepend($this->post->featuredMedia)->filter()->unique('id')->values();
    }

    public static function isOwnAddress(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);

        return ! $host || in_array(strtolower($host), array_map(
            fn (string $own): string => strtolower((string) parse_url($own, PHP_URL_HOST)),
            [(string) config('app.url'), (string) site_config('site_url'), url('/')],
        ), true);
    }
}
