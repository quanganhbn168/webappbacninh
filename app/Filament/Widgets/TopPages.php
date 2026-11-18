<?php

namespace App\Filament\Widgets;

use App\Domain\Analytics\AnalyticsReports;

/** Most viewed pages (GA4). */
class TopPages extends AnalyticsTable
{
    protected static ?int $sort = 5;

    public static function canView(): bool
    {
        return app(AnalyticsReports::class)->ga4Configured();
    }

    protected function heading(): string
    {
        return 'Trang được xem nhiều';
    }

    protected function source(): string
    {
        return 'Google Analytics 4';
    }

    protected function columns(): array
    {
        return ['Trang', 'Lượt xem', 'Người dùng'];
    }

    protected function rows(): array
    {
        $rows = $this->reports()->ga4TopPages($this->ga4Period());
        $bars = self::bars($rows, 'value');

        return array_map(fn (array $row, float $bar): array => [
            'cells' => [$row['label'], self::number($row['value']), self::number($row['extra'])],
            'href' => url($row['label']),
            'bar' => $bar,
        ], $rows, $bars);
    }
}
