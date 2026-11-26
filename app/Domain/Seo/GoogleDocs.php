<?php

namespace App\Domain\Seo;

/**
 * The Google Search Central pages the scored rules rest on (read 26/11/2026). A rule that
 * none of them states is advice and leaves the score alone. Google publishes no length limit
 * for titles or descriptions, no word count, no keyword density and no score of its own.
 */
final class GoogleDocs
{
    private const BASE = 'https://developers.google.com/search/docs/';

    public const TITLE = self::BASE.'appearance/title-link';

    public const SNIPPET = self::BASE.'appearance/snippet';

    public const STARTER = self::BASE.'fundamentals/seo-starter-guide';

    public const SPAM = self::BASE.'essentials/spam-policies';

    public const URLS = self::BASE.'crawling-indexing/url-structure';

    public const LINKS = self::BASE.'crawling-indexing/links-crawlable';

    public const IMAGES = self::BASE.'appearance/google-images';

    public const DISCOVER = self::BASE.'appearance/google-discover';

    public const NOINDEX = self::BASE.'crawling-indexing/block-indexing';

    public const CANONICAL = self::BASE.'crawling-indexing/consolidate-duplicate-urls';

    public const SITEMAP = self::BASE.'crawling-indexing/sitemaps/build-sitemap';

    public const ARTICLE = self::BASE.'appearance/structured-data/article';

    public const BREADCRUMB = self::BASE.'appearance/structured-data/breadcrumb';

    public const HELPFUL = self::BASE.'fundamentals/creating-helpful-content';

    public const DATES = self::BASE.'appearance/publication-dates';

    public const PAGE_EXPERIENCE = self::BASE.'appearance/page-experience';

    public const VITALS = self::BASE.'appearance/core-web-vitals';

    public const GOOGLEBOT = self::BASE.'crawling-indexing/googlebot';
}
