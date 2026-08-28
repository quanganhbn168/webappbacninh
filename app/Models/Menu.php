<?php

namespace App\Models;

use App\Support\FrontendMenuCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = ['name', 'location', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    protected static function booted(): void
    {
        static::saved(function (Menu $menu): void {
            $cache = app(FrontendMenuCache::class);
            $cache->forget((string) $menu->getOriginal('location'));
            $cache->forget((string) $menu->location);
        });

        static::deleted(fn (Menu $menu) => app(FrontendMenuCache::class)
            ->forget((string) $menu->getOriginal('location')));
    }
}
