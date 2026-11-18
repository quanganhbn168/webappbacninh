{{-- "Tạo mới" in the topbar: every resource the signed-in user may create, grouped as in the sidebar. --}}
@php
    $groups = collect(filament()->getResources())
        ->filter(fn (string $resource): bool => $resource::hasPage('create') && $resource::canCreate())
        ->groupBy(fn (string $resource): string => (string) (($group = $resource::getNavigationGroup()) instanceof \Filament\Support\Contracts\HasLabel ? $group->getLabel() : $group))
        ->sortBy(fn ($resources, string $label): int => collect(\App\Filament\NavigationGroup::cases())->search(fn ($case): bool => $case->getLabel() === $label));
@endphp
@if ($groups->isNotEmpty())
    <x-filament::dropdown placement="bottom-end" width="xs" max-height="70vh">
        <x-slot name="trigger">
            <x-filament::button icon="heroicon-m-plus" size="md">Tạo mới</x-filament::button>
        </x-slot>

        @foreach ($groups as $label => $resources)
            <x-filament::dropdown.header>{{ $label ?: 'Khác' }}</x-filament::dropdown.header>
            <x-filament::dropdown.list>
                @foreach ($resources->sortBy(fn (string $resource): int => $resource::getNavigationSort() ?? 0) as $resource)
                    <x-filament::dropdown.list.item tag="a" :href="$resource::getUrl('create')" :icon="$resource::getNavigationIcon()">
                        {{ \Illuminate\Support\Str::ucfirst($resource::getModelLabel()) }}
                    </x-filament::dropdown.list.item>
                @endforeach
            </x-filament::dropdown.list>
        @endforeach
    </x-filament::dropdown>
@endif
