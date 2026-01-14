<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Branding
            ['key' => 'site_name', 'value' => 'WebApp Bắc Ninh', 'group' => 'branding'],
            ['key' => 'site_description', 'value' => 'Nền tảng thiết kế website chuyên nghiệp, chuẩn SEO và tối ưu chuyển đổi tại Bắc Ninh.', 'group' => 'branding'],
            ['key' => 'site_short_name', 'value' => 'WebAppBN', 'group' => 'branding'],
            ['key' => 'site_logo_wide', 'value' => 'images/logo-wide.png', 'group' => 'branding'], // Logo dài (Header)
            ['key' => 'site_logo_white', 'value' => 'images/logo-white.png', 'group' => 'branding'], // Logo trắng (Nền đen)
            ['key' => 'site_logo_square', 'value' => 'images/logo-square.png', 'group' => 'branding'], // Logo vuông (Mobile, Footer)
            ['key' => 'site_favicon', 'value' => 'images/favicon.ico', 'group' => 'branding'],
            
            // Contact
            ['key' => 'contact_email', 'value' => 'contact@webappbacninh.com', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '0987654321', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => 'TP. Bắc Ninh, Bắc Ninh', 'group' => 'contact'],

            // Mail Configuration
            ['key' => 'mail_mailer', 'value' => 'smtp', 'group' => 'mail'],
            ['key' => 'mail_host', 'value' => 'smtp.gmail.com', 'group' => 'mail'],
            ['key' => 'mail_port', 'value' => '587', 'group' => 'mail'],
            ['key' => 'mail_username', 'value' => '', 'group' => 'mail'],
            ['key' => 'mail_password', 'value' => '', 'group' => 'mail'],
            ['key' => 'mail_encryption', 'value' => 'tls', 'group' => 'mail'],
            ['key' => 'mail_from_address', 'value' => 'noreply@webappbacninh.com', 'group' => 'mail'],
            ['key' => 'mail_from_name', 'value' => 'WebApp Bắc Ninh', 'group' => 'mail'],

            // Payment & Integrations
            ['key' => 'payment_momo_api_key', 'value' => '', 'group' => 'payment'],
            ['key' => 'payment_zalo_app_id', 'value' => '', 'group' => 'payment'],
            ['key' => 'payment_sepay_api_key', 'value' => '', 'group' => 'payment'],
            ['key' => 'payment_vietqr_client_id', 'value' => '', 'group' => 'payment'],
            ['key' => 'payment_vietqr_api_key', 'value' => '', 'group' => 'payment'],
            
            // SEO & Social
            ['key' => 'default_og_image', 'value' => 'images/default-og.jpg', 'group' => 'seo'], // Ảnh chia sẻ mặc định
            ['key' => 'meta_keywords', 'value' => 'thiết kế web, web bắc ninh', 'group' => 'seo'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/webappbacninh', 'group' => 'social'],
            ['key' => 'zalo_url', 'value' => 'https://zalo.me/0987654321', 'group' => 'social'],

            // Hero Section (Homepage)
            ['key' => 'hero_badge', 'value' => 'Phiên bản Beta 1.0', 'group' => 'hero'],
            ['key' => 'hero_title', 'value' => 'Khởi tạo Website<br>Chỉ trong 5 giây', 'group' => 'hero'],
            ['key' => 'hero_subtitle', 'value' => 'Nền tảng công nghệ "All-in-One". Không chỉ là website bán hàng, mà là cả một hệ sinh thái công cụ hỗ trợ kinh doanh, tính toán và quản trị dành riêng cho người Bắc Ninh.', 'group' => 'hero'],
            ['key' => 'hero_cta_text', 'value' => 'Tạo Website Ngay', 'group' => 'hero'],
            ['key' => 'hero_cta_link', 'value' => '#register-section', 'group' => 'hero'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']], 
                [
                    'value' => $setting['value'], 
                    'group' => $setting['group'] ?? 'general'
                ]
            );
        }
    }
}
