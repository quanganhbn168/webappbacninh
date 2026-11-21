<?php

namespace App\Filament\Widgets;

use App\Domain\Analytics\AnalyticsReports;
use App\Domain\Analytics\AnalyticsUnavailable;
use App\Filament\Widgets\Concerns\ReadsAnalytics;
use Filament\Support\Icons\Heroicon;
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
            self::position($now['position'], $before['position']),
        ];
    }

    /**
     * Rank 1 is the top, so a smaller number is better: the change reads as places gained
     * or lost ("Tăng 1,3 bậc") rather than a percentage that grows when the site drops.
     */
    private static function position(float $now, float $before): Stat
    {
        $stat = Stat::make('Vị trí trung bình', self::number($now, 1));
        if ($before == 0.0 || $now == 0.0) {
            return $stat->description('Kỳ trước chưa có số liệu')->color('gray');
        }

        $gained = round($before - $now, 1);
        if (abs($gained) < 0.1) {
            return $stat->description('Giữ nguyên so với kỳ trước')->color('gray');
        }

        return $stat
            ->description(($gained > 0 ? 'Tăng ' : 'Tụt ').self::number(abs($gained), 1).' bậc so với kỳ trước')
            ->descriptionIcon($gained > 0 ? Heroicon::ArrowTrendingUp : Heroicon::ArrowTrendingDown)
            ->color($gained > 0 ? 'success' : 'danger');
    }
}
