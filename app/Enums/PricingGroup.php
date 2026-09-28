<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PricingGroup: string implements HasLabel
{
    case Website = 'website';
    case Software = 'software';
    case Hosting = 'hosting';
    case Care = 'care';
    case Addon = 'addon';

    public function getLabel(): string
    {
        return $this->label();
    }

    public function label(): string
    {
        return match ($this) {
            self::Website => 'Website',
            self::Software => 'CRM & Phần mềm',
            self::Hosting => 'Hosting / Domain / Email',
            self::Care => 'Chăm sóc & Vận hành',
            self::Addon => 'Dịch vụ bổ sung',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $group): array => [$group->value => $group->label()])->all();
    }

    /**
     * Groups shown as tabs in the pricing table.
     *
     * @return array<int, self>
     */
    public static function tabs(): array
    {
        return [self::Website, self::Software, self::Hosting, self::Care];
    }
}
