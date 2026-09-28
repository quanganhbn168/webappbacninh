<?php

namespace App\Domain\Pages;

/**
 * The fixed pages of the public site. Each page is a Blade view with its own route;
 * the admin edits only its SEO fields and hero banner (App\Models\Page, looked up by key).
 */
final class SitePages
{
    /**
     * key => [route name, default title (admin, breadcrumbs, <title>), default meta description].
     *
     * @var array<string, array{0: string, 1: string, 2: string}>
     */
    public const PAGES = [
        'home' => ['home', 'Trang chủ', 'WebApp Bắc Ninh thiết kế website, landing page, phát triển phần mềm, CRM, booking và đồng hành vận hành số cùng doanh nghiệp.'],
        'services' => ['services.overview', 'Dịch vụ', 'Dịch vụ thiết kế website, phần mềm doanh nghiệp, CRM & booking, SEO, quảng cáo, hosting và chăm sóc website tại Bắc Ninh.'],
        'website-design' => ['services.index', 'Thiết kế website', 'Thiết kế website doanh nghiệp, website bán hàng, landing page và website theo ngành. Giao diện phù hợp, dễ quản trị, SEO nền tảng và hỗ trợ lâu dài.'],
        'operations' => ['operations.index', 'Dịch vụ vận hành', 'Hosting, bảo trì, quản trị website, đăng bài, SEO, nội dung Facebook và nâng cấp chức năng theo nhu cầu doanh nghiệp.'],
        'hosting' => ['hosting', 'Hosting, tên miền và email', 'Hosting tốc độ cao, tên miền .vn/.com và email theo tên miền doanh nghiệp, cài đặt và hỗ trợ kỹ thuật trọn gói.'],
        'solutions' => ['solutions', 'Giải pháp', 'Giải pháp số theo ngành và theo bài toán: website, bán hàng, CRM, booking, quản lý nội bộ cho doanh nghiệp vừa và nhỏ.'],
        'products' => ['products', 'Sản phẩm', 'Website theo ngành, CRM, booking system và mini ERP đóng gói sẵn, triển khai nhanh và tùy biến theo doanh nghiệp.'],
        'themes' => ['themes.index', 'Kho giao diện', 'Kho giao diện website doanh nghiệp, bán hàng, landing page và website theo ngành. Lọc nhanh theo nhu cầu, lĩnh vực và mức đầu tư.'],
        'projects' => ['projects.index', 'Dự án', 'Các dự án website và phần mềm WebApp Bắc Ninh đã triển khai cho doanh nghiệp sản xuất, thương mại, giáo dục, du lịch.'],
        'pricing' => ['pricing', 'Bảng giá', 'Bảng giá thiết kế website, phần mềm, hosting và chăm sóc website minh bạch, tư vấn theo nhu cầu thực tế.'],
        'articles' => ['articles.index', 'Kiến thức', 'Bài viết thực dụng về thiết kế website, SEO nền tảng, hosting, bảo trì, quản trị nội dung và vận hành số.'],
        'tools' => ['tools.index', 'Công cụ miễn phí', 'Công cụ miễn phí: tính thuế TNCN, thuế hộ kinh doanh, thuế doanh nghiệp, tạo mã QR, lịch vạn niên, lấy ảnh cover video.'],
        'contact' => ['contact', 'Liên hệ', 'Liên hệ WebApp Bắc Ninh để tư vấn thiết kế website, phần mềm, hosting, bảo trì, SEO và hợp tác kỹ thuật.'],
        'about' => ['about', 'Giới thiệu', 'WebApp Bắc Ninh tập trung thiết kế website, đồng hành vận hành nội dung và triển khai kỹ thuật phù hợp doanh nghiệp nhỏ và vừa.'],
        'agency' => ['agency', 'Hợp tác Agency', 'Nhận triển khai website, landing page, bảo trì và module kỹ thuật theo hình thức giới thiệu khách, đồng triển khai hoặc white-label.'],
        'privacy' => ['legal.privacy', 'Chính sách bảo mật', 'Chính sách thu thập, sử dụng, lưu trữ và bảo vệ thông tin khách hàng khi sử dụng website WebApp Bắc Ninh.'],
        'terms' => ['legal.terms', 'Điều khoản sử dụng', 'Điều khoản sử dụng website, nội dung và dịch vụ của WebApp Bắc Ninh.'],
        'warranty' => ['legal.warranty', 'Chính sách bảo hành', 'Phạm vi bảo hành, hỗ trợ kỹ thuật và cách tiếp nhận yêu cầu sau khi bàn giao website, phần mềm.'],
        'payment-process' => ['legal.payment', 'Quy trình thanh toán', 'Các bước báo giá, ký hợp đồng, thanh toán theo giai đoạn và bàn giao dự án tại WebApp Bắc Ninh.'],
    ];

    public static function title(string $key): string
    {
        return self::PAGES[$key][1] ?? $key;
    }

    public static function description(string $key): string
    {
        return self::PAGES[$key][2] ?? '';
    }

    public static function url(string $key): ?string
    {
        return isset(self::PAGES[$key]) ? route(self::PAGES[$key][0]) : null;
    }
}
