<?php

namespace App\Domain\Navigation\Actions;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Support\FrontendMenuCache;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * The active items of a menu location as a nested array, cached until the
 * menu or one of its items changes. The current page is marked by the view.
 */
final class BuildMenuTree
{
    public function __construct(private readonly FrontendMenuCache $cache) {}

    /**
     * @return array<int, array{title: string, url: string, icon: ?string, new_tab: bool, children: array<int, mixed>}>
     */
    public function execute(string $location): array
    {
        return $this->load($location)['items'];
    }

    /**
     * The menu name, used as the heading of footer columns.
     */
    public function name(string $location): string
    {
        return $this->load($location)['name'];
    }

    /**
     * @return array{name: string, items: array<int, array<string, mixed>>}
     */
    private function load(string $location): array
    {
        return Cache::rememberForever($this->cache->key($location), function () use ($location): array {
            $menu = Menu::query()->where('location', $location)->where('is_active', true)->first();

            if (! $menu) {
                return ['name' => '', 'items' => []];
            }

            $items = $menu->allItems()->where('is_active', true)->orderBy('position')->orderBy('id')->get()->groupBy('parent_id');

            return ['name' => $menu->name, 'items' => $this->branch($items, null)];
        });
    }

    /**
     * Whether an item (or one of its children) points at the current page.
     *
     * @param  array{url: string, children: array<int, mixed>}  $item
     */
    public static function isActive(array $item, ?string $currentPath = null): bool
    {
        $currentPath ??= '/'.trim(request()->path(), '/');
        $path = parse_url($item['url'], PHP_URL_PATH);
        $host = parse_url($item['url'], PHP_URL_HOST);

        if (($host === null || $host === request()->getHost()) && is_string($path) && '/'.trim($path, '/') === $currentPath && ! str_contains($item['url'], '#')) {
            return true;
        }

        foreach ($item['children'] as $child) {
            if (self::isActive($child, $currentPath)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  Collection<int|string, Collection<int, MenuItem>>  $items
     */
    private function branch($items, ?int $parentId): array
    {
        return $items->get($parentId ?? '', collect())->map(fn (MenuItem $item): array => [
            'title' => $item->title,
            'url' => (string) $item->url,
            'icon' => $item->icon,
            'new_tab' => $item->open_in_new_tab,
            'children' => $this->branch($items, $item->id),
        ])->values()->all();
    }
}
