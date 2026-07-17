<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
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
        try {
            $values = collect(site_settings());
            View::share('site_settings', $values);

            if (request()->is('system029/admin*')) {
                config(['adminlte.logo' => $values->get('name', config('site.name'))]);
            }
        } catch (\Throwable) {
            View::share('site_settings', collect(config('site')));
        }
    }
}
