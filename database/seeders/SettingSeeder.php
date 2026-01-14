<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Branding
            ['key' => 'site_name', 'value' => 'WebApp Bắc Ninh', 'group' => 'branding'],
            ['key' => 'site_logo', 'value' => 'images/logo.png', 'group' => 'branding'],
            ['key' => 'site_logo_wide', 'value' => 'images/logo-wide.png', 'group' => 'branding'],
            ['key' => 'site_logo_square', 'value' => 'images/logo-square.png', 'group' => 'branding'],
            ['key' => 'site_favicon', 'value' => 'images/webapp.svg', 'group' => 'branding'],
            
            // Contact
            ['key' => 'contact_phone', 'value' => '0856 843 891', 'group' => 'contact'],
            ['key' => 'contact_email', 'value' => 'webappbacninh@gmail.com', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => 'Nhị Trai, Trung Chính, Lương Tài, Bắc Ninh', 'group' => 'contact'],
            ['key' => 'contact_name', 'value' => 'Mr. Trần Quang Anh', 'group' => 'contact'],
            
            // Socials
            ['key' => 'social_facebook', 'value' => 'https://www.facebook.com/webappbacninh', 'group' => 'social'],
            ['key' => 'social_youtube', 'value' => '#', 'group' => 'social'],
            ['key' => 'social_tiktok', 'value' => '#', 'group' => 'social'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
