<?php

namespace App\Filament\Resources\Menus\Pages;

use App\Domain\Pages\SitePages;
use App\Filament\Resources\Menus\MenuResource;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\OperationService;
use App\Models\PostCategory;
use App\Models\Service;
use App\Models\TemplateCategory;
use App\Support\FrontendMenuCache;
use App\Support\Icons;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

/**
 * Menu builder: pick existing pages on the left, arrange the tree on the right
 * (drag to reorder, buttons to nest), then save the whole menu at once.
 * The header allows one level of sub-items; footer columns are flat lists.
 * A header item can open as a mega menu, whose sub-items carry a column title,
 * an icon, a description and a badge.
 */
class ManageMenus extends Page
{
    protected static string $resource = MenuResource::class;

    protected string $view = 'filament.menus.manage';

    protected static ?string $title = 'Menu website';

    private const URL_RULE = 'regex:/^(\/|#|https?:\/\/|tel:|mailto:)/i';

    #[Url(as: 'menu')]
    public string $location = Menu::HEADER;

    public string $name = '';

    public bool $isActive = true;

    /** @var array<int, array<string, mixed>> */
    public array $items = [];

    public ?string $editing = null;

    public bool $dirty = false;

    /** @var array<int, string> "group:index" of the links ticked in the left panel */
    public array $picked = [];

    public string $customTitle = '';

    public string $customUrl = '';

    public function mount(): void
    {
        if (! array_key_exists($this->location, Menu::locations())) {
            $this->location = Menu::HEADER;
        }

        $this->load();
    }

    public function switchTo(string $location): void
    {
        abort_unless(array_key_exists($location, Menu::locations()), 404);

        $this->location = $location;
        $this->load();
    }

    public function updated(string $property): void
    {
        if (str_starts_with($property, 'items') || in_array($property, ['name', 'isActive'], true)) {
            $this->dirty = true;
        }
    }

    #[Computed]
    public function menu(): Menu
    {
        return Menu::query()->firstOrCreate(
            ['location' => $this->location],
            ['name' => Menu::locations()[$this->location], 'is_active' => true],
        );
    }

    public function allowsChildren(): bool
    {
        return $this->location === Menu::HEADER;
    }

    /**
     * Links an editor can add without typing a path.
     *
     * @return array<string, array{label: string, links: array<int, array{title: string, url: string}>}>
     */
    #[Computed]
    public function sources(): array
    {
        $path = fn (string $url): string => parse_url($url, PHP_URL_PATH) ?: '/';

        return array_filter([
            'pages' => ['label' => 'Trang cố định', 'links' => collect(SitePages::PAGES)
                ->map(fn (array $page): array => ['title' => $page[1], 'url' => $path(route($page[0]))])->values()->all()],
            'services' => ['label' => 'Dịch vụ', 'links' => Service::query()->active()->ordered()->get()
                ->map(fn (Service $service): array => ['title' => $service->title, 'url' => $path($service->url)])->all()],
            'operations' => ['label' => 'Dịch vụ vận hành', 'links' => OperationService::query()->active()->ordered()->get()
                ->map(fn (OperationService $service): array => ['title' => $service->title, 'url' => $path($service->url)])->all()],
            'industries' => ['label' => 'Ngành giao diện', 'links' => TemplateCategory::query()->whereHas('templates', fn ($query) => $query->active())->orderBy('name')->get()
                ->map(fn (TemplateCategory $category): array => ['title' => $category->name, 'url' => $path($category->url)])->all()],
            'articles' => ['label' => 'Danh mục bài viết', 'links' => PostCategory::query()->where('is_active', true)->orderBy('name')->get()
                ->map(fn (PostCategory $category): array => ['title' => $category->name, 'url' => $path($category->url)])->all()],
        ], fn (array $group): bool => $group['links'] !== []);
    }

    /** @return array<int, string> */
    #[Computed]
    public function iconNames(): array
    {
        return array_keys(Icons::all());
    }

    public function addPicked(): void
    {
        foreach ($this->picked as $pick) {
            [$group, $index] = array_pad(explode(':', $pick, 2), 2, null);
            $link = $this->sources[$group]['links'][(int) $index] ?? null;
            if ($link) {
                $this->items[] = $this->newItem($link['title'], $link['url']);
            }
        }

        $this->picked = [];
        $this->dirty = true;
    }

    public function addCustom(): void
    {
        $this->validate([
            'customTitle' => ['required', 'max:255'],
            'customUrl' => ['required', 'max:2048', self::URL_RULE],
        ], [], ['customTitle' => 'tên hiển thị', 'customUrl' => 'đường dẫn']);

        $this->items[] = $this->newItem($this->customTitle, $this->customUrl);
        $this->reset('customTitle', 'customUrl');
        $this->dirty = true;
    }

    public function edit(string $key): void
    {
        $this->editing = $this->editing === $key ? null : $key;
    }

    public function remove(string $key): void
    {
        $this->mutate(function (array &$list, int $index): void {
            array_splice($list, $index, 1);
        }, $key);
    }

    public function toggle(string $key): void
    {
        $this->mutate(function (array &$list, int $index): void {
            $list[$index]['is_active'] = ! $list[$index]['is_active'];
        }, $key);
    }

    public function move(string $key, int $step): void
    {
        $this->mutate(function (array &$list, int $index) use ($step): void {
            $target = $index + $step;
            if (isset($list[$target])) {
                [$list[$index], $list[$target]] = [$list[$target], $list[$index]];
            }
        }, $key);
    }

    /** Makes a top-level item the last sub-item of the item above it. */
    public function indent(string $key): void
    {
        $index = $this->topIndex($key);
        if (! $this->allowsChildren() || $index === null || $index === 0 || $this->items[$index]['children'] !== []) {
            return;
        }

        $item = $this->items[$index];
        array_splice($this->items, $index, 1);
        $this->items[$index - 1]['children'][] = $item;
        $this->dirty = true;
    }

    /** Moves a sub-item out, right after its parent. */
    public function outdent(string $key): void
    {
        foreach ($this->items as $parentIndex => $parent) {
            foreach ($parent['children'] as $childIndex => $child) {
                if ($child['key'] === $key) {
                    array_splice($this->items[$parentIndex]['children'], $childIndex, 1);
                    array_splice($this->items, $parentIndex + 1, 0, [$child + ['children' => []]]);
                    $this->dirty = true;

                    return;
                }
            }
        }
    }

    /**
     * Called by drag and drop with the keys of one list in their new order.
     *
     * @param  array<int, string>  $keys
     */
    public function reorder(array $keys, ?string $parentKey = null): void
    {
        $parentIndex = $parentKey === null ? null : $this->topIndex($parentKey);
        $list = $parentIndex === null ? $this->items : $this->items[$parentIndex]['children'];
        $byKey = collect($list)->keyBy('key');

        $ordered = collect($keys)->map(fn (string $key) => $byKey->get($key))->filter()->values();
        if ($ordered->count() !== count($list)) {
            return;
        }

        if ($parentIndex === null) {
            $this->items = $ordered->all();
        } else {
            $this->items[$parentIndex]['children'] = $ordered->all();
        }
        $this->dirty = true;
    }

    public function save(): void
    {
        abort_unless(MenuResource::canEdit($this->menu), 403);

        $this->validate([
            'name' => ['required', 'max:255'],
            'items.*.title' => ['required', 'max:255'],
            'items.*.url' => ['required', 'max:2048', self::URL_RULE],
            'items.*.children.*.title' => ['required', 'max:255'],
            'items.*.children.*.url' => ['required', 'max:2048', self::URL_RULE],
            'items.*.badge' => ['nullable', 'max:30'],
            'items.*.children.*.badge' => ['nullable', 'max:30'],
            'items.*.children.*.description' => ['nullable', 'max:255'],
            'items.*.children.*.column_title' => ['nullable', 'max:80'],
        ], [
            'regex' => 'Đường dẫn phải bắt đầu bằng /, #, https://, tel: hoặc mailto:',
        ], [
            'name' => 'tên menu',
            'items.*.title' => 'tên hiển thị',
            'items.*.url' => 'đường dẫn',
            'items.*.children.*.title' => 'tên hiển thị',
            'items.*.children.*.url' => 'đường dẫn',
            'items.*.badge' => 'nhãn',
            'items.*.children.*.badge' => 'nhãn',
            'items.*.children.*.description' => 'mô tả',
            'items.*.children.*.column_title' => 'tên cột',
        ]);

        $menu = $this->menu;

        DB::transaction(function () use ($menu): void {
            $menu->update(['name' => $this->name, 'is_active' => $this->isActive]);

            $keep = [];
            foreach (array_values($this->items) as $position => $item) {
                $parent = $this->persist($menu, $item, null, $position);
                $keep[] = $parent->id;

                foreach (array_values($this->allowsChildren() ? $item['children'] : []) as $childPosition => $child) {
                    $keep[] = $this->persist($menu, $child, $parent->id, $childPosition)->id;
                }
            }

            // Sub-items first, so no parent is removed while it still has children.
            $menu->allItems()->whereNotIn('id', $keep)->orderByRaw('parent_id is null')->get()->each->delete();
        });

        app(FrontendMenuCache::class)->forget($menu->location);
        $this->load();

        Notification::make()->success()->title('Đã lưu menu')->send();
    }

    public function discard(): void
    {
        $this->load();
    }

    private function load(): void
    {
        unset($this->menu);
        $menu = $this->menu->load('items.children');

        $this->name = $menu->name;
        $this->isActive = $menu->is_active;
        $this->items = $menu->items->map(fn (MenuItem $item): array => $this->toState($item) + [
            'children' => $item->children->map(fn (MenuItem $child): array => $this->toState($child))->all(),
        ])->all();
        $this->editing = null;
        $this->picked = [];
        $this->dirty = false;
        $this->resetValidation();
    }

    /** @return array<string, mixed> */
    private function toState(MenuItem $item): array
    {
        return [
            'key' => 'i'.$item->id,
            'id' => $item->id,
            'title' => $item->title,
            'url' => $item->url,
            'icon' => $item->icon,
            'description' => $item->description,
            'badge' => $item->badge,
            'column_title' => $item->column_title,
            'is_mega' => $item->is_mega,
            'open_in_new_tab' => $item->open_in_new_tab,
            'is_active' => $item->is_active,
        ];
    }

    /** @return array<string, mixed> */
    private function newItem(string $title, string $url): array
    {
        return [
            'key' => 'n'.Str::lower(Str::random(10)),
            'id' => null,
            'title' => $title,
            'url' => $url,
            'icon' => null,
            'description' => null,
            'badge' => null,
            'column_title' => null,
            'is_mega' => false,
            'open_in_new_tab' => false,
            'is_active' => true,
            'children' => [],
        ];
    }

    /** @param array<string, mixed> $state */
    private function persist(Menu $menu, array $state, ?int $parentId, int $position): MenuItem
    {
        $item = $state['id'] ? $menu->allItems()->find($state['id']) : null;
        $item ??= new MenuItem(['menu_id' => $menu->id]);

        // Sub-item details only mean something inside a header drop-down.
        $isChild = $parentId && $this->allowsChildren();
        $text = fn (string $key): ?string => trim((string) Arr::get($state, $key)) ?: null;

        $item->fill([
            'menu_id' => $menu->id,
            'parent_id' => $parentId,
            'title' => trim((string) $state['title']),
            'url' => trim((string) $state['url']),
            'icon' => $isChild ? $text('icon') : null,
            'description' => $isChild ? $text('description') : null,
            'column_title' => $isChild ? $text('column_title') : null,
            'badge' => $this->allowsChildren() ? $text('badge') : null,
            'is_mega' => ! $parentId && $this->allowsChildren() && (bool) Arr::get($state, 'is_mega'),
            'open_in_new_tab' => (bool) $state['open_in_new_tab'],
            'is_active' => (bool) $state['is_active'],
            'position' => $position,
        ])->save();

        return $item;
    }

    private function topIndex(string $key): ?int
    {
        foreach ($this->items as $index => $item) {
            if ($item['key'] === $key) {
                return $index;
            }
        }

        return null;
    }

    /**
     * Runs $change on the list (top level or sub-items) that holds $key.
     *
     * @param  callable(array<int, array<string, mixed>>&, int): void  $change
     */
    private function mutate(callable $change, string $key): void
    {
        foreach ($this->items as $index => $item) {
            if ($item['key'] === $key) {
                $change($this->items, $index);
                $this->items = array_values($this->items);
                $this->dirty = true;

                return;
            }
            foreach ($item['children'] as $childIndex => $child) {
                if ($child['key'] === $key) {
                    $change($this->items[$index]['children'], $childIndex);
                    $this->items[$index]['children'] = array_values($this->items[$index]['children']);
                    $this->dirty = true;

                    return;
                }
            }
        }
    }
}
