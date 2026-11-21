<?php

namespace App\Filament\Widgets;

use App\Domain\Analytics\AnalyticsReports;
use App\Domain\Analytics\AnalyticsUnavailable;
use App\Filament\Widgets\Concerns\ReadsAnalytics;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

/** Sessions by device (GA4), apart from the channels so the two totals are never added up. */
class TrafficDevices extends ChartWidget
{
    use InteractsWithPageFilters;
    use ReadsAnalytics;

    private const DEVICES = ['desktop' => 'Máy tính', 'mobile' => 'Điện thoại', 'tablet' => 'Máy tính bảng', 'smart tv' => 'TV'];

    private const COLORS = ['#2563eb', '#d97706', '#7c3aed', '#64748b', '#0d9488'];

    protected static ?int $sort = 7;

    protected ?string $heading = 'Thiết bị';

    protected ?string $pollingInterval = null;

    protected ?string $maxHeight = '240px';

    public ?string $error = null;

    public static function canView(): bool
    {
        return app(AnalyticsReports::class)->ga4Configured();
    }

    public function getDescription(): ?string
    {
        return $this->error ?? 'Google Analytics 4 · theo số phiên';
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        try {
            $devices = $this->reports()->ga4Devices($this->ga4Period());
            $this->error = null;
        } catch (AnalyticsUnavailable $e) {
            $this->error = $e->getMessage();
            $devices = [];
        }

        $total = array_sum(array_column($devices, 'extra')) ?: 1;

        return [
            'datasets' => [[
                'data' => array_column($devices, 'extra'),
                'backgroundColor' => array_slice(self::COLORS, 0, max(1, count($devices))),
                'borderWidth' => 0,
            ]],
            'labels' => array_map(
                fn (array $row): string => (self::DEVICES[$row['label']] ?? ucfirst($row['label'])).' · '.self::number($row['extra'] / $total * 100).'%',
                $devices,
            ),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'cutout' => '62%',
            'scales' => ['x' => ['display' => false], 'y' => ['display' => false]],
            'plugins' => ['legend' => ['position' => 'right']],
        ];
    }
}
