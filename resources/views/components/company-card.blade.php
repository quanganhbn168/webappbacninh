@props(['channels' => []])
{{-- The company details from admin → Cài đặt, so articles never have to repeat them. --}}
<aside {{ $attributes->class(['company-card']) }} aria-labelledby="company-card-title">
    <h2 id="company-card-title">{{ site_config('company_name') ?: site_config('name') }}</h2>
    <ul class="company-card__list">
        @if (site_config('address'))
            <li><x-icon name="map-pin" /><span>{{ site_config('address') }}</span></li>
        @endif
        @if (site_config('phone'))
            <li><x-icon name="phone" /><span><a href="tel:{{ site_config('phone_href') }}">{{ site_config('phone') }}</a>@if (site_config('phone_secondary') && site_config('phone_secondary_href')) · <a href="tel:{{ site_config('phone_secondary_href') }}">{{ site_config('phone_secondary') }}</a>@endif</span></li>
        @endif
        @if (site_config('email'))
            <li><x-icon name="mail" /><a href="mailto:{{ site_config('email') }}">{{ site_config('email') }}</a></li>
        @endif
        @if (site_config('site_url'))
            <li><x-icon name="globe" /><a href="{{ rtrim((string) site_config('site_url'), '/') }}/">{{ parse_url((string) site_config('site_url'), PHP_URL_HOST) }}</a></li>
        @endif
        @if (site_config('working_time'))
            <li><x-icon name="clock" /><span>{{ site_config('working_time') }}</span></li>
        @endif
    </ul>
    <div class="company-card__actions">
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#consult-modal">Nhận tư vấn</button>
        @foreach ($channels as $channel)
            <a class="btn btn-outline-dark" href="{{ $channel['url'] }}" target="_blank" rel="noopener noreferrer"><x-icon :name="$channel['icon']" /> {{ $channel['label'] }}</a>
        @endforeach
    </div>
</aside>
