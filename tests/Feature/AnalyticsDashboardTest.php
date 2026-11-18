<?php

namespace Tests\Feature;

use App\Domain\Analytics\AnalyticsReports;
use App\Domain\Analytics\AnalyticsUnavailable;
use App\Domain\Analytics\GoogleAccessToken;
use App\Domain\Analytics\Period;
use App\Filament\Widgets\SearchOverview;
use App\Filament\Widgets\TopQueries;
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
            && $request['dimensions'] === ['query']);
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
