<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{ editing: false, state: $wire.entangle('{{ $getStatePath() }}') }" class="flex flex-wrap items-center gap-2 text-sm">
        <span class="break-all text-gray-500">{{ $getPrefixLabel() }}</span>
        <span x-show="!editing" x-text="state || 'duong-dan-tu-dong'" class="break-all"></span>
        <button type="button" x-show="!editing" x-on:click="editing = true; $nextTick(() => $refs.slug.focus())" class="text-primary-600 underline">Chỉnh sửa</button>
        <div x-show="editing" x-cloak class="flex items-center gap-2">
            <x-filament::input.wrapper>
                <x-filament::input x-ref="slug" x-model="state" x-on:keydown.enter.prevent="editing = false; $wire.set('{{ $getStatePath() }}', state)" aria-label="Chỉnh sửa đường dẫn" />
            </x-filament::input.wrapper>
            <button type="button" x-on:click="editing = false; $wire.set('{{ $getStatePath() }}', state)" class="text-primary-600 underline">Xong</button>
        </div>
    </div>
</x-dynamic-component>
