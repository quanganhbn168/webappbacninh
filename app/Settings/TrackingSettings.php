<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class TrackingSettings extends Settings
{
    public bool $enabled;

    public string $google_tag_id;

    public string $head_code;

    public string $body_start_code;

    public string $body_end_code;

    public static function group(): string
    {
        return 'tracking';
    }
}
