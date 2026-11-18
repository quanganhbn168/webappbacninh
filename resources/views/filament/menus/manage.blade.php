<x-filament-panels::page>
    {{-- Which menu --}}
    <x-filament::tabs label="Chọn menu">
        @foreach (\App\Models\Menu::locations() as $value => $label)
            <x-filament::tabs.item :active="$location === $value" wire:click="switchTo('{{ $value }}')"
                :wire:confirm="$dirty && $location !== $value ? 'Menu đang có thay đổi chưa lưu. Chuyển sang menu khác sẽ bỏ các thay đổi đó?' : null">
                {{ $label }}
            </x-filament::tabs.item>
        @endforeach
    </x-filament::tabs>

    <div class="grid items-start gap-6 lg:grid-cols-3">
        {{-- Left: add items --}}
        <div class="flex flex-col gap-6 lg:sticky lg:top-20">
            <x-filament::section heading="Thêm trang có sẵn" description="Tick các trang cần thêm, không phải gõ đường dẫn." compact>
                <div class="flex flex-col divide-y divide-gray-100 dark:divide-white/10">
                    @foreach ($this->sources as $group => $source)
                        <details class="group py-2" @if ($loop->first) open @endif>
                            <summary class="flex cursor-pointer list-none items-center justify-between py-1 text-sm font-semibold text-gray-950 dark:text-white">
                                <span>{{ $source['label'] }} <span class="font-normal text-gray-500">({{ count($source['links']) }})</span></span>
                                <x-filament::icon icon="heroicon-m-chevron-down" class="h-4 w-4 text-gray-400 transition group-open:rotate-180" />
                            </summary>
                            <div class="mt-2 flex max-h-64 flex-col gap-1 overflow-y-auto pr-1">
                                @foreach ($source['links'] as $index => $link)
                                    <label class="flex cursor-pointer items-start gap-2 rounded-lg px-2 py-1.5 hover:bg-gray-50 dark:hover:bg-white/5">
                                        <x-filament::input.checkbox wire:model="picked" value="{{ $group }}:{{ $index }}" class="mt-0.5" />
                                        <span class="min-w-0 text-sm leading-5">
                                            <span class="block text-gray-950 dark:text-white">{{ $link['title'] }}</span>
                                            <span class="block truncate text-xs text-gray-500">{{ $link['url'] }}</span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </details>
                    @endforeach
                </div>
                <x-filament::button class="mt-3 w-full" icon="heroicon-m-plus" wire:click="addPicked">Thêm vào menu</x-filament::button>
            </x-filament::section>

            <x-filament::section heading="Link tùy chỉnh" description="Trang ngoài, số điện thoại hoặc mục trong trang." compact>
                <form wire:submit="addCustom" class="flex flex-col gap-3">
                    <div>
                        <x-filament::input.wrapper :valid="! $errors->has('customTitle')">
                            <x-filament::input type="text" wire:model="customTitle" placeholder="Tên hiển thị" />
                        </x-filament::input.wrapper>
                        @error('customTitle')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-filament::input.wrapper :valid="! $errors->has('customUrl')">
                            <x-filament::input type="text" wire:model="customUrl" placeholder="/duong-dan, https://… hoặc tel:…" />
                        </x-filament::input.wrapper>
                        @error('customUrl')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
                    </div>
                    <x-filament::button type="submit" color="gray" icon="heroicon-m-link">Thêm link</x-filament::button>
                </form>
            </x-filament::section>
        </div>

        {{-- Right: the menu --}}
        <div class="lg:col-span-2">
            <x-filament::section>
                <x-slot name="heading">
                    <span class="flex items-center gap-2">
                        Cấu trúc menu
                        @if ($dirty)<x-filament::badge color="warning">Chưa lưu</x-filament::badge>@endif
                    </span>
                </x-slot>
                <x-slot name="description">
                    Kéo <x-filament::icon icon="heroicon-m-bars-2" class="inline h-4 w-4 align-text-bottom" /> để đổi thứ tự.
                    @if ($this->allowsChildren())
                        Bấm <x-filament::icon icon="heroicon-m-arrow-right" class="inline h-4 w-4 align-text-bottom" /> để đưa một mục vào làm menu con của mục phía trên.
                    @else
                        Cột footer là danh sách một cấp.
                    @endif
                </x-slot>

                <div class="mb-5 grid gap-4 sm:grid-cols-[1fr_auto] sm:items-end">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-950 dark:text-white" for="menu-name">
                            {{ $this->allowsChildren() ? 'Tên menu' : 'Tiêu đề cột footer' }}
                        </label>
                        <x-filament::input.wrapper :valid="! $errors->has('name')">
                            <x-filament::input id="menu-name" type="text" wire:model.blur="name" />
                        </x-filament::input.wrapper>
                        @error('name')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
                    </div>
                    <label class="flex items-center gap-2 pb-2 text-sm font-medium text-gray-950 dark:text-white">
                        <x-filament::input.checkbox wire:model.live="isActive" /> Hiển thị menu này
                    </label>
                </div>

                @if ($items === [])
                    <div class="rounded-xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500 dark:border-white/20">
                        Menu chưa có mục nào. Chọn trang ở cột bên trái để thêm.
                    </div>
                @else
                    <ul class="flex flex-col gap-2" x-sortable data-sortable-animation-duration="150"
                        x-on:end.stop="$wire.reorder($event.target.sortable.toArray(), null)">
                        @foreach ($items as $index => $item)
                            <li x-sortable-item="{{ $item['key'] }}" wire:key="menu-item-{{ $item['key'] }}">
                                @include('filament.menus.item', ['item' => $item, 'path' => "items.$index", 'child' => false, 'first' => $loop->first, 'last' => $loop->last])

                                @if ($item['children'] !== [])
                                    <ul class="mt-2 ml-6 flex flex-col gap-2 border-l-2 border-primary-200 pl-4 dark:border-primary-500/30" x-sortable data-sortable-animation-duration="150"
                                        x-on:end.stop="$wire.reorder($event.target.sortable.toArray(), @js($item['key']))">
                                        @foreach ($item['children'] as $childIndex => $childItem)
                                            <li x-sortable-item="{{ $childItem['key'] }}" wire:key="menu-item-{{ $childItem['key'] }}">
                                                @include('filament.menus.item', ['item' => $childItem, 'path' => "items.$index.children.$childIndex", 'child' => true, 'first' => $loop->first, 'last' => $loop->last])
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif

                <x-slot name="footer">
                    <div class="flex flex-wrap items-center gap-3">
                        <x-filament::button wire:click="save" icon="heroicon-m-check">Lưu menu</x-filament::button>
                        @if ($dirty)
                            <x-filament::button color="gray" wire:click="discard" wire:confirm="Bỏ mọi thay đổi chưa lưu?">Hủy thay đổi</x-filament::button>
                        @endif
                        @if ($errors->has('items.*'))
                            <span class="text-sm text-danger-600">Có mục chưa hợp lệ, xem các ô đỏ ở trên.</span>
                        @endif
                    </div>
                </x-slot>
            </x-filament::section>
        </div>
    </div>

    <datalist id="menu-icon-names">
        @foreach ($this->iconNames as $iconName)<option value="{{ $iconName }}"></option>@endforeach
    </datalist>
</x-filament-panels::page>
