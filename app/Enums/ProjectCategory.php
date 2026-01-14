<?php

namespace App\Enums;

enum ProjectCategory: string
{
    case ECOMMERCE = 'ecommerce';
    case FNB = 'fnb';
    case REAL_ESTATE = 'real_estate';
    case CORPORATE = 'corporate';
    case EDUCATION = 'education';
    case HEALTHCARE = 'healthcare';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this) {
            self::ECOMMERCE => 'E-commerce',
            self::FNB => 'F&B',
            self::REAL_ESTATE => 'Bất động sản',
            self::CORPORATE => 'Doanh nghiệp',
            self::EDUCATION => 'Giáo dục',
            self::HEALTHCARE => 'Y tế',
            self::OTHER => 'Khác',
        };
    }

    public static function options(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }
}
