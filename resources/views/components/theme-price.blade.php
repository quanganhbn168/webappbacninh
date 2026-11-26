@props(['theme', 'label' => 'Giá từ'])
{{-- A discounted design shows the old price struck through above the new one in red; "Liên hệ" stands alone. --}}
<div {{ $attributes->class(['theme-price']) }}>
    @if ($theme->old_price)
        <del><span class="visually-hidden">Giá cũ </span>{{ money($theme->old_price) }}</del>
        <strong class="theme-price__sale"><span class="visually-hidden">Giá khuyến mãi </span>{{ $theme->price_label }}</strong>
    @else
        @if ($theme->effective_price)<small>{{ $label }}</small>@endif
        <strong>{{ $theme->price_label }}</strong>
    @endif
</div>
