<?php

namespace Database\Seeders;

use App\Domain\Media\Actions\ImportLocalImage;
use App\Domain\Site\Actions\GenerateFaviconAssets;
use App\Settings\ContactSettings;
use App\Settings\FaviconSettings;
use App\Settings\SeoSettings;
use App\Settings\SiteDefaults;
use App\Settings\SocialSettings;
use App\Settings\TrackingSettings;
use App\Settings\WebsiteSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/** The company's own identity, as the live site shows it. */
class SettingsSeeder extends Seeder
{
    /** Google Analytics 4 and the Pinterest domain check of the live site. */
    private const LIVE_HEAD_CODE = <<<'HTML'
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-0H8PZFEFK8"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', 'G-0H8PZFEFK8');
        </script>
        <meta name="p:domain_verify" content="af33cd5f550b65fd2c8c7615e658569d"/>
        HTML;

    public function run(GenerateFaviconAssets $favicons, ImportLocalImage $images): void
    {
        // refresh() on each group: during migrate:fresh the application has already read the
        // settings of the database being replaced, and keeps that copy.
        $contact = app(ContactSettings::class)->refresh();
        foreach (['phone', 'phone_href', 'phone_secondary', 'phone_secondary_href', 'email', 'address', 'working_time'] as $key) {
            $contact->{$key} = SiteDefaults::get($key);
        }
        $contact->save();

        $social = app(SocialSettings::class)->refresh();
        $social->facebook = SiteDefaults::get('facebook');
        $social->zalo = SiteDefaults::get('zalo');
        $social->whatsapp = SiteDefaults::get('whatsapp');
        $social->youtube = SiteDefaults::get('youtube');
        $social->save();

        $website = app(WebsiteSettings::class)->refresh();
        $logo = $images->execute('images/brand/logo.png', 'site/branding', 'Logo '.SiteDefaults::get('name'));
        $website->logo_wide_media_id = $logo?->id;
        $website->logo_square_media_id = $logo?->id;
        $website->site_favicon = $this->publish('favicon.png', 'site/branding/favicon/source.png');
        $website->save();

        $seo = app(SeoSettings::class)->refresh();
        $seo->default_meta_description = 'Thiết kế website theo nhu cầu doanh nghiệp, tối ưu SEO và chuyển đổi.';
        $seo->default_meta_keywords = 'thiết kế website, web Bắc Ninh, website doanh nghiệp';
        $seo->default_og_image = $this->publish('share.jpg', 'site/seo/share.jpg');
        $seo->save();

        $favicon = app(FaviconSettings::class)->refresh();
        $favicon->generated_version = $favicons->execute($website->site_favicon, $favicon->maskable_icon, $favicon->background_color, true)->version;
        $favicon->save();

        // Visits on a developer's machine or in tests must not reach the real statistics.
        if (app()->isProduction()) {
            $tracking = app(TrackingSettings::class)->refresh();
            $tracking->head_code = self::LIVE_HEAD_CODE;
            $tracking->save();
        }

        app()->forgetInstance('site.settings');
    }

    /** Copies a brand image shipped in public/images/brand to the disk uploads live on. */
    private function publish(string $file, string $target): string
    {
        Storage::disk('public')->put($target, file_get_contents(public_path('images/brand/'.$file)));

        return $target;
    }
}
