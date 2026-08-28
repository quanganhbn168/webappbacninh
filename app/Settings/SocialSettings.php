<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SocialSettings extends Settings
{
    public string $facebook;

    public string $messenger;

    public string $zalo;

    public string $telegram;

    public string $wechat_id;

    public string $wechat_qr;

    public string $whatsapp;

    public string $youtube;

    public static function group(): string
    {
        return 'social';
    }
}
