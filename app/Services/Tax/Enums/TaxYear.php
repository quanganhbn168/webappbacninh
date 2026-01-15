<?php

namespace App\Services\Tax\Enums;

enum TaxYear: string
{
    case Y2025 = '2025';
    case Y2026 = '2026';

    public function label(): string
    {
        return match ($this) {
            self::Y2025 => '2025 (Luật cũ)',
            self::Y2026 => '2026 (Luật mới)',
        };
    }
}
