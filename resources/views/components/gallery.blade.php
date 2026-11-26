{{--
    Image slider with a thumbnail strip, on the project and theme detail pages. Swiper comes from
    public/vendor/swiper; resources/js/components/gallery.js starts it.
    "slides" is a model's slides; "scroll" suits tall website screenshots: they fill the frame
    and scroll while hovered, instead of being shown whole.
--}}
@props(['slides', 'title', 'sizes' => '100vw', 'width' => 800, 'height' => 500, 'scroll' => false])

@once
    @push('head')
        <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
        @vite('resources/js/components/gallery.js')
    @endpush
@endonce

<div {{ $attributes->class(['gallery', 'gallery--scroll' => $scroll]) }} data-gallery>
    <div class="swiper gallery__main">
        <div class="swiper-wrapper">
            @foreach ($slides as $index => $slide)
                <div class="swiper-slide" @if ($scroll) tabindex="0" @endif>
                    <x-img :src="$slide" :sizes="$sizes" :alt="$title.($index ? ' - ảnh '.($index + 1) : '')" :width="$width" :height="$height" :loading="$index ? 'lazy' : null" :fetchpriority="$index ? null : 'high'" />
                </div>
            @endforeach
        </div>
        @if (count($slides) > 1)
            <button class="gallery__nav gallery__nav--prev" type="button" aria-label="Ảnh trước"><x-icon name="chevron-left" /></button>
            <button class="gallery__nav gallery__nav--next" type="button" aria-label="Ảnh tiếp theo"><x-icon name="chevron-right" /></button>
            <span class="gallery__counter"></span>
        @endif
    </div>
    @if (count($slides) > 1)
        <div class="swiper gallery__thumbs">
            <div class="swiper-wrapper">
                @foreach ($slides as $index => $slide)
                    <div class="swiper-slide"><button type="button" aria-label="Xem ảnh {{ $index + 1 }}"><img src="{{ $slide['thumb'] }}" alt="" width="120" height="80" loading="lazy"></button></div>
                @endforeach
            </div>
        </div>
    @endif
</div>
