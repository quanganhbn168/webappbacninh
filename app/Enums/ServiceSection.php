<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Where a landing service lives on the public site: website design services
 * under /thiet-ke-website, every other service under /dich-vu.
 */
enum ServiceSection: string implements HasLabel
{
    case Website = 'website';
    case General = 'general';

    public function getLabel(): string
    {
        return match ($this) {
            self::Website => 'Thiết kế website (/thiet-ke-website/…)',
            self::General => 'Dịch vụ khác (/dich-vu/…)',
        };
    }

    public function routeName(): string
    {
        return match ($this) {
            self::Website => 'services.show',
            self::General => 'services.detail',
        };
    }

    /** Breadcrumb parent of a service page in this section. */
    public function parent(): array
    {
        return match ($this) {
            self::Website => ['name' => 'Thiết kế website', 'url' => route('services.index')],
            self::General => ['name' => 'Dịch vụ', 'url' => route('services.overview')],
        };
    }
}
