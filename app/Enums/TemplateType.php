<?php

namespace App\Enums;

enum TemplateType: string
{
    case Business = 'doanh-nghiep';
    case Commerce = 'ban-hang';
    case Landing = 'landing-page';
    case Service = 'dich-vu';

    public function label(): string
    {
        return match ($this) {
            self::Business => 'Website doanh nghiệp',
            self::Commerce => 'Website bán hàng',
            self::Landing => 'Landing page',
            self::Service => 'Website dịch vụ',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $type): array => [$type->value => $type->label()])->all();
    }
}
