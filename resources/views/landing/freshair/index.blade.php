@php
    use Illuminate\Support\HtmlString;

    $br = fn (?string $text): string => nl2br(e((string) $text));
    $cycle = fn (array $items, int $index): string|array => $items[$index % count($items)];
    $emFirst = function (string $text): HtmlString {
        $parts = explode(' ', $text, 2);

        return new HtmlString('<em>'.e($parts[0]).'</em>'.(isset($parts[1]) ? ' '.e($parts[1]) : ''));
    };
    $emLast = function (string $text): HtmlString {
        $at = mb_strrpos($text, ' ');

        return new HtmlString($at === false ? e($text) : e(mb_substr($text, 0, $at)).' <em>'.e(mb_substr($text, $at + 1)).'</em>');
    };
    $tel = preg_replace('/[^0-9+]/', '', $c['hotline']);
    $product = $c['product'];
    $productFull = $c['product_full'];
    $title = $landing->meta_title ?: $landing->name;
    $description = (string) $landing->meta_description;
    $hero = $landing->image('hero_image', 'landing/freshair/hero.webp');
    $productImage = $landing->image('product_image', 'landing/freshair/product.webp');
    $filtersImage = $landing->image('filters_image', 'landing/purehome/filters.webp');
    $share = $landing->image('share_image', 'landing/freshair/share.jpg');
    $discount = $landing->discountPercent();
    $money = $landing->money().'đ';
    $oldMoney = $landing->compare_price ? $landing->money($landing->compare_price).'đ' : null;
    $ratingLabel = str_replace('.', ',', $c['rating']);
    $gallery = [
        ['living', 'Sản phẩm trong phòng khách', 'Tô điểm không gian hiện đại'],
        ['display', 'Cận cảnh màn hình cảm ứng', 'Theo dõi chất lượng không khí'],
        ['filter', 'Bộ lọc bên trong', 'Màng lọc HEPA H13 cao cấp'],
        ['bedroom', 'Trong phòng ngủ', 'Vận hành êm ái, giấc ngủ sâu hơn'],
        ['family', 'Gia đình sử dụng', 'Không khí sạch cho cả nhà'],
        ['office', 'Góc làm việc sạch thoáng', 'Tập trung hơn, thoải mái hơn'],
    ];
    $productGallery = array_merge([['product', $productFull, '']], $gallery);
    $reviewPhotos = [['living', 'bedroom', 'family'], ['display', 'filter', 'living'], ['living', 'office', 'family'], ['office', 'living', 'bedroom']];
    $heroLines = preg_split('/\R/', trim($c['hero_title']));
    $heroFirst = array_shift($heroLines);
    $config = [
        'price' => $landing->price,
        'product' => $product,
        'productFull' => $productFull,
        'email' => $c['email'],
        'hotline' => $c['hotline'],
        'cartKey' => 'landing-'.$landing->slug.'-cart-v1',
        'shareTitle' => $c['share_title'],
        'shareText' => $productFull.', giá ưu đãi '.$money.'.',
    ];
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
    <meta name="theme-color" content="#086b36">
    <link rel="canonical" href="{{ $landing->url() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('landing/purehome/leaf.svg') }}">
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
    <link rel="preload" as="image" href="{{ $hero }}" fetchpriority="high">
    <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">
    @vite(['resources/css/landing/freshair.css', 'resources/js/landing/freshair.js'])
    <x-landing.tracking :landing="$landing" position="head" />
</head>
<body class="freshair"@if ($landing->isDemo) data-demo @endif>
    <x-landing.tracking :landing="$landing" position="body_start" />
    <a class="fa-skip" href="#noi-dung">Đến nội dung chính</a>
    <div class="fa-sale-banner" id="trang-chu"><div class="fa-container"><x-landing.freshair-icon name="bolt" /><strong>{{ $c['sale_label'] }}</strong><span class="fa-sale-dot">•</span><b>{{ $emLast($c['sale_text_a']) }}</b><span class="fa-sale-dot">•</span><b>{{ $emFirst($c['sale_text_b']) }}</b><a href="#uu-dai">Khám phá ưu đãi <x-landing.freshair-icon name="arrow-right" /></a></div></div>
    <header class="fa-header">
        <div class="fa-container fa-header-main">
            <a class="fa-brand" href="#trang-chu" aria-label="{{ $c['brand'] }} — Trang chủ"><x-landing.purehome-icon name="leaf" class="fa-icon fa-brand-mark" /><span><strong>{{ $c['brand'] }}</strong><small>{{ $c['brand_tagline'] }}</small></span></a>
            <form class="fa-search" role="search"><label class="fa-sr-only" for="fa-search-input">Tìm thông tin về {{ $product }}</label><input id="fa-search-input" type="search" placeholder="Bạn muốn tìm thông tin gì về {{ $product }}?" autocomplete="off"><button type="submit" aria-label="Tìm kiếm"><x-landing.freshair-icon name="magnifying-glass" /></button></form>
            <a class="fa-hotline" href="tel:{{ $tel }}"><x-landing.freshair-icon name="phone-volume" /><span><small>Hotline tư vấn</small><strong>{{ $c['hotline'] }}</strong><small>({{ $c['hotline_hours'] }})</small></span></a>
            <button class="fa-cart-button" type="button" data-open-cart aria-label="Mở giỏ hàng"><x-landing.freshair-icon name="cart-shopping" /><span>Giỏ hàng</span><b data-cart-count>0</b></button>
        </div>
    </header>
    <main id="noi-dung">
        <section class="fa-hero" aria-labelledby="fa-hero-title">
            <div class="fa-container fa-hero-layout">
                <div class="fa-hero-scene">
                    <img class="fa-hero-image" src="{{ $hero }}" alt="{{ $landing->name }}" width="1792" height="768" fetchpriority="high">
                    <div class="fa-hero-copy"><p class="fa-hero-kicker">{{ $c['hero_kicker'] }}</p><h1 id="fa-hero-title"><em>{{ $heroFirst }}</em>@foreach ($heroLines as $line)<br>{{ $line }}@endforeach <x-landing.purehome-icon name="leaf" class="fa-icon fa-brand-mark" /></h1><p><strong>{{ $c['hero_strong'] }}</strong><br>{{ $c['hero_text'] }}</p><div class="fa-hero-icons">@foreach ($c['hero_icons'] as $item)<div><span><x-landing.freshair-icon :name="$cycle(['wind', 'spray-can-sparkles', 'shield-halved', 'heart-pulse'], $loop->index)" /></span><b>{{ $item['a'] }}<br>{{ $item['b'] }}</b></div>@endforeach</div><a class="fa-hero-cta" href="#san-pham">Khám phá {{ $product }} <x-landing.freshair-icon name="arrow-right" /></a></div>
                    <p class="fa-hero-note">{!! $br($c['hero_note']) !!}</p>
                </div>
                <aside class="fa-hero-benefits" aria-label="Ưu điểm {{ $product }}">
                    @foreach ($c['hero_benefits'] as $item)<div><span><x-landing.freshair-icon :name="$cycle(['filter', 'spray-can-sparkles', 'volume-low', 'bolt', 'house', 'shield-halved'], $loop->index)" /></span><p><strong>{{ $item['title'] }}</strong><small>{{ $item['text'] }}</small></p></div>@endforeach
                </aside>
            </div>
        </section>

        <section class="fa-container fa-shop" id="san-pham" aria-labelledby="fa-product-title" data-search-section="sản phẩm máy lọc freshair giá mua hàng">
            <div class="fa-product-gallery">
                <div class="swiper fa-thumbnails" aria-label="Chọn ảnh sản phẩm">
                    <div class="swiper-wrapper">
                        @foreach ($productGallery as [$scene, $caption])
                            <div class="swiper-slide"><button data-photo-index="{{ $loop->index }}" type="button" aria-label="{{ $caption }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                                @if ($scene === 'product')<img src="{{ $productImage }}" alt="" width="100" height="100">@else<span class="fa-scene fa-scene-{{ $scene }}"></span>@endif
                            </button></div>
                        @endforeach
                    </div>
                </div>
                <div class="swiper fa-product-slider" aria-label="Ảnh sản phẩm {{ $product }}">
                    <div class="swiper-wrapper">
                        @foreach ($productGallery as [$scene, $caption])
                            <div class="swiper-slide"><button class="fa-main-photo" type="button" data-gallery-open="{{ $scene === 'product' ? 'product' : $loop->index - 1 }}" aria-label="Phóng to: {{ $caption }}">
                                @if ($scene === 'product')<img src="{{ $productImage }}" alt="{{ $caption }}" width="1254" height="1254">@else<span class="fa-main-sprite fa-scene fa-scene-{{ $scene }}" role="img" aria-label="{{ $caption }}"></span>@endif
                                <span class="fa-photo-zoom"><x-landing.freshair-icon name="magnifying-glass-plus" /> Xem ảnh lớn</span>
                            </button></div>
                        @endforeach
                    </div>
                    <button class="fa-slider-arrow fa-product-prev" type="button" aria-label="Ảnh sản phẩm trước"><x-landing.freshair-icon name="chevron-left" /></button>
                    <button class="fa-slider-arrow fa-product-next" type="button" aria-label="Ảnh sản phẩm tiếp theo"><x-landing.freshair-icon name="chevron-right" /></button>
                </div>
                <div class="fa-slider-footer"><span>Kéo để xem thêm ảnh</span><span class="fa-product-pagination"></span></div>
            </div>
            <div class="fa-product-info"><div class="fa-product-badges"><span>{{ $c['badge_hot'] }}</span><span><x-landing.freshair-icon name="certificate" /> {{ $c['badge_trust'] }}</span></div><h2 id="fa-product-title">{{ $productFull }}</h2><p class="fa-product-tagline">{{ $c['tagline'] }}</p><div class="fa-rating"><span class="fa-stars" aria-label="{{ $ratingLabel }} trên 5 sao">@for ($star = 0; $star < 5; $star++)<x-landing.freshair-icon name="star" />@endfor</span><strong>{{ $c['rating'] }}/5</strong><span>|</span><a href="#danh-gia">{{ $c['review_count'] }} đánh giá</a><span>|</span><span>Đã bán <b>{{ $c['sold_count'] }}</b></span></div><div class="fa-price-box"><div><x-landing.freshair-icon name="bolt" /><strong>{{ $c['price_label'] }}</strong><span>{{ $c['price_label_sub'] }}</span></div><div class="fa-price-content"><div>@if ($oldMoney)<del>{{ $oldMoney }}</del>@endif<strong class="fa-price">{{ $money }}</strong></div>@if ($discount)<b class="fa-discount">Giảm {{ $discount }}%</b>@endif<span class="fa-price-note">{!! $br($c['price_note']) !!}</span></div></div><h3 class="fa-small-heading">Voucher ưu đãi</h3><div class="fa-vouchers">@foreach ($c['vouchers'] as $item)<button type="button" data-voucher="{{ $item['code'] }}"><x-landing.freshair-icon :name="str_contains(strtoupper($item['code']), 'SHIP') ? 'truck-fast' : 'gift'" /><span><strong>{{ $item['title'] }}</strong><small>{{ $item['note'] }}</small></span></button>@endforeach</div><p class="fa-voucher-help">Chạm để lấy mã · Áp dụng theo điều kiện từng ưu đãi</p><h3 class="fa-small-heading">Hình thức thanh toán</h3><div class="fa-payment-options"><span><x-landing.freshair-icon name="credit-card" /><span><b>Thanh toán an toàn</b><small>Xác nhận trực tiếp khi đặt mua</small></span></span><span><x-landing.freshair-icon name="money-bill-wave" /><span><b>Thanh toán khi nhận hàng</b><small>(COD)</small></span></span></div><div class="fa-quantity-line"><label for="fa-quantity">Số lượng:</label><div class="fa-quantity"><button type="button" data-quantity-step="-1" aria-label="Giảm số lượng"><x-landing.freshair-icon name="minus" /></button><input id="fa-quantity" type="number" value="1" min="1" max="10" inputmode="numeric" aria-label="Số lượng sản phẩm"><button type="button" data-quantity-step="1" aria-label="Tăng số lượng"><x-landing.freshair-icon name="plus" /></button></div><span class="fa-stock"><x-landing.freshair-icon name="circle-dot" /> {{ $c['stock_text'] }}</span></div></div>
            <div class="fa-buy-panel"><button class="fa-button fa-button-red" type="button" data-buy><x-landing.freshair-icon name="cart-shopping" /><span><strong>MUA NGAY</strong><small>Giao nhanh tận nhà</small></span></button><button class="fa-button fa-button-outline" type="button" data-add-cart><x-landing.freshair-icon name="cart-shopping" /><span><strong>Thêm vào giỏ</strong><small>Tiện lợi, mua thêm sản phẩm khác</small></span></button><div class="fa-buy-perks">@foreach ($c['buy_perks'] as $item)<span><x-landing.freshair-icon :name="$cycle(['truck-fast', 'arrows-rotate', 'shield-halved'], $loop->index)" /><span><b>{{ $item['title'] }}</b><small>{{ $item['text'] }}</small></span></span>@endforeach</div><a class="fa-consult-link" href="#tu-van"><x-landing.freshair-icon name="phone-volume" />Cần tư vấn? Để {{ $c['brand'] }} giúp bạn</a><button class="fa-share-button" type="button" data-share><x-landing.freshair-icon name="share-nodes" />Chia sẻ sản phẩm</button></div>
        </section>

        <div class="fa-container fa-trust-strip">@foreach ($c['trust'] as $item)<div><x-landing.freshair-icon :name="$cycle(['truck-fast', 'shield-halved', 'arrows-rotate', 'money-bill-wave'], $loop->index)" /><span><strong>{{ $item['title'] }}</strong><small>{{ $item['text'] }}</small></span></div>@endforeach<div class="fa-family-trust"><x-landing.freshair-icon name="users" /><span>Hơn <b>{{ $c['family_count'] }} <x-landing.freshair-icon name="heart" /></b><small>{{ $c['family_text'] }}</small></span></div></div>

        <section class="fa-container fa-section" id="hinh-anh" aria-labelledby="fa-gallery-title" data-search-section="hình ảnh thực tế bộ sưu tập phòng ngủ phòng khách văn phòng gia đình">
            <div class="fa-section-heading"><span class="fa-heading-icon fa-coral"><x-landing.freshair-icon name="camera" /></span><div><h2 id="fa-gallery-title">Bộ ảnh sưu tầm / Hình ảnh thực tế</h2><p>Khám phá {{ \Illuminate\Support\Str::lcfirst($productFull) }} trong không gian sống</p></div><button type="button" class="fa-text-button" data-gallery-open="0">Xem tất cả hình ảnh <x-landing.freshair-icon name="arrow-right" /></button></div>
            <div class="swiper fa-gallery-grid" aria-label="Hình ảnh {{ $product }} trong không gian sống"><div class="swiper-wrapper">@foreach ($gallery as [$scene, $caption, $note])<button class="swiper-slide fa-gallery-card" type="button" data-gallery-open="{{ $loop->index }}"><span class="fa-scene fa-scene-{{ $scene }}" role="img" aria-label="{{ $caption }}"></span><span class="fa-gallery-caption"><strong>{{ $caption }}</strong><small>{{ $note }}</small></span></button>@endforeach</div><div class="fa-gallery-pagination"></div></div>
        </section>

        <section class="fa-container fa-technology-grid" id="cong-nghe" aria-label="Công nghệ, thông số và tư vấn" data-search-section="công nghệ thông số diện tích cadr hepa màng lọc công suất điện ồn kích thước trọng lượng">
            <article class="fa-filter-card"><div class="fa-section-heading"><span class="fa-heading-icon"><x-landing.freshair-icon name="filter" /></span><div><h2>{{ $c['filters_title'] }}</h2><p>{{ $c['filters_subtitle'] }}</p></div></div><img src="{{ $filtersImage }}" alt="{{ $c['filters_title'] }}" width="1254" height="1254" loading="lazy"><ol>@foreach ($c['filters'] as $item)<li><b>{{ $loop->iteration }}</b><span><strong>{{ $item['title'] }}</strong><small>{{ $item['text'] }}</small></span></li>@endforeach</ol></article>
            <article class="fa-specs-card"><div class="fa-section-heading"><span class="fa-heading-icon"><x-landing.freshair-icon name="clipboard-list" /></span><h2>Thông số kỹ thuật</h2></div><dl>@foreach ($c['specs'] as $item)<div><dt>{{ $item['label'] }}</dt><dd>{{ $item['value'] }}</dd></div>@endforeach</dl><p class="fa-spec-note"><x-landing.freshair-icon name="ruler-combined" />{{ $c['specs_note'] }}</p></article>
            <section class="fa-consult-card" id="tu-van" aria-labelledby="fa-consult-title"><div class="fa-section-heading"><span class="fa-heading-icon"><x-landing.freshair-icon name="headset" /></span><div><h2 id="fa-consult-title">{{ $c['consult_title'] }}</h2><p>{{ $c['consult_text'] }}</p></div></div>
                <form id="fa-consult-form" @if ($landing->isDemo) method="dialog" @else action="{{ route('leads.store') }}" method="post" @endif data-landing-lead data-need="Tư vấn {{ $product }}">
                    @csrf
                    <div class="fa-lead-trap" aria-hidden="true"><label>Website <input name="website" tabindex="-1" autocomplete="off"></label></div>
                    <label><span>Họ và tên <b>*</b></span><input type="text" name="name" placeholder="Họ và tên của bạn" required maxlength="80" autocomplete="name"></label>
                    <label><span>Số điện thoại <b>*</b></span><input type="tel" name="phone" placeholder="Số điện thoại liên hệ" required pattern="\+?[0-9][0-9 \-]{7,18}[0-9]" maxlength="20" autocomplete="tel" inputmode="tel"></label>
                    <label><span>Email <small>(không bắt buộc)</small></span><input type="email" name="email" placeholder="Địa chỉ email" maxlength="120" autocomplete="email"></label>
                    <label><span>Nhu cầu sử dụng</span><select name="room" data-lead-note="Nhu cầu sử dụng">@foreach ($c['consult_rooms'] as $room)<option>{{ $room['label'] }}</option>@endforeach</select></label>
                    <button class="fa-button fa-button-red" type="submit"><span><strong>ĐĂNG KÝ TƯ VẤN NGAY</strong><small>Nhận ưu đãi đặc biệt hôm nay</small></span></button>
                    <p class="fa-form-note fa-form-status" data-lead-status role="status" hidden></p>
                    <p class="fa-form-note"><x-landing.freshair-icon name="lock" />{{ $c['brand'] }} sẽ gọi lại cho bạn trong thời gian sớm nhất</p>
                </form>
            </section>
        </section>

        <section class="fa-container fa-section" id="uu-dai" aria-labelledby="fa-gifts-title" data-search-section="ưu đãi khuyến mãi voucher quà tặng combo giảm giá freeship">
            <div class="fa-section-heading"><span class="fa-heading-icon fa-coral"><x-landing.freshair-icon name="gift" /></span><div><h2 id="fa-gifts-title">{{ $c['gifts_title'] }}</h2><p>{{ $c['gifts_subtitle'] }}</p></div></div><div class="fa-gift-grid"><article class="fa-gift-card"><span class="fa-gift-photo fa-gift-filter" role="img" aria-label="{{ $c['gift_1_title'] }} tặng kèm"></span><div><b class="fa-gift-label">TẶNG NGAY</b><h3>{{ $emLast($c['gift_1_title']) }}</h3><p>Trị giá <strong>{{ $c['gift_1_value'] }}</strong></p><small>{{ $c['gift_1_note'] }}</small></div></article><article class="fa-gift-card"><span class="fa-gift-photo fa-gift-meter" role="img" aria-label="{{ $c['gift_2_title'] }}"></span><div><b class="fa-gift-label">TẶNG NGAY</b><h3>{{ $c['gift_2_title'] }}</h3><p>Trị giá <strong>{{ $c['gift_2_value'] }}</strong></p><small>{{ $c['gift_2_note'] }}</small></div></article><button type="button" class="fa-gift-card fa-gift-voucher" data-voucher="{{ $c['gift_voucher_code'] }}"><span class="fa-ticket"><x-landing.freshair-icon name="percent" /></span><span><b class="fa-gift-label">VOUCHER</b><strong>{{ $emLast($c['gift_voucher_title']) }}</strong><small>{{ $c['gift_voucher_note'] }}</small></span></button><article class="fa-gift-message"><x-landing.freshair-icon name="gift" /><div><h3>{{ $c['gift_message_title'] }}</h3><p>{!! $br($c['gift_message_text']) !!}</p></div></article></div>
        </section>

        <section class="fa-container fa-section fa-why" aria-labelledby="fa-why-title"><div class="fa-section-heading"><span class="fa-heading-icon"><x-landing.freshair-icon name="circle-check" /></span><h2 id="fa-why-title">{{ $c['why_title'] }}</h2></div><div class="fa-why-grid">@foreach ($c['why'] as $item)<article><span><x-landing.freshair-icon :name="$cycle(['wind', 'spray-can-sparkles', 'heart-pulse', 'bolt', 'volume-low', 'shield-halved'], $loop->index)" /></span><div><h3>{{ $item['title'] }}</h3><p>{{ $item['text'] }}</p></div></article>@endforeach</div></section>

        <section class="fa-container fa-section" id="danh-gia" aria-labelledby="fa-reviews-title" data-search-section="đánh giá khách hàng nhận xét tin dùng">
            <div class="fa-section-heading"><span class="fa-heading-icon fa-gold"><x-landing.freshair-icon name="star" /></span><div><h2 id="fa-reviews-title">{{ $c['reviews_title'] }}</h2><p>Hơn {{ $c['review_count'] }} khách hàng đã đánh giá với điểm trung bình <b class="fa-orange">{{ $c['rating'] }}/5</b></p></div><button type="button" class="fa-text-button" data-open-reviews>Xem tất cả đánh giá <x-landing.freshair-icon name="arrow-right" /></button></div><div class="fa-reviews-grid" id="fa-reviews-list">@foreach ($c['reviews'] as $item)<article class="fa-review-card"><header><span class="fa-avatar fa-avatar-{{ $item['avatar'] ?? 'lan' }}" role="img" aria-label="Ảnh minh họa khách hàng"></span><div><h3>{{ $item['name'] }}</h3><div><span class="fa-stars" aria-label="5 trên 5 sao">@for ($star = 0; $star < 5; $star++)<x-landing.freshair-icon name="star" />@endfor</span><small><x-landing.freshair-icon name="circle-check" /> Đã mua hàng</small></div></div></header><p>{{ $item['text'] }}</p><div class="fa-review-photos">@foreach ($cycle($reviewPhotos, $loop->index) as $scene)<button type="button" data-gallery-open="{{ array_search($scene, array_column($gallery, 0)) }}" aria-label="Xem ảnh {{ $scene === 'living' ? 'phòng khách' : 'trải nghiệm sản phẩm' }}"><span class="fa-scene fa-scene-{{ $scene }}"></span></button>@endforeach</div></article>@endforeach</div>
        </section>

        <section class="fa-container fa-section fa-faq" id="hoi-dap" aria-labelledby="fa-faq-title" data-search-section="câu hỏi hỏi đáp bảo hành điện diện tích màng lọc đổi trả giao hàng"><div class="fa-section-heading"><span class="fa-heading-icon"><x-landing.freshair-icon name="circle-question" /></span><div><h2 id="fa-faq-title">{{ $c['faq_title'] }}</h2><p>{{ $c['faq_subtitle'] }}</p></div></div><div class="fa-faq-grid">@foreach ($c['faqs'] as $item)<details><summary>{{ $item['question'] }}<x-landing.freshair-icon name="chevron-down" /></summary><p>{{ $item['answer'] }}</p></details>@endforeach</div></section>
    </main>
    <footer class="fa-footer"><div class="fa-container"><a class="fa-brand" href="#trang-chu"><x-landing.purehome-icon name="leaf" class="fa-icon fa-brand-mark" /><span><strong>{{ $c['brand'] }}</strong><small>{{ $c['brand_tagline'] }}</small></span></a><p>{!! $br($c['footer_text']) !!}</p><a href="tel:{{ $tel }}"><x-landing.freshair-icon name="phone-volume" />{{ $c['hotline'] }}</a><a href="mailto:{{ $c['email'] }}">{{ $c['email'] }}</a><button type="button" data-share><x-landing.freshair-icon name="share-nodes" /> Chia sẻ sản phẩm</button></div></footer>
    <aside class="fa-sticky-buy" aria-label="Đặt mua nhanh"><div class="fa-container"><img src="{{ $productImage }}" width="64" height="64" alt="{{ $product }}"><div class="fa-sticky-product"><strong>{{ $productFull }}</strong><div>@if ($oldMoney)<del>{{ $oldMoney }}</del>@endif<b>{{ $money }}</b>@if ($discount)<span class="fa-discount">Giảm {{ $discount }}%</span>@endif</div></div><div class="fa-sticky-perks"><span><x-landing.freshair-icon name="truck-fast" />Freeship<br>toàn quốc</span><span><x-landing.freshair-icon name="shield-halved" />Bảo hành<br>24 tháng</span><span><x-landing.freshair-icon name="arrows-rotate" />Đổi trả<br>7 ngày</span></div><button class="fa-button fa-button-red" type="button" data-buy><x-landing.freshair-icon name="cart-shopping" /><span><strong>MUA NGAY</strong><small>Giao nhanh tận nhà</small></span></button></div></aside>

    <div class="fa-toast" role="status" aria-live="polite" hidden></div>
    <dialog class="fa-dialog" id="fa-cart-dialog" aria-labelledby="fa-cart-title"><button class="fa-dialog-close fa-icon-button" type="button" data-close-dialog aria-label="Đóng"><x-landing.freshair-icon name="xmark" /></button><h2 id="fa-cart-title">Giỏ hàng của bạn</h2><div class="fa-cart-empty"><x-landing.freshair-icon name="cart-shopping" /><p>Giỏ hàng đang trống.</p><button class="fa-button fa-button-green" type="button" data-close-dialog>Tiếp tục xem sản phẩm</button></div><div class="fa-cart-content" hidden><div class="fa-cart-product"><img src="{{ $productImage }}" alt="{{ $product }}" width="120" height="120"><div><h3>{{ $productFull }}</h3><p>Số lượng: <b data-cart-quantity>1</b></p><strong class="fa-price">{{ $money }}</strong></div></div><div class="fa-cart-total"><span>Tạm tính</span><strong data-cart-total>{{ $money }}</strong></div><p class="fa-dialog-description">{{ $c['cart_note'] }}</p><a class="fa-button fa-button-red" href="tel:{{ $tel }}"><x-landing.freshair-icon name="phone-volume" />Gọi đặt hàng: {{ $c['hotline'] }}</a><a class="fa-email-order" href="mailto:{{ $c['email'] }}" data-order-email>Gửi yêu cầu đặt hàng qua email</a><button class="fa-remove-cart" type="button" data-clear-cart>Xóa sản phẩm khỏi giỏ</button></div></dialog>
    <dialog class="fa-dialog fa-lightbox" id="fa-gallery-dialog" aria-labelledby="fa-gallery-caption">
        <button class="fa-dialog-close fa-icon-button" type="button" data-close-dialog aria-label="Đóng ảnh"><x-landing.freshair-icon name="xmark" /></button>
        <div class="swiper fa-lightbox-slider" aria-label="Xem ảnh phóng to">
            <div class="swiper-wrapper">
                @foreach ($productGallery as [$scene, $caption])
                    <div class="swiper-slide" data-caption="{{ $caption }}">
                        @if ($scene === 'product')<img class="fa-lightbox-image" src="{{ $productImage }}" alt="{{ $caption }}" width="1254" height="1254" loading="lazy">@else<div class="fa-lightbox-image fa-scene fa-scene-{{ $scene }}" role="img" aria-label="{{ $caption }}"></div>@endif
                    </div>
                @endforeach
            </div>
        </div>
        <div class="fa-lightbox-controls"><button class="fa-lightbox-prev" type="button" aria-label="Ảnh trước"><x-landing.freshair-icon name="arrow-left" /></button><p id="fa-gallery-caption" aria-live="polite">{{ $productFull }} · 1/{{ count($productGallery) }}</p><button class="fa-lightbox-next" type="button" aria-label="Ảnh tiếp theo"><x-landing.freshair-icon name="arrow-right" /></button></div>
    </dialog>
    <dialog class="fa-dialog fa-reviews-dialog" id="fa-reviews-dialog" aria-labelledby="fa-all-reviews-title"><button class="fa-dialog-close fa-icon-button" type="button" data-close-dialog aria-label="Đóng đánh giá"><x-landing.freshair-icon name="xmark" /></button><h2 id="fa-all-reviews-title">Khách hàng nói về {{ $product }}</h2><p class="fa-dialog-description"><span class="fa-stars">@for ($star = 0; $star < 5; $star++)<x-landing.freshair-icon name="star" />@endfor</span> Những trải nghiệm được chia sẻ</p><div class="fa-reviews-dialog-list"></div></dialog>
    <dialog class="fa-dialog" id="fa-share-dialog" aria-labelledby="fa-share-title"><button class="fa-dialog-close fa-icon-button" type="button" data-close-dialog aria-label="Đóng chia sẻ"><x-landing.freshair-icon name="xmark" /></button><h2 id="fa-share-title">Chia sẻ không khí trong lành</h2><img class="fa-share-preview" src="{{ $share }}" alt="Ảnh chia sẻ {{ $product }}" width="1200" height="630" loading="lazy"><p class="fa-dialog-description">Gửi {{ $product }} tới người thân và bạn bè.</p><label class="fa-share-url"><span>Đường dẫn sản phẩm</span><input readonly value="{{ $landing->url() }}" aria-label="Đường dẫn chia sẻ sản phẩm"></label><button class="fa-button fa-button-green" type="button" data-copy-share>Sao chép đường dẫn</button><button class="fa-native-share" type="button" data-native-share hidden>Chia sẻ bằng ứng dụng trên thiết bị</button></dialog>
    <script type="application/json" id="landing-config">@json($config, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG)</script>
    <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
    <x-landing.tracking :landing="$landing" position="body_end" />
</body>
</html>
