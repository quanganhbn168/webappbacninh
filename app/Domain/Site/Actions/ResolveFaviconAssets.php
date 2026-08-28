<?php

namespace App\Domain\Site\Actions;

use App\Domain\Site\Data\FaviconAssetsData;
use App\Settings\FaviconSettings;
use App\Settings\GeneralSettings;
use App\Settings\WebsiteSettings;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class ResolveFaviconAssets
{
    public function execute(): FaviconAssetsData
    {
        $general = app(GeneralSettings::class);
        $website = app(WebsiteSettings::class);
        $favicon = app(FaviconSettings::class);
        $version = trim($favicon->generated_version);
        $directory = $version === '' ? '' : 'site/branding/favicon/generated/'.$version;
        $generated = $directory !== ''
            && Storage::disk('public')->exists($directory.'/favicon.ico')
            && Storage::disk('public')->exists($directory.'/favicon-512x512.png')
            && Storage::disk('public')->exists($directory.'/maskable-icon-512x512.png');
        $sourcePath = trim($website->site_favicon);
        $sourceUrl = site_asset_url($sourcePath, 'images/webapp.svg');

        return new FaviconAssetsData(
            generated: $generated,
            version: $generated ? $version : '',
            directory: $generated ? $directory : '',
            sourceUrl: $sourceUrl,
            sourceMime: $this->mimeFromPath($sourcePath === '' ? 'images/webapp.svg' : $sourcePath),
            applicationName: trim($general->name),
            shortName: trim($favicon->short_name) ?: Str::limit(trim($general->name), 30, ''),
            themeColor: $this->normalizeColor($favicon->theme_color, '#0f172a'),
            backgroundColor: $this->normalizeColor($favicon->background_color, '#ffffff'),
            safariMaskIconUrl: site_asset_url($favicon->safari_mask_icon),
            safariMaskColor: $this->normalizeColor($favicon->safari_mask_color, '#0f172a'),
        );
    }

    private function mimeFromPath(string $path): string
    {
        return match (strtolower(pathinfo(parse_url($path, PHP_URL_PATH) ?: $path, PATHINFO_EXTENSION))) {
            'png' => 'image/png',
            'webp' => 'image/webp',
            'jpg', 'jpeg' => 'image/jpeg',
            'ico' => 'image/x-icon',
            default => 'image/svg+xml',
        };
    }

    private function normalizeColor(string $color, string $fallback): string
    {
        $color = trim($color);

        return preg_match('/^#[0-9a-f]{6}$/i', $color) ? strtolower($color) : $fallback;
    }
}
