<?php

namespace App\Domain\Analytics;

use Carbon\CarbonImmutable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * The few GA4 and Search Console figures the admin dashboard shows, read through the
 * official REST APIs (GA4 Data API v1beta, Search Console searchAnalytics.query) and
 * cached: GA4 for 30 minutes, Search Console for 6 hours, realtime for 1 minute.
 * Every method returns plain arrays and throws AnalyticsUnavailable with a readable reason.
 */
class AnalyticsReports
{
    public const GA4_METRICS = ['activeUsers', 'newUsers', 'sessions', 'screenPageViews', 'engagementRate', 'averageSessionDuration', 'keyEvents'];

    public function __construct(private readonly GoogleAccessToken $token) {}

    public function ga4Configured(): bool
    {
        return filled(config('services.google_analytics.ga4_property_id')) && $this->token->hasCredentials();
    }

    public function searchConsoleConfigured(): bool
    {
        return filled(config('services.google_analytics.gsc_site_url')) && $this->token->hasCredentials();
    }

    // ---------------- GA4 ----------------

    /**
     * Totals of the period and the one before.
     *
     * @return array{current: array<string, float>, previous: array<string, float>}
     */
    public function ga4Totals(Period $period): array
    {
        return $this->remember('ga4.totals.'.$period->key(), 30, function () use ($period): array {
            $data = $this->ga4('runReport', [
                'dateRanges' => [$period->range() + ['name' => 'current'], $period->previous()->range() + ['name' => 'previous']],
                'metrics' => array_map(fn (string $name): array => ['name' => $name], self::GA4_METRICS),
            ]);

            $totals = ['current' => array_fill_keys(self::GA4_METRICS, 0.0), 'previous' => array_fill_keys(self::GA4_METRICS, 0.0)];
            foreach ($data['rows'] ?? [] as $row) {
                $range = $row['dimensionValues'][0]['value'] ?? 'current';
                foreach (self::GA4_METRICS as $i => $metric) {
                    $totals[$range][$metric] = (float) ($row['metricValues'][$i]['value'] ?? 0);
                }
            }

            return $totals;
        });
    }

    /**
     * Users and sessions per day, every day of the period present.
     *
     * @return array<string, array{users: int, sessions: int}> keyed by Y-m-d
     */
    public function ga4Daily(Period $period): array
    {
        return $this->remember('ga4.daily.'.$period->key(), 30, function () use ($period): array {
            $data = $this->ga4('runReport', [
                'dateRanges' => [$period->range()],
                'dimensions' => [['name' => 'date']],
                'metrics' => [['name' => 'activeUsers'], ['name' => 'sessions']],
                'orderBys' => [['dimension' => ['dimensionName' => 'date']]],
            ]);

            $days = $this->emptyDays($period, ['users' => 0, 'sessions' => 0]);
            foreach ($data['rows'] ?? [] as $row) {
                $value = (string) ($row['dimensionValues'][0]['value'] ?? '');
                if (! preg_match('/^\d{8}$/', $value)) {
                    continue;
                }
                $date = CarbonImmutable::createFromFormat('Ymd', $value)->toDateString();
                if (isset($days[$date])) {
                    $days[$date] = ['users' => (int) ($row['metricValues'][0]['value'] ?? 0), 'sessions' => (int) ($row['metricValues'][1]['value'] ?? 0)];
                }
            }

            return $days;
        });
    }

    /** @return array<int, array{label: string, value: int, extra: int}> path, views, users */
    public function ga4TopPages(Period $period, int $limit = 10): array
    {
        return $this->ga4Ranking($period, 'pagePath', ['screenPageViews', 'activeUsers'], $limit);
    }

    /** @return array<int, array{label: string, value: int, extra: int}> channel, sessions, key events */
    public function ga4Channels(Period $period): array
    {
        return $this->ga4Ranking($period, 'sessionDefaultChannelGroup', ['sessions', 'keyEvents'], 10);
    }

    /** @return array<int, array{label: string, value: int, extra: int}> device, users, sessions */
    public function ga4Devices(Period $period): array
    {
        return $this->ga4Ranking($period, 'deviceCategory', ['activeUsers', 'sessions'], 5);
    }

    /** Users active in the last 30 minutes. */
    public function ga4Realtime(): int
    {
        return $this->remember('ga4.realtime', 1, function (): int {
            $data = $this->ga4('runRealtimeReport', ['metrics' => [['name' => 'activeUsers']]]);

            return (int) ($data['rows'][0]['metricValues'][0]['value'] ?? 0);
        });
    }

    // ---------------- Search Console ----------------

    /**
     * @return array{current: array<string, float>, previous: array<string, float>} clicks, impressions, ctr (0-1), position
     */
    public function searchTotals(Period $period): array
    {
        return $this->remember('gsc.main.totals.'.$period->key(), 360, fn (): array => [
            'current' => $this->searchTotal($period),
            'previous' => $this->searchTotal($period->previous()),
        ]);
    }

    /** @return array<string, array{clicks: int, impressions: int}> keyed by Y-m-d */
    public function searchDaily(Period $period): array
    {
        return $this->remember('gsc.main.daily.'.$period->key(), 360, function () use ($period): array {
            $days = $this->emptyDays($period, ['clicks' => 0, 'impressions' => 0]);
            foreach ($this->search($period, ['date'], 1000) as $row) {
                if (isset($days[$row['keys'][0] ?? ''])) {
                    $days[$row['keys'][0]] = ['clicks' => (int) $row['clicks'], 'impressions' => (int) $row['impressions']];
                }
            }

            return $days;
        });
    }

    /** @return array<int, array{label: string, clicks: int, impressions: int, ctr: float, position: float}> */
    public function searchTopQueries(Period $period, int $limit = 10): array
    {
        return $this->remember('gsc.main.queries.'.$limit.'.'.$period->key(), 360, fn (): array => $this->searchRanking($period, 'query', $limit));
    }

    /**
     * label is the path on the main site, or the rest of the URL for another host or protocol
     * (a domain property also counts subdomains and http://, www. copies of a page).
     *
     * @return array<int, array{label: string, url: string, clicks: int, impressions: int, ctr: float, position: float}>
     */
    public function searchTopPages(Period $period, int $limit = 10): array
    {
        return $this->remember('gsc.main.pages.'.$limit.'.'.$period->key(), 360, fn (): array => array_map(
            fn (array $row): array => ['label' => $this->pageLabel($row['label']), 'url' => $row['label']] + $row,
            $this->searchRanking($period, 'page', $limit),
        ));
    }

    /**
     * "Quick wins": search terms on page 1 bottom or page 2 (position 8 – 20) with the most
     * impressions, and the page Google shows for each. A small push moves them into the top 5.
     *
     * @return array<int, array{query: string, page: string, url: string, clicks: int, impressions: int, ctr: float, position: float}>
     */
    public function searchOpportunities(Period $period, int $limit = 10): array
    {
        return $this->remember('gsc.main.opportunities.'.$limit.'.'.$period->key(), 360, function () use ($period, $limit): array {
            $rows = array_filter(
                $this->search($period, ['query', 'page'], 1000),
                fn (array $row): bool => $row['position'] >= 7.5 && $row['position'] < 20.5,
            );
            usort($rows, fn (array $a, array $b): int => $b['impressions'] <=> $a['impressions']);

            return array_map(fn (array $row): array => [
                'query' => (string) $row['keys'][0],
                'page' => $this->pageLabel((string) $row['keys'][1]),
                'url' => (string) $row['keys'][1],
                'clicks' => (int) $row['clicks'],
                'impressions' => (int) $row['impressions'],
                'ctr' => (float) $row['ctr'],
                'position' => (float) $row['position'],
            ], array_slice($rows, 0, $limit));
        });
    }

    // ---------------- internals ----------------

    /** "/bang-gia" on https://main-host, otherwise the URL without "https://": cnetpos.example.vn/, http://www.example.vn/. */
    private function pageLabel(string $url): string
    {
        $host = $this->mainHost();
        $origin = 'https://'.$host;

        if ($host !== '' && ($url === $origin || str_starts_with($url, $origin.'/') || str_starts_with($url, $origin.'?'))) {
            return '/'.ltrim(substr($url, strlen($origin)), '/');
        }

        return preg_replace('#^https://#', '', $url);
    }

    /** example.vn for "sc-domain:example.vn", www.example.vn for "https://www.example.vn/". */
    private function mainHost(): string
    {
        $site = (string) config('services.google_analytics.gsc_site_url');

        return str_starts_with($site, 'sc-domain:') ? substr($site, 10) : (string) parse_url($site, PHP_URL_HOST);
    }

    /**
     * A domain property also reports every subdomain (other sites hosted under it); keep
     * only the main site, with its http:// and www. copies that redirect to it.
     *
     * @return array<string, mixed>
     */
    private function mainSiteFilter(): array
    {
        if (! str_starts_with((string) config('services.google_analytics.gsc_site_url'), 'sc-domain:')) {
            return [];
        }

        return ['dimensionFilterGroups' => [['filters' => [[
            'dimension' => 'page',
            'operator' => 'includingRegex',
            'expression' => '^https?://(www\.)?'.str_replace('.', '\.', $this->mainHost()).'([/?#]|$)',
        ]]]]];
    }

    /** @return array<int, array{label: string, value: int, extra: int}> */
    private function ga4Ranking(Period $period, string $dimension, array $metrics, int $limit): array
    {
        return $this->remember("ga4.{$dimension}.{$limit}.".$period->key(), 30, function () use ($period, $dimension, $metrics, $limit): array {
            $data = $this->ga4('runReport', [
                'dateRanges' => [$period->range()],
                'dimensions' => [['name' => $dimension]],
                'metrics' => array_map(fn (string $name): array => ['name' => $name], $metrics),
                'orderBys' => [['metric' => ['metricName' => $metrics[0]], 'desc' => true]],
                'limit' => $limit,
            ]);

            return array_map(fn (array $row): array => [
                'label' => (string) $row['dimensionValues'][0]['value'],
                'value' => (int) $row['metricValues'][0]['value'],
                'extra' => (int) ($row['metricValues'][1]['value'] ?? 0),
            ], $data['rows'] ?? []);
        });
    }

    /** @return array<string, float> */
    private function searchTotal(Period $period): array
    {
        $row = $this->search($period, [], 1)[0] ?? [];

        return [
            'clicks' => (float) ($row['clicks'] ?? 0),
            'impressions' => (float) ($row['impressions'] ?? 0),
            'ctr' => (float) ($row['ctr'] ?? 0),
            'position' => (float) ($row['position'] ?? 0),
        ];
    }

    /** @return array<int, array{label: string, clicks: int, impressions: int, ctr: float, position: float}> */
    private function searchRanking(Period $period, string $dimension, int $limit): array
    {
        return array_map(fn (array $row): array => [
            'label' => (string) $row['keys'][0],
            'clicks' => (int) $row['clicks'],
            'impressions' => (int) $row['impressions'],
            'ctr' => (float) $row['ctr'],
            'position' => (float) $row['position'],
        ], $this->search($period, [$dimension], $limit));
    }

    /** @return array<string, mixed> */
    private function ga4(string $method, array $body): array
    {
        if (! $this->ga4Configured()) {
            throw new AnalyticsUnavailable('Chưa kết nối Google Analytics 4.');
        }

        $property = preg_replace('/\D/', '', (string) config('services.google_analytics.ga4_property_id'));

        return $this->call("https://analyticsdata.googleapis.com/v1beta/properties/{$property}:{$method}", $body, 'Google Analytics');
    }

    /** @return array<int, array<string, mixed>> */
    private function search(Period $period, array $dimensions, int $limit): array
    {
        if (! $this->searchConsoleConfigured()) {
            throw new AnalyticsUnavailable('Chưa kết nối Google Search Console.');
        }

        $site = rawurlencode((string) config('services.google_analytics.gsc_site_url'));

        return $this->call("https://searchconsole.googleapis.com/webmasters/v3/sites/{$site}/searchAnalytics/query", $period->range() + [
            'dimensions' => $dimensions,
            'rowLimit' => $limit,
        ] + $this->mainSiteFilter(), 'Search Console')['rows'] ?? [];
    }

    /** @return array<string, mixed> */
    private function call(string $url, array $body, string $service): array
    {
        try {
            return Http::withToken($this->token->get())->acceptJson()->timeout(15)->post($url, $body)->throw()->json() ?? [];
        } catch (RequestException $e) {
            $status = $e->response->status();
            $reason = (string) ($e->response->json('error.message') ?? '');
            Log::warning("{$service} API error", ['status' => $status, 'message' => $reason]);

            throw new AnalyticsUnavailable(match (true) {
                $status === 403 => "{$service}: service account chưa được cấp quyền xem, hoặc API chưa được bật trong Google Cloud.",
                $status === 404 => "{$service}: không tìm thấy property, kiểm tra lại mã property / địa chỉ site.",
                $status === 429 => "{$service}: vượt hạn mức gọi API, thử lại sau ít phút.",
                default => "{$service} lỗi {$status}".($reason !== '' ? ": {$reason}" : '.'),
            }, previous: $e);
        } catch (AnalyticsUnavailable $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::warning("{$service} connection failed", ['error' => $e->getMessage()]);

            throw new AnalyticsUnavailable("Không kết nối được {$service}: ".$e->getMessage(), previous: $e);
        }
    }

    /**
     * Caches successful results only, so a fixed configuration shows data right away.
     *
     * @template T
     *
     * @param  callable(): T  $load
     * @return T
     */
    private function remember(string $key, int $minutes, callable $load): mixed
    {
        return Cache::remember('analytics.'.$key, now()->addMinutes($minutes), $load);
    }

    /**
     * @param  array<string, int>  $zero
     * @return array<string, array<string, int>>
     */
    private function emptyDays(Period $period, array $zero): array
    {
        $days = [];
        for ($day = $period->start; $day->lte($period->end); $day = $day->addDay()) {
            $days[$day->toDateString()] = $zero;
        }

        return $days;
    }
}
