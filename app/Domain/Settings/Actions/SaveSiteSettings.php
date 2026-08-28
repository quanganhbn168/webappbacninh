<?php

namespace App\Domain\Settings\Actions;

use App\Domain\Site\Actions\GenerateFaviconAssets;
use App\Domain\Site\Exceptions\InvalidFaviconSource;
use App\Settings\ContactSettings;
use App\Settings\FaviconSettings;
use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use App\Settings\SocialSettings;
use App\Settings\TrackingSettings;
use App\Settings\WebsiteSettings;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class SaveSiteSettings
{
    public function __construct(private readonly GenerateFaviconAssets $generateFaviconAssets) {}

    /** @param array<string, mixed> $data */
    public function execute(array $data): void
    {
        $websiteData = Arr::get($data, 'website', []);
        $faviconData = Arr::get($data, 'favicon', []);
        $sourcePath = trim((string) ($websiteData['site_favicon'] ?? ''));
        $faviconData['generated_version'] = '';

        try {
            if ($sourcePath === '') {
                $this->generateFaviconAssets->clear();
            } else {
                $generated = $this->generateFaviconAssets->execute(
                    $sourcePath,
                    trim((string) ($faviconData['maskable_icon'] ?? '')),
                    trim((string) ($faviconData['background_color'] ?? '#ffffff')),
                );
                $faviconData['generated_version'] = $generated->version;
            }
        } catch (InvalidFaviconSource $exception) {
            throw ValidationException::withMessages([
                'data.website.site_favicon' => $exception->getMessage(),
            ]);
        }

        DB::transaction(function () use ($data, $faviconData, $websiteData): void {
            $this->persist(app(GeneralSettings::class), Arr::get($data, 'general', []), ['name', 'company_name', 'default_language']);
            $this->persist(app(WebsiteSettings::class), $websiteData, ['site_url', 'site_logo_wide', 'site_logo_white', 'site_logo_square', 'site_favicon']);

            $seo = Arr::get($data, 'seo', []);
            $seo['page_meta'] = json_decode((string) ($seo['page_meta_json'] ?? '{}'), true, 512, JSON_THROW_ON_ERROR);
            unset($seo['page_meta_json']);
            $this->persist(app(SeoSettings::class), $seo, ['default_meta_title', 'default_meta_description', 'default_meta_keywords', 'default_og_image', 'google_site_verification', 'page_meta']);

            $this->persist(app(ContactSettings::class), Arr::get($data, 'contact', []), ['phone', 'phone_href', 'phone_secondary', 'phone_secondary_href', 'email', 'address', 'working_time']);
            $this->persist(app(SocialSettings::class), Arr::get($data, 'social', []), ['facebook', 'messenger', 'zalo', 'telegram', 'wechat_id', 'wechat_qr', 'whatsapp', 'youtube']);
            $this->persist(app(TrackingSettings::class), Arr::get($data, 'tracking', []), ['enabled', 'google_tag_id', 'head_code', 'body_start_code', 'body_end_code']);
            $this->persist(app(FaviconSettings::class), $faviconData, ['short_name', 'maskable_icon', 'safari_mask_icon', 'theme_color', 'background_color', 'safari_mask_color', 'generated_version']);
        });
    }

    /**
     * @param  array<string, mixed>  $values
     * @param  array<int, string>  $fields
     */
    private function persist(object $settings, array $values, array $fields): void
    {
        foreach ($fields as $field) {
            $value = $values[$field] ?? '';
            $settings->{$field} = match (true) {
                is_bool($settings->{$field}) => (bool) $value,
                is_array($value) => $value,
                default => trim((string) $value),
            };
        }

        $settings->save();
    }
}
