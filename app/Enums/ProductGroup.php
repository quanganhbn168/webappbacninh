<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductGroup: string implements HasLabel
{
    case Website = 'website';
    case Crm = 'crm';
    case Booking = 'booking';
    case Erp = 'erp';
    case Custom = 'custom';

    public function getLabel(): string
    {
        return $this->label();
    }

    public function label(): string
    {
        return match ($this) {
            self::Website => 'Website theo ngành',
            self::Crm => 'CRM',
            self::Booking => 'Booking System',
            self::Erp => 'Mini ERP',
            self::Custom => 'Phần mềm riêng',
        };
    }

    /** Label on the product card image. */
    public function badge(): string
    {
        return match ($this) {
            self::Website => 'Website theo ngành',
            self::Crm, self::Booking, self::Erp => 'Phần mềm quản lý',
            self::Custom => 'Giải pháp tùy chỉnh',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $group): array => [$group->value => $group->label()])->all();
    }
}
