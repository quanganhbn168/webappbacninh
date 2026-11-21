<?php

namespace Tests\Feature;

use App\Domain\Analytics\AnalyticsReports;
use App\Domain\Analytics\AnalyticsUnavailable;
use App\Domain\Analytics\GoogleAccessToken;
use App\Domain\Analytics\Period;
use App\Filament\Widgets\LeadSources;
use App\Filament\Widgets\SearchOverview;
use App\Filament\Widgets\TopQueries;
use App\Filament\Widgets\TrafficDevices;
use App\Filament\Widgets\TrafficOverview;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class AnalyticsDashboardTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_without_configuration_the_dashboard_shows_how_to_connect(): void
    {
        config(['services.google_analytics.ga4_property_id' => null, 'services.google_analytics.gsc_site_url' => null]);

        $this->actingAs($this->admin(), 'admin')->get('/admin')->assertOk()
            ->assertSee('Kết nối Google Analytics 4 và Search Console')
            ->assertSee('GA4_PROPERTY_ID');
        Http::assertNothingSent();
    }

    public function test_ga4_totals_compare_with_the_previous_period(): void
    {
        $this->connect();
        Http::fake(['analyticsdata.googleapis.com/*' => Http::response(['rows' => [
            ['dimensionValues' => [['value' => 'current']], 'metricValues' => $this->values([120, 80, 150, 400, 0.62, 95, 6])],
            ['dimensionValues' => [['value' => 'previous']], 'metricValues' => $this->values([100, 70, 130, 380, 0.5, 80, 3])],
        ]])]);

        $totals = app(AnalyticsReports::class)->ga4Totals(Period::forGa4(28));

        $this->assertSame(120.0, $totals['current']['activeUsers']);
        $this->assertSame(3.0, $totals['previous']['keyEvents']);
        Http::assertSent(fn ($request) => str_ends_with($request->url(), 'properties/123456:runReport')
            && $request->hasHeader('Authorization', 'Bearer test-token')
            && $request['dateRanges'][1]['name'] === 'previous');
    }

    public function test_search_console_queries_are_read_from_the_site_property(): void
    {
        $this->connect();
        Http::fake(['searchconsole.googleapis.com/*' => Http::response(['rows' => [
            ['keys' => ['thiết kế website bắc ninh'], 'clicks' => 12, 'impressions' => 340, 'ctr' => 0.035, 'position' => 6.4],
        ]])]);

        $rows = app(AnalyticsReports::class)->searchTopQueries(Period::forSearchConsole(28));

        $this->assertSame('thiết kế website bắc ninh', $rows[0]['label']);
        Http::assertSent(fn ($request) => str_contains($request->url(), 'sites/sc-domain%3Aexample.vn/searchAnalytics/query')
            && $request['dimensions'] === ['query']
            && $request['dimensionFilterGroups'][0]['filters'][0]['expression'] === '^https?://(www\.)?example\.vn([/?#]|$)');
    }

    public function test_search_pages_keep_the_host_when_it_is_not_the_main_site(): void
    {
        $this->connect();
        Http::fake(['searchconsole.googleapis.com/*' => Http::response(['rows' => [
            ['keys' => ['https://example.vn/'], 'clicks' => 5, 'impressions' => 128, 'ctr' => 0.04, 'position' => 14.4],
            ['keys' => ['https://shop.example.vn/'], 'clicks' => 2, 'impressions' => 25, 'ctr' => 0.08, 'position' => 3.9],
            ['keys' => ['http://www.example.vn/bang-gia?x=1'], 'clicks' => 0, 'impressions' => 5, 'ctr' => 0, 'position' => 23.0],
        ]])]);

        $rows = app(AnalyticsReports::class)->searchTopPages(Period::forSearchConsole(28));

        $this->assertSame(['/', 'shop.example.vn/', 'http://www.example.vn/bang-gia?x=1'], array_column($rows, 'label'));
        $this->assertSame('https://shop.example.vn/', $rows[1]['url']);
    }

    public function test_opportunities_are_terms_at_position_8_to_20_by_impressions(): void
    {
        $this->connect();
        Http::fake(['searchconsole.googleapis.com/*' => Http::response(['rows' => [
            ['keys' => ['top term', 'https://example.vn/a'], 'clicks' => 9, 'impressions' => 900, 'ctr' => 0.01, 'position' => 3.0],
            ['keys' => ['tạo mã qr ngân hàng', 'https://example.vn/tools/ngan-hang'], 'clicks' => 3, 'impressions' => 140, 'ctr' => 0.021, 'position' => 12.2],
            ['keys' => ['vòng quay ăn trưa', 'https://example.vn/tools/vong-quay-an-trua'], 'clicks' => 1, 'impressions' => 320, 'ctr' => 0.031, 'position' => 8.5],
            ['keys' => ['deep term', 'https://example.vn/b'], 'clicks' => 0, 'impressions' => 999, 'ctr' => 0, 'position' => 45.7],
        ]])]);

        $rows = app(AnalyticsReports::class)->searchOpportunities(Period::forSearchConsole(28));

        $this->assertSame(['vòng quay ăn trưa', 'tạo mã qr ngân hàng'], array_column($rows, 'query'));
        $this->assertSame('/tools/vong-quay-an-trua', $rows[0]['page']);
        Http::assertSent(fn ($request) => $request['dimensions'] === ['query', 'page']);
    }

    public function test_average_position_change_reads_as_places_gained_or_lost(): void
    {
        $this->connect();
        $totals = fn (float $position): array => ['rows' => [['clicks' => 10, 'impressions' => 100, 'ctr' => 0.1, 'position' => $position]]];
        Http::fakeSequence('searchconsole.googleapis.com/*')
            ->push($totals(27.7))->push($totals(26.4))->push(['rows' => []]);

        Livewire::actingAs($this->admin(), 'admin')->test(SearchOverview::class, ['pageFilters' => ['days' => 7]])
            ->assertSee('Tụt 1,3 bậc so với kỳ trước')->assertDontSee('+4,9%');
    }

    public function test_a_permission_error_is_explained_instead_of_breaking_the_page(): void
    {
        $this->connect();
        Http::fake(['*' => Http::response(['error' => ['message' => 'denied']], 403)]);

        $this->expectException(AnalyticsUnavailable::class);
        $this->expectExceptionMessage('chưa được cấp quyền xem');

        app(AnalyticsReports::class)->ga4Totals(Period::forGa4(7));
    }

    public function test_widgets_render_the_figures(): void
    {
        $this->connect();
        Http::fake([
            'analyticsdata.googleapis.com/*:runRealtimeReport' => Http::response(['rows' => [['metricValues' => [['value' => '4']]]]]),
            'analyticsdata.googleapis.com/*' => Http::response(['rows' => [
                ['dimensionValues' => [['value' => 'current']], 'metricValues' => $this->values([1234, 800, 1500, 4000, 0.62, 95, 6])],
                ['dimensionValues' => [['value' => 'previous']], 'metricValues' => $this->values([1000, 700, 1300, 3800, 0.5, 80, 3])],
            ]]),
            'searchconsole.googleapis.com/*' => Http::response(['rows' => [
                ['keys' => ['website bắc ninh'], 'clicks' => 12, 'impressions' => 340, 'ctr' => 0.035, 'position' => 6.4],
            ]]),
        ]);
        $admin = $this->admin();

        Livewire::actingAs($admin, 'admin')->test(TrafficOverview::class, ['pageFilters' => ['days' => 7]])
            ->assertSee('Người dùng')->assertSee('1.234')->assertSee('+23% so với kỳ trước')->assertSee('Đang truy cập');
        Livewire::actingAs($admin, 'admin')->test(SearchOverview::class, ['pageFilters' => ['days' => 7]])
            ->assertSee('Lượt nhấp')->assertSee('Vị trí trung bình');
        Livewire::actingAs($admin, 'admin')->test(TopQueries::class, ['pageFilters' => ['days' => 7]])
            ->assertSee('website bắc ninh')->assertSee('3,5%');
        Livewire::actingAs($admin, 'admin')->test(TrafficOverview::class, ['pageFilters' => ['days' => 7]])
            ->assertSee('Tỷ lệ ra liên hệ');
        Livewire::actingAs($admin, 'admin')->test(TrafficDevices::class, ['pageFilters' => ['days' => 7]])->assertOk();
        Livewire::actingAs($admin, 'admin')->test(LeadSources::class, ['pageFilters' => ['days' => 7]])
            ->assertSee('Trang sinh ra liên hệ');
    }

    private function connect(): void
    {
        config(['services.google_analytics.ga4_property_id' => '123456', 'services.google_analytics.gsc_site_url' => 'sc-domain:example.vn']);
        $this->app->instance(GoogleAccessToken::class, new class extends GoogleAccessToken
        {
            public function hasCredentials(): bool
            {
                return true;
            }

            public function get(): string
            {
                return 'test-token';
            }
        });
    }

    /** @return array<int, array{value: string}> */
    private function values(array $numbers): array
    {
        return array_map(fn ($n): array => ['value' => (string) $n], $numbers);
    }

    private function admin(): User
    {
        return User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }
}
