<?php

use Illuminate\Support\Facades\DB;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.company_name', $this->legacyValue('name', config('site.name')));
        $this->migrator->add('general.default_language', 'vi');

        $this->migrator->add('website.site_url', $this->legacyValue('site_url', config('site.site_url')));
        $this->migrator->add('website.site_logo_wide', '');
        $this->migrator->add('website.site_logo_white', '');
        $this->migrator->add('website.site_logo_square', '');
        $this->migrator->add('website.site_favicon', '');

        $this->migrator->add('seo.default_meta_title', $this->legacyValue('name', config('site.name')));
        $this->migrator->add('seo.default_meta_description', 'Thiết kế website theo nhu cầu doanh nghiệp, tối ưu SEO và chuyển đổi.');
        $this->migrator->add('seo.default_meta_keywords', 'thiết kế website, web Bắc Ninh, website doanh nghiệp');
        $this->migrator->add('seo.default_og_image', '');
        $this->migrator->add('seo.google_site_verification', '');
        $this->migrator->add('seo.google_analytics_id', '');

        $this->migrator->add('contact.phone', $this->legacyValue('phone', config('site.phone')));
        $this->migrator->add('contact.phone_href', $this->legacyValue('phone_href', config('site.phone_href')));
        $this->migrator->add('contact.email', $this->legacyValue('email', config('site.email')));
        $this->migrator->add('contact.address', $this->legacyValue('address', config('site.address')));
        $this->migrator->add('contact.working_time', $this->legacyValue('working_time', config('site.working_time')));

        $this->migrator->add('social.facebook', $this->legacyValue('facebook', config('site.facebook')));
        $this->migrator->add('social.messenger', '');
        $this->migrator->add('social.zalo', $this->legacyValue('zalo', config('site.zalo')));
        $this->migrator->add('social.telegram', '');
        $this->migrator->add('social.wechat', '');
        $this->migrator->add('social.whatsapp', '');
        $this->migrator->add('social.youtube', $this->legacyValue('youtube', config('site.youtube')));
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
