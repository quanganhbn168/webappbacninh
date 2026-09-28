# WebApp Bắc Ninh

Website giới thiệu dịch vụ của WebApp Bắc Ninh, kèm trang quản trị tự xây trên Filament.
Nền tảng: Laravel 13, Filament 5, Livewire 4, Tailwind CSS 4 + Alpine (giao diện public), Curator (thư viện ảnh), Filament Shield (phân quyền).

Hướng dẫn triển khai: [DEPLOYMENT.md](DEPLOYMENT.md). Giao diện đã duyệt: [docs/APPROVED-INTERFACE.md](docs/APPROVED-INTERFACE.md).

## Nội dung quản lý trong admin (`/admin`)

| Mục | Model | Trang public |
|---|---|---|
| Nội dung → Trang | `Page` | `/gioi-thieu`, `/hop-tac-agency`, trang chính sách, trang tự tạo tại `/{slug}` |
| Nội dung → Dịch vụ thiết kế web | `Service`, `ServiceCategory` | `/thiet-ke-website/{slug}` (có landing) hoặc `/{slug}` |
| Nội dung → Dịch vụ vận hành | `OperationService` | `/dich-vu-van-hanh`, `/dich-vu-van-hanh/{slug}` |
| Nội dung → Dự án, Nhóm dự án | `Project`, `ProjectCategory` | `/du-an`, `/du-an/{slug}` |
| Kho giao diện → Giao diện, Ngành, Tính năng | `Template`, `TemplateCategory`, `ThemeFeature` | `/kho-giao-dien`, `/kho-giao-dien/{slug}` |
| Blog → Bài viết, Danh mục | `Post`, `PostCategory` | `/kien-thuc`, `/kien-thuc/{slug}` |
| Nội dung → Liên hệ | `Lead` | form liên hệ trên các trang |
| Cài đặt | `App\Settings\*` | logo, liên hệ, mạng xã hội, SEO, mã theo dõi, favicon |

Database là nguồn dữ liệu duy nhất. `config/` chỉ chứa cấu hình kỹ thuật (khóa API, cổng thanh toán…), không chứa nội dung.

## Cấu trúc mã nguồn

```
app/
  Domain/            nghiệp vụ theo mảng (xem app/Domain/README.md)
    Content/  Identity/  Media/  Pages/  Settings/  Site/
  Enums/             kiểu dữ liệu cố định (PageTemplate, TemplateType, SocialProvider)
  Filament/          trang quản trị: Resources/<Tên>/{Schemas,Tables,Pages}
  Http/Controllers/  controller mỏng: lấy model, trả view
  Models/            Eloquent model + accessor dùng cho view
  Settings/          nhóm cài đặt (spatie/laravel-settings) và SiteDefaults
  Support/           tiện ích kỹ thuật (cache menu, cấp quyền Shield)
Modules/             gói tính năng bật/tắt (Ecommerce, RealEstate) — để dành cho tenant
database/seeders/    dữ liệu mẫu cho bản cài mới (không ghi đè dữ liệu đã sửa trong admin)
```

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
