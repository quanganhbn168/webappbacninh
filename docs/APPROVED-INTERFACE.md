# Giao diện WebApp Bắc Ninh

## Phạm vi

Trang chủ, dịch vụ và hosting lấy từ `webappbacninh-new-interface-home-full-source.zip`. Sáu trang giải pháp, sản phẩm, dự án, bảng giá, blog và liên hệ lấy từ `webappbacninh-6-trang-html-tailwind-alpine.zip`. Giữ hình ảnh, nội dung và hướng thiết kế đã duyệt; chuẩn hóa lề, responsive, điều hướng và tài nguyên.

- Layout chung: `resources/views/layouts/master.blade.php`.
- Layout trắng cho trang tiện ích, đăng nhập và lỗi: `resources/views/layouts/plain.blade.php` (không header/footer).
- Phần dùng chung: `resources/views/partials/frontend/`.
- Trang lỗi: `resources/views/errors/404.blade.php`, `500.blade.php`; trang 500 dùng khi `APP_DEBUG=false`.
- Các trang mới: `resources/views/frontend/pages/`.
- CSS/JS: `resources/css/frontend.css`, `resources/js/frontend.js`; Tailwind 4 + Alpine, build bằng Vite.
- Ảnh: `public/frontend/images/`.
- Roboto Variable và Caveat Variable lấy từ package Fontsource, tự host qua Vite.
- Trang chi tiết CMS dùng layout chung và `managed-content.css`; các rule nội dung được giới hạn bằng CSS scope để không ảnh hưởng header/footer.
- Backend quản trị vẫn là Filament 5. Đã loại bỏ AdminLTE, các controller/view cũ không còn route và bản PHP trùng.

## Dữ liệu và chức năng

Tên website, điện thoại, email, mạng xã hội, favicon, SEO và tracking tiếp tục lấy từ hệ thống settings hiện có. Trang danh sách dự án và blog lấy dữ liệu CMS; URL chi tiết, nội dung dịch vụ và dữ liệu hiện có được giữ lại.

Form tư vấn trên header và trang liên hệ gửi vào `POST /lien-he`, dùng `StoreLeadRequest` và `LeadController`. Chỉ thông báo thành công sau khi máy chủ lưu dữ liệu.

Các phần giới thiệu, bảng giá và sản phẩm đóng gói trong bộ thiết kế hiện là nội dung Blade. Một số nút demo mở bản xem trước minh họa; chưa phải sản phẩm thanh toán hoặc ứng dụng đã triển khai. Kiểm tra tên miền trong giao diện chỉ kiểm tra định dạng, không khẳng định tên miền còn trống. Không triển khai newsletter giả lập.

## Build và triển khai

```sh
composer install --no-dev --prefer-dist --optimize-autoloader
# Chỉ chạy lần đầu nếu môi trường chưa có CURATOR_GLIDE_TOKEN:
php artisan curator:token
php artisan migrate --force
php artisan blog:import-media
pnpm install --frozen-lockfile
pnpm run build
php artisan optimize:clear
php artisan view:cache
php artisan test
```

Bản cập nhật blog cần migration bổ sung bảng `curator`, khóa ảnh và cờ quản lý ảnh cho bài viết/danh mục. `blog:import-media` sao chép ảnh blog cũ có sẵn trên máy, giữ nguyên tệp gốc và bỏ qua bài đã dùng Curator; chạy lại an toàn. Lệnh không ghi đè nội dung/SEO đã sửa trong CMS. Tệp mới nằm trong `storage/app/public/blog/imports`; không được Git chuyển lên hosting, cần đồng bộ media riêng hoặc chạy import tại hosting có ảnh gốc. Không dùng `migrate:fresh`.

`CURATOR_GLIDE_TOKEN` cần tạo riêng một lần ở mỗi môi trường; không đưa token vào Git và không tạo lại ở mỗi lần deploy. `deploy.sh` cài Composer, build asset, migrate và import ảnh, sau đó xây lại cache.

Local sử dụng `https://webappbacninh.test` với Laragon, root là thư mục `public` của checkout này. Tên miền `.vn` không bị chuyển hướng về local. Không có thay đổi DNS công khai hoặc triển khai production. Bản khôi phục PHP cũ nằm ngoài Git, trong `storage/app/local-interface-setup/legacy-php-backup.zip`.

## Tài nguyên tiện ích tự host

- `public/vendor/alpinejs.min.js`: Alpine 3.17.2, từ package đã khóa trong pnpm.
- `public/vendor/lunar-1.7.7.js`: lunar-javascript 1.7.7, từ bản phát hành npm trên unpkg.
- `public/vendor/easy-qrcode-4.5.0.min.js`: easyqrcodejs 4.5.0, từ bản phát hành npm trên jsDelivr.

Các thư viện được tải về và phục vụ tại local; không còn tải runtime từ CDN ở các trang tiện ích này. API dịch vụ bên ngoài như VietQR là tích hợp chức năng, không phải thư viện giao diện.

## Quản trị blog và liên hệ

- `/admin/posts` và `/admin/post-categories`: chọn ảnh qua Curator trên ổ public, tải ảnh và chèn ảnh vào trình soạn thảo. Ảnh đại diện và `og:image` là hai lựa chọn riêng. Bỏ `og:image` sẽ dùng ảnh đại diện. Dữ liệu SEO bổ sung/từ khóa cũ được giữ trong DB nhưng không còn trường nhập trên form blog.
- Đường dẫn tự tạo theo tiêu đề/tên danh mục, hiển thị dạng permalink; bấm **Chỉnh sửa** mới mở input. Khi lưu, kiểm tra bảng `slugs` và bảng nội dung, thêm `-2`, `-3` khi trùng. Đổi tiêu đề bài đã có không đổi slug. URL bài: `/kien-thuc/{slug}`, danh mục: `/kien-thuc/danh-muc/{slug}`. Đổi slug thủ công không tạo lịch sử redirect cho slug cũ.
- Trang chủ/blog lấy bài từ CMS: 1 bài lớn và tối đa 3 bài nhỏ, ưu tiên bài đánh dấu nổi bật rồi bổ sung bài mới nhất nếu chưa đủ. Không lặp bài để lấp chỗ; bài hẹn xuất bản chưa tới ngày không hiển thị.
- `/admin/leads`: xem thông tin yêu cầu tư vấn, tìm kiếm và cập nhật trạng thái Mới / Đã liên hệ / Đã xử lý. Chưa gửi email thông báo tự động.
- `/admin/settings` → **Thương hiệu**: logo ngang dùng cho header/footer; favicon nguồn giữ quy trình sinh bộ favicon/manifest hiện có. Logo/favicon hiện dùng upload cấu hình riêng; Curator áp dụng cho blog.
- **Liên hệ**: số điện thoại, email, địa chỉ, giờ làm việc. **Mạng xã hội**: nút Zalo/Messenger/Telegram/WhatsApp chỉ hiện khi có URL hợp lệ; nút gọi và tư vấn dùng cấu hình thật.
