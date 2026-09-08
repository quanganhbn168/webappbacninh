<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Máy lọc không khí PureHome A3 — Không khí trong lành cho cả gia đình</title>
    <meta name="description" content="PureHome A3 với hệ thống lọc 4 lớp, màng lọc HEPA H13, vận hành êm ái. Không khí sạch hơn, cuộc sống xanh hơn. Giá ưu đãi 3.990.000đ.">
    <meta name="theme-color" content="#074e7d">
    <link rel="canonical" href="{{ route('landing.purehome') }}">
    <link rel="image_src" href="{{ asset('landing/purehome/hero.webp') }}">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="PureHome">
    <meta property="og:title" content="PureHome A3 — Không khí trong lành cho cả gia đình">
    <meta property="og:description" content="Máy lọc không khí PureHome A3, giá ưu đãi 3.990.000đ. Miễn phí giao hàng toàn quốc, bảo hành 24 tháng.">
    <meta property="og:url" content="{{ route('landing.purehome') }}">
    <meta property="og:image" content="{{ asset('landing/purehome/hero.webp') }}">
    <meta property="og:image:secure_url" content="{{ secure_asset('landing/purehome/hero.webp') }}">
    <meta property="og:image:type" content="image/webp">
    <meta property="og:image:width" content="1774">
    <meta property="og:image:height" content="887">
    <meta property="og:image:alt" content="Máy lọc không khí PureHome A3 trong không gian sống xanh">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="PureHome A3 — Không khí trong lành cho cả gia đình">
    <meta name="twitter:description" content="Giá ưu đãi 3.990.000đ. Miễn phí giao hàng toàn quốc, bảo hành 24 tháng.">
    <meta name="twitter:image" content="{{ asset('landing/purehome/hero.webp') }}">
    <meta name="twitter:image:alt" content="Máy lọc không khí PureHome A3 trong không gian sống xanh">
    <link rel="icon" type="image/svg+xml" href="{{ asset('landing/purehome/leaf.svg') }}">
    <link rel="preload" as="image" href="{{ asset('landing/purehome/hero.webp') }}" fetchpriority="high">
    @vite(['resources/css/landing/purehome.css', 'resources/js/landing/purehome.js'])
</head>
<body class="purehome">
    <a class="ph-skip" href="#noi-dung">Đến nội dung chính</a>
    <header class="ph-header" id="trang-chu">
        <div class="ph-container ph-header-inner">
            <a class="ph-brand" href="#trang-chu" aria-label="PureHome — Trang chủ">
                <span class="ph-brand-top"><x-landing.purehome-icon name="leaf" /><strong>PureHome</strong></span>
                <span>Không khí sạch, cuộc sống xanh</span>
            </a>
            <nav class="ph-nav" id="ph-navigation" aria-label="Điều hướng chính">
                <a class="is-active" href="#trang-chu">Trang chủ</a>
                <a href="#san-pham">Sản phẩm</a>
                <a href="#uu-diem">Ưu điểm</a>
                <a href="#danh-gia">Đánh giá</a>
                <a href="#lien-he">Liên hệ</a>
            </nav>
            <a class="ph-hotline" href="tel:19008686"><span class="ph-phone-circle"><x-landing.purehome-icon name="phone" /></span><span><small>Hotline tư vấn</small><strong>1900 8686</strong></span></a>
            <button class="ph-cart ph-icon-button" type="button" data-dialog-open="ph-order" aria-label="Xem giỏ hàng PureHome A3"><x-landing.purehome-icon name="cart" /></button>
            <button class="ph-menu ph-icon-button" type="button" aria-expanded="false" aria-controls="ph-navigation" aria-label="Mở menu"><x-landing.purehome-icon name="menu" /></button>
        </div>
    </header>

    <main id="noi-dung">
        <section class="ph-hero" aria-labelledby="ph-title">
            <img class="ph-hero-background" src="{{ asset('landing/purehome/hero.webp') }}" alt="Máy lọc không khí PureHome A3 màu trắng trong phòng khách ngập ánh sáng và cây xanh" width="1536" height="768" fetchpriority="high">
            <div class="ph-container ph-hero-inner">
                <div class="ph-hero-copy">
                    <p class="ph-eyebrow">Hít thở sạch hơn, sống khỏe hơn</p>
                    <h1 id="ph-title">Máy lọc không khí<br>PureHome A3</h1>
                    <p class="ph-hero-subtitle">Không khí trong lành cho cả gia đình</p>
                    <p class="ph-hero-description">Loại bỏ bụi mịn, vi khuẩn, khử mùi hiệu quả.<br>Mang đến không gian sống sạch sẽ, an toàn và tràn đầy năng lượng mỗi ngày.</p>
                    <div class="ph-price-row"><strong class="ph-price">3.990.000<sup>đ</sup></strong><del>5.990.000đ</del><span class="ph-discount">Tiết kiệm 33%</span></div>
                    <div class="ph-hero-actions"><button class="ph-button ph-button-green" type="button" data-dialog-open="ph-order"><x-landing.purehome-icon name="cart" />Mua ngay<x-landing.purehome-icon name="arrow" /></button><a class="ph-button ph-button-outline" href="#san-pham">Xem chi tiết</a></div>
                    <div class="ph-assurances">
                        <span><x-landing.purehome-icon name="truck" /><span>Miễn phí giao hàng<br>toàn quốc</span></span>
                        <span><x-landing.purehome-icon name="shield" /><span>Bảo hành chính hãng<br>24 tháng</span></span>
                        <span><x-landing.purehome-icon name="rotate" /><span>Đổi trả dễ dàng<br>trong 7 ngày</span></span>
                    </div>
                </div>
                <p class="ph-handwritten ph-hero-note">Không khí sạch<br><span>Hạnh phúc trọn vẹn</span><i>♡</i></p>
                <div class="ph-lifestyle">
                    <div><span class="ph-lifestyle-icon"><x-landing.purehome-icon name="family" /></span><p><strong>Sạch hơn</strong><br>cho gia đình</p></div>
                    <div><span class="ph-lifestyle-icon"><x-landing.purehome-icon name="leaf" /></span><p><strong>Xanh hơn</strong><br>cho tương lai</p></div>
                    <div><span class="ph-lifestyle-icon"><x-landing.purehome-icon name="heart" /></span><p><strong>Khỏe mạnh hơn</strong><br>mỗi ngày</p></div>
                </div>
            </div>
        </section>

        <section class="ph-benefits ph-section" id="uu-diem" aria-labelledby="ph-benefits-title">
            <div class="ph-container">
                <h2 id="ph-benefits-title">Không khí sạch mang lại cuộc sống tốt đẹp hơn</h2>
                <div class="ph-benefit-grid">
                    @foreach ([['air', 'Lọc bụi mịn PM2.5', 'Loại bỏ đến 99,97%', 'bụi mịn, vi khuẩn'], ['leaf', 'Khử mùi hiệu quả', 'Giúp không gian luôn', 'thoáng mát, dễ chịu'], ['quiet', 'Vận hành êm ái', 'Độ ồn thấp, phù hợp', 'cho giấc ngủ ngon'], ['bolt', 'Tiết kiệm điện', 'Công nghệ hiện đại,', 'hiệu suất cao'], ['home', 'Phù hợp mọi không gian', 'Lý tưởng cho phòng ngủ,', 'phòng khách, văn phòng']] as [$icon, $title, $line1, $line2])
                        <article class="ph-card ph-benefit-card"><span class="ph-feature-icon {{ in_array($icon, ['leaf', 'bolt']) ? 'ph-green' : '' }}"><x-landing.purehome-icon :name="$icon" /></span><h3>{{ $title }}</h3><p>{{ $line1 }}<br>{{ $line2 }}</p></article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="ph-product" id="san-pham" aria-labelledby="ph-product-title">
            <div class="ph-container ph-product-grid">
                <div class="ph-product-visual">
                    <img class="ph-product-image" src="{{ asset('landing/purehome/detail.webp') }}" alt="Cận cảnh thiết kế PureHome A3, màn hình cảm ứng và cửa hút khí 360 độ" width="1024" height="1024" loading="lazy">
                    <p class="ph-handwritten ph-product-note">Từng chi tiết<br>vì bầu không khí tốt hơn<x-landing.purehome-icon name="leaf" /></p>
                    <div class="ph-design-features">
                        <div><span><x-landing.purehome-icon name="design" /></span><p><strong>Thiết kế hiện đại</strong>Tinh tế, phù hợp<br>mọi không gian</p></div>
                        <div><span><x-landing.purehome-icon name="touch" /></span><p><strong>Màn hình cảm ứng</strong>Dễ dàng thao tác</p></div>
                        <div><span><x-landing.purehome-icon name="shield" /></span><p><strong>Cửa hút khí 360°</strong>Lọc sạch không khí<br>từ mọi hướng</p></div>
                    </div>
                </div>
                <div class="ph-technology">
                    <h2 id="ph-product-title">Công nghệ tiên tiến<br> trong từng lớp lọc</h2>
                    <p>PureHome A3 được trang bị hệ thống lọc 4 lớp,<br class="ph-desktop-break"> loại bỏ hiệu quả bụi mịn, vi khuẩn, virus và các tác nhân gây dị ứng, mang đến bầu không khí sạch và an toàn cho cả gia đình.</p>
                    <div class="ph-filter-layout">
                        <img src="{{ asset('landing/purehome/filters.webp') }}" alt="Bốn lớp màng lọc: lọc thô, HEPA H13, than hoạt tính và lớp lọc bổ trợ" width="1024" height="1024" loading="lazy">
                        <ol class="ph-filter-list">
                            <li><span>1</span><div><h3>Màng lọc thô</h3><p>Giữ lại tóc, lông thú, bụi lớn</p></div></li>
                            <li><span>2</span><div><h3>Màng lọc HEPA H13</h3><p>Loại bỏ 99,97% bụi mịn PM2.5, vi khuẩn, virus</p></div></li>
                            <li><span>3</span><div><h3>Màng lọc than hoạt tính</h3><p>Khử mùi, hấp thụ khí độc hại (VOC, formaldehyde...)</p></div></li>
                            <li><span>4</span><div><h3>Màng lọc bổ trợ</h3><p>Giúp không khí thêm trong lành, tươi mát</p></div></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="ph-reasons ph-section" aria-labelledby="ph-reasons-title">
            <div class="ph-container">
                <h2 id="ph-reasons-title">Vì sao nên chọn PureHome A3?</h2>
                <p class="ph-section-subtitle">Sự lựa chọn tin cậy của hàng nghìn gia đình Việt</p>
                <div class="ph-reason-grid">
                    @foreach ([['shield', 'Chất lượng đáng tin cậy', 'Sản phẩm đạt tiêu chuẩn', 'quốc tế, an toàn cho sức khỏe.'], ['family', 'Bảo vệ cả gia đình', 'Không gian sống trong lành', 'cho trẻ nhỏ, người lớn tuổi', 'và người nhạy cảm.'], ['diamond', 'Thiết kế hiện đại', 'Tinh tế, sang trọng,', 'hài hòa với mọi không gian', 'nội thất.'], ['leaf', 'Vì một tương lai xanh', 'Góp phần giảm ô nhiễm,', 'bảo vệ môi trường sống', 'cho thế hệ mai sau.']] as $reason)
                        <article class="ph-card ph-reason-card"><x-landing.purehome-icon :name="$reason[0]" /><h3>{{ $reason[1] }}</h3><p>@foreach (array_slice($reason, 2) as $line){{ $line }}@unless($loop->last)<br>@endunless @endforeach</p></article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="ph-reviews ph-section" id="danh-gia" aria-labelledby="ph-reviews-title">
            <div class="ph-container">
                <div class="ph-review-heading"><div><h2 id="ph-reviews-title">Khách hàng nói gì về PureHome A3?</h2><p class="ph-section-subtitle">Hàng nghìn gia đình đã tin tưởng và lựa chọn PureHome</p></div><button class="ph-button ph-button-outline ph-review-all" type="button" data-dialog-open="ph-reviews-dialog">Xem tất cả đánh giá<x-landing.purehome-icon name="arrow" /></button></div>
                <div class="ph-review-grid" id="ph-review-cards">
                    @foreach ([['lan', 'Không khí trong lành hơn hẳn!', 'Từ khi có PureHome A3, bé nhà mình ít hắt hơi, ngủ ngon hơn. Máy chạy rất êm, thiết kế đẹp, dễ sử dụng.', 'Nguyễn Thị Lan', 'Hà Nội'], ['hoang', 'Sản phẩm rất đáng mua', 'Không gian phòng khách luôn thoáng mát, hết mùi đồ ăn. Máy hoạt động êm, tiết kiệm điện. Rất hài lòng!', 'Trần Minh Hoàng', 'TP. Hồ Chí Minh'], ['huong', 'Tốt cho sức khỏe gia đình', 'Mình bị dị ứng bụi, từ khi dùng máy thấy cải thiện rõ rệt. Không khí dễ chịu hơn, ngủ ngon hơn nhiều.', 'Lê Thu Hương', 'Đà Nẵng']] as [$avatar, $quote, $review, $name, $city])
                        <article class="ph-card ph-review-card"><div class="ph-review-top"><span class="ph-avatar ph-avatar-{{ $avatar }}" role="img" aria-label="Ảnh minh họa {{ $name }}"></span><div><span class="ph-stars" aria-label="5 trên 5 sao">★★★★★</span><h3>“{{ $quote }}”</h3></div></div><p>{{ $review }}</p><footer><strong>{{ $name }}</strong><span>{{ $city }}</span></footer></article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="ph-offer" id="uu-dai" aria-labelledby="ph-offer-title">
            <div class="ph-container ph-offer-inner">
                <div><h2 id="ph-offer-title">Ưu đãi đặc biệt hôm nay!</h2><p>Sở hữu PureHome A3 với mức giá tốt nhất<br>để bảo vệ sức khỏe gia đình bạn.</p></div>
                <div class="ph-offer-price"><span>Chỉ còn</span><strong class="ph-price">3.990.000<sup>đ</sup></strong><div><del>5.990.000đ</del><span class="ph-discount">Tiết kiệm 33%</span></div></div>
                <button class="ph-button ph-button-green" type="button" data-dialog-open="ph-order"><x-landing.purehome-icon name="cart" />Mua hàng ngay<x-landing.purehome-icon name="arrow" /></button>
                <div class="ph-offer-perks"><span><x-landing.purehome-icon name="truck" />Miễn phí giao hàng toàn quốc</span><span><x-landing.purehome-icon name="shield" />Bảo hành 24 tháng</span><span><x-landing.purehome-icon name="gift" />Tặng kèm bộ lọc dự phòng</span></div>
            </div>
        </section>

        <section class="ph-faq ph-section" aria-labelledby="ph-faq-title">
            <div class="ph-container">
                <h2 id="ph-faq-title">Câu hỏi thường gặp</h2>
                <p class="ph-section-subtitle">Giải đáp những thắc mắc phổ biến về PureHome A3</p>
                <div class="ph-faq-grid">
                    @foreach ([['Máy lọc không khí PureHome A3 phù hợp cho diện tích bao nhiêu?', 'PureHome A3 phù hợp với phòng ngủ, phòng khách và văn phòng. Liên hệ hotline 1900 8686 để được tư vấn theo diện tích và cách bố trí không gian của bạn.'], ['Có tốn nhiều điện không?', 'Máy sử dụng công nghệ tiết kiệm điện và có thể điều chỉnh chế độ hoạt động theo nhu cầu. Mức điện năng thực tế phụ thuộc vào chế độ và thời gian sử dụng.'], ['Máy có gây tiếng ồn khi hoạt động không?', 'PureHome A3 vận hành êm ái, phù hợp sử dụng khi làm việc hoặc nghỉ ngơi. Chọn chế độ thấp khi sử dụng trong phòng ngủ.'], ['Sản phẩm có bảo hành không?', 'Sản phẩm được bảo hành chính hãng 24 tháng. Vui lòng giữ thông tin mua hàng và liên hệ 1900 8686 khi cần hỗ trợ.'], ['Bao lâu thì cần thay màng lọc?', 'Thời gian thay màng lọc phụ thuộc vào chất lượng không khí và tần suất sử dụng. Kiểm tra màng lọc định kỳ và thay theo hướng dẫn đi kèm sản phẩm.'], ['Tôi có thể đổi trả nếu không hài lòng?', 'PureHome hỗ trợ đổi trả trong 7 ngày theo chính sách đổi trả. Vui lòng liên hệ hotline để được hướng dẫn về tình trạng sản phẩm và các điều kiện áp dụng.']] as [$question, $answer])
                        <details><summary>{{ $question }}<span class="ph-faq-plus" aria-hidden="true"></span></summary><p>{{ $answer }}</p></details>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    <footer class="ph-footer" id="lien-he">
        <div class="ph-container">
            <div class="ph-footer-grid">
                <div class="ph-footer-about"><a class="ph-brand" href="#trang-chu"><span class="ph-brand-top"><x-landing.purehome-icon name="leaf" /><strong>PureHome</strong></span><span>Không khí sạch, cuộc sống xanh</span></a><p>PureHome - Vì một cuộc sống khỏe mạnh hơn cho mọi gia đình Việt. Chúng tôi cam kết mang đến những sản phẩm chất lượng, góp phần kiến tạo không gian sống trong lành và bền vững.</p><div class="ph-socials" aria-label="Kết nối PureHome"><a href="#ph-contact" aria-label="Liên hệ PureHome qua Facebook">f</a><a href="#ph-contact" aria-label="Liên hệ PureHome qua YouTube"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="6" width="18" height="13" rx="4" fill="currentColor"/><path d="m10 9 6 3.5-6 3.5Z" fill="#25485e"/></svg></a><a href="#ph-contact" aria-label="Liên hệ PureHome qua TikTok">♪</a><a href="#ph-contact" aria-label="Liên hệ PureHome qua Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17" cy="7" r="1" fill="currentColor"/></svg></a></div></div>
                <div><h3>Liên kết nhanh</h3><nav aria-label="Liên kết cuối trang"><a href="#trang-chu">Trang chủ</a><a href="#san-pham">Sản phẩm</a><a href="#uu-diem">Ưu điểm</a><a href="#danh-gia">Đánh giá</a><a href="#lien-he">Liên hệ</a></nav></div>
                <div><h3>Chính sách</h3><nav aria-label="Chính sách PureHome"><button type="button" data-policy="warranty">Chính sách bảo hành</button><button type="button" data-policy="returns">Chính sách đổi trả</button><button type="button" data-policy="shipping">Chính sách giao hàng</button><button type="button" data-policy="privacy">Chính sách bảo mật</button><button type="button" data-policy="terms">Điều khoản sử dụng</button></nav></div>
                <div class="ph-footer-contact" id="ph-contact"><h3>Liên hệ</h3><a href="tel:19008686"><x-landing.purehome-icon name="phone" /><span><strong>1900 8686</strong><small>(8:00 - 21:00 hàng ngày)</small></span></a><a href="mailto:cskh@purehome.vn"><x-landing.purehome-icon name="mail" /><span>cskh@purehome.vn</span></a><p><x-landing.purehome-icon name="pin" /><span>Số 123 Đường An Phú,<br>Quận 2, TP. Thủ Đức, TP. Hồ Chí Minh</span></p></div>
            </div>
            <div class="ph-footer-bottom"><span>© 2024 PureHome. Tất cả quyền được bảo lưu.</span><span>Sống xanh mỗi ngày, cho ngày mai tươi sáng hơn.<x-landing.purehome-icon name="leaf" /></span></div>
        </div>
    </footer>

    <dialog class="ph-dialog" id="ph-order" aria-labelledby="ph-order-title"><button class="ph-dialog-close ph-icon-button" type="button" data-dialog-close aria-label="Đóng"><x-landing.purehome-icon name="close" /></button><p class="ph-eyebrow">Không khí sạch cho cả gia đình</p><h2 id="ph-order-title">Đặt mua PureHome A3</h2><div class="ph-order-product"><img src="{{ asset('landing/purehome/detail.webp') }}" alt="Máy lọc PureHome A3" width="160" height="160" loading="lazy"><div><h3>Máy lọc không khí PureHome A3</h3><p>Màu trắng · Số lượng: 1</p><strong class="ph-price">3.990.000<sup>đ</sup></strong><del>5.990.000đ</del></div></div><p>Miễn phí giao hàng toàn quốc · Bảo hành 24 tháng · Đổi trả trong 7 ngày.</p><p>Gọi PureHome để được tư vấn và xác nhận thông tin đặt hàng.</p><a class="ph-button ph-button-green" href="tel:19008686"><x-landing.purehome-icon name="phone" />Gọi đặt hàng: 1900 8686</a><a class="ph-dialog-email" href="mailto:cskh@purehome.vn?subject=Dat%20mua%20PureHome%20A3">Gửi yêu cầu qua email</a></dialog>
    <dialog class="ph-dialog ph-dialog-wide" id="ph-reviews-dialog" aria-labelledby="ph-all-reviews-title"><button class="ph-dialog-close ph-icon-button" type="button" data-dialog-close aria-label="Đóng"><x-landing.purehome-icon name="close" /></button><h2 id="ph-all-reviews-title">Đánh giá về PureHome A3</h2><p class="ph-dialog-rating"><span class="ph-stars" aria-label="5 trên 5 sao">★★★★★</span> 5/5 · 3 đánh giá</p><div class="ph-dialog-reviews"></div></dialog>
    <dialog class="ph-dialog" id="ph-policy" aria-labelledby="ph-policy-title"><button class="ph-dialog-close ph-icon-button" type="button" data-dialog-close aria-label="Đóng"><x-landing.purehome-icon name="close" /></button><h2 id="ph-policy-title"></h2><p id="ph-policy-content"></p><p>Cần hỗ trợ thêm? Gọi <a href="tel:19008686">1900 8686</a> hoặc email <a href="mailto:cskh@purehome.vn">cskh@purehome.vn</a>.</p></dialog>
</body>
</html>
