{{-- Dark call-to-action band. Pass :secondary-href="false" to hide the second button. --}}
@props(['title', 'text' => null, 'need' => 'Tư vấn dự án', 'button' => 'Nhận tư vấn miễn phí', 'secondaryHref' => null, 'secondaryLabel' => 'Xem Demo'])
@php($secondaryHref ??= route('products'))
<section {{ $attributes->merge(['class' => 'section cta-section']) }}>
    <div class="container">
        <div class="cta-banner">
            <div>
                <h2>{{ $title }}</h2>
                @if ($text)
                    <p>{{ $text }}</p>
                @endif
            </div>
            <div class="btn-row">
                <button class="btn btn-primary" type="button" data-consult="{{ $need }}">{{ $button }} <x-icon name="arrow-right" /></button>
                @if ($secondaryHref)
                    <a class="btn btn-outline-light" href="{{ $secondaryHref }}">{{ $secondaryLabel }}</a>
                @endif
            </div>
        </div>
    </div>
</section>
