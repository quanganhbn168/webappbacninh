{{-- One row of the menu builder: $item, $path ("items.0" or "items.0.children.1"), $child, $first, $last. --}}
@php
    $open = $editing === $item['key'];
    $hasError = $errors->has($path.'.title') || $errors->has($path.'.url');
    $canIndent = ! $child && ! $first && $this->allowsChildren() && ($item['children'] ?? []) === [];
@endphp
<div @class([
    'rounded-xl border bg-white shadow-sm dark:bg-gray-900',
    'border-danger-400' => $hasError,
    'border-primary-400 ring-1 ring-primary-400' => $open && ! $hasError,
    'border-gray-200 dark:border-white/10' => ! $open && ! $hasError,
    'opacity-60' => ! $item['is_active'],
])>
    <div class="flex items-center gap-3 px-3 py-2.5">
        <button type="button" x-sortable-handle class="cursor-move text-gray-400 hover:text-gray-600" title="Kéo để đổi thứ tự">
            <x-filament::icon icon="heroicon-m-bars-2" class="h-5 w-5" />
        </button>

        @if ($child && filled($item['icon']))
            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-500/10 [&_svg]:h-4 [&_svg]:w-4">{{ \App\Support\Icons::svg($item['icon']) }}</span>
        @endif

        <button type="button" wire:click="edit('{{ $item['key'] }}')" class="min-w-0 flex-1 text-left">
            <span class="flex items-center gap-2">
                <span class="truncate font-medium text-gray-950 dark:text-white">{{ $item['title'] ?: 'Chưa đặt tên' }}</span>
                @unless ($item['is_active'])<x-filament::badge color="gray" size="sm">Đang ẩn</x-filament::badge>@endunless
                @if ($item['open_in_new_tab'])<x-filament::badge color="info" size="sm">Tab mới</x-filament::badge>@endif
                @if (! $item['id'])<x-filament::badge color="success" size="sm">Mới</x-filament::badge>@endif
            </span>
            <span class="block truncate text-xs text-gray-500">{{ $item['url'] }}</span>
        </button>

        <div class="flex shrink-0 items-center gap-0.5">
            @if ($canIndent)
                <x-filament::icon-button icon="heroicon-m-arrow-right" color="gray" size="sm" wire:click="indent('{{ $item['key'] }}')" label="Đưa vào làm menu con của mục trên" tooltip="Làm menu con" />
            @endif
            @if ($child)
                <x-filament::icon-button icon="heroicon-m-arrow-left" color="gray" size="sm" wire:click="outdent('{{ $item['key'] }}')" label="Đưa ra ngoài" tooltip="Đưa ra menu chính" />
            @endif
            <x-filament::icon-button icon="heroicon-m-chevron-up" color="gray" size="sm" wire:click="move('{{ $item['key'] }}', -1)" label="Lên" :disabled="$first" />
            <x-filament::icon-button icon="heroicon-m-chevron-down" color="gray" size="sm" wire:click="move('{{ $item['key'] }}', 1)" label="Xuống" :disabled="$last" />
            <x-filament::icon-button :icon="$item['is_active'] ? 'heroicon-m-eye' : 'heroicon-m-eye-slash'" color="gray" size="sm" wire:click="toggle('{{ $item['key'] }}')" :label="$item['is_active'] ? 'Ẩn mục' : 'Hiện mục'" :tooltip="$item['is_active'] ? 'Ẩn' : 'Hiện'" />
            <x-filament::icon-button icon="heroicon-m-pencil-square" :color="$open ? 'primary' : 'gray'" size="sm" wire:click="edit('{{ $item['key'] }}')" label="Sửa" tooltip="Sửa" />
            <x-filament::icon-button icon="heroicon-m-trash" color="danger" size="sm" wire:click="remove('{{ $item['key'] }}')"
                :wire:confirm="($item['children'] ?? []) !== [] ? 'Xóa mục này cùng '.count($item['children']).' menu con?' : 'Xóa mục này khỏi menu?'" label="Xóa" tooltip="Xóa" />
        </div>
    </div>

    @if ($open || $hasError)
        <div class="grid gap-4 border-t border-gray-100 px-4 py-4 sm:grid-cols-2 dark:border-white/10">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-950 dark:text-white">Tên hiển thị</label>
                <x-filament::input.wrapper :valid="! $errors->has($path.'.title')">
                    <x-filament::input type="text" wire:model.blur="{{ $path }}.title" />
                </x-filament::input.wrapper>
                @error($path.'.title')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-950 dark:text-white">Đường dẫn</label>
                <x-filament::input.wrapper :valid="! $errors->has($path.'.url')">
                    <x-filament::input type="text" wire:model.blur="{{ $path }}.url" placeholder="/lien-he hoặc https://…" />
                </x-filament::input.wrapper>
                @error($path.'.url')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
            </div>
            @if ($child && $this->allowsChildren())
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-950 dark:text-white">Icon <span class="font-normal text-gray-500">(hiện trong menu con)</span></label>
                    <x-filament::input.wrapper>
                        <x-filament::input type="text" list="menu-icon-names" wire:model.blur="{{ $path }}.icon" placeholder="Gõ để tìm, ví dụ: monitor" />
                    </x-filament::input.wrapper>
                </div>
            @endif
            <div class="flex flex-wrap items-end gap-x-6 gap-y-2 pb-2">
                <label class="flex items-center gap-2 text-sm text-gray-950 dark:text-white">
                    <x-filament::input.checkbox wire:model.live="{{ $path }}.open_in_new_tab" /> Mở tab mới
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-950 dark:text-white">
                    <x-filament::input.checkbox wire:model.live="{{ $path }}.is_active" /> Hiển thị
                </label>
                @if (str_starts_with((string) $item['url'], '/'))
                    <a href="{{ url($item['url']) }}" target="_blank" rel="noopener" class="text-sm font-medium text-primary-600 hover:underline">Xem trang ↗</a>
                @endif
            </div>
        </div>
    @endif
</div>
