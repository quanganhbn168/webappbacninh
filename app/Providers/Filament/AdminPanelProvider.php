<?php

namespace App\Providers\Filament;

use App\Domain\Site\Actions\ResolveFaviconAssets;
use App\Filament\NavigationGroup;
use Awcodes\Curator\CuratorPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsIconAlias;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->authGuard('admin')
            ->login()
            ->favicon(function (): string {
                $assets = app(ResolveFaviconAssets::class)->execute();

                return $assets->generated
                    ? $assets->url('favicon-48x48.png')
                    : $assets->sourceUrl;
            })
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandName(fn (): string => (string) (site_config('name') ?: config('app.name')))
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('17rem')
            // Ctrl+K / Cmd+K focuses the search; the field shows the shortcut.
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchFieldKeyBindingSuffix()
            // The bell: new contact requests land here (App\Domain\Content\NotifyNewLead).
            ->databaseNotifications()
            ->databaseNotificationsPolling('60s')
            // Topbar starts with the ☰ sidebar toggle, then a link to the public site.
            ->renderHook(PanelsRenderHook::TOPBAR_LOGO_AFTER, fn () => view('filament.topbar.home-link'))
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER, fn () => view('filament.topbar.create-menu'))
            ->renderHook(PanelsRenderHook::USER_MENU_AFTER, fn () => view('filament.topbar.user-name'))
            // Group order comes from the NavigationGroup enum.
            ->navigationGroups(NavigationGroup::class)
            // Filament capitalises every word of labels in breadcrumbs and headings
            // ("Danh Mục Dịch Vụ"); Vietnamese labels are kept as written.
            ->bootUsing(function (Panel $panel): void {
                // One ☰ icon toggles the sidebar both ways, instead of the < > chevrons.
                FilamentIcon::register([
                    PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON => Heroicon::OutlinedBars3,
                    PanelsIconAlias::SIDEBAR_EXPAND_BUTTON => Heroicon::OutlinedBars3,
                ]);

                foreach ($panel->getResources() as $resource) {
                    $resource::titleCaseModelLabel(false);
                }
            })
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            // App\Filament\Pages\Dashboard (GA4 + Search Console) replaces Filament's default dashboard.
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()->navigationGroup(NavigationGroup::System)->navigationSort(20),
                CuratorPlugin::make()->label('Tệp')->pluralLabel('Thư viện ảnh')->navigationGroup(NavigationGroup::Website)->navigationSort(60),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
