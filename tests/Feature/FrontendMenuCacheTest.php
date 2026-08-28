<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Support\FrontendMenuCache;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class FrontendMenuCacheTest extends TestCase
{
    use DatabaseTransactions;

    public function test_saving_a_menu_invalidates_its_public_cache(): void
    {
        $menu = Menu::query()->where('location', 'header')->firstOrFail();
        $key = app(FrontendMenuCache::class)->key($menu->location);
        Cache::forever($key, ['stale']);

        $menu->name = $menu->name.' ';
        $menu->save();

        $this->assertFalse(Cache::has($key));
    }

    public function test_creating_and_deleting_a_menu_invalidates_its_public_cache(): void
    {
        $location = 'test-cache-menu';
        $key = app(FrontendMenuCache::class)->key($location);
        Cache::forever($key, ['stale']);

        $menu = Menu::query()->create([
            'name' => 'Test cache menu',
            'location' => $location,
            'is_active' => true,
        ]);

        $this->assertFalse(Cache::has($key));

        Cache::forever($key, ['stale']);
        $menu->delete();

        $this->assertFalse(Cache::has($key));
    }

    public function test_saving_a_menu_item_invalidates_its_parent_menu_cache(): void
    {
        $menu = Menu::query()->where('location', 'header')->firstOrFail();
        $item = MenuItem::query()->where('menu_id', $menu->id)->firstOrFail();
        $key = app(FrontendMenuCache::class)->key($menu->location);
        Cache::forever($key, ['stale']);

        $item->position++;
        $item->save();

        $this->assertFalse(Cache::has($key));
    }

    public function test_deleting_a_menu_item_invalidates_its_parent_menu_cache(): void
    {
        $menu = Menu::query()->where('location', 'header')->firstOrFail();
        $item = MenuItem::query()->where('menu_id', $menu->id)->whereDoesntHave('children')->firstOrFail();
        $key = app(FrontendMenuCache::class)->key($menu->location);
        Cache::forever($key, ['stale']);

        $item->delete();

        $this->assertFalse(Cache::has($key));
    }
}
