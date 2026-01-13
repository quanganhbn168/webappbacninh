# TECHNICAL SPECIFICATION: SaaS PLATFORM ARCHITECTURE (LARAVEL 11)

**Project Goal:** Xây dựng hệ thống Multi-tenant quản lý 200-400 website đa ngành (Bán hàng, BĐS, Booking) trên cùng một source code.

---

## I. KIẾN TRÚC TỔNG THỂ (SYSTEM ARCHITECTURE)

### 1. Mô hình: Multi-tenancy (Database per Tenant)
Sử dụng mô hình **mỗi khách hàng một Database riêng** để đảm bảo an toàn dữ liệu và dễ dàng scale.

*   **Landlord (Hệ thống chủ):**
    *   Quản lý danh sách khách hàng (`tenants`).
    *   Quản lý gói cước (`subscriptions`).
    *   Tự động cấp phát Database khi tạo mới.
*   **Tenant (Khách thuê):**
    *   Dữ liệu nghiệp vụ riêng biệt: `products`, `orders`, `customers`.
    *   Không thể truy cập chéo dữ liệu của nhau.

### 2. Cấu trúc Code: Modular Monolith
Chia source code thành các Module độc lập để quản lý đa ngành nghề.

*   **Core Modules (Dịch vụ dùng chung):**
    *   `Payment`: Xử lý thanh toán (VNPAY, Momo).
    *   `Auth`: Quản lý User, Phân quyền (RBAC).
    *   `Media`: Upload, resize ảnh, lưu trữ (S3/Local).
    *   `Notification`: Gửi Mail, Zalo, SMS.
*   **Business Modules (Nghiệp vụ bán cho khách):**
    *   `Ecommerce`: Logic bán hàng (Cart, Product, Order).
    *   `RealEstate`: Logic BĐS (Project, Property, Map).
    *   `Booking`: Logic đặt lịch (Calendar, Slot).

---

## II. THIẾT KẾ DATABASE (KEY SCHEMAS)

### 1. Bảng `tenants` (DB Landlord)
| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | String | Mã định danh (VD: `shop-a`). |
| `domain` | String | Tên miền chính (VD: `shpa.com`). |
| `data` | JSON | Lưu cấu hình DB connection. |
| `plan_id` | Int | Gói cước đang dùng. |
| `features` | JSON | Các tính năng được bật (VD: `['ecommerce', 'blog']`). |

### 2. Bảng `products` & `variants` (DB Tenant)
*Tối ưu cho Ecommerce giống Haravan.*
*   **Products:** `id`, `name`, `slug`, `content`, `seo_meta` (JSON).
*   **Variants:** Quan trọng để xử lý biến thể.
    *   `sku`: Mã kho.
    *   `price`: Giá bán.
    *   `attributes`: JSON (VD: `{"color": "Red", "size": "M"}`).

### 3. Bảng `theme_configs` (DB Tenant)
*Dùng cho tính năng tùy biến giao diện.*
*   `page`: Tên trang (homepage, contact).
*   `schema`: **JSON** - Lưu cấu hình nội dung (Text, Ảnh, Màu sắc) do khách nhập.

---

## III. CƠ CHẾ THEME ENGINE (GIAO DIỆN)

Sử dụng cơ chế **Fixed Layout + Dynamic Content** (Khung cứng, nội dung mềm) để an toàn và dễ dùng.

1.  **File View (Blade):** Code sẵn HTML/CSS đẹp, chừa chỗ trống (`placeholder`) cho biến dữ liệu.
2.  **File Config:** Định nghĩa các ô nhập liệu (Text, Image, Color Picker).
3.  **Trình Admin (Customizer):**
    *   Dùng **Vue.js** để làm giao diện nhập liệu.
    *   Hiển thị **Live Preview** (Gõ đến đâu thấy đến đó).
    *   Lưu dữ liệu dưới dạng JSON vào bảng `theme_configs`.

---

## IV. CÔNG NGHỆ & PACKAGES (LARAVEL 11 STACK)

Các thư viện chủ lực đã hỗ trợ Laravel 11/PHP 8.2+:

| Mục đích | Package | Ghi chú |
| :--- | :--- | :--- |
| **Multi-tenancy** | `stancl/tenancy` | Tự động nhận diện domain, switch DB. |
| **Modules** | `nwidart/laravel-modules` | Tổ chức code theo thư mục Module. |
| **Phân quyền** | `spatie/laravel-permission` | Quản lý Admin/Sale/Kho. |
| **Media** | `spatie/laravel-medialibrary` | Xử lý ảnh, crop ảnh tự động. |
| **Backup** | `spatie/laravel-backup` | Backup DB định kỳ lên Cloud. |
| **SEO** | `artesaos/seotools` | Render thẻ Meta chuẩn Google. |
| **Search** | `laravel/scout` + MeiliSearch | Tìm kiếm nhanh, hỗ trợ gõ sai chính tả. |
| **API/Webhook** | `spatie/laravel-webhook-server` | Bắn đơn hàng sang bên thứ 3. |
| **Debug** | `barryvdh/laravel-debugbar` | Soi query, tối ưu hiệu năng. |

---

## V. CHIẾN LƯỢC VẬN HÀNH & KIẾM TIỀN (OPS)

### 1. Phân cấp gói dịch vụ (Subscription)
*   **Gói Cơ Bản:** Giới hạn tính năng qua Feature Flags (Check quyền trong Code).
*   **Gói Pro:** Mở khóa module nâng cao (Webhook, API, Báo cáo).
*   **Cơ chế:** Cronjob quét ngày hết hạn -> Tự động treo web nếu không đóng tiền.

### 2. Tự động hóa Hạ tầng (Infrastructure)
*   **SSL:** Cấu hình Caddy Server hoặc Nginx để Auto-SSL cho custom domain của khách.
*   **Queue:** Sử dụng Redis để xử lý tác vụ nặng (Gửi mail hàng loạt, Import sản phẩm) -> Tránh treo server.

### 
## VII. QUẢN TRỊ RỦI RO & GIẢI PHÁP (RISKS & SOLUTIONS)

Trước khi triển khai, cần xác nhận các rủi ro kỹ thuật sau đây (đã thống nhất với Lead):

### 1. Database Connections Limit
*   **Rủi ro**: 200-400 Database riêng biệt sẽ tạo áp lực connection cực lớn lên MySQL Service, có thể gây lỗi `Too many connections`.
*   **Giải pháp (Mitigation)**:
    *   Cấu hình MySQL `max_connections` cao (e.g., 2000+).
    *   Scale: Tách Server DB riêng (RDS/VPS Database chuyên dụng) khi đạt 50 tenants.

### 2. DevOps & Wildcard SSL
*   **Rủi ro**: Cấu hình SSL cho hàng trăm subdomains động (`*.domain.com`) phức tạp, dễ lỗi Certificate.
*   **Giải pháp**:
    *   Sử dụng **Caddy Server** (thay vì Nginx) để tự động hóa 100% việc cấp phát SSL cho domain mới (On-Demand TLS).
    *   Dev Local: Cấu hình `Laaragon` hoặc `dnsmasq` để routing `*.app.test` tự động.

### 3. Phức tạp trong Deployment (Migrations)
*   **Rủi ro**: Chạy `migrate` cho 400 tenants mất quá nhiều thời gian (>15 phút), rủi ro treo giữa chừng.
*   **Giải pháp**:
    *   Tuyệt đối KHÔNG chạy migrate foreground.
    *   Sử dụng **Database Queues** để chạy job `TenantsMigrateJob` trong background, update từng cụm tenant.

---
*Document approved for Phase 1 execution.*
