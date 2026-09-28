<?php

namespace App\Enums;

enum SocialProvider: string
{
    case Google = 'google';
    case Facebook = 'facebook';
    case Zalo = 'zalo';

    public function label(): string
    {
        return match ($this) {
            self::Google => 'Google',
            self::Facebook => 'Facebook',
            self::Zalo => 'Zalo',
        };
    }

    /**
     * Only Google guarantees the address belongs to the person signing in,
     * so only Google may attach itself to an existing account by email.
     */
    public function verifiesEmail(): bool
    {
        return $this === self::Google;
    }

    public function isConfigured(): bool
    {
        return filled(config("services.{$this->value}.client_id")) && filled(config("services.{$this->value}.client_secret"));
    }

    /**
     * @return array<int, self>
     */
    public static function configured(): array
    {
        return array_values(array_filter(self::cases(), fn (self $provider): bool => $provider->isConfigured()));
    }
}
