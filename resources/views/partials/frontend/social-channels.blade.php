    <aside class="floating-actions fixed right-4 bottom-4 z-30 flex flex-col items-end gap-2" aria-label="Liên hệ nhanh">
        @if(site_config('phone_href'))
            <a class="floating-action floating-actions__phone" href="tel:{{ site_config('phone_href') }}" aria-label="Gọi tư vấn" title="Gọi tư vấn">@include('partials.frontend.contact-icon', ['icon' => 'phone'])</a>
        @endif
        <button type="button" class="floating-action" data-consult aria-label="Yêu cầu tư vấn" title="Yêu cầu tư vấn">@include('partials.frontend.contact-icon', ['icon' => 'chat'])</button>
        @foreach ($socialChannels['floating'] as $channel)
            <a class="floating-actions__{{ $channel['key'] }} floating-action" href="{{ $channel['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $channel['label'] }}" title="{{ $channel['label'] }}">@include('partials.frontend.contact-icon', ['icon' => $channel['key']])</a>
        @endforeach
        @if ($socialChannels['wechat'])
            <div x-data="{ open: false }" class="rounded-xl bg-white p-3 shadow-lg">
                <button type="button" @click="open = !open" :aria-expanded="open" aria-controls="wechatContactModal">WeChat</button>
                <div id="wechatContactModal" x-show="open" x-cloak class="w-48 pt-3">
                    <img src="{{ $socialChannels['wechat']['qr_url'] }}" alt="Mã QR WeChat" class="w-full">
                    <p>{{ $socialChannels['wechat']['id'] }}</p>
                </div>
            </div>
        @endif
    <button type="button" class="floating-action floating-action--top" x-data x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })" aria-label="Về đầu trang" title="Về đầu trang">@include('partials.frontend.contact-icon', ['icon' => 'top'])</button>
    </aside>
