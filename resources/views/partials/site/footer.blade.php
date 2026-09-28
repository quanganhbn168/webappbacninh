@php($menus = app(\App\Domain\Navigation\Actions\BuildMenuTree::class))
<footer class="site-footer">
    <div class="container">
        <div class="row gy-4 site-footer__grid">
            <div class="col-lg-3 col-md-6">
                @include('partials.site.brand')
                <p class="site-footer__about">Đồng hành cùng doanh nghiệp trong hành trình chuyển đổi số với những giải pháp website, phần mềm và vận hành hiệu quả.</p>
                @if ($socialChannels['footer'] !== [])
                    <div class="site-footer__social">
                        @foreach ($socialChannels['footer'] as $channel)
                            <a href="{{ $channel['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $channel['label'] }}" title="{{ $channel['label'] }}"><x-icon :name="$channel['icon']" /></a>
                        @endforeach
                    </div>
                @endif
            </div>
            @foreach ([\App\Models\Menu::FOOTER_SERVICES, \App\Models\Menu::FOOTER_PRODUCTS, \App\Models\Menu::FOOTER_ABOUT] as $location)
                @php($footerItems = $menus->execute($location))
                @if ($footerItems !== [])
                    <nav class="col-lg-2 col-md-6 col-6 site-footer__column" aria-label="{{ $menus->name($location) }}">
                        <h2>{{ $menus->name($location) }}</h2>
                        @foreach ($footerItems as $item)
                            <a href="{{ $item['url'] }}" @if ($item['new_tab']) target="_blank" rel="noopener" @endif>{{ $item['title'] }}</a>
                        @endforeach
                    </nav>
                @endif
            @endforeach
            <div class="col-lg-3 col-md-6 site-footer__column site-footer__contact">
                <h2>Liên hệ</h2>
                <a href="tel:{{ site_config('phone_href') }}"><x-icon name="phone" />{{ site_config('phone') }}</a>
                @if (site_config('phone_secondary') && site_config('phone_secondary_href'))
                    <a href="tel:{{ site_config('phone_secondary_href') }}"><x-icon name="phone-call" />{{ site_config('phone_secondary') }}</a>
                @endif
                <a href="mailto:{{ site_config('email') }}"><x-icon name="mail" />{{ site_config('email') }}</a>
                <span><x-icon name="map-pin" />{{ site_config('address') }}</span>
            </div>
        </div>
        <div class="site-footer__bottom">
            <p>© {{ date('Y') }} {{ site_config('name') }}. Bảo lưu mọi quyền.</p>
            <nav aria-label="Chính sách">
                <a href="{{ route('legal.privacy') }}">Bảo mật</a>
                <a href="{{ route('legal.terms') }}">Điều khoản</a>
                <a href="{{ route('sitemap') }}">Sitemap</a>
            </nav>
        </div>
    </div>
</footer>
