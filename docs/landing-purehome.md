# PureHome A3 — landing mẫu 1

- URL: `/landing/mau-may-loc-khong-khi-mau-1`.
- Đường dẫn có dấu cách `/landing/mau-may-loc-khong khi-mau-1` chuyển hướng 301 về URL trên.
- Nội dung, giá, thương hiệu, đánh giá, số điện thoại và chính sách là dữ liệu mẫu cố định theo ảnh tham chiếu; không đọc từ CMS.
- View: `resources/views/landing/purehome/index.blade.php`.
- CSS/JS riêng: `resources/css/landing/purehome.css`, `resources/js/landing/purehome.js`.
- Icon SVG: `resources/views/components/landing/purehome-icon.blade.php`.
- Build: `pnpm run build` (pnpm 10.15.0).
- Nút mua/giỏ hàng mở thông tin sản phẩm và liên hệ đặt mua qua điện thoại/email. Không tạo đơn, không gửi biểu mẫu hoặc xử lý thanh toán.
- Ảnh chân dung được tạo bằng AI, là nhân vật hư cấu minh họa giao diện mẫu.

## Kiểm tra local

- `pnpm run build`, `php artisan view:cache`, kiểm tra cú pháp PHP/JS và `git diff --check`: đạt.
- `php artisan test tests/Feature/FrontendSiteTest.php --filter=test_all_static_and_listing_pages_render --colors=never`: 1 test, 30 assertions đạt.
- HTTP kernel: route chính 200; URL có dấu cách 301 về route chính; toàn bộ đường dẫn ảnh và bundle trong HTML có file thực tế.
- Chrome local: đã xem desktop, mobile 390px và khung 809px của ảnh mẫu; kiểm tra menu đóng/mở, FAQ, hộp mua hàng, 3 đánh giá, chính sách và đóng dialog bằng Escape. Không có lỗi JavaScript được ghi nhận.
- Chưa triển khai lên production.

## Ảnh đã tạo

Dùng công cụ image_gen tích hợp. Các ảnh cuối cùng được lưu tại `public/landing/purehome/{hero,detail,filters,customers,foliage}.webp`. Chuyển định dạng PNG sang WebP quality 90, giữ nguyên kích thước pixel đầu ra. Đây là tài nguyên cố định của mẫu, cần triển khai cùng mã nguồn.

### foliage

Use case: ads-marketing. Asset type: wide subtle decorative background photo for a clean air purifier landing page testimonial section, 1536x768 landscape. Airy very pale icy blue and white background, an out-of-focus bright living room with gauzy white curtain suggested only. Soft blurred fresh green pothos leaves frame the extreme left border and extreme right border, greenery in bottom corners only. Center 85 percent is clean nearly white blue empty negative space for HTML overlays. Bright luminous diffused daylight, natural elegant fresh peaceful atmosphere. No products, no machine, no people, no furniture, no text, no icons, no logos, no watermark. Very subtle commercial photographic background matching a green and navy clean-air brand.

## Prompt cuối cùng

### hero

Use case: product-mockup. Asset type: photographic background for a Vietnamese air purifier landing page, 1536x768 wide. Generate just the photography, NOT a website screenshot, NO headings, buttons, icons, labels, typography or watermark. Reference image: the user's attached PureHome website screenshot is a visual reference for the machine and room. Recreate its hero photographic composition very closely. Bright airy modern living room, off-white sofa on right, floor-to-ceiling sheer white curtains, light oak floor, indoor green plants, sunlit peaceful fresh blue-white atmosphere. Large premium warm-white rounded rectangular cylindrical air purifier near x=1040 y=440, occupying 62%-81% of image width and 18%-96% of image height, fully visible with soft shadow on floor. Black oval slatted top vent with small turquoise ring control, small circular black blue-edged front display reading 028, lower front half filled with tiny perforated ventilation holes, tiny green leaf and text PureHome at front center. Keep LEFT 52% of image very pale blue white almost empty soft background, intended for HTML text overlay; no machine on left. Very soft translucent fresh-air blue ribbons behind machine, sparse floating green leaves. Plant and two neutral books bottom right. Product must look like the exact same machine in the reference. Premium photoreal product advertising, natural detail, restrained bloom.

### detail

Use case: product-mockup. Asset type: square product feature section background, 1024x1024. The attached PureHome landing screenshot is a visual reference; generate only the photographic art of its second machine section, NO website UI, text blocks, headings, badges or watermark. A premium warm-white air purifier with tall softly rounded rectangular cylindrical body, black oval top with fine concentric vent slots and small cyan ring touch control, circular small black front display edged blue reading 028, small leaf logo and exact brand PureHome, lower body tiny black perforated vents. Whole machine dominant on right half at x=650 occupying y=150..920, realistic frontal three-quarter view matching reference. Pale icy blue white room background with gauzy curtain, diffused window sunlight and very faint green foliage. White floor with soft contact shadow. Left 42% clear soft pale blue-white negative space for three feature badges. Refined photoreal advertising consistent with hero. Do not generate another machine, any human, website or text except tiny product brand and screen.

### filters

Use case: product-mockup. Asset type: air purifier filter exploded-view illustration for a landing page, square 1024x1024. Reference is the four-layer filter illustration in the attached PureHome screenshot. Generate only the four rectangular filter panels separated horizontally in a clean three-quarter isometric exploded view, floating over a pure white background. From front left to back right: slim white fine prefilter mesh frame, thick white folded HEPA pleated panel, black activated carbon honeycomb panel, slim dark gray support mesh filter. Four panels, clean rectangular upright shapes tilted in matching perspective, arranged diagonally with ample visible gaps. Slight pale blue airflow mist passing through and two small fresh green leaves, delicate shadows. White seamless background all edges, no machine, NO text, numbering, infographic labels, arrows, watermark or UI. Crisp photoreal 3D product advertising rendering.

### customers

Use case: photorealistic-natural. Asset type: one horizontal triptych image strip used as three customer avatar crops on a Vietnamese air purifier demo landing page. Generate 1536x512 with three equal square panels exactly side by side, no spacing or borders. First panel: friendly Vietnamese woman age 32, shoulder-length dark brown hair, white top, warm smile facing camera. Middle panel: friendly Vietnamese man age 35, short dark hair, clean shaven, pale blue shirt, smiling facing camera. Third panel: friendly Vietnamese woman age 38, shoulder-length dark brown hair, cream blouse, smiling facing camera. Head and shoulder portrait in each panel, face centered in each square panel, eyes at same height, neutral soft pale gray background, soft natural light, candid realistic photography. Fictional people, no text, no logos, no watermark, no website UI.
