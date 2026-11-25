{{-- A price plan on its service page, numbered in the order shown. --}}
@props(['plan', 'number' => 1, 'anchor' => '#lien-he-dich-vu'])
<article {{ $attributes->class(['price-card', 'is-featured' => $plan->is_featured]) }}>
    @if ($plan->is_featured)<span class="price-card__badge">{{ $plan->badge ?: 'Được quan tâm' }}</span>@endif
    <span class="eyebrow mb-0">Gói {{ str_pad((string) $number, 2, '0', STR_PAD_LEFT) }}</span>
    <h3>{{ $plan->name }}</h3>
    <p class="price-card__price">{{ $plan->priceText() }}</p>
    @if ($plan->summary)<p class="price-card__summary mt-0">{{ $plan->summary }}</p>@endif
    @if ($plan->features)
        <ul class="check-list">@foreach ($plan->features as $feature)<li>{{ $feature }}</li>@endforeach</ul>
    @endif
    <a @class(['btn', 'mt-auto', 'btn-primary' => $plan->is_featured, 'btn-outline-primary' => ! $plan->is_featured]) href="{{ $anchor }}">{{ $plan->cta_label ?: 'Nhận báo giá' }}</a>
</article>
