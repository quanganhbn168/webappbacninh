<?php

namespace App\Filament\Widgets;

use App\Domain\Analytics\AnalyticsReports;
use App\Domain\Analytics\AnalyticsUnavailable;
use App\Filament\Widgets\Concerns\ReadsAnalytics;
use Carbon\CarbonImmutable;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

/** Clicks and impressions per day in Google Search; impressions on their own axis. */
class SearchChart extends ChartWidget
{
    use InteractsWithPageFilters;
    use ReadsAnalytics;

    protected static ?int $sort = 4;

    protected ?string $heading = 'Lượt nhấp và hiển thị trên Google';

    protected ?string $pollingInterval = null;

    protected ?string $maxHeight = '280px';

    public ?string $error = null;

    public static function canView(): bool
    {
        return app(AnalyticsReports::class)->searchConsoleConfigured();
    }

    public function getDescription(): ?string
    {
        return $this->error ?? 'Search Console';
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        try {
            $daily = $this->reports()->searchDaily($this->searchPeriod());
            $this->error = null;
        } catch (AnalyticsUnavailable $e) {
            $this->error = $e->getMessage();
            $daily = [];
        }

        return [
            'datasets' => [
                ['label' => 'Lượt nhấp', 'data' => array_column($daily, 'clicks'), 'borderColor' => '#2563eb', 'backgroundColor' => 'rgba(37,99,235,.12)', 'fill' => true, 'tension' => .3, 'yAxisID' => 'y'],
                ['label' => 'Lượt hiển thị', 'data' => array_column($daily, 'impressions'), 'borderColor' => '#7c3aed', 'backgroundColor' => 'transparent', 'tension' => .3, 'yAxisID' => 'y1'],
            ],
            'labels' => array_map(fn (string $date): string => CarbonImmutable::parse($date)->format('d/m'), array_keys($daily)),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => ['position' => 'left', 'beginAtZero' => true, 'title' => ['display' => true, 'text' => 'Lượt nhấp']],
                'y1' => ['position' => 'right', 'beginAtZero' => true, 'grid' => ['drawOnChartArea' => false], 'title' => ['display' => true, 'text' => 'Hiển thị']],
            ],
        ];
    }
}
