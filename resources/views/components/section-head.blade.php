@props(['title', 'lead' => null, 'eyebrow' => null, 'href' => null, 'link' => null, 'center' => false, 'level' => 'h2'])
<div {{ $attributes->class(['section-head', 'section-head--center' => $center]) }}>
    <div>
        @if ($eyebrow)
            <p class="eyebrow">{{ $eyebrow }}</p>
        @endif
        <{{ $level }} class="h2">{{ $title }}</{{ $level }}>
        @if ($lead)
            <p>{{ $lead }}</p>
        @endif
    </div>
    @if ($href)
        <a class="link-arrow" href="{{ $href }}">{{ $link ?? 'Xem tất cả' }} <x-icon name="arrow-right" /></a>
    @endif
</div>
