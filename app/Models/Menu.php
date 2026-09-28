<?php

namespace App\Models;

use App\Support\FrontendMenuCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    public const HEADER = 'header';

    public const FOOTER_SERVICES = 'footer_services';

    public const FOOTER_PRODUCTS = 'footer_products';

    public const FOOTER_ABOUT = 'footer_about';

    protected $fillable = ['name', 'location', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    /**
     * @return array<string, string>
     */
    public static function locations(): array
    {
        return [
            self::HEADER => 'Menu chính (header)',
            self::FOOTER_SERVICES => 'Footer – cột 1',
            self::FOOTER_PRODUCTS => 'Footer – cột 2',
            self::FOOTER_ABOUT => 'Footer – cột 3',
        ];
    }

    /**
     * Top-level items, each with its children, in display order.
     */
    public function items(): HasMany
    {
        return $this->allItems()->whereNull('parent_id')->orderBy('position')->orderBy('id');
    }

    public function allItems(): HasMany
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
