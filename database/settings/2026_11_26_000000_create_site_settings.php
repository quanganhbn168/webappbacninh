<?php

use App\Settings\SiteDefaults;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

/** Every setting of the site with its starting value; SettingsSeeder fills in the company's own. */
return new class extends SettingsMigration
{
    public function up(): void
    {
        $groups = [
            'general' => ['name' => SiteDefaults::get('name'), 'company_name' => SiteDefaults::get('name'), 'default_language' => 'vi'],
            'website' => ['site_url' => SiteDefaults::get('site_url'), 'logo_wide_media_id' => null, 'logo_white_media_id' => null, 'logo_square_media_id' => null, 'site_favicon' => ''],
            'contact' => [
                'phone' => SiteDefaults::get('phone'), 'phone_href' => SiteDefaults::get('phone_href'),
                'phone_secondary' => SiteDefaults::get('phone_secondary'), 'phone_secondary_href' => SiteDefaults::get('phone_secondary_href'),
                'email' => SiteDefaults::get('email'), 'address' => SiteDefaults::get('address'), 'working_time' => SiteDefaults::get('working_time'),
            ],
            'social' => ['facebook' => '', 'messenger' => '', 'zalo' => '', 'telegram' => '', 'wechat_id' => '', 'wechat_qr' => '', 'whatsapp' => '', 'youtube' => ''],
            'seo' => ['default_meta_title' => SiteDefaults::get('name'), 'default_meta_description' => '', 'default_meta_keywords' => '', 'default_og_image' => '', 'google_site_verification' => ''],
            'tracking' => ['enabled' => true, 'head_code' => '', 'body_start_code' => '', 'body_end_code' => ''],
            'favicon' => ['short_name' => SiteDefaults::get('name'), 'maskable_icon' => '', 'safari_mask_icon' => '', 'theme_color' => '#0f172a', 'background_color' => '#ffffff', 'safari_mask_color' => '#0f172a', 'generated_version' => ''],
            'project' => [
                'code_prefix' => 'DA-', 'code_suffix' => '', 'code_digits' => 3, 'code_next' => 1,
                'deliverable_presets' => ['Trang chủ', 'Giới thiệu', 'Dịch vụ', 'Sản phẩm', 'Chi tiết sản phẩm', 'Giỏ hàng', 'Đặt hàng', 'Dự án', 'Tin tức', 'Liên hệ', 'Form tư vấn', 'Quản trị nội dung', 'Quản lý đơn hàng', 'Giao diện responsive', 'SEO nền tảng', 'Hướng dẫn sử dụng'],
            ],
        ];

        foreach ($groups as $group => $settings) {
            foreach ($settings as $name => $value) {
                $this->migrator->add("{$group}.{$name}", $value);
            }
        }
    }
};
