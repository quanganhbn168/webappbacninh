<?php

namespace App\Filament;

use Filament\Support\Contracts\HasLabel;

/**
 * Admin sidebar groups, in the order they appear. Every resource and page sets
 * `$navigationGroup = NavigationGroup::X` and a `$navigationSort` inside its group.
 */
enum NavigationGroup: string implements HasLabel
{
    /** Việc cần xử lý hằng ngày */
    case Customers = 'customers';

    /** Khung website: trang, menu, banner, ảnh */
    case Website = 'website';

    /** Những gì đang bán */
    case Services = 'services';

    /** Hồ sơ năng lực: dự án, giao diện mẫu, đánh giá */
    case Portfolio = 'portfolio';

    case Articles = 'articles';

    case System = 'system';

    public function getLabel(): string
    {
        return match ($this) {
            self::Customers => 'Khách hàng',
            self::Website => 'Website',
            self::Services => 'Dịch vụ & Bảng giá',
            self::Portfolio => 'Dự án & Giao diện',
            self::Articles => 'Kiến thức',
            self::System => 'Hệ thống',
        };
    }
}
