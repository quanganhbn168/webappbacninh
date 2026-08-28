<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('seo.page_meta', [
            'home' => [
                'title' => 'Thiết kế website tại Bắc Ninh | WebApp Bắc Ninh',
                'description' => 'WebApp Bắc Ninh thiết kế website doanh nghiệp, website bán hàng, landing page và đồng hành hosting, bảo trì, đăng bài, SEO, nội dung Facebook sau bàn giao.',
            ],
            'about' => [
                'title' => 'Giới thiệu WebApp Bắc Ninh',
                'description' => 'WebApp Bắc Ninh tập trung thiết kế website, đồng hành vận hành nội dung và triển khai kỹ thuật phù hợp doanh nghiệp nhỏ và vừa.',
            ],
            'contact' => [
                'title' => 'Liên hệ tư vấn website | WebApp Bắc Ninh',
                'description' => 'Liên hệ WebApp Bắc Ninh để tư vấn thiết kế website, kho giao diện, hosting, bảo trì, SEO, nội dung và hợp tác kỹ thuật.',
            ],
            'pricing' => [
                'title' => 'Bảng giá thiết kế website và vận hành | WebApp Bắc Ninh',
                'description' => 'Tham khảo các gói thiết kế website, hosting, bảo trì, đăng bài, SEO và vận hành nội dung của WebApp Bắc Ninh.',
            ],
            'agency' => [
                'title' => 'Hợp tác kỹ thuật và gia công website cho Agency | WebApp Bắc Ninh',
                'description' => 'Nhận triển khai website, landing page, bảo trì và module kỹ thuật theo hình thức giới thiệu khách, đồng triển khai hoặc white-label.',
            ],
            'services' => [
                'title' => 'Dịch vụ thiết kế website tại Bắc Ninh | WebApp Bắc Ninh',
                'description' => 'Thiết kế website doanh nghiệp, website bán hàng, landing page và website theo ngành tại Bắc Ninh. Giao diện phù hợp, dễ quản trị, SEO nền tảng và hỗ trợ lâu dài.',
            ],
            'themes' => [
                'title' => 'Kho giao diện website theo ngành | WebApp Bắc Ninh',
                'description' => 'Khám phá kho giao diện website doanh nghiệp, bán hàng, landing page và website theo ngành. Lọc nhanh theo nhu cầu, lĩnh vực và mức đầu tư.',
            ],
            'projects' => [
                'title' => 'Dự án website và phần mềm đã triển khai | WebApp Bắc Ninh',
                'description' => 'Tham khảo các dự án website doanh nghiệp, website bán hàng, landing page, website giáo dục, du lịch và hệ thống quản lý của WebApp Bắc Ninh.',
            ],
            'articles' => [
                'title' => 'Kiến thức website, SEO và vận hành | WebApp Bắc Ninh',
                'description' => 'Bài viết thực dụng về thiết kế website, SEO nền tảng, hosting, bảo trì, quản trị nội dung và vận hành số cho doanh nghiệp.',
            ],
            'operations' => [
                'title' => 'Dịch vụ vận hành website, SEO và nội dung | WebApp Bắc Ninh',
                'description' => 'Hosting, bảo trì, quản trị website, đăng bài, SEO, nội dung Facebook và nâng cấp chức năng theo nhu cầu doanh nghiệp.',
            ],
        ]);
    }

    public function down(): void
    {
        $this->migrator->delete('seo.page_meta');
    }
};
