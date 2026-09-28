<?php

namespace App\Support;

use App\Models\Menu;
use Illuminate\Support\Facades\Cache;

final class FrontendMenuCache
{
    public function key(string $location): string
    {
        return 'frontend.menu.'.$location.'.v2';
    }

    public function forget(string $location): void
    {
        if ($location === '') {
            return;
        }

        Cache::forget($this->key($location));
    }

    public function forgetAll(): void
    {
        foreach (array_keys(Menu::locations()) as $location) {
            $this->forget($location);
        }
    }
}
