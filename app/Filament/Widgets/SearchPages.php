<?php

namespace App\Filament\Widgets;

use App\Domain\Analytics\AnalyticsReports;

/** Pages that get the most clicks from Google (Search Console). */
class SearchPages extends AnalyticsTable
{
    protected static ?int $sort = 10;

    public static function canView(): bool
    {
        return app(AnalyticsReports::class)->searchConsoleConfigured();
    }

    protected function heading(): string
    {
        return 'Trang được nhấp từ Google';
    }

    protected function source(): string
    {
        return 'Search Console';
    }

    protected function columns(): array
    {
        return ['Trang', 'Nhấp', 'Hiển thị', 'Vị trí'];
    }

    protected function rows(): array
    {
        $rows = $this->reports()->searchTopPages($this->searchPeriod());
        $bars = self::bars($rows, 'clicks');

        return array_map(fn (array $row, float $bar): array => [
            'cells' => [$row['label'], self::number($row['clicks']), self::number($row['impressions']), self::number($row['position'], 1)],
            'href' => $row['url'],
            'bar' => $bar,
        ], $rows, $bars);
    }
}
