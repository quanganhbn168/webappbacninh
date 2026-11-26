<?php

namespace App\Providers;

use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Config\CuratorManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Zalo\ZaloExtendSocialite;

class AppServiceProvider extends ServiceProvider
{
    /** Largest image upload, in kilobytes. Same limit as config/livewire.php. */
    private const MAX_IMAGE_KB = 30720;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // The image library and its picker allow 5 MB by default; full-page website screenshots are larger.
        $this->app->resolving(CuratorManager::class, fn (CuratorManager $curator) => $curator->maxSize(self::MAX_IMAGE_KB));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(SocialiteWasCalled::class, [ZaloExtendSocialite::class, 'handle']);
        CuratorPicker::configureUsing(fn (CuratorPicker $picker) => $picker->maxSize(self::MAX_IMAGE_KB));
    }
}
