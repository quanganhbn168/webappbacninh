<?php

namespace App\Domain\Settings\Actions;

use App\Settings\ContactSettings;
use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use App\Settings\SocialSettings;
use App\Settings\TrackingSettings;
use App\Settings\WebsiteSettings;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

final class SaveSiteSettings
{
    /** @param array<string, mixed> $data */
    public function execute(array $data): void
    {
        DB::transaction(function () use ($data): void {
            $this->persist(app(GeneralSettings::class), Arr::get($data, 'general', []), ['name', 'company_name', 'default_language']);
            $this->persist(app(WebsiteSettings::class), Arr::get($data, 'website', []), ['site_url', 'site_logo_wide', 'site_logo_white', 'site_logo_square', 'site_favicon']);

            $seo = Arr::get($data, 'seo', []);
            $seo['page_meta'] = json_decode((string) ($seo['page_meta_json'] ?? '{}'), true, 512, JSON_THROW_ON_ERROR);
            unset($seo['page_meta_json']);
            $this->persist(app(SeoSettings::class), $seo, ['default_meta_title', 'default_meta_description', 'default_meta_keywords', 'default_og_image', 'google_site_verification', 'page_meta']);

            $this->persist(app(ContactSettings::class), Arr::get($data, 'contact', []), ['phone', 'phone_href', 'email', 'address', 'working_time']);
            $this->persist(app(SocialSettings::class), Arr::get($data, 'social', []), ['facebook', 'messenger', 'zalo', 'telegram', 'wechat', 'whatsapp', 'youtube']);
            $this->persist(app(TrackingSettings::class), Arr::get($data, 'tracking', []), ['enabled', 'google_tag_id', 'head_code', 'body_start_code', 'body_end_code']);
        });
    }

    /**
     * @param array<string, mixed> $values
     * @param array<int, string> $fields
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
