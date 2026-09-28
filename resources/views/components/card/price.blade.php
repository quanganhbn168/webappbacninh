@props(['plan'])
<article {{ $attributes->class(['price-card', 'is-featured' => $plan->is_featured]) }}>
    @if ($plan->is_featured)
        <span class="price-card__badge">{{ $plan->badge ?: 'Phổ biến nhất' }}</span>
    @endif
    <h3>{{ $plan->name }}</h3>
    @if ($plan->summary)
        <p class="price-card__summary">{{ $plan->summary }}</p>
    @endif
    <p class="price-card__price">
        @if ($plan->price_prefix)<small>{{ $plan->price_prefix }}</small>@endif{{ $plan->price }}@if ($plan->price_suffix)<small>{{ $plan->price_suffix }}</small>@endif
    </p>
    @if ($plan->features)
        <ul class="check-list">
            @foreach ($plan->features as $feature)
                <li>{{ $feature }}</li>
            @endforeach
        </ul>
    @endif
    <button type="button" @class(['btn', 'mt-auto', 'btn-primary' => $plan->is_featured, 'btn-outline-primary' => ! $plan->is_featured]) data-consult="{{ $plan->name }}">{{ $plan->cta_label ?: 'Nhận tư vấn' }}</button>
</article>
