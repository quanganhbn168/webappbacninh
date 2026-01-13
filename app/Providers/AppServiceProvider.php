<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share settings with all views (Cached)
        try {
            $settings = \Illuminate\Support\Facades\Cache::remember('site_settings', 86400, function () {
                return \App\Models\Setting::all()->pluck('value', 'key');
            });

            \Illuminate\Support\Facades\View::share('site_settings', $settings);

            // Override AdminLTE Config (Only for Admin Routes)
            if (request()->is('system029/admin*')) {
                if (isset($settings['site_logo_square'])) {
                    config(['adminlte.logo_img' => $settings['site_logo_square']]);
                }
                if (isset($settings['site_name'])) {
                    config(['adminlte.logo' => $settings['site_name']]);
                }
            }
        } catch (\Exception $e) {
            // Fails if table missing (e.g. migration not run)
        }
    }
}
