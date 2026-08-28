<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ContactSettings extends Settings
{
    public string $phone;

    public string $phone_href;

    public string $phone_secondary;

    public string $phone_secondary_href;

    public string $email;

    public string $address;

    public string $working_time;

    public static function group(): string
    {
        return 'contact';
    }
}
