<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('favicon.short_name', 'WebApp Bắc Ninh');
        $this->migrator->add('favicon.maskable_icon', '');
        $this->migrator->add('favicon.safari_mask_icon', '');
        $this->migrator->add('favicon.theme_color', '#0f172a');
        $this->migrator->add('favicon.background_color', '#ffffff');
        $this->migrator->add('favicon.safari_mask_color', '#0f172a');
        $this->migrator->add('favicon.generated_version', '');
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('favicon.short_name');
        $this->migrator->deleteIfExists('favicon.maskable_icon');
        $this->migrator->deleteIfExists('favicon.safari_mask_icon');
        $this->migrator->deleteIfExists('favicon.theme_color');
        $this->migrator->deleteIfExists('favicon.background_color');
        $this->migrator->deleteIfExists('favicon.safari_mask_color');
        $this->migrator->deleteIfExists('favicon.generated_version');
    }
};
