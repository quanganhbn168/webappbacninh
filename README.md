# WebApp Bắc Ninh

Website giới thiệu dịch vụ của WebApp Bắc Ninh, kèm trang quản trị tự xây trên Filament.
Nền tảng: Laravel 13, Filament 5, Livewire 4, Bootstrap 5.3 (giao diện public, Sass trong `resources/scss/bootstrap.scss`), Curator (thư viện ảnh), Filament Shield (phân quyền).

Hướng dẫn triển khai: [DEPLOYMENT.md](DEPLOYMENT.md). Giao diện đã duyệt: [docs/APPROVED-INTERFACE.md](docs/APPROVED-INTERFACE.md).

## Nội dung quản lý trong admin (`/admin`)

| Mục | Model | Trang public |
|---|---|---|
| Nội dung → Trang & SEO | `Page` | SEO (tiêu đề, mô tả, ảnh chia sẻ, noindex) và banner đầu trang của 19 trang cố định (`App\Domain\Pages\SitePages`); nội dung trang nằm trong view |
| Nội dung → Sản phẩm | `Product` | `/san-pham`, dải sản phẩm ở trang chủ |
| Nội dung → Bảng giá | `PricingPlan` | `/bang-gia`, trang chủ, `/dich-vu`, `/thiet-ke-website`, `/hosting-domain-email`, `/dich-vu-van-hanh` |
| Nội dung → Đánh giá khách hàng | `Testimonial` | trang chủ |
| Nội dung → Công cụ miễn phí | `MiniApp` | `/cong-cu` và mục "Công cụ miễn phí khác" ở trang công cụ |
| Nội dung → Banner quảng cáo | `AdBanner` | các vị trí ở trang chủ, trang dịch vụ, cột phải bài viết |
| Nội dung → Dịch vụ thiết kế web | `Service`, `ServiceCategory` | `/thiet-ke-website/{slug}` (có landing) hoặc `/{slug}` |
| Nội dung → Dịch vụ vận hành | `OperationService` | `/dich-vu-van-hanh`, `/dich-vu-van-hanh/{slug}` |
| Nội dung → Dự án, Nhóm dự án | `Project`, `ProjectCategory` | `/du-an`, `/du-an/{slug}` |
| Kho giao diện → Giao diện, Ngành, Tính năng | `Template`, `TemplateCategory`, `ThemeFeature` | `/kho-giao-dien`, `/kho-giao-dien/{slug}` |
| Blog → Bài viết, Danh mục | `Post`, `PostCategory` | `/kien-thuc`, `/kien-thuc/{slug}` |
| Nội dung → Menu | `Menu`, `MenuItem` | menu header và 3 cột link ở footer |
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
Modules/             gói tính năng bật/tắt (Ecommerce, RealEstate) — để dành cho tenant
database/seeders/    dữ liệu mẫu cho bản cài mới (không ghi đè dữ liệu đã sửa trong admin)
```

Giao diện public (chi tiết: [docs/APPROVED-INTERFACE.md](docs/APPROVED-INTERFACE.md)):

- Mỗi trang là một view Blade trong `resources/views/site/` (ví dụ `site/projects/index.blade.php`, `site/projects/show.blade.php`), kế thừa `layouts/site.blade.php`.
- Khối dùng chung là Blade component trong `resources/views/components/`: `<x-hero>`, `<x-section-head>`, `<x-feature-card>`, `<x-card.project|post|product|theme|price|package|quote>`, `<x-steps>`, `<x-faq>`, `<x-cta-banner>`, `<x-lead-form>`, `<x-lead-section>`, `<x-ad-banners>`, `<x-icon>`.
- CSS: Bootstrap 5 (`resources/scss/bootstrap.scss`, chỉ các phần đang dùng) rồi `resources/css/site.css` (token, header/footer, thẻ, khối nội dung).
- Icon: `<x-icon name="phone" />`, bộ SVG trong `resources/icons/icons.json` (Lucide + logo thương hiệu từ Simple Icons). Thêm icon: sửa danh sách trong `scripts/build-icons.mjs` rồi chạy `node scripts/build-icons.mjs`. Ô chọn icon trong admin dùng `App\Filament\Forms\IconPicker`.
- JavaScript: `resources/js/site.js` (plugin Bootstrap cần dùng + các module trong `resources/js/site/`: form tư vấn, lọc danh mục, kho giao diện, thư viện ảnh, ToolKit cho trang công cụ).
- Trang công cụ (`resources/views/tools`) dùng `layouts/tool.blade.php` (có header, footer, breadcrumb). Trang đăng nhập, lỗi, thanh toán dùng `layouts/basic.blade.php`. Tailwind chỉ còn dùng cho admin Filament và hai landing page mẫu.

Luồng một request: `Route → Controller → Model (scope/accessor) → View`. Logic dùng lại ở nhiều nơi (admin, controller, lệnh artisan) đặt trong `app/Domain/<Mảng>/Actions`, mỗi class làm một việc.

Ảnh của giao diện, dự án, dịch vụ và bài viết dùng Curator (`image_id`, `gallery` là danh sách id). Ảnh có sẵn trong `public/` được nhập bằng `App\Domain\Media\Actions\ImportLocalImage`.

Mỗi resource Filament mới cần quyền Shield: tạo trong migration bằng `App\Support\ShieldPermissions::grantToSuperAdmin([...])` để deploy chỉ cần `migrate`.

## Chạy trên máy

```bash
composer install && pnpm install
cp .env.example .env && php artisan key:generate && php artisan curator:token
php artisan migrate --seed
pnpm build          # hoặc pnpm dev
php artisan test
```
