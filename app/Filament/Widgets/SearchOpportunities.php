<?php

namespace App\Filament\Widgets;

use App\Domain\Analytics\AnalyticsReports;

/**
 * Search terms at position 8 – 20 with the most impressions, and the page Google shows:
 * the pages worth improving first (content, FAQ, internal links) to reach the top 5.
 */
class SearchOpportunities extends AnalyticsTable
{
    protected static ?int $sort = 11;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return app(AnalyticsReports::class)->searchConsoleConfigured();
    }

    protected function heading(): string
    {
        return 'Cơ hội lên top (vị trí 8 – 20)';
    }

    protected function source(): string
    {
        return 'Search Console · từ khóa sắp vào top, nên tối ưu trang bên dưới mỗi từ khóa trước';
    }

    protected function columns(): array
    {
        return ['Từ khóa · trang', 'Hiển thị', 'Nhấp', 'CTR', 'Vị trí'];
    }

    protected function rows(): array
    {
        $rows = $this->reports()->searchOpportunities($this->searchPeriod());
        $bars = self::bars($rows, 'impressions');

        return array_map(fn (array $row, float $bar): array => [
            'cells' => [$row['query'], self::number($row['impressions']), self::number($row['clicks']), self::number($row['ctr'] * 100, 1).'%', self::number($row['position'], 1)],
            'sub' => $row['page'],
            'href' => $row['url'],
            'bar' => $bar,
        ], $rows, $bars);
    }
}
