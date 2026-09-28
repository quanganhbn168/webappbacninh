{{-- Numbered process: items are [icon, title, text]. --}}
@props(['items' => []])
<ol {{ $attributes->merge(['class' => 'steps']) }}>
    @foreach ($items as [$icon, $title, $text])
        <li class="step">
            <div class="step__top">
                <span class="step__number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                <x-icon :name="$icon" class="step__icon" />
            </div>
            <h3>{{ $title }}</h3>
            <p>{{ $text }}</p>
        </li>
    @endforeach
</ol>
