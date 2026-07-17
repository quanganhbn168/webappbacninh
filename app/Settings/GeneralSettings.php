<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $name;

    public string $company_name;

    public string $default_language;

    public static function group(): string
    {
        return 'general';
    }
}
