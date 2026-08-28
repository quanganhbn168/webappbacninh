<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('contact.phone_secondary', '');
        $this->migrator->add('contact.phone_secondary_href', '');
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('contact.phone_secondary');
        $this->migrator->deleteIfExists('contact.phone_secondary_href');
    }
};
