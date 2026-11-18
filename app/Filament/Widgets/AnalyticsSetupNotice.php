<?php

namespace App\Filament\Widgets;

use App\Domain\Analytics\AnalyticsReports;
use App\Domain\Analytics\GoogleAccessToken;
use Filament\Widgets\Widget;

/** Shown until GA4 and Search Console are both connected: what is missing and how to add it. */
class AnalyticsSetupNotice extends Widget
{
    protected static ?int $sort = -10;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.analytics-setup-notice';

    public static function canView(): bool
    {
        $reports = app(AnalyticsReports::class);

        return ! $reports->ga4Configured() || ! $reports->searchConsoleConfigured();
    }

    /** @return array<string, bool> */
    protected function getViewData(): array
    {
        return [
            'hasKey' => app(GoogleAccessToken::class)->hasCredentials(),
            'keyPath' => str_replace(base_path().DIRECTORY_SEPARATOR, '', app(GoogleAccessToken::class)->credentialsPath()),
            'hasGa4' => filled(config('services.google_analytics.ga4_property_id')),
            'hasGsc' => filled(config('services.google_analytics.gsc_site_url')),
        ];
    }
}
