<?php

namespace App\Filament\Widgets;

use App\Domain\Analytics\AnalyticsUnavailable;
use App\Filament\Resources\Leads\LeadResource;
use App\Filament\Widgets\Concerns\ReadsAnalytics;
use App\Models\Lead;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/** Website traffic from GA4 plus the contact requests received, for the selected period. */
class TrafficOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;
    use ReadsAnalytics;

    protected static ?int $sort = 1;

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';

    protected function getHeading(): ?string
    {
        return 'Truy cập website';
    }

    protected function getDescription(): ?string
    {
        return 'Google Analytics 4 · '.$this->ga4Period()->label();
    }

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $leads = $this->leadStat();

        if (! $this->reports()->ga4Configured()) {
            return [$leads, Stat::make('Google Analytics 4', 'Chưa kết nối')->description('Xem hướng dẫn kết nối ở trên')->color('gray')];
        }

        try {
            $period = $this->ga4Period();
            ['current' => $now, 'previous' => $before] = $this->reports()->ga4Totals($period);
            $daily = $this->reports()->ga4Daily($period);
        } catch (AnalyticsUnavailable $e) {
            return [$leads, Stat::make('Google Analytics 4', 'Lỗi kết nối')->description($e->getMessage())->color('danger')];
        }

        $users = array_column($daily, 'users');
        $sessions = array_column($daily, 'sessions');

        return [
            self::compared('Người dùng', self::number($now['activeUsers']), $now['activeUsers'], $before['activeUsers'], $users),
            self::compared('Người dùng mới', self::number($now['newUsers']), $now['newUsers'], $before['newUsers']),
            self::compared('Phiên truy cập', self::number($now['sessions']), $now['sessions'], $before['sessions'], $sessions),
            self::compared('Lượt xem trang', self::number($now['screenPageViews']), $now['screenPageViews'], $before['screenPageViews']),
            self::compared('Tỷ lệ tương tác', self::number($now['engagementRate'] * 100, 1).'%', $now['engagementRate'], $before['engagementRate']),
            self::compared('Thời gian mỗi phiên', self::duration($now['averageSessionDuration']), $now['averageSessionDuration'], $before['averageSessionDuration']),
            self::compared('Sự kiện chính', self::number($now['keyEvents']), $now['keyEvents'], $before['keyEvents'])
                ->extraAttributes(['title' => 'Các sự kiện được đánh dấu là "sự kiện chính" trong GA4, ví dụ gửi form, bấm gọi.']),
            $leads,
            $this->conversionStat($now['sessions'], $before['sessions']),
            $this->realtimeStat(),
        ];
    }

    private function leadStat(): Stat
    {
        [$now, $before] = $this->leadCounts();

        return self::compared('Khách gửi liên hệ', self::number($now), $now, $before)
            ->icon(Heroicon::OutlinedUserPlus)
            ->url(LeadResource::getUrl('index'));
    }

    /** Contact requests per 100 sessions, this period and the one before. */
    private function conversionStat(float $sessions, float $previousSessions): Stat
    {
        [$leads, $previousLeads] = $this->leadCounts();
        $rate = $sessions > 0 ? $leads / $sessions : 0.0;
        $previousRate = $previousSessions > 0 ? $previousLeads / $previousSessions : 0.0;

        return self::compared('Tỷ lệ ra liên hệ', self::number($rate * 100, 1).'%', $rate, $previousRate)
            ->extraAttributes(['title' => 'Số khách gửi liên hệ chia cho số phiên truy cập.']);
    }

    /** @return array{0: int, 1: int} leads in the period and in the one before */
    private function leadCounts(): array
    {
        $period = $this->ga4Period();
        $count = fn ($p): int => Lead::query()->whereBetween('created_at', [$p->start->startOfDay(), $p->end->endOfDay()])->count();

        return $this->leadCounts ??= [$count($period), $count($period->previous())];
    }

    /** @var array{0: int, 1: int}|null */
    private ?array $leadCounts = null;

    private function realtimeStat(): Stat
    {
        try {
            $online = $this->reports()->ga4Realtime();
        } catch (AnalyticsUnavailable) {
            return Stat::make('Đang truy cập', '–')->description('Không lấy được số liệu thời gian thực')->color('gray');
        }

        return Stat::make('Đang truy cập', self::number($online))
            ->description('Trong 30 phút gần nhất')
            ->descriptionIcon(Heroicon::Signal)
            ->color($online > 0 ? 'success' : 'gray');
    }
}
