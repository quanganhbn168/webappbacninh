<?php

namespace App\Enums;

enum BannerSlot: string
{
    case HOMEPAGE_HERO = 'homepage_hero';
    case HOMEPAGE_PROMO = 'homepage_promo';
    case AFTER_HERO = 'after_hero';
    case BEFORE_BLOG = 'before_blog';
    case SIDEBAR = 'sidebar';
    case POPUP = 'popup';

    public function label(): string
    {
        return match($this) {
            self::HOMEPAGE_HERO => 'Trang chủ - Hero',
            self::HOMEPAGE_PROMO => 'Trang chủ - Promo',
            self::AFTER_HERO => 'Sau Hero',
            self::BEFORE_BLOG => 'Trước Blog',
            self::SIDEBAR => 'Sidebar',
            self::POPUP => 'Popup',
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
