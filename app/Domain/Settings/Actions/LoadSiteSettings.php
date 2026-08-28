<?php

namespace App\Domain\Settings\Actions;

use App\Settings\ContactSettings;
use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use App\Settings\SocialSettings;
use App\Settings\TrackingSettings;
use App\Settings\WebsiteSettings;

final class LoadSiteSettings
{
    /** @return array<string, mixed> */
    public function execute(): array
    {
        $general = app(GeneralSettings::class);
        $website = app(WebsiteSettings::class);
        $seo = app(SeoSettings::class);
        $contact = app(ContactSettings::class);
        $social = app(SocialSettings::class);
        $tracking = app(TrackingSettings::class);

        return [
            'general' => [
                'name' => $general->name,
                'company_name' => $general->company_name,
                'default_language' => $general->default_language,
            ],
            'website' => [
                'site_url' => $website->site_url,
                'site_logo_wide' => $website->site_logo_wide,
                'site_logo_white' => $website->site_logo_white,
                'site_logo_square' => $website->site_logo_square,
                'site_favicon' => $website->site_favicon,
            ],
            'seo' => [
                'default_meta_title' => $seo->default_meta_title,
                'default_meta_description' => $seo->default_meta_description,
                'default_meta_keywords' => $seo->default_meta_keywords,
                'default_og_image' => $seo->default_og_image,
                'google_site_verification' => $seo->google_site_verification,
                'page_meta_json' => json_encode($seo->page_meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
            ],
            'contact' => [
                'phone' => $contact->phone,
                'phone_href' => $contact->phone_href,
                'email' => $contact->email,
                'address' => $contact->address,
                'working_time' => $contact->working_time,
            ],
            'social' => [
                'facebook' => $social->facebook,
                'messenger' => $social->messenger,
                'zalo' => $social->zalo,
                'telegram' => $social->telegram,
                'wechat' => $social->wechat,
                'whatsapp' => $social->whatsapp,
                'youtube' => $social->youtube,
            ],
            'tracking' => [
                'enabled' => $tracking->enabled,
                'google_tag_id' => $tracking->google_tag_id,
                'head_code' => $tracking->head_code,
                'body_start_code' => $tracking->body_start_code,
                'body_end_code' => $tracking->body_end_code,
            ],
        ];
    }
}
