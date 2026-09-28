@props(['title' => 'Nhận tư vấn', 'text' => 'Gửi thông tin để được tư vấn.', 'need' => '', 'eyebrow' => 'Nhận tư vấn'])
<section {{ $attributes->merge(['class' => 'section section--dark lead-section']) }}>
    <div class="container">
        <div class="lead-panel">
            <div class="lead-panel__copy">
                <p class="eyebrow">{{ $eyebrow }}</p>
                <h2>{{ $title }}</h2>
                <p>{{ $text }}</p>
                <div class="lead-panel__contacts">
                    <a href="tel:{{ site_config('phone_href') }}"><x-icon name="phone" /><span><small>Hotline / Zalo</small><strong>{{ site_config('phone') }}</strong></span></a>
                    <a href="mailto:{{ site_config('email') }}"><x-icon name="mail" /><span><small>Email</small><strong class="text-break">{{ site_config('email') }}</strong></span></a>
                </div>
            </div>
            <div class="lead-panel__form">
                <x-lead-form :need="$need" :id="$attributes->get('id', 'lead').'-form'" />
            </div>
        </div>
    </div>
</section>
