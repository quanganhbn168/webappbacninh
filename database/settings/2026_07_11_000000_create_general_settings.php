<?php

use App\Settings\SiteDefaults;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $defaults = SiteDefaults::all();

        foreach ($defaults as $name => $value) {
            $this->migrator->add("general.{$name}", $value);
        }
    }
};
