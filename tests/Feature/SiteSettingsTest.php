<?php

namespace Tests\Feature;

use App\Settings\ContactSettings;
use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use App\Settings\SocialSettings;
use App\Settings\TrackingSettings;
use App\Settings\WebsiteSettings;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SiteSettingsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_structured_spatie_settings_are_available_through_the_site_helper(): void
    {
        $general = app(GeneralSettings::class);
        $website = app(WebsiteSettings::class);
        $seo = app(SeoSettings::class);
        $contact = app(ContactSettings::class);
        $social = app(SocialSettings::class);
        $tracking = app(TrackingSettings::class);

        $this->assertSame($general->name, site_config('name'));
        $this->assertSame($website->site_url, site_config('site_url'));
        $this->assertSame($seo->default_meta_title, site_config('default_meta_title'));
        $this->assertSame($seo->page_meta, site_config('page_meta'));
        $this->assertSame($seo->page_meta['home']['title'], site_page_seo('home')['title']);
        $this->assertSame($contact->email, site_config('email'));
        $this->assertSame($social->zalo, site_config('zalo'));
        $this->assertSame($tracking->google_tag_id, site_config('google_tag_id'));
        $this->assertTrue(site_config('tracking_enabled'));
    }
}
