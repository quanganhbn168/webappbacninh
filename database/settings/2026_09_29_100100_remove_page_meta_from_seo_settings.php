<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

// Per-page SEO now lives in the pages table (admin: Trang).
return new class extends SettingsMigration
{
    public function up(): void
    {
        if ($this->migrator->exists('seo.page_meta')) {
            $this->migrator->delete('seo.page_meta');
        }
    }

    public function down(): void
    {
        $this->migrator->add('seo.page_meta', []);
    }
};
