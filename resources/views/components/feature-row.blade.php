@props(['image', 'title', 'text' => null, 'reversed' => false, 'note' => null, 'imageAlt' => null])
<article {{ $attributes->class(['feature-row', 'feature-row--reversed' => $reversed]) }}>
    <div class="feature-row__image">
        <img src="{{ $image }}" alt="{{ $imageAlt ?? $title }}" width="720" height="480" loading="lazy" decoding="async">
    </div>
    <div class="feature-row__body">
        <h3 class="h2">{{ $title }}</h3>
        @if ($text)
            <p class="lead-text">{{ $text }}</p>
        @endif
        {{ $slot }}
    </div>
    @if ($note)
        <p class="feature-row__note text-hand" aria-hidden="true">{{ $note }}</p>
    @endif
</article>
