@if ($socialChannels['floating'] || $socialChannels['wechat'])
    <aside class="fixed right-4 bottom-4 z-30 flex flex-col items-end gap-2" aria-label="Liên hệ nhanh">
        @foreach ($socialChannels['floating'] as $channel)
            <a class="floating-actions__{{ $channel['key'] }} rounded-full bg-ink px-4 py-2 text-sm text-white shadow-lg" href="{{ $channel['url'] }}" target="_blank" rel="noopener noreferrer">{{ $channel['label'] }}</a>
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
    </aside>
@endif
