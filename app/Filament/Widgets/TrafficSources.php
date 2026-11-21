<?php

namespace App\Filament\Widgets;

use App\Domain\Analytics\AnalyticsReports;

/** Where visits come from (GA4 default channel group); devices have their own chart. */
class TrafficSources extends AnalyticsTable
{
    protected static ?int $sort = 6;

    private const CHANNELS = [
        'Organic Search' => 'Tìm kiếm tự nhiên',
        'Direct' => 'Truy cập trực tiếp',
        'Referral' => 'Website khác giới thiệu',
        'Organic Social' => 'Mạng xã hội',
        'Paid Search' => 'Quảng cáo tìm kiếm',
        'Paid Social' => 'Quảng cáo mạng xã hội',
        'Cross-network' => 'Quảng cáo đa kênh',
        'Display' => 'Quảng cáo hiển thị',
        'Email' => 'Email',
        'Organic Video' => 'Video',
        'Unassigned' => 'Chưa xác định',
    ];

    public static function canView(): bool
    {
        return app(AnalyticsReports::class)->ga4Configured();
    }

    protected function heading(): string
    {
        return 'Nguồn truy cập';
    }

    protected function source(): string
    {
        return 'Google Analytics 4';
    }

    protected function columns(): array
    {
        return ['Kênh', 'Phiên', 'Sự kiện chính'];
    }

    protected function rows(): array
    {
        $channels = $this->reports()->ga4Channels($this->ga4Period());
        $bars = self::bars($channels, 'value');

        return array_map(fn (array $row, float $bar): array => [
            'cells' => [self::CHANNELS[$row['label']] ?? $row['label'], self::number($row['value']), self::number($row['extra'])],
            'bar' => $bar,
        ], $channels, $bars);
    }
}
