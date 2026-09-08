# FreshAir S8 — landing mẫu 2

- Route cố định: `/landing/mau-may-loc-khong-khi-mau-2`, tên `landing.freshair`.
- Nội dung, thông số, giá, ưu đãi, đánh giá và thông tin liên hệ là dữ liệu giao diện mẫu được viết cố định theo hai ảnh tham chiếu. Không truy vấn CMS, không tạo bảng hoặc bản ghi.
- Bố cục kết hợp hero lớn và dải lợi ích, khối mua hàng ba cột, thư viện ảnh sáu góc, công nghệ/thông số/tư vấn, quà tặng, đánh giá, FAQ và thanh mua cố định.
- View: `resources/views/landing/freshair/index.blade.php`. CSS và JS riêng: `resources/{css,js}/landing/freshair.*`.
- Giỏ hàng là bản mẫu lưu số lượng trong localStorage, tối đa 10 sản phẩm. Đặt mua và yêu cầu tư vấn cần người dùng xác nhận gửi qua email hoặc gọi hotline. Không tạo đơn hàng, gửi email tự động, thu tiền hoặc báo thành công giả.
- Voucher được sao chép để cung cấp khi đặt mua, không tự trừ thêm vào giá sale.
- Tìm kiếm nội dung trong trang, gallery/zoom/chuyển ảnh bằng bàn phím, FAQ, giỏ hàng, chia sẻ và xem trước ảnh chia sẻ đều hoạt động phía trình duyệt.
- Mẫu 2 bỏ thanh menu điều hướng, nút menu mobile và breadcrumb. Header thương hiệu/tìm kiếm/giỏ hàng tiếp nối trực tiếp hero.
- Dùng Swiper 14.0.2 có sẵn tại `public/vendor/swiper`, không thêm dependency hay CDN. Ảnh lớn và thumbnail đồng bộ 7 ảnh; lightbox dùng Swiper, hỗ trợ kéo/vuốt, mũi tên và bàn phím; bộ sưu tập hiển thị 6 ảnh desktop, trượt ngang trên mobile.
- Mẫu 1 được bổ sung OG/Twitter metadata, tận dụng ảnh hero đã có.

## Ảnh và chia sẻ

Dùng công cụ image_gen tích hợp. 5 ảnh mới được lưu ở `public/landing/freshair/`: `hero.webp` (1915x821), `product.webp` (1254x1254), `gallery.webp` (1536x1024, lưới 3x2), `gifts.webp` (1774x887, hai ảnh quà), `share.jpg` (1730x909). Chuyển định dạng bằng GD, giữ nguyên số pixel. WebP quality 90, JPEG quality 92. Các ảnh là tài nguyên cố định, triển khai cùng mã nguồn.

Tận dụng `public/landing/purehome/filters.webp`, `foliage.webp`, `customers.webp` và icon SVG. Ảnh chân dung/gia đình là hình minh họa AI cho giao diện mẫu.

`og:image`, `og:image:secure_url`, type, kích thước thật, alt, canonical, `og:url`, `twitter:card=summary_large_image`, Twitter title/description/image và `image_src` đều render sẵn trong HTML. Nút chia sẻ mở preview, sao chép URL; trình duyệt có Web Share API có thêm chia sẻ qua ứng dụng. Bot mạng xã hội chỉ truy cập được khi trang và ảnh đã triển khai trên tên miền công khai.

## Kiểm tra trước khi bàn giao

- `pnpm run build`, `node --check resources/js/landing/freshair.js`, `php artisan view:cache`, `git diff --check`: đạt.
- `php artisan test tests/Feature/FrontendSiteTest.php --filter=test_all_static_and_listing_pages_render --colors=never`: 1 test, 30 assertions đạt.
- Gọi HTTP kernel kiểm tra cả hai mẫu: HTTP 200, canonical, OG/Twitter metadata render sẵn; kích thước và MIME ảnh chia sẻ khớp tệp thật.
- Chrome local: kiểm tra bố cục desktop, 944px và mobile 390px, không tràn ngang, không lỗi console. Kiểm tra gallery, FAQ, giỏ hàng 3 sản phẩm tổng 10.770.000đ, xóa giỏ, nút mua nhanh, form bắt buộc/số điện thoại, bản nháp tư vấn, xem đánh giá, tìm kiếm và sao chép liên kết.
- Kiểm tra cập nhật Swiper: không còn nav/breadcrumb trong HTML; Chrome desktop/mobile không tràn ngang hoặc lỗi console; ảnh lớn/thumbnail/lightbox đồng bộ, ArrowRight/Escape, kéo ảnh mobile và chấm điều hướng bộ sưu tập hoạt động.
- Chỉ xác nhận giao diện và hành vi tại local; chưa triển khai hoặc kiểm tra bot chia sẻ trên server công khai. Không gửi đơn hàng/email trong quá trình kiểm tra.

## Prompt tạo ảnh

### hero

Use case: product-mockup. Asset type: full-width hero photography for FreshAir S8 air purifier store landing page, landscape 1792x768. The TWO attached FreshAir landing screenshots are reference images for the air purifier and aesthetic. Generate only photographic artwork, not a website, NO headlines, badges, UI, banners, labels, watermark. Sunlit bright modern Vietnamese apartment, soft white curtains, beige sofa, pale oak furniture, leafy indoor plants. Center-right at 59% width: large tall white rounded-square column air purifier, black rounded rectangle top ventilation grille, circular black front display with thin green illuminated rim reading 028, small text FreshAir on front, very fine lower ventilation perforations, tall slim proportions. Whole device visible sitting on cream rug with natural shadow, 83% of canvas height, very sharp. LEFT 36% is pale soft icy white almost empty background for HTML hero copy. Right 25% has quiet couch and plant background. Premium photoreal appliance campaign, peaceful natural diffuse daylight. Match the device in the two reference screenshots closely. No text except FreshAir and tiny display.

### product

Use case: product-mockup. Asset type: square FreshAir S8 product main photograph, 1024x1024. Use the TWO attached FreshAir landing screenshots as references for the same tall white air purifier. One FreshAir S8 standing fully visible centered in a bright minimal living room, white sheer curtains behind, beige sofa on right, green plant on left, cream rug. Device is white tall slim softly rounded square column, black slatted oval-rectangular top vent, small black circular front LED display edged green reading 028, small brand FreshAir, lower half perforated dots. Device occupies 70% height and 34% width of image. Realistic commercial photography, soft natural light, uncluttered composition. No UI, no text except tiny FreshAir brand and 028 screen, no watermark.

### gallery

Use case: product-mockup. Asset type: a single 3-column by 2-row photographic contact sheet used as a six-frame image gallery sprite, 1536x1024, each of six cells square, no gaps no borders. Reference TWO attached FreshAir screenshot images. Each cell shows the exact SAME FreshAir S8 white tall slim rounded-square column air purifier with black vented top, green-rim round black 028 display, FreshAir front logo and lower perforated vents. Top left: whole purifier in an elegant sunlit beige living room next to green plant. Top middle: angled close-up of black top touch control panel and circular digital readout, cyan-green light details. Top right: product's replaceable cylindrical white pleated HEPA filter cartridge with black caps, beside the open purifier casing on clean white background. Bottom left: whole purifier in peaceful softly lit bedroom next to bed, warm small bedside lamp at dusk. Bottom middle: wholesome Vietnamese young parents and small child sitting on sofa together, purifier visible beside them, candid natural family lifestyle photo. Bottom right: whole purifier next to a bright minimal home office desk and green plants. Premium consistent warm-white realistic product photography. Every cell is a distinct square photograph, straight 3x2 grid. No captions, no frames, no website UI, no watermark. No faces repeated within same panel.

### gifts

Use case: product-mockup. Asset type: single horizontal diptych product image for gift offer cards, 1536x768 with two equal square halves, no separator. Pure white background. Left half: one tall cylindrical replacement HEPA H13 air purifier filter, pleated white paper, black plastic top and bottom ring, subtle shadow, studio product photo centered with generous margins. Right half: one small white rounded rectangular digital thermometer/hygrometer on white background, standing slightly angled, LCD reading 26.3 degrees and 58 percent, subtle soft shadow. Product photography, no gift box, no ribbons, NO captions, no marketing text, no logo, no watermark.

### share

Use case: ads-marketing. Asset type: polished Facebook/Open Graph sharing cover for a Vietnamese FreshAir S8 air purifier landing, landscape EXACT ratio 1200:630. Use TWO attached FreshAir screenshot references for the appliance. Create a finished attractive social share image, not a website screenshot. Left 55% very pale green white background with spacious crisp professionally typeset Vietnamese text. Small dark green leaf logo and exact brand "FreshAir Mall" at top left. Main large dark forest-green heading in 2 lines: "Không khí sạch" and "Cho cuộc sống tốt đẹp hơn". Supporting text: "Máy lọc không khí FreshAir S8". Orange-red sale price pill near lower left with large white exact text "3.590.000đ", a small adjacent dark green label "GIẢM 40%". Small green line below "HEPA H13  •  Bảo hành 24 tháng". Right 45%: photoreal full white tall slim rounded-square FreshAir air purifier in airy sunlit beige living room with lush small plants and sheer white curtain; black vented top, green-ring black front circular 028 display, FreshAir brand, lower perforated vents. Device fully visible and large. Balanced clean campaign graphic, green nature accents, red-orange pricing accent. Exact legible Vietnamese diacritics, no other words, no CTA buttons, no watermarks. Keep safe margins 55px around all content.
