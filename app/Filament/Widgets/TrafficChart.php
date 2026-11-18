<?php

namespace App\Filament\Widgets;

use App\Domain\Analytics\AnalyticsReports;
use App\Domain\Analytics\AnalyticsUnavailable;
use App\Filament\Widgets\Concerns\ReadsAnalytics;
use Carbon\CarbonImmutable;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

/** Users and sessions per day (GA4). */
class TrafficChart extends ChartWidget
{
    use InteractsWithPageFilters;
    use ReadsAnalytics;

    protected static ?int $sort = 3;

    protected ?string $heading = 'Người dùng và phiên theo ngày';

    protected ?string $pollingInterval = null;

    protected ?string $maxHeight = '280px';

    public ?string $error = null;

    public static function canView(): bool
    {
        return app(AnalyticsReports::class)->ga4Configured();
    }

    public function getDescription(): ?string
    {
        return $this->error ?? 'Google Analytics 4';
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        try {
            $daily = $this->reports()->ga4Daily($this->ga4Period());
            $this->error = null;
        } catch (AnalyticsUnavailable $e) {
            $this->error = $e->getMessage();
            $daily = [];
        }

        return [
            'datasets' => [
                ['label' => 'Người dùng', 'data' => array_column($daily, 'users'), 'borderColor' => '#d97706', 'backgroundColor' => 'rgba(217,119,6,.12)', 'fill' => true, 'tension' => .3],
                ['label' => 'Phiên', 'data' => array_column($daily, 'sessions'), 'borderColor' => '#475569', 'backgroundColor' => 'transparent', 'tension' => .3],
            ],
            'labels' => array_map(fn (string $date): string => CarbonImmutable::parse($date)->format('d/m'), array_keys($daily)),
        ];
    }
}
