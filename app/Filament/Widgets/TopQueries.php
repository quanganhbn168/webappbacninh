<?php

namespace App\Filament\Widgets;

use App\Domain\Analytics\AnalyticsReports;

/** Search terms that bring the site up in Google (Search Console). */
class TopQueries extends AnalyticsTable
{
    protected static ?int $sort = 7;

    public static function canView(): bool
    {
        return app(AnalyticsReports::class)->searchConsoleConfigured();
    }

    protected function heading(): string
    {
        return 'Từ khóa trên Google';
    }

    protected function source(): string
    {
        return 'Search Console';
    }

    protected function columns(): array
    {
        return ['Từ khóa', 'Nhấp', 'Hiển thị', 'CTR', 'Vị trí'];
    }

    protected function rows(): array
    {
        $rows = $this->reports()->searchTopQueries($this->searchPeriod());
        $bars = self::bars($rows, 'clicks');

        return array_map(fn (array $row, float $bar): array => [
            'cells' => [$row['label'], self::number($row['clicks']), self::number($row['impressions']), self::number($row['ctr'] * 100, 1).'%', self::number($row['position'], 1)],
            'bar' => $bar,
        ], $rows, $bars);
    }
}
