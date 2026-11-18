<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

// The Google tag ID field is gone: Google Analytics is pasted into the head code with every other tag.
return new class extends SettingsMigration
{
    public function up(): void
    {
        if ($this->migrator->exists('tracking.google_tag_id')) {
            $this->migrator->delete('tracking.google_tag_id');
        }
    }

    public function down(): void
    {
        $this->migrator->add('tracking.google_tag_id', '');
    }
};
