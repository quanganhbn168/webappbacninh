<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class WebsiteSettings extends Settings
{
    public string $site_url;

    /** The logos are pictures of the media library. */
    public ?int $logo_wide_media_id;

    public ?int $logo_white_media_id;

    public ?int $logo_square_media_id;

    public string $site_favicon;

    public static function group(): string
    {
        return 'website';
    }
}
