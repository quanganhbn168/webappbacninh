<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class WebsiteSettings extends Settings
{
    public string $site_url;

    public string $site_logo_wide;

    public string $site_logo_white;

    public string $site_logo_square;

    public string $site_favicon;

    public static function group(): string
    {
        return 'website';
    }
}
