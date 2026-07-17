<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $defaults = config('site');

        foreach ($defaults as $name => $value) {
            $this->migrator->add("general.{$name}", $value);
        }
    }
};
