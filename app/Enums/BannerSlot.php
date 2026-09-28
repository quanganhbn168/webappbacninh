<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BannerSlot: string implements HasLabel
{
    case HOMEPAGE_HERO = 'homepage_hero';
    case HOMEPAGE_PROMO = 'homepage_promo';
    case AFTER_HERO = 'after_hero';
    case BEFORE_BLOG = 'before_blog';
    case SIDEBAR = 'sidebar';
    case POPUP = 'popup';

    public function getLabel(): string
    {
        return $this->label();
    }

    public function label(): string
    {
        return match ($this) {
            self::HOMEPAGE_HERO => 'Trang chủ - dưới hero',
            self::HOMEPAGE_PROMO => 'Trang chủ - giữa trang',
            self::AFTER_HERO => 'Trang dịch vụ - sau hero',
            self::BEFORE_BLOG => 'Trang chủ - trước Kiến thức',
            self::SIDEBAR => 'Bài viết - cột phải',
            self::POPUP => 'Popup',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())->reject(fn (self $slot): bool => $slot === self::POPUP)->mapWithKeys(fn (self $slot): array => [$slot->value => $slot->label()])->all();
    }
}
