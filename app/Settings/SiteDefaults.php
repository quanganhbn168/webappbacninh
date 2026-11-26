<?php

namespace App\Settings;

/**
 * Values used before anything is saved in Cài đặt, and to seed a fresh install.
 */
final class SiteDefaults
{
    public const VALUES = [
        'name' => 'WebApp Bắc Ninh',
        'site_url' => 'https://webappbacninh.vn',
        'phone' => '096 562 5210',
        'phone_href' => '0965625210',
        'phone_secondary' => '0856 843 891',
        'phone_secondary_href' => '0856843891',
        'email' => 'support@webappbacninh.vn',
        'address' => 'Bắc Ninh, Việt Nam',
        'working_time' => 'Thứ 2 - CN: 00:00 - 23:59',
        'facebook' => 'https://www.facebook.com/webappbacninh',
        'youtube' => '',
        'zalo' => 'https://zalo.me/0856843891',
        'whatsapp' => 'https://wa.me/84856843891',
    ];

    public static function get(string $key): string
    {
        return $key === 'site_url' ? (string) config('app.url', self::VALUES['site_url']) : self::VALUES[$key];
    }

    /**
     * @return array<string, string>
     */
    public static function all(): array
    {
        return array_merge(self::VALUES, ['site_url' => self::get('site_url')]);
    }
}
