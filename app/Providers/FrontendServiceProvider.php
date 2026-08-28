<?php

namespace App\Providers;

use App\Support\FrontendContent;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class FrontendServiceProvider extends ServiceProvider
{
    /**
     * Provide public header navigation to the frontend layout.
     */
    public function boot(): void
    {
        View::composer('frontend.site.layouts.header', function ($view): void {
            try {
                $content = app(FrontendContent::class);
                $websiteMenuItems = $content->websiteServices();
                $operationMenuItems = $content->operationServices();
            } catch (\Throwable) {
                $websiteMenuItems = array_values(config('website_services', []));
                $operationMenuItems = array_values(config('operation_services', []));
            }

            $view->with('headerNavigation', app(FrontendContent::class)->headerNavigation(
                $websiteMenuItems,
                $operationMenuItems,
            ));
        });
    }
}
