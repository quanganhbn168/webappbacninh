<a aria-label="{{ site_config('name') }} - Trang chủ" class="brand {{ $class ?? '' }}" href="{{ route('home') }}">
    @if ($logo = site_logo())
        <x-img :src="app(\App\Domain\Media\Actions\BuildResponsiveImage::class)->execute($logo, [230, 460], quality: 90)" sizes="230px" class="brand__logo" :alt="site_config('name')" width="230" height="64" />
    @else
        <span class="brand__name">WEBAPP <b>BẮC NINH</b></span>
        <span class="brand__tagline"><i></i>WEBSITE - PHẦN MỀM - VẬN HÀNH SỐ<i></i></span>
    @endif
</a>
