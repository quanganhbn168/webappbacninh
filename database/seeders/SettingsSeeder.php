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
            ['key' => 'site_logo_wide', 'value' => 'images/logo-wide.png', 'group' => 'branding'], // Logo dài (Header)
            ['key' => 'site_logo_white', 'value' => 'images/logo-white.png', 'group' => 'branding'], // Logo trắng (Nền đen)
            ['key' => 'site_logo_square', 'value' => 'images/logo-square.png', 'group' => 'branding'], // Logo vuông (Mobile, Footer)
            ['key' => 'site_favicon', 'value' => 'images/favicon.ico', 'group' => 'branding'],
            
            // Contact
            ['key' => 'contact_email', 'value' => 'contact@webappbacninh.com', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '0987654321', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => 'TP. Bắc Ninh, Bắc Ninh', 'group' => 'contact'],
            
            // SEO & Social
            ['key' => 'default_og_image', 'value' => 'images/default-og.jpg', 'group' => 'seo'], // Ảnh chia sẻ mặc định
            ['key' => 'meta_keywords', 'value' => 'thiết kế web, web bắc ninh', 'group' => 'seo'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/webappbacninh', 'group' => 'social'],
            ['key' => 'zalo_url', 'value' => 'https://zalo.me/0987654321', 'group' => 'social'],
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
