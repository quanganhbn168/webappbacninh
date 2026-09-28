<?php

namespace Database\Seeders;

use App\Settings\ContactSettings;
use App\Settings\FaviconSettings;
use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use App\Settings\SiteDefaults;
use App\Settings\SocialSettings;
use App\Settings\WebsiteSettings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $general = app(GeneralSettings::class);
        $general->name = SiteDefaults::get('name');
        $general->company_name = SiteDefaults::get('name');
        $general->default_language = 'vi';
        $general->save();

        $website = app(WebsiteSettings::class);
        $website->site_url = SiteDefaults::get('site_url');
        $website->site_logo_wide = '';
        $website->site_logo_white = '';
        $website->site_logo_square = '';
        $website->site_favicon = '';
        $website->save();

        $favicon = app(FaviconSettings::class);
        $favicon->short_name = 'WebApp Bắc Ninh';
        $favicon->maskable_icon = '';
        $favicon->safari_mask_icon = '';
        $favicon->theme_color = '#0f172a';
        $favicon->background_color = '#ffffff';
        $favicon->safari_mask_color = '#0f172a';
        $favicon->generated_version = '';
        $favicon->save();

        $seo = app(SeoSettings::class);
        $seo->default_meta_title = SiteDefaults::get('name');
        $seo->default_meta_description = 'Thiết kế website theo nhu cầu doanh nghiệp, tối ưu SEO và chuyển đổi.';
        $seo->default_meta_keywords = 'thiết kế website, web Bắc Ninh, website doanh nghiệp';
        $seo->default_og_image = '';
        $seo->google_site_verification = '';
        $seo->save();

        $contact = app(ContactSettings::class);
        $contact->phone = SiteDefaults::get('phone');
        $contact->phone_href = SiteDefaults::get('phone_href');
        $contact->phone_secondary = SiteDefaults::get('phone_secondary');
        $contact->phone_secondary_href = SiteDefaults::get('phone_secondary_href');
        $contact->email = SiteDefaults::get('email');
        $contact->address = SiteDefaults::get('address');
        $contact->working_time = SiteDefaults::get('working_time');
        $contact->save();

        $social = app(SocialSettings::class);
        $social->facebook = SiteDefaults::get('facebook');
        $social->messenger = '';
        $social->zalo = SiteDefaults::get('zalo');
        $social->telegram = '';
        $social->wechat_id = '';
        $social->wechat_qr = '';
        $social->whatsapp = '';
        $social->youtube = SiteDefaults::get('youtube');
        $social->save();
    }
}
