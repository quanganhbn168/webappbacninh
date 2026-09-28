    <aside class="floating-actions position-fixed bottom-0 end-0 m-3 d-flex flex-column align-items-end gap-2" aria-label="Liên hệ nhanh">
        @if(site_config('phone_href'))
            <a class="floating-action floating-actions__phone" href="tel:{{ site_config('phone_href') }}" aria-label="Gọi tư vấn" title="Gọi tư vấn">@include('partials.frontend.contact-icon', ['icon' => 'phone'])</a>
        @endif
        <button type="button" class="floating-action" data-consult aria-label="Yêu cầu tư vấn" title="Yêu cầu tư vấn">@include('partials.frontend.contact-icon', ['icon' => 'chat'])</button>
        @foreach ($socialChannels['floating'] as $channel)
            <a class="floating-actions__{{ $channel['key'] }} floating-action" href="{{ $channel['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $channel['label'] }}" title="{{ $channel['label'] }}">@include('partials.frontend.contact-icon', ['icon' => $channel['key']])</a>
        @endforeach
        @if ($socialChannels['wechat'])
            <div class="floating-wechat rounded-3 bg-white p-3 shadow">
                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="collapse" data-bs-target="#wechatContactModal" aria-expanded="false" aria-controls="wechatContactModal">WeChat</button>
                <div id="wechatContactModal" class="collapse pt-3">
                    <img src="{{ $socialChannels['wechat']['qr_url'] }}" alt="Mã QR WeChat" class="img-fluid">
                    <p>{{ $socialChannels['wechat']['id'] }}</p>
                </div>
            </div>
        @endif
    <button type="button" class="floating-action floating-action--top" data-scroll-top aria-label="Về đầu trang" title="Về đầu trang">@include('partials.frontend.contact-icon', ['icon' => 'top'])</button>
    </aside>
