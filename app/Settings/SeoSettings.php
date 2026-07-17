<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SeoSettings extends Settings
{
    public string $default_meta_title;

    public string $default_meta_description;

    public string $default_meta_keywords;

    public string $default_og_image;

    public string $google_site_verification;

    public string $google_analytics_id;

    public static function group(): string
    {
        return 'seo';
    }
}
