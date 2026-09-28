<?php

use App\Settings\SiteDefaults;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.company_name', $this->legacyValue('name', SiteDefaults::get('name')));
        $this->migrator->add('general.default_language', 'vi');

        $this->migrator->add('website.site_url', $this->legacyValue('site_url', SiteDefaults::get('site_url')));
        $this->migrator->add('website.site_logo_wide', '');
        $this->migrator->add('website.site_logo_white', '');
        $this->migrator->add('website.site_logo_square', '');
        $this->migrator->add('website.site_favicon', '');

        $this->migrator->add('seo.default_meta_title', $this->legacyValue('name', SiteDefaults::get('name')));
        $this->migrator->add('seo.default_meta_description', 'Thiết kế website theo nhu cầu doanh nghiệp, tối ưu SEO và chuyển đổi.');
        $this->migrator->add('seo.default_meta_keywords', 'thiết kế website, web Bắc Ninh, website doanh nghiệp');
        $this->migrator->add('seo.default_og_image', '');
        $this->migrator->add('seo.google_site_verification', '');
        $this->migrator->add('seo.google_analytics_id', '');

        $this->migrator->add('contact.phone', $this->legacyValue('phone', SiteDefaults::get('phone')));
        $this->migrator->add('contact.phone_href', $this->legacyValue('phone_href', SiteDefaults::get('phone_href')));
        $this->migrator->add('contact.email', $this->legacyValue('email', SiteDefaults::get('email')));
        $this->migrator->add('contact.address', $this->legacyValue('address', SiteDefaults::get('address')));
        $this->migrator->add('contact.working_time', $this->legacyValue('working_time', SiteDefaults::get('working_time')));

        $this->migrator->add('social.facebook', $this->legacyValue('facebook', SiteDefaults::get('facebook')));
        $this->migrator->add('social.messenger', '');
        $this->migrator->add('social.zalo', $this->legacyValue('zalo', SiteDefaults::get('zalo')));
        $this->migrator->add('social.telegram', '');
        $this->migrator->add('social.wechat', '');
        $this->migrator->add('social.whatsapp', '');
        $this->migrator->add('social.youtube', $this->legacyValue('youtube', SiteDefaults::get('youtube')));
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('group', ['website', 'seo', 'contact', 'social'])->delete();
        DB::table('settings')->where('group', 'general')->whereIn('name', ['company_name', 'default_language'])->delete();
    }

    private function legacyValue(string $name, mixed $default): mixed
    {
        $payload = DB::table('settings')
            ->where('group', 'general')
            ->where('name', $name)
            ->value('payload');

        if ($payload === null) {
            return $default;
        }

        try {
            return json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return $default;
        }
    }
};
