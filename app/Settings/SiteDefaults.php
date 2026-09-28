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
        'phone' => '0986 123 168',
        'phone_href' => '0986123168',
        'phone_secondary' => '',
        'phone_secondary_href' => '',
        'email' => 'info@webappbacninh.vn',
        'address' => 'Bắc Ninh, Việt Nam',
        'working_time' => 'Thứ 2 - Thứ 7: 08:00 - 18:00',
        'facebook' => '',
        'youtube' => '',
        'zalo' => '',
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
