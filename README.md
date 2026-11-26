# WebApp Bắc Ninh

Website giới thiệu dịch vụ của WebApp Bắc Ninh, kèm trang quản trị tự xây trên Filament.
Nền tảng: Laravel 13, Filament 5, Livewire 4, Bootstrap 5.3 (giao diện public, Sass trong `resources/scss/`), Curator (thư viện ảnh), Filament Shield (phân quyền).

Hướng dẫn triển khai: [DEPLOYMENT.md](DEPLOYMENT.md). Giao diện đã duyệt: [docs/APPROVED-INTERFACE.md](docs/APPROVED-INTERFACE.md).

## Nội dung quản lý trong admin (`/admin`)

| Mục | Model | Trang public |
|---|---|---|
| Nội dung → Trang & SEO | `Page` | SEO (tiêu đề, mô tả, ảnh chia sẻ, noindex) và banner đầu trang của 19 trang cố định (`App\Domain\Pages\SitePages`); nội dung trang nằm trong view |
| Nội dung → Sản phẩm | `Product` | `/san-pham`, dải sản phẩm ở trang chủ |
| Nội dung → Bảng giá | `PricingPlan` | `/bang-gia`, trang chủ, `/dich-vu`, `/thiet-ke-website`, `/hosting-domain-email`, `/dich-vu-van-hanh` |
| Nội dung → Đánh giá khách hàng | `Testimonial` | trang chủ |
| Nội dung → Công cụ miễn phí | `MiniApp` | `/cong-cu` và mục "Công cụ miễn phí khác" ở trang công cụ |
| Nội dung → Landing quảng cáo | `LandingPage` | `/landing/{slug}` — trang chạy quảng cáo, chọn 1 trong 2 bố cục cố định (PureHome, FreshAir), sửa nội dung, giá, ảnh, SEO, mã đo lường riêng; đếm liên hệ và lưu thông số UTM/gclid/fbclid; có nhân bản. Mặc định noindex |
| Nội dung → Banner quảng cáo | `AdBanner` | các vị trí ở trang chủ, trang dịch vụ, cột phải bài viết |
| Nội dung → Dịch vụ thiết kế web | `Service`, `ServiceCategory` | `/thiet-ke-website/{slug}` (có landing) hoặc `/{slug}` |
| Nội dung → Dịch vụ vận hành | `OperationService` | `/dich-vu-van-hanh`, `/dich-vu-van-hanh/{slug}` |
| Nội dung → Dự án, Nhóm dự án | `Project`, `ProjectCategory` | `/du-an`, `/du-an/{slug}` |
| Kho giao diện → Giao diện, Ngành, Tính năng | `Template`, `TemplateCategory`, `ThemeFeature` | `/kho-giao-dien`, `/kho-giao-dien/{slug}`, `/kho-giao-dien/{slug}/demo` (khung xem thử máy tính / tablet / mobile; demo là bố cục landing sửa ngay trong giao diện, hoặc link ngoài). Mỗi ngành có trang `/mau-website/{slug}`: giới thiệu, nội dung, câu hỏi thường gặp nhập ở Ngành nghề; chỉ vào Google và sitemap khi bật "Cho Google lập chỉ mục" |
| Kiến thức → Bài viết, Danh mục bài viết | `Post`, `PostCategory` | `/kien-thuc` (trang tổng), `/{slug}` (danh mục và bài viết). Bài viết có mục lục, mục câu hỏi thường gặp (kèm FAQ schema) và khung thông tin công ty lấy từ Cài đặt |
| Nội dung → Menu | `Menu`, `MenuItem` | menu header và 3 cột link ở footer. Mục trên header bật "Mega menu" thì menu con chia cột theo "Tên cột", có icon, mô tả và nhãn |
| Nội dung → Liên hệ | `Lead` | form liên hệ trên các trang |
| Cài đặt | `App\Settings\*` | logo, liên hệ, mạng xã hội, SEO, mã theo dõi, favicon |

Database là nguồn dữ liệu duy nhất. `config/` chỉ chứa cấu hình kỹ thuật (khóa API, cổng thanh toán…), không chứa nội dung.

## Cấu trúc mã nguồn

```
app/
  Domain/            nghiệp vụ theo mảng (xem app/Domain/README.md)
    Content/  Identity/  Media/  Navigation/  Pages/  Settings/  Site/
  Enums/             kiểu dữ liệu cố định (ProductGroup, PricingGroup, BannerSlot, TemplateType…)
  Filament/          trang quản trị: Resources/<Tên>/{Schemas,Tables,Pages}
  Http/Controllers/  controller mỏng: lấy model, trả view
  Models/            Eloquent model + accessor dùng cho view
  Settings/          nhóm cài đặt (spatie/laravel-settings) và SiteDefaults
  Support/           tiện ích kỹ thuật (bộ icon, cache menu, cấp quyền Shield)
database/seeders/    nội dung của bản cài mới: thông tin công ty, menu, dịch vụ, dự án, giao diện, bài viết
```

Giao diện public (chi tiết: [docs/APPROVED-INTERFACE.md](docs/APPROVED-INTERFACE.md)):

- Mỗi trang là một view Blade trong `resources/views/site/` (ví dụ `site/projects/index.blade.php`, `site/projects/show.blade.php`), kế thừa `layouts/site.blade.php`.
- Khối dùng chung là Blade component trong `resources/views/components/`: `<x-hero>`, `<x-section-head>`, `<x-feature-card>`, `<x-card.project|post|product|theme|price|package|quote>`, `<x-steps>`, `<x-faq>`, `<x-cta-banner>`, `<x-lead-form>`, `<x-lead-section>`, `<x-ad-banners>`, `<x-icon>`.
- CSS: Sass trong `resources/scss/`: token màu `_tokens.scss` cấu hình cho Bootstrap 5 (`_bootstrap.scss`, chỉ các phần đang dùng); `site.scss` cho trang public, `basic.scss` cho trang đăng nhập/lỗi/thanh toán. Mỗi layout nạp một file.
- Icon: `<x-icon name="phone" />`, bộ SVG trong `resources/icons/icons.json` (Lucide + logo thương hiệu từ Simple Icons). Thêm icon: sửa danh sách trong `scripts/build-icons.mjs` rồi chạy `node scripts/build-icons.mjs`. Ô chọn icon trong admin dùng `App\Filament\Forms\IconPicker`.
- JavaScript: `resources/js/site.js` (plugin Bootstrap cần dùng + các module trong `resources/js/site/`: form tư vấn, lọc danh mục, kho giao diện, thư viện ảnh, ToolKit cho trang công cụ).
- JS riêng của một trang nằm ở `resources/js/tools/<trang>.js` hoặc `resources/js/payment/<trang>.js`, nạp bằng `@push('scripts') @vite('resources/js/tools/<trang>.js') @endpush`; Vite tự nhận file mới trong hai thư mục này. Không viết `<script>`, `<style>` hay `onclick=` thẳng trong view.
- Trang công cụ (`resources/views/site/tools`) dùng `layouts/tool.blade.php` (có header, footer, breadcrumb). Trang đăng nhập, lỗi, thanh toán dùng `layouts/basic.blade.php`. Tailwind chỉ còn dùng cho admin Filament và hai landing page mẫu.

Luồng một request: `Route → Controller → Model (scope/accessor) → View`. Logic dùng lại ở nhiều nơi (admin, controller, lệnh artisan) đặt trong `app/Domain/<Mảng>/Actions`, mỗi class làm một việc.

Ảnh của giao diện, dự án, dịch vụ và bài viết dùng Curator (`image_id`, `gallery` là danh sách id). Ảnh có sẵn trong `public/` được nhập bằng `App\Domain\Media\Actions\ImportLocalImage`.

Mỗi resource Filament mới cần quyền Shield: chạy lại `php artisan app:setup-admin --email=<email quản trị>` sau khi deploy; lệnh sinh quyền còn thiếu và gán cho `super_admin`, không đổi mật khẩu.

## Chạy trên máy

Cần Node 20 trở lên (Tailwind 4 không chạy trên Node 18) và pnpm, không dùng npm.

```bash
composer install && pnpm install
cp .env.example .env && php artisan key:generate && php artisan curator:token
php artisan migrate --seed
php artisan app:setup-admin   # hỏi email và mật khẩu quản trị; không có tài khoản nào viết sẵn trong code
pnpm build          # hoặc pnpm dev
php artisan test
```

Test chạy trên database riêng `webappbacninh_test` (khai báo trong `phpunit.xml`), không đụng database của site. Tạo một lần:

```bash
# tạo database webappbacninh_test trong MySQL, rồi:
DB_DATABASE=webappbacninh_test php artisan migrate:fresh --seed
DB_DATABASE=webappbacninh_test php artisan app:setup-admin --email=admin@example.com --no-interaction
```

Các test dùng `DatabaseTransactions` (tự hoàn tác) trên dữ liệu mẫu này, nên không dùng `RefreshDatabase`.
