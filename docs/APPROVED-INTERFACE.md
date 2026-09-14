# Giao diện WebApp Bắc Ninh

## Phạm vi

Trang chủ, dịch vụ và hosting lấy từ `webappbacninh-new-interface-home-full-source.zip`. Sáu trang giải pháp, sản phẩm, dự án, bảng giá, blog và liên hệ lấy từ `webappbacninh-6-trang-html-tailwind-alpine.zip`. Giữ hình ảnh, nội dung và hướng thiết kế đã duyệt; chuẩn hóa lề, responsive, điều hướng và tài nguyên.

- Layout chung: `resources/views/layouts/interface.blade.php`.
- Các trang mới: `resources/views/frontend/interface/`.
- CSS/JS: `resources/css/interface.css`, `resources/js/interface.js`; Tailwind 4 + Alpine, build bằng Vite.
- Ảnh: `public/frontend/interface/images/`.
- Roboto Variable và Caveat Variable lấy từ package Fontsource, tự host qua Vite.
- Trang chi tiết CMS dùng layout chung và `managed-content.css`; các rule nội dung được giới hạn bằng CSS scope để không ảnh hưởng header/footer.
- Backend quản trị vẫn là Filament 5. Đã loại bỏ AdminLTE, các controller/view cũ không còn route và bản PHP trùng.

## Dữ liệu và chức năng

Tên website, điện thoại, email, mạng xã hội, favicon, SEO và tracking tiếp tục lấy từ hệ thống settings hiện có. Trang danh sách dự án và blog lấy dữ liệu CMS; URL chi tiết, nội dung dịch vụ và dữ liệu hiện có được giữ lại.

Form tư vấn trên header và trang liên hệ gửi vào `POST /lien-he`, dùng `StoreLeadRequest` và `LeadController`. Chỉ thông báo thành công sau khi máy chủ lưu dữ liệu.

Các phần giới thiệu, bảng giá và sản phẩm đóng gói trong bộ thiết kế hiện là nội dung Blade. Một số nút demo mở bản xem trước minh họa; chưa phải sản phẩm thanh toán hoặc ứng dụng đã triển khai. Kiểm tra tên miền trong giao diện chỉ kiểm tra định dạng, không khẳng định tên miền còn trống. Không triển khai newsletter giả lập.

## Build và triển khai

```sh
pnpm install --frozen-lockfile
pnpm run build
php artisan optimize:clear
php artisan view:cache
php artisan test
```

Không cần migration, seed hay ghi đè database để thay giao diện. Khi triển khai code lên hosting, cần build asset và xóa cache view cũ. Không dùng `migrate:fresh`.

Local sử dụng `https://webappbacninh.test` với Laragon, root là thư mục `public` của checkout này. Tên miền `.vn` không bị chuyển hướng về local. Không có thay đổi DNS công khai hoặc triển khai production. Bản khôi phục PHP cũ nằm ngoài Git, trong `storage/app/local-interface-setup/legacy-php-backup.zip`.

## Tài nguyên tiện ích tự host

- `public/vendor/alpinejs.min.js`: Alpine 3.17.2, từ package đã khóa trong pnpm.
- `public/vendor/lunar-1.7.7.js`: lunar-javascript 1.7.7, từ bản phát hành npm trên unpkg.
- `public/vendor/easy-qrcode-4.5.0.min.js`: easyqrcodejs 4.5.0, từ bản phát hành npm trên jsDelivr.

Các thư viện được tải về và phục vụ tại local; không còn tải runtime từ CDN ở các trang tiện ích này. API dịch vụ bên ngoài như VietQR là tích hợp chức năng, không phải thư viện giao diện.
