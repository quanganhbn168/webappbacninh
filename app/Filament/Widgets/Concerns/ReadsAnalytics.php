<?php

namespace App\Filament\Widgets\Concerns;

use App\Domain\Analytics\AnalyticsReports;
use App\Domain\Analytics\Period;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget\Stat;

/** Shared by the dashboard widgets: the selected period, number formats and change badges. */
trait ReadsAnalytics
{
    protected function reports(): AnalyticsReports
    {
        return app(AnalyticsReports::class);
    }

    protected function days(): int
    {
        return (int) ($this->pageFilters['days'] ?? 28);
    }

    protected function ga4Period(): Period
    {
        return Period::forGa4($this->days());
    }

    protected function searchPeriod(): Period
    {
        return Period::forSearchConsole($this->days());
    }

    protected static function number(float|int $value, int $decimals = 0): string
    {
        return number_format($value, $decimals, ',', '.');
    }

    protected static function duration(float $seconds): string
    {
        $seconds = (int) round($seconds);

        return intdiv($seconds, 60).' phút '.str_pad((string) ($seconds % 60), 2, '0', STR_PAD_LEFT).' giây';
    }

    /**
     * A stat with its change against the previous period. $lowerIsBetter flips the colour
     * (average position in Google: going down is good).
     */
    protected static function compared(string $label, string $value, float $current, float $previous, ?array $chart = null, bool $lowerIsBetter = false): Stat
    {
        $stat = Stat::make($label, $value);
        if ($chart) {
            $stat->chart(array_values($chart));
        }

        if ($previous == 0.0) {
            return $stat->description($current > 0 ? 'Kỳ trước chưa có số liệu' : 'Chưa có số liệu')->color('gray');
        }

        $change = ($current - $previous) / $previous * 100;
        $better = $lowerIsBetter ? $change < 0 : $change > 0;

        return $stat
            ->description(($change >= 0 ? '+' : '').self::number($change, abs($change) < 10 ? 1 : 0).'% so với kỳ trước')
            ->descriptionIcon($change >= 0 ? Heroicon::ArrowTrendingUp : Heroicon::ArrowTrendingDown)
            ->color(abs($change) < 0.5 ? 'gray' : ($better ? 'success' : 'danger'));
    }
}
