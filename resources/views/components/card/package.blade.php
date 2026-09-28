{{-- A service package stored on the service itself: name, price, desc, items, featured. --}}
@props(['package', 'number' => 1, 'anchor' => '#lien-he-dich-vu'])
@php($featured = ! empty($package['featured']))
<article {{ $attributes->class(['price-card', 'is-featured' => $featured]) }}>
    @if ($featured)<span class="price-card__badge">Được quan tâm</span>@endif
    <span class="eyebrow mb-0">Gói {{ str_pad((string) $number, 2, '0', STR_PAD_LEFT) }}</span>
    <h3>{{ $package['name'] }}</h3>
    <p class="price-card__price">{{ $package['price'] }}</p>
    @if (! empty($package['desc']))<p class="price-card__summary mt-0">{{ $package['desc'] }}</p>@endif
    @if (! empty($package['items']))
        <ul class="check-list">@foreach ($package['items'] as $item)<li>{{ $item }}</li>@endforeach</ul>
    @endif
    <a @class(['btn', 'mt-auto', 'btn-primary' => $featured, 'btn-outline-primary' => ! $featured]) href="{{ $anchor }}">Nhận báo giá</a>
</article>
