@props(['theme'])
<article {{ $attributes->class(['media-card', 'theme-card']) }}
    data-theme-card
    data-search="{{ implode(' ', [$theme->name, $theme->code, $theme->industry_label, $theme->description, ...($theme->tags ?? [])]) }}"
    data-type="{{ $theme->type?->value }}"
    data-industry="{{ $theme->industry_slug }}"
    data-price="{{ (int) $theme->price }}"
    data-features="{{ implode(',', $theme->feature_keys) }}"
    data-year="{{ (int) $theme->year }}"
    data-featured="{{ $theme->featured_score }}"
    data-name="{{ $theme->name }}">
    <div class="media-card__image ratio-card">
        <img class="img-cover" src="{{ $theme->image_url }}" alt="" width="640" height="400" loading="lazy" decoding="async">
        <div class="theme-card__badges">
            @if (filled($theme->badge))
                <span @class(['theme-card__badge', 'theme-card__badge--hot' => $theme->badge === 'Bán chạy', 'theme-card__badge--new' => $theme->badge === 'Mới'])>{{ $theme->badge }}</span>
            @endif
            <span class="theme-card__badge">{{ $theme->type_label }}</span>
        </div>
    </div>
    <div class="media-card__body">
        <div class="theme-card__meta"><span>{{ $theme->industry_label }}</span><span class="theme-card__code">{{ $theme->code }}</span></div>
        <h3 class="media-card__title"><a href="{{ $theme->url }}">{{ $theme->name }}</a></h3>
        <p>{{ \Illuminate\Support\Str::limit((string) $theme->description, 120) }}</p>
        @if ($theme->tags)
            <div class="media-card__tags">@foreach (array_slice($theme->tags, 0, 3) as $tag)<span class="tag tag--gold">{{ $tag }}</span>@endforeach</div>
        @endif
        <div class="theme-card__footer">
            <div><small>Chi phí tham khảo từ</small><strong>{{ money($theme->price ?? 0) }}</strong></div>
            @if ($theme->demo_url)
                <a class="btn btn-outline-primary btn-sm" href="{{ $theme->demo_url }}" target="_blank" rel="noopener">Xem demo</a>
            @endif
        </div>
    </div>
</article>
