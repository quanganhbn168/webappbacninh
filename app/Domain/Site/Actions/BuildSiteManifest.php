<?php

namespace App\Domain\Site\Actions;

use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;

final class BuildSiteManifest
{
    public function __construct(private readonly ResolveFaviconAssets $resolveFaviconAssets) {}

    /** @return array<string, mixed> */
    public function execute(): array
    {
        $assets = $this->resolveFaviconAssets->execute();
        $general = app(GeneralSettings::class);
        $seo = app(SeoSettings::class);
        $icons = [];

        if ($assets->generated) {
            foreach ([192, 512] as $size) {
                $icons[] = [
                    'src' => $assets->url("favicon-{$size}x{$size}.png"),
                    'sizes' => "{$size}x{$size}",
                    'type' => 'image/png',
                    'purpose' => 'any',
                ];
            }

            foreach ([192, 512] as $size) {
                $icons[] = [
                    'src' => $assets->url("maskable-icon-{$size}x{$size}.png"),
                    'sizes' => "{$size}x{$size}",
                    'type' => 'image/png',
                    'purpose' => 'maskable',
                ];
            }
        } else {
            $fallback = [
                'src' => $assets->sourceUrl,
                'type' => $assets->sourceMime,
                'purpose' => 'any',
            ];

            if ($assets->sourceMime === 'image/svg+xml') {
                $fallback['sizes'] = 'any';
            }

            $icons[] = $fallback;
        }

        return [
            'id' => '/',
            'name' => $assets->applicationName,
            'short_name' => $assets->shortName,
            'description' => trim($seo->default_meta_description),
            'lang' => trim($general->default_language) ?: 'vi',
            'dir' => 'ltr',
            'start_url' => '/',
            'scope' => '/',
            'display' => 'standalone',
            'background_color' => $assets->backgroundColor,
            'theme_color' => $assets->themeColor,
            'prefer_related_applications' => false,
            'icons' => $icons,
        ];
    }
}
