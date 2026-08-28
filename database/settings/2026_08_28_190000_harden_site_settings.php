<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        foreach (['facebook', 'messenger', 'zalo', 'telegram', 'wechat', 'whatsapp', 'youtube'] as $channel) {
            $property = "social.{$channel}";

            if (! $this->migrator->exists($property)) {
                continue;
            }

            $this->migrator->update(
                $property,
                static fn (mixed $value): mixed => trim((string) $value) === '#' ? '' : $value,
            );
        }

        $this->migrator->deleteIfExists('seo.google_analytics_id');
    }

    public function down(): void
    {
        if (! $this->migrator->exists('seo.google_analytics_id')) {
            $this->migrator->add('seo.google_analytics_id', '');
        }
    }
};
