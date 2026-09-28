@props(['product', 'variant' => 'full'])
@if ($variant === 'tile')
    <a {{ $attributes->class(['product-tile']) }} href="{{ $product->detail_url ?: route('products') }}">
        <span class="product-tile__image"><img class="img-cover" src="{{ $product->image_url }}" alt="" width="320" height="220" loading="lazy" decoding="async"></span>
        <span class="product-tile__name">{{ $product->name }}</span>
    </a>
@else
    <article {{ $attributes->class(['media-card', 'media-card--center']) }}>
        <div class="media-card__image ratio-card">
            <img class="img-cover" src="{{ $product->image_url }}" alt="{{ $product->name }}" width="640" height="400" loading="lazy" decoding="async">
            <span class="media-card__badge">{{ $product->group?->badge() }}</span>
        </div>
        <div class="media-card__body">
            <h3 class="media-card__title">{{ $product->name }}</h3>
            @if ($product->summary)
                <p>{{ $product->summary }}</p>
            @endif
            @if ($product->tags)
                <div class="media-card__tags">
                    @foreach ($product->tags as $tag)
                        <span class="tag">{{ $tag }}</span>
                    @endforeach
                </div>
            @endif
            <div class="media-card__actions">
                @if ($product->detail_url)
                    <a class="btn btn-outline-primary btn-sm" href="{{ $product->detail_url }}">Xem chi tiết</a>
                @else
                    <button class="btn btn-outline-primary btn-sm" type="button" data-consult="{{ $product->name }}">Xem chi tiết</button>
                @endif
                @if ($product->demo_url)
                    <a class="btn btn-primary btn-sm" href="{{ $product->demo_url }}" target="_blank" rel="noopener">Xem Demo</a>
                @else
                    <button class="btn btn-primary btn-sm" type="button" data-consult="Xem demo: {{ $product->name }}">Xem Demo</button>
                @endif
            </div>
        </div>
    </article>
@endif
