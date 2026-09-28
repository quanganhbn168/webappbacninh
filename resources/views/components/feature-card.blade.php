@props(['icon' => null, 'title', 'text' => null, 'href' => null, 'number' => null])
@php($tag = $href ? 'a' : 'div')
<{{ $tag }} {{ $attributes->class(['feature-card', 'feature-card--link' => $href]) }} @if ($href) href="{{ $href }}" @endif>
    @if ($icon)
        <span class="icon-tile"><x-icon :name="$icon" /></span>
    @elseif ($number)
        <span class="feature-card__number">{{ $number }}</span>
    @endif
    <div>
        <h3>{{ $title }}</h3>
        @if ($text)
            <p>{{ $text }}</p>
        @endif
        {{ $slot }}
    </div>
</{{ $tag }}>
