<?php

namespace App\Traits;

use App\Models\Slug;
use App\Observers\SlugObserver;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::whenBooted(fn (): mixed => static::observe(SlugObserver::class));
    }

    public function slugEntry()
    {
        return $this->morphOne(Slug::class, 'reference');
    }
}
