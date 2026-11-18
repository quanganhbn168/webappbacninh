<?php

namespace App\Filament\Widgets;

use App\Domain\Analytics\AnalyticsReports;
use App\Domain\Analytics\AnalyticsUnavailable;
use App\Filament\Widgets\Concerns\ReadsAnalytics;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/** How the site appears in Google Search, from Search Console. */
class SearchOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;
    use ReadsAnalytics;

    protected static ?int $sort = 2;

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return app(AnalyticsReports::class)->searchConsoleConfigured();
    }

    protected function getHeading(): ?string
    {
        return 'Google Tìm kiếm';
    }

    protected function getDescription(): ?string
    {
        return 'Search Console · '.$this->searchPeriod()->label().' (Google cập nhật chậm khoảng 2 – 3 ngày)';
    }

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        try {
            $period = $this->searchPeriod();
            ['current' => $now, 'previous' => $before] = $this->reports()->searchTotals($period);
            $daily = $this->reports()->searchDaily($period);
        } catch (AnalyticsUnavailable $e) {
            return [Stat::make('Search Console', 'Lỗi kết nối')->description($e->getMessage())->color('danger')];
        }

        return [
            self::compared('Lượt nhấp', self::number($now['clicks']), $now['clicks'], $before['clicks'], array_column($daily, 'clicks')),
            self::compared('Lượt hiển thị', self::number($now['impressions']), $now['impressions'], $before['impressions'], array_column($daily, 'impressions')),
            self::compared('Tỷ lệ nhấp (CTR)', self::number($now['ctr'] * 100, 1).'%', $now['ctr'], $before['ctr']),
            self::compared('Vị trí trung bình', self::number($now['position'], 1), $now['position'], $before['position'], lowerIsBetter: true),
        ];
    }
}
