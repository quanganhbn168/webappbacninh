<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SiteServiceProvider extends ServiceProvider
{
    /**
     * Share site settings with the public views.
     */
    public function boot(): void
    {
        try {
            $values = collect(site_settings());
            View::share('site_settings', $values);
        } catch (\Throwable) {
            View::share('site_settings', collect(config('site')));
        }
    }
}
