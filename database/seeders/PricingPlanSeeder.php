<?php

namespace Database\Seeders;

use App\Models\PricingPlan;
use Illuminate\Database\Seeder;

class PricingPlanSeeder extends Seeder
{
    /**
     * Creates the reference plans once; plans edited in the admin are never overwritten.
     */
    public function run(): void
    {
        foreach ($this->plans() as $order => $plan) {
            PricingPlan::query()->firstOrCreate(
                ['group' => $plan['group'], 'name' => $plan['name']],
                $plan + ['order' => $order + 1, 'is_active' => true],
            );
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function plans(): array
    {
        return [
            ['group' => 'website', 'name' => 'Landing Page', 'price_prefix' => 'Từ', 'price' => '3.000.000đ',
                'features' => ['Thiết kế theo yêu cầu', 'Tối ưu hiển thị đa thiết bị', 'Tích hợp form, tracking', 'Bàn giao nhanh']],
            ['group' => 'website', 'name' => 'Website Cơ bản', 'price' => '12.000.000đ', 'is_featured' => true, 'badge' => 'Phổ biến nhất',
                'features' => ['Đầy đủ tính năng cơ bản', 'Chuẩn SEO, tốc độ cao', 'Dễ dàng quản trị', 'Hỗ trợ 12 tháng']],
            ['group' => 'website', 'name' => 'Website Nâng cao', 'price' => '16.000.000đ',
                'features' => ['Thiết kế theo thương hiệu', 'Tích hợp tính năng nâng cao', 'Tối ưu SEO & bảo mật', 'Hỗ trợ 12 tháng']],
            ['group' => 'website', 'name' => 'Website Cao cấp / WebApp', 'price_prefix' => 'Từ', 'price' => '22.000.000đ',
                'features' => ['Theo yêu cầu riêng', 'Tích hợp phần mềm, API', 'Bảo mật & hiệu năng cao', 'Hỗ trợ lâu dài']],
            ['group' => 'software', 'name' => 'CRM & Phần mềm theo yêu cầu', 'price' => 'Báo giá theo phạm vi', 'cta_label' => 'Nhận báo giá',
                'summary' => 'Báo giá theo phạm vi nghiệp vụ',
                'features' => ['Khảo sát quy trình thực tế', 'Phân quyền & quản lý dữ liệu', 'Tích hợp hệ thống theo nhu cầu', 'Bàn giao và đào tạo sử dụng']],
            ['group' => 'hosting', 'name' => 'Hosting Basic', 'price' => '1.500.000đ', 'price_suffix' => '/năm', 'cta_label' => 'Đăng ký ngay',
                'summary' => 'Phù hợp landing page, website nhỏ',
                'features' => ['Dung lượng 5GB SSD', 'Băng thông không giới hạn', 'SSL miễn phí', 'Backup định kỳ']],
            ['group' => 'hosting', 'name' => 'Hosting Business', 'price' => '2.500.000đ', 'price_suffix' => '/năm', 'cta_label' => 'Đăng ký ngay', 'is_featured' => true, 'badge' => 'Phổ biến nhất',
                'summary' => 'Phù hợp website doanh nghiệp',
                'features' => ['Dung lượng 15GB SSD', 'Băng thông không giới hạn', 'SSL miễn phí', 'Email doanh nghiệp (5 tài khoản)', 'Backup hằng ngày']],
            ['group' => 'hosting', 'name' => 'Hosting Pro', 'price' => '4.500.000đ', 'price_suffix' => '/năm', 'cta_label' => 'Đăng ký ngay',
                'summary' => 'Phù hợp website lớn, traffic cao',
                'features' => ['Dung lượng 30GB SSD', 'Băng thông không giới hạn', 'SSL miễn phí', 'Email doanh nghiệp (10 tài khoản)', 'Backup nhiều phiên bản']],
            ['group' => 'care', 'name' => 'Chăm sóc kỹ thuật', 'price_prefix' => 'Từ', 'price' => '500.000đ', 'price_suffix' => '/tháng',
                'summary' => 'Phù hợp website ít cập nhật nhưng cần hoạt động ổn định.',
                'features' => ['Hosting và backup', 'Kiểm tra định kỳ', 'Hỗ trợ lỗi kỹ thuật', 'Cập nhật nhỏ']],
            ['group' => 'care', 'name' => 'Quản trị nội dung', 'price_prefix' => 'Từ', 'price' => '1.500.000đ', 'price_suffix' => '/tháng',
                'summary' => 'Phù hợp doanh nghiệp có bài viết, sản phẩm và dự án mới.',
                'features' => ['Đăng nội dung định kỳ', 'Chuẩn hóa ảnh và bài', 'Cập nhật banner, dịch vụ', 'Báo cáo khối lượng']],
            ['group' => 'care', 'name' => 'Website tăng trưởng', 'price_prefix' => 'Từ', 'price' => '3.500.000đ', 'price_suffix' => '/tháng', 'is_featured' => true, 'badge' => 'Đầy đủ nhất',
                'summary' => 'Phù hợp doanh nghiệp muốn duy trì cả kỹ thuật, nội dung và SEO.',
                'features' => ['Chăm sóc kỹ thuật', 'Nội dung định kỳ', 'SEO và Search Console', 'Báo cáo và đề xuất tháng sau']],
            ['group' => 'addon', 'name' => 'Hosting Website', 'icon' => 'server', 'price_prefix' => 'Từ', 'price' => '500.000đ', 'price_suffix' => '/năm',
                'summary' => 'Tốc độ cao, ổn định, bảo mật dữ liệu, sao lưu định kỳ.'],
            ['group' => 'addon', 'name' => 'Tên miền & Email', 'icon' => 'globe', 'price_prefix' => 'Từ', 'price' => '350.000đ', 'price_suffix' => '/năm',
                'summary' => 'Đăng ký tên miền, email doanh nghiệp chuyên nghiệp.'],
            ['group' => 'addon', 'name' => 'Chăm sóc Website', 'icon' => 'shield-check', 'price_prefix' => 'Từ', 'price' => '500.000đ', 'price_suffix' => '/tháng',
                'summary' => 'Cập nhật nội dung, backup, kiểm tra bảo mật định kỳ.'],
            ['group' => 'addon', 'name' => 'CRM & Phần mềm riêng', 'icon' => 'chart-column', 'price' => 'Liên hệ báo giá',
                'summary' => 'Tư vấn và phát triển theo yêu cầu doanh nghiệp.'],
        ];
    }
}
