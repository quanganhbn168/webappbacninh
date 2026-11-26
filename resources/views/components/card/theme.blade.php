@props(['theme'])
<article {{ $attributes->class(['theme-card']) }}
    data-theme-card
    data-search="{{ implode(' ', [$theme->name, $theme->code, $theme->industry_label, $theme->description, ...($theme->tags ?? [])]) }}"
    data-type="{{ $theme->type?->value }}"
    data-industry="{{ $theme->industry_slug }}"
    data-price="{{ (int) $theme->effective_price }}"
    data-features="{{ implode(',', $theme->feature_keys) }}"
    data-year="{{ (int) $theme->year }}"
    data-featured="{{ $theme->featured_score }}"
    data-name="{{ $theme->name }}">
    <a class="theme-card__window" href="{{ $theme->url }}" tabindex="-1" aria-hidden="true">
        {{-- A tall screenshot scrolls through on hover. --}}
        <span class="theme-card__screen">
            <x-img :src="$theme->cover" sizes="(min-width: 992px) 400px, (min-width: 768px) 50vw, 100vw" alt="{{ $theme->name }}" width="640" height="400" loading="lazy" decoding="async" />
        </span>
        @if (filled($theme->badge))
            <span @class(['theme-card__badge', 'theme-card__badge--hot' => $theme->badge === 'Bán chạy', 'theme-card__badge--new' => $theme->badge === 'Mới'])>{{ $theme->badge }}</span>
        @endif
    </a>
    <div class="theme-card__body">
        <h3 class="theme-card__title"><a href="{{ $theme->url }}">{{ $theme->name }}</a></h3>
        <p class="theme-card__meta">{{ $theme->industry_label }} · {{ $theme->code }}</p>
        <div class="theme-card__footer">
            <x-theme-price :theme="$theme" />
            <div class="theme-card__actions">
                @if ($theme->preview_url)
                    <a class="btn btn-outline-dark theme-card__preview" href="{{ $theme->preview_url }}" target="_blank" rel="noopener" aria-label="Xem demo {{ $theme->name }}" title="Xem demo"><x-icon name="eye" /></a>
                @endif
                <a class="btn btn-primary" href="{{ $theme->url }}#chon-mau">Chọn mẫu này</a>
            </div>
        </div>
    </div>
</article>
