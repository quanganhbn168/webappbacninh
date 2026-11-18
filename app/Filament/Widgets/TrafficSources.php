<?php

namespace App\Filament\Widgets;

use App\Domain\Analytics\AnalyticsReports;

/** Where visits come from (GA4 default channel group) and on which devices. */
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

    private const DEVICES = ['desktop' => 'Máy tính', 'mobile' => 'Điện thoại', 'tablet' => 'Máy tính bảng', 'smart tv' => 'TV'];

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
        return 'Google Analytics 4 · kèm thiết bị ở cuối bảng';
    }

    protected function columns(): array
    {
        return ['Kênh', 'Phiên', 'Sự kiện chính'];
    }

    protected function rows(): array
    {
        $period = $this->ga4Period();
        $channels = $this->reports()->ga4Channels($period);
        $devices = $this->reports()->ga4Devices($period);
        $bars = self::bars($channels, 'value');
        $deviceTotal = array_sum(array_column($devices, 'value')) ?: 1;

        $rows = array_map(fn (array $row, float $bar): array => [
            'cells' => [self::CHANNELS[$row['label']] ?? $row['label'], self::number($row['value']), self::number($row['extra'])],
            'bar' => $bar,
        ], $channels, $bars);

        foreach ($devices as $device) {
            $rows[] = ['cells' => [
                (self::DEVICES[$device['label']] ?? ucfirst($device['label'])).' · '.self::number($device['value'] / $deviceTotal * 100).'% người dùng',
                self::number($device['extra']),
                '',
            ]];
        }

        return $rows;
    }
}
