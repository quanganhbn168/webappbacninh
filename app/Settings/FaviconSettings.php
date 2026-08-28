<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class FaviconSettings extends Settings
{
    public string $short_name;

    public string $maskable_icon;

    public string $safari_mask_icon;

    public string $theme_color;

    public string $background_color;

    public string $safari_mask_color;

    public string $generated_version;

    public static function group(): string
    {
        return 'favicon';
    }
}
