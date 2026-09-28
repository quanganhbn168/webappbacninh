<?php

namespace App\Enums;

enum PageTemplate: string
{
    case Document = 'document';
    case About = 'about';
    case Agency = 'agency';

    public function label(): string
    {
        return match ($this) {
            self::Document => 'Văn bản (chính sách, điều khoản, hướng dẫn)',
            self::About => 'Giới thiệu (giao diện thiết kế sẵn)',
            self::Agency => 'Hợp tác Agency (giao diện thiết kế sẵn)',
        };
    }

    public function view(): string
    {
        return match ($this) {
            self::Document => 'frontend.site.pages.document',
            self::About => 'frontend.site.pages.about',
            self::Agency => 'frontend.site.pages.agency',
        };
    }

    public function usesBlocks(): bool
    {
        return $this === self::Document;
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $template): array => [$template->value => $template->label()])->all();
    }
}
