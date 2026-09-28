<aside class="floating-actions" aria-label="Liên hệ nhanh">
    @if (site_config('phone_href'))
        <a class="floating-action floating-action--phone" href="tel:{{ site_config('phone_href') }}" aria-label="Gọi tư vấn" title="Gọi tư vấn"><x-icon name="phone" /></a>
    @endif
    @foreach ($socialChannels['floating'] as $channel)
        <a class="floating-action floating-action--{{ $channel['key'] }}" href="{{ $channel['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $channel['label'] }}" title="{{ $channel['label'] }}"><x-icon :name="$channel['icon']" /></a>
    @endforeach
    <button type="button" class="floating-action" data-consult aria-label="Yêu cầu tư vấn" title="Yêu cầu tư vấn"><x-icon name="message-circle-more" /></button>
    @if ($socialChannels['wechat'])
        <div class="dropup">
            <button type="button" class="floating-action floating-action--wechat" data-bs-toggle="dropdown" aria-expanded="false" aria-label="WeChat" title="WeChat"><x-icon name="qr-code" /></button>
            <div class="dropdown-menu dropdown-menu-end p-3 text-center floating-actions__wechat">
                <img src="{{ $socialChannels['wechat']['qr_url'] }}" alt="Mã QR WeChat" width="180" height="180" loading="lazy">
                <p class="small mb-0 mt-2">WeChat: {{ $socialChannels['wechat']['id'] }}</p>
            </div>
        </div>
    @endif
    <button type="button" class="floating-action floating-action--top" data-scroll-top aria-label="Về đầu trang" title="Về đầu trang" hidden><x-icon name="arrow-up" /></button>
</aside>
