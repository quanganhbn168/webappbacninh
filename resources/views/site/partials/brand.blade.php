<a aria-label="{{ site_config('name') }} - Trang chủ" class="brand {{ $class ?? '' }}" href="{{ route('home') }}">
    @if (site_config('site_logo_wide'))
        <img class="brand__logo" src="{{ site_asset_url(site_config('site_logo_wide')) }}" alt="{{ site_config('name') }}" width="230" height="64">
    @else
        <span class="brand__name">WEBAPP <b>BẮC NINH</b></span>
        <span class="brand__tagline"><i></i>WEBSITE - PHẦN MỀM - VẬN HÀNH SỐ<i></i></span>
    @endif
</a>
