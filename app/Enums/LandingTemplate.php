<?php

namespace App\Enums;

use App\Domain\Landing\FreshairDefaults;
use App\Domain\Landing\PurehomeDefaults;

/** The fixed landing page layouts. Adding one means a new view, a defaults class and an admin field set. */
enum LandingTemplate: string
{
    case Purehome = 'purehome';
    case Freshair = 'freshair';

    public function label(): string
    {
        return match ($this) {
            self::Purehome => 'Mẫu 1 — PureHome (nhẹ, xanh dương, đặt hàng qua điện thoại)',
            self::Freshair => 'Mẫu 2 — FreshAir (bán hàng, thư viện ảnh, voucher, giỏ hàng)',
        };
    }

    public function view(): string
    {
        return 'landing.'.$this->value.'.index';
    }

    /** @return array<string, mixed> */
    public function defaults(): array
    {
        return match ($this) {
            self::Purehome => PurehomeDefaults::content(),
            self::Freshair => FreshairDefaults::content(),
        };
    }

    /** @return array{name: string, meta_title: string, meta_description: string, price: int, compare_price: int} */
    public function sample(): array
    {
        return match ($this) {
            self::Purehome => PurehomeDefaults::sample(),
            self::Freshair => FreshairDefaults::sample(),
        };
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $case): array => [$case->value => $case->label()])->all();
    }
}
