<?php

namespace Database\Seeders;

use App\Settings\ContactSettings;
use App\Settings\FaviconSettings;
use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use App\Settings\SocialSettings;
use App\Settings\WebsiteSettings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $general = app(GeneralSettings::class);
        $general->name = config('site.name');
        $general->company_name = config('site.name');
        $general->default_language = 'vi';
        $general->save();

        $website = app(WebsiteSettings::class);
        $website->site_url = config('site.site_url');
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
        $seo->default_meta_title = config('site.name');
        $seo->default_meta_description = 'Thiết kế website theo nhu cầu doanh nghiệp, tối ưu SEO và chuyển đổi.';
        $seo->default_meta_keywords = 'thiết kế website, web Bắc Ninh, website doanh nghiệp';
        $seo->default_og_image = '';
        $seo->google_site_verification = '';
        $seo->google_analytics_id = '';
        $seo->save();

        $contact = app(ContactSettings::class);
        $contact->phone = config('site.phone');
        $contact->phone_href = config('site.phone_href');
        $contact->email = config('site.email');
        $contact->address = config('site.address');
        $contact->working_time = config('site.working_time');
        $contact->save();

        $social = app(SocialSettings::class);
        $social->facebook = config('site.facebook');
        $social->messenger = '';
        $social->zalo = config('site.zalo');
        $social->telegram = '';
        $social->wechat = '';
        $social->whatsapp = '';
        $social->youtube = config('site.youtube');
        $social->save();
    }
}
