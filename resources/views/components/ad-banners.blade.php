{{-- Active banners for a slot (admin: Banner quảng cáo). Renders nothing when the slot is empty. --}}
@props(['position'])
@php($banners = \App\Models\AdBanner::query()->forSlot(\App\Enums\BannerSlot::from($position))->with('image')->get())
@if ($banners->isNotEmpty())
    <div {{ $attributes->merge(['class' => 'ad-banners ad-banners--'.$position]) }}>
        @foreach ($banners as $banner)
            <a class="ad-banner" href="{{ $banner->link ?: '#' }}" @if ($banner->open_new_tab) target="_blank" rel="noopener sponsored" @endif>
                <img src="{{ $banner->image_url }}" alt="{{ $banner->alt_text ?: $banner->name }}" loading="lazy" decoding="async" @if ($banner->image) width="{{ $banner->image->width }}" height="{{ $banner->image->height }}" @endif>
            </a>
        @endforeach
    </div>
@endif
