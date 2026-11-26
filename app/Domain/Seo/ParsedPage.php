<?php

namespace App\Domain\Seo;

use DOMDocument;
use DOMElement;
use DOMXPath;

/** The HTML a visitor and a search engine receive, with the parts the rules look at. */
final class ParsedPage
{
    private readonly DOMXPath $xpath;

    public function __construct(public readonly string $html)
    {
        $document = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8">'.$html);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $this->xpath = new DOMXPath($document);
    }

    /** @return array<int, DOMElement> */
    public function elements(string $query): array
    {
        return array_values(array_filter(iterator_to_array($this->xpath->query($query) ?: []), fn ($node): bool => $node instanceof DOMElement));
    }

    /**
     * Elements inside the text written in the editor.
     *
     * @return array<int, DOMElement>
     */
    public function body(string $path): array
    {
        return $this->elements('//div['.self::hasClass('prose').']'.$path);
    }

    public function text(string $query): string
    {
        return trim(implode(' ', array_map(fn (DOMElement $node): string => self::textOf($node), $this->elements($query))));
    }

    public function attribute(string $query, string $name): string
    {
        return trim((string) ($this->elements($query)[0] ?? null)?->getAttribute($name));
    }

    public function title(): string
    {
        return $this->text('//head/title');
    }

    public function meta(string $name): string
    {
        return $this->attribute('//meta[@name="'.$name.'" or @property="'.$name.'"]', 'content');
    }

    /** Lead, body and questions: what the article itself says, without the menus around it. */
    public function articleText(): string
    {
        return trim($this->text('//p['.self::hasClass('article-intro').']').' '
            .$this->text('//div['.self::hasClass('prose').']').' '
            .$this->text('//section['.self::hasClass('article-faq').']'));
    }

    /** @return array<int, array<string, mixed>> the nodes of every JSON-LD graph */
    public function schema(): array
    {
        $nodes = [];

        foreach ($this->elements('//script[@type="application/ld+json"]') as $script) {
            $json = json_decode($script->textContent, true);
            $nodes = array_merge($nodes, is_array($json) ? ($json['@graph'] ?? [$json]) : []);
        }

        return $nodes;
    }

    /** Text with a space between blocks, so the last word of one does not glue to the next. */
    public static function textOf(DOMElement $element): string
    {
        $html = (string) $element->ownerDocument?->saveHTML($element);
        $html = (string) preg_replace('#</(p|li|h[1-6]|td|th|div|blockquote|summary)>|<br\s*/?>#i', '$0 ', $html);

        return trim((string) preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    }

    private static function hasClass(string $class): string
    {
        return 'contains(concat(" ", normalize-space(@class), " "), " '.$class.' ")';
    }
}
