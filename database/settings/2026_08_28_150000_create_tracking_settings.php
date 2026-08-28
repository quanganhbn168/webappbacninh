<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('tracking.enabled', true);
        $this->migrator->add('tracking.google_tag_id', 'G-ZEKMT39KKJ');
        $this->migrator->add('tracking.head_code', '');
        $this->migrator->add('tracking.body_start_code', '');
        $this->migrator->add('tracking.body_end_code', '');
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('tracking.enabled');
        $this->migrator->deleteIfExists('tracking.google_tag_id');
        $this->migrator->deleteIfExists('tracking.head_code');
        $this->migrator->deleteIfExists('tracking.body_start_code');
        $this->migrator->deleteIfExists('tracking.body_end_code');
    }
};
