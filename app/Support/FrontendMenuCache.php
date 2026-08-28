<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

final class FrontendMenuCache
{
    public function key(string $location): string
    {
        return 'frontend.menu.'.$location.'.v1';
    }

    public function forget(string $location): void
    {
        if ($location === '') {
            return;
        }

        Cache::forget($this->key($location));
    }
}
