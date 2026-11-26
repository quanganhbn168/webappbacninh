@php
    $br = fn (?string $text): string => nl2br(e((string) $text));
    $cycle = fn (array $icons, int $index): string => $icons[$index % count($icons)];
    $tel = preg_replace('/[^0-9+]/', '', $c['hotline']);
    $product = $c['product'];
    $title = $landing->meta_title ?: $landing->name;
    $description = (string) $landing->meta_description;
    $hero = $landing->image('hero_image', 'landing/purehome/hero.webp');
    $detail = $landing->image('detail_image', 'landing/purehome/detail.webp');
    $filtersImage = $landing->image('filters_image', 'landing/purehome/filters.webp');
    $share = $c['share_image'] ? $landing->image('share_image', 'landing/purehome/hero.webp') : $hero;
    $discount = $landing->discountPercent();
    $price = fn (string $class = 'ph-price') => new \Illuminate\Support\HtmlString('<strong class="'.$class.'">'.e($landing->money()).'<sup>đ</sup></strong>');
    $config = ['policies' => [
        'warranty' => ['Chính sách bảo hành', $c['policy_warranty']],
        'returns' => ['Chính sách đổi trả', $c['policy_returns']],
        'shipping' => ['Chính sách giao hàng', $c['policy_shipping']],
        'privacy' => ['Chính sách bảo mật', $c['policy_privacy']],
        'terms' => ['Điều khoản sử dụng', $c['policy_terms']],
    ]];
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    @if ($landing->noindex)<meta name="robots" content="noindex, nofollow">@endif
    <meta name="theme-color" content="#074e7d">
    <link rel="canonical" href="{{ $landing->url() }}">
    <link rel="image_src" href="{{ $share }}">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $c['brand'] }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $landing->url() }}">
    <meta property="og:image" content="{{ $share }}">
    <meta property="og:image:alt" content="{{ $landing->name }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $share }}">
    <meta name="twitter:image:alt" content="{{ $landing->name }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('landing/purehome/leaf.svg') }}">
    <link rel="preload" as="image" href="{{ $hero }}" fetchpriority="high">
    @vite(['resources/css/landing/purehome.css', 'resources/js/landing/purehome.js'])
    <x-landing.tracking :landing="$landing" position="head" />
</head>
<body class="purehome"@if ($landing->isDemo) data-demo @endif>
    <x-landing.tracking :landing="$landing" position="body_start" />
    <a class="ph-skip" href="#noi-dung">Đến nội dung chính</a>
    <header class="ph-header" id="trang-chu">
        <div class="ph-container ph-header-inner">
            <a class="ph-brand" href="#trang-chu" aria-label="{{ $c['brand'] }} — Trang chủ">
                <span class="ph-brand-top"><x-landing.purehome-icon name="leaf" /><strong>{{ $c['brand'] }}</strong></span>
                <span>{{ $c['brand_tagline'] }}</span>
            </a>
            <nav class="ph-nav" id="ph-navigation" aria-label="Điều hướng chính">
                <a class="is-active" href="#trang-chu">Trang chủ</a>
                <a href="#san-pham">Sản phẩm</a>
                <a href="#uu-diem">Ưu điểm</a>
                <a href="#danh-gia">Đánh giá</a>
                <a href="#lien-he">Liên hệ</a>
            </nav>
            <a class="ph-hotline" href="tel:{{ $tel }}"><span class="ph-phone-circle"><x-landing.purehome-icon name="phone" /></span><span><small>Hotline tư vấn</small><strong>{{ $c['hotline'] }}</strong></span></a>
            <button class="ph-cart ph-icon-button" type="button" data-dialog-open="ph-order" aria-label="Đặt mua {{ $product }}"><x-landing.purehome-icon name="cart" /></button>
            <button class="ph-menu ph-icon-button" type="button" aria-expanded="false" aria-controls="ph-navigation" aria-label="Mở menu"><x-landing.purehome-icon name="menu" /></button>
        </div>
    </header>

    <main id="noi-dung">
        <section class="ph-hero" aria-labelledby="ph-title">
            <img class="ph-hero-background" src="{{ $hero }}" alt="{{ $landing->name }}" width="1536" height="768" fetchpriority="high">
            <div class="ph-container ph-hero-inner">
                <div class="ph-hero-copy">
                    <p class="ph-eyebrow">{{ $c['hero_eyebrow'] }}</p>
                    <h1 id="ph-title">{!! $br($c['hero_title']) !!}</h1>
                    <p class="ph-hero-subtitle">{{ $c['hero_subtitle'] }}</p>
                    <p class="ph-hero-description">{!! $br($c['hero_description']) !!}</p>
                    <div class="ph-price-row">{{ $price() }}@if ($landing->compare_price)<del>{{ $landing->money($landing->compare_price) }}đ</del>@endif @if ($discount)<span class="ph-discount">Tiết kiệm {{ $discount }}%</span>@endif</div>
                    <div class="ph-hero-actions"><button class="ph-button ph-button-green" type="button" data-dialog-open="ph-order"><x-landing.purehome-icon name="cart" />Mua ngay<x-landing.purehome-icon name="arrow" /></button><a class="ph-button ph-button-outline" href="#san-pham">Xem chi tiết</a></div>
                    <div class="ph-assurances">
                        @foreach ($c['assurances'] as $item)
                            <span><x-landing.purehome-icon :name="$cycle(['truck', 'shield', 'rotate'], $loop->index)" /><span>{!! $br($item['text']) !!}</span></span>
                        @endforeach
                    </div>
                </div>
                @php($noteLines = preg_split('/\R/', trim($c['hero_note'])))
                <p class="ph-handwritten ph-hero-note">{{ array_shift($noteLines) }}@if ($noteLines)<br><span>{!! $br(implode("\n", $noteLines)) !!}</span>@endif<i>♡</i></p>
                <div class="ph-lifestyle">
                    @foreach ($c['lifestyle'] as $item)
                        <div><span class="ph-lifestyle-icon"><x-landing.purehome-icon :name="$cycle(['family', 'leaf', 'heart'], $loop->index)" /></span><p><strong>{{ $item['title'] }}</strong><br>{{ $item['text'] }}</p></div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="ph-benefits ph-section" id="uu-diem" aria-labelledby="ph-benefits-title">
            <div class="ph-container">
                <h2 id="ph-benefits-title">{{ $c['benefits_title'] }}</h2>
                <div class="ph-benefit-grid">
                    @foreach ($c['benefits'] as $item)
                        @php($icon = $cycle(['air', 'leaf', 'quiet', 'bolt', 'home'], $loop->index))
                        <article class="ph-card ph-benefit-card"><span class="ph-feature-icon {{ in_array($icon, ['leaf', 'bolt']) ? 'ph-green' : '' }}"><x-landing.purehome-icon :name="$icon" /></span><h3>{{ $item['title'] }}</h3><p>{!! $br($item['text']) !!}</p></article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="ph-product" id="san-pham" aria-labelledby="ph-product-title">
            <div class="ph-container ph-product-grid">
                <div class="ph-product-visual">
                    <img class="ph-product-image" src="{{ $detail }}" alt="{{ $product }}" width="1024" height="1024" loading="lazy">
                    <p class="ph-handwritten ph-product-note">{!! $br($c['product_note']) !!}<x-landing.purehome-icon name="leaf" /></p>
                    <div class="ph-design-features">
                        @foreach ($c['design_features'] as $item)
                            <div><span><x-landing.purehome-icon :name="$cycle(['design', 'touch', 'shield'], $loop->index)" /></span><p><strong>{{ $item['title'] }}</strong>{!! $br($item['text']) !!}</p></div>
                        @endforeach
                    </div>
                </div>
                <div class="ph-technology">
                    <h2 id="ph-product-title">{!! $br($c['tech_title']) !!}</h2>
                    <p>{!! str_replace("\n", '<br class="ph-desktop-break"> ', e($c['tech_intro'])) !!}</p>
                    <div class="ph-filter-layout">
                        <img src="{{ $filtersImage }}" alt="{{ $c['tech_title'] }}" width="1024" height="1024" loading="lazy">
                        <ol class="ph-filter-list">
                            @foreach ($c['filters'] as $item)
                                <li><span>{{ $loop->iteration }}</span><div><h3>{{ $item['title'] }}</h3><p>{{ $item['text'] }}</p></div></li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="ph-reasons ph-section" aria-labelledby="ph-reasons-title">
            <div class="ph-container">
                <h2 id="ph-reasons-title">{{ $c['reasons_title'] }}</h2>
                <p class="ph-section-subtitle">{{ $c['reasons_subtitle'] }}</p>
                <div class="ph-reason-grid">
                    @foreach ($c['reasons'] as $item)
                        <article class="ph-card ph-reason-card"><x-landing.purehome-icon :name="$cycle(['shield', 'family', 'diamond', 'leaf'], $loop->index)" /><h3>{{ $item['title'] }}</h3><p>{!! $br($item['text']) !!}</p></article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="ph-reviews ph-section" id="danh-gia" aria-labelledby="ph-reviews-title">
            <div class="ph-container">
                <div class="ph-review-heading"><div><h2 id="ph-reviews-title">{{ $c['reviews_title'] }}</h2><p class="ph-section-subtitle">{{ $c['reviews_subtitle'] }}</p></div><button class="ph-button ph-button-outline ph-review-all" type="button" data-dialog-open="ph-reviews-dialog">Xem tất cả đánh giá<x-landing.purehome-icon name="arrow" /></button></div>
                <div class="ph-review-grid" id="ph-review-cards">
                    @foreach ($c['reviews'] as $item)
                        <article class="ph-card ph-review-card"><div class="ph-review-top"><span class="ph-avatar ph-avatar-{{ $item['avatar'] ?? 'lan' }}" role="img" aria-label="Ảnh minh họa {{ $item['name'] }}"></span><div><span class="ph-stars" aria-label="5 trên 5 sao">★★★★★</span><h3>“{{ $item['quote'] }}”</h3></div></div><p>{{ $item['text'] }}</p><footer><strong>{{ $item['name'] }}</strong><span>{{ $item['city'] ?? '' }}</span></footer></article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="ph-offer" id="uu-dai" aria-labelledby="ph-offer-title">
            <div class="ph-container ph-offer-inner">
                <div><h2 id="ph-offer-title">{{ $c['offer_title'] }}</h2><p>{!! $br($c['offer_text']) !!}</p></div>
                <div class="ph-offer-price"><span>Chỉ còn</span>{{ $price() }}<div>@if ($landing->compare_price)<del>{{ $landing->money($landing->compare_price) }}đ</del>@endif @if ($discount)<span class="ph-discount">Tiết kiệm {{ $discount }}%</span>@endif</div></div>
                <button class="ph-button ph-button-green" type="button" data-dialog-open="ph-order"><x-landing.purehome-icon name="cart" />Mua hàng ngay<x-landing.purehome-icon name="arrow" /></button>
                <div class="ph-offer-perks">@foreach ($c['perks'] as $item)<span><x-landing.purehome-icon :name="$cycle(['truck', 'shield', 'gift'], $loop->index)" />{{ $item['text'] }}</span>@endforeach</div>
            </div>
        </section>

        <section class="ph-faq ph-section" aria-labelledby="ph-faq-title">
            <div class="ph-container">
                <h2 id="ph-faq-title">{{ $c['faq_title'] }}</h2>
                <p class="ph-section-subtitle">{{ $c['faq_subtitle'] }}</p>
                <div class="ph-faq-grid">
                    @foreach ($c['faqs'] as $item)
                        <details><summary>{{ $item['question'] }}<span class="ph-faq-plus" aria-hidden="true"></span></summary><p>{{ $item['answer'] }}</p></details>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    <footer class="ph-footer" id="lien-he">
        <div class="ph-container">
            <div class="ph-footer-grid">
                <div class="ph-footer-about"><a class="ph-brand" href="#trang-chu"><span class="ph-brand-top"><x-landing.purehome-icon name="leaf" /><strong>{{ $c['brand'] }}</strong></span><span>{{ $c['brand_tagline'] }}</span></a><p>{{ $c['footer_about'] }}</p><div class="ph-socials" aria-label="Kết nối {{ $c['brand'] }}"><a href="#ph-contact" aria-label="Liên hệ {{ $c['brand'] }} qua Facebook">f</a><a href="#ph-contact" aria-label="Liên hệ {{ $c['brand'] }} qua YouTube"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="6" width="18" height="13" rx="4" fill="currentColor"/><path d="m10 9 6 3.5-6 3.5Z" fill="#25485e"/></svg></a><a href="#ph-contact" aria-label="Liên hệ {{ $c['brand'] }} qua TikTok">♪</a><a href="#ph-contact" aria-label="Liên hệ {{ $c['brand'] }} qua Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17" cy="7" r="1" fill="currentColor"/></svg></a></div></div>
                <div><h3>Liên kết nhanh</h3><nav aria-label="Liên kết cuối trang"><a href="#trang-chu">Trang chủ</a><a href="#san-pham">Sản phẩm</a><a href="#uu-diem">Ưu điểm</a><a href="#danh-gia">Đánh giá</a><a href="#lien-he">Liên hệ</a></nav></div>
                <div><h3>Chính sách</h3><nav aria-label="Chính sách {{ $c['brand'] }}"><button type="button" data-policy="warranty">Chính sách bảo hành</button><button type="button" data-policy="returns">Chính sách đổi trả</button><button type="button" data-policy="shipping">Chính sách giao hàng</button><button type="button" data-policy="privacy">Chính sách bảo mật</button><button type="button" data-policy="terms">Điều khoản sử dụng</button></nav></div>
                <div class="ph-footer-contact" id="ph-contact"><h3>Liên hệ</h3><a href="tel:{{ $tel }}"><x-landing.purehome-icon name="phone" /><span><strong>{{ $c['hotline'] }}</strong><small>({{ $c['hotline_hours'] }})</small></span></a><a href="mailto:{{ $c['email'] }}"><x-landing.purehome-icon name="mail" /><span>{{ $c['email'] }}</span></a><p><x-landing.purehome-icon name="pin" /><span>{!! $br($c['address']) !!}</span></p></div>
            </div>
            <div class="ph-footer-bottom"><span>{{ $c['footer_copy'] }}</span><span>{{ $c['footer_slogan'] }}<x-landing.purehome-icon name="leaf" /></span></div>
        </div>
    </footer>

    <dialog class="ph-dialog" id="ph-order" aria-labelledby="ph-order-title"><button class="ph-dialog-close ph-icon-button" type="button" data-dialog-close aria-label="Đóng"><x-landing.purehome-icon name="close" /></button><p class="ph-eyebrow">{{ $c['hero_subtitle'] }}</p><h2 id="ph-order-title">Đặt mua {{ $product }}</h2><div class="ph-order-product"><img src="{{ $detail }}" alt="{{ $product }}" width="160" height="160" loading="lazy"><div><h3>{{ $landing->name }}</h3><p>Số lượng: 1</p>{{ $price() }}@if ($landing->compare_price)<del>{{ $landing->money($landing->compare_price) }}đ</del>@endif</div></div><p>{{ $c['order_note'] }}</p>
        <form class="ph-lead" @if ($landing->isDemo) method="dialog" @else action="{{ route('leads.store') }}" method="post" @endif data-landing-lead data-need="Đặt mua {{ $product }}" novalidate>
            @csrf
            <div class="ph-lead-trap" aria-hidden="true"><label>Website <input name="website" tabindex="-1" autocomplete="off"></label></div>
            <p class="ph-lead-title">Để lại số điện thoại, {{ $c['brand'] }} gọi lại ngay:</p>
            <input name="name" autocomplete="name" maxlength="150" required placeholder="Họ và tên" aria-label="Họ và tên">
            <input name="phone" type="tel" inputmode="tel" autocomplete="tel" maxlength="30" required placeholder="Số điện thoại" aria-label="Số điện thoại">
            <button class="ph-button ph-button-green" type="submit">Gọi lại cho tôi</button>
            <p class="ph-lead-status" data-lead-status role="status" hidden></p>
        </form>
        <a class="ph-button ph-button-outline" href="tel:{{ $tel }}"><x-landing.purehome-icon name="phone" />Gọi đặt hàng: {{ $c['hotline'] }}</a><a class="ph-dialog-email" href="mailto:{{ $c['email'] }}?subject={{ rawurlencode('Đặt mua '.$product) }}">Gửi yêu cầu qua email</a></dialog>
    <dialog class="ph-dialog ph-dialog-wide" id="ph-reviews-dialog" aria-labelledby="ph-all-reviews-title"><button class="ph-dialog-close ph-icon-button" type="button" data-dialog-close aria-label="Đóng"><x-landing.purehome-icon name="close" /></button><h2 id="ph-all-reviews-title">Đánh giá về {{ $product }}</h2><p class="ph-dialog-rating"><span class="ph-stars" aria-label="5 trên 5 sao">★★★★★</span> 5/5 · {{ count($c['reviews']) }} đánh giá</p><div class="ph-dialog-reviews"></div></dialog>
    <dialog class="ph-dialog" id="ph-policy" aria-labelledby="ph-policy-title"><button class="ph-dialog-close ph-icon-button" type="button" data-dialog-close aria-label="Đóng"><x-landing.purehome-icon name="close" /></button><h2 id="ph-policy-title"></h2><p id="ph-policy-content"></p><p>Cần hỗ trợ thêm? Gọi <a href="tel:{{ $tel }}">{{ $c['hotline'] }}</a> hoặc email <a href="mailto:{{ $c['email'] }}">{{ $c['email'] }}</a>.</p></dialog>
    <script type="application/json" id="landing-config">@json($config, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG)</script>
    <x-landing.tracking :landing="$landing" position="body_end" />
</body>
</html>
