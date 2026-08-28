<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('social.wechat_id', '');
        $this->migrator->add('social.wechat_qr', '');

        foreach (['facebook', 'messenger', 'zalo', 'telegram', 'whatsapp', 'youtube'] as $channel) {
            $property = "social.{$channel}";

            if (! $this->migrator->exists($property)) {
                continue;
            }

            $this->migrator->update(
                $property,
                static fn (mixed $value): mixed => str_starts_with(trim((string) $value), '#') ? '' : $value,
            );
        }
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('social.wechat_id');
        $this->migrator->deleteIfExists('social.wechat_qr');

        if (! $this->migrator->exists('social.wechat')) {
            $this->migrator->add('social.wechat', '');
        }
    }
};
