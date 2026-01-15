<?php

namespace App\Services\Tax\Enums;

enum BusinessSector: string
{
    case TRADING = 'trading';                           // Phân phối, cung cấp hàng hóa
    case SERVICE = 'service';                           // Dịch vụ, ăn uống
    case MANUFACTURING = 'manufacturing';               // Sản xuất, vận tải
    case CONSTRUCTION_WITH_MATERIALS = 'construction_with';    // Xây dựng có thầu NVL
    case CONSTRUCTION_WITHOUT_MATERIALS = 'construction_without'; // Xây dựng không thầu NVL
    case DIGITAL_CONTENT = 'digital';                   // Nội dung số, game

    public function label(): string
    {
        return match ($this) {
            self::TRADING => 'Phân phối, cung cấp hàng hóa',
            self::SERVICE => 'Dịch vụ, ăn uống, lưu trú',
            self::MANUFACTURING => 'Sản xuất, vận tải',
            self::CONSTRUCTION_WITH_MATERIALS => 'Xây dựng có bao thầu nguyên vật liệu',
            self::CONSTRUCTION_WITHOUT_MATERIALS => 'Xây dựng không bao thầu nguyên vật liệu',
            self::DIGITAL_CONTENT => 'Nội dung số, trò chơi điện tử',
        };
    }

    /**
     * Get VAT rate for this sector.
     */
    public function vatRate(): float
    {
        return match ($this) {
            self::TRADING => 0.01,  // 1%
            self::SERVICE => 0.05,  // 5%
            self::MANUFACTURING => 0.03,  // 3%
            self::CONSTRUCTION_WITH_MATERIALS => 0.03,  // 3%
            self::CONSTRUCTION_WITHOUT_MATERIALS => 0.05,  // 5%
            self::DIGITAL_CONTENT => 0.05,  // 5%
        };
    }

    /**
     * Get PIT rate for this sector (Household business).
     */
    public function pitRate(): float
    {
        return match ($this) {
            self::TRADING => 0.005,  // 0.5%
            self::SERVICE => 0.02,   // 2%
            self::MANUFACTURING => 0.015,  // 1.5%
            self::CONSTRUCTION_WITH_MATERIALS => 0.015,  // 1.5%
            self::CONSTRUCTION_WITHOUT_MATERIALS => 0.02,  // 2%
            self::DIGITAL_CONTENT => 0.05,  // 5% (tăng từ 2%)
        };
    }

    /**
     * Get all sectors as array for frontend.
     */
    public static function toArray(): array
    {
        return array_map(fn ($case) => [
            'value' => $case->value,
            'label' => $case->label(),
            'vat_rate' => $case->vatRate() * 100,
            'pit_rate' => $case->pitRate() * 100,
        ], self::cases());
    }
}
