# Giao diện WebApp Bắc Ninh

Giao diện public giữ thiết kế đã duyệt (màu vàng #ffac00, nâu đậm, Roboto, chữ viết tay Caveat) và được dựng lại trên Bootstrap 5 với một bộ component chung.

## Cấu trúc

- Layout: `resources/views/layouts/site.blade.php` (trang public), `layouts/tool.blade.php` (trang công cụ, kế thừa `site`), `layouts/basic.blade.php` (đăng nhập, lỗi 404/500/503, thanh toán).
- Header, footer, nút liên hệ nổi, modal tư vấn/kiểm tra tên miền: `resources/views/partials/site/`.
- Trang: `resources/views/site/`
  - `home.blade.php`, `pages/{services,hosting,solutions,products,pricing,contact,about,agency}.blade.php`, `pages/legal/*.blade.php`
  - `projects/`, `articles/`, `themes/`, `services/`, `operations/`, `tools/index.blade.php`
  - `partials/`: lưới dịch vụ, dải hợp tác Agency, quy trình 5 bước, module mở rộng — dùng lại ở nhiều trang.
- Component: `resources/views/components/` (xem README).
- CSS: `resources/css/site/{base,chrome,hero,cards,blocks,content,catalog,tools}.css`.

## Nội dung nào sửa ở đâu

| Nội dung | Sửa ở |
|---|---|
| Tiêu đề SEO, mô tả, ảnh chia sẻ, noindex, banner đầu trang (dòng chữ nhỏ, tiêu đề, dòng nhấn, mô tả, ảnh) | Admin → Trang & SEO |
| Sản phẩm, gói giá, đánh giá khách hàng, công cụ miễn phí, banner quảng cáo | Admin → mục tương ứng |
| Dịch vụ, dự án, kho giao diện, bài viết, menu | Admin (như trước) |
| Chữ cố định trong từng trang (tiêu đề mục, danh sách lợi ích, câu hỏi thường gặp chung) | View Blade của trang |

Banner đầu trang: để trống trong admin thì dùng nội dung viết sẵn trong view (`<x-hero>` nhận giá trị mặc định, admin ghi đè khi có).

## Quy ước

- Không dùng Font Awesome; icon dùng `<x-icon>`.
- Form tư vấn: `<x-lead-form>` gửi `POST /lien-he` (JSON khi có JavaScript, form thường khi không), lưu vào Admin → Liên hệ. Nút có `data-consult="…"` mở modal tư vấn và điền sẵn nhu cầu; `data-domain` mở modal kiểm tra tên miền (`/domain-check`).
- Lọc danh sách ngắn bằng `data-catalog` (sản phẩm, dự án, giải pháp); kho giao diện dùng `data-theme-library`; blog phân trang phía máy chủ.
- Màu chữ vàng trên nền trắng dùng `--gold-text`/`--gold-700` để đủ tương phản; nền vàng dùng chữ nâu đậm.
- Ảnh dưới màn hình đầu dùng `loading="lazy"` và có `width`/`height`.

## Tài nguyên tự host

- `public/vendor/lunar-1.7.7.js`: lunar-javascript 1.7.7 (lịch vạn niên).
- `public/vendor/easy-qrcode-4.5.0.min.js`: easyqrcodejs 4.5.0 (tạo QR).
- Roboto Variable và Caveat Variable qua Fontsource.
