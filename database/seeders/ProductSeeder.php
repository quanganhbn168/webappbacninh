<?php

namespace Database\Seeders;

use App\Domain\Media\Actions\ImportLocalImage;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProductSeeder extends Seeder
{
    /**
     * Creates the sample products once; products edited in the admin are never overwritten.
     */
    public function run(ImportLocalImage $images): void
    {
        foreach ($this->products() as $order => $row) {
            if (Product::query()->where('name', $row['name'])->exists()) {
                continue;
            }

            Product::query()->create(Arr::except($row, 'image') + [
                'image_id' => $images->execute('frontend/images/'.$row['image'], 'products', $row['name'])?->id,
                'order' => $order + 1,
                'is_active' => true,
            ]);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function products(): array
    {
        return [
            ['name' => 'Website Spa & Academy', 'group' => 'website', 'image' => 'spa.webp', 'is_featured' => true, 'detail_url' => '/kho-giao-dien', 'tags' => ['Website', 'Booking', 'Thành viên'],
                'summary' => 'Website giới thiệu dịch vụ spa, đặt lịch online, quản lý khóa học và học viên chuyên nghiệp.'],
            ['name' => 'Website Du lịch', 'group' => 'website', 'image' => 'travel.webp', 'is_featured' => true, 'detail_url' => '/kho-giao-dien', 'tags' => ['Website', 'Booking', 'Thanh toán'],
                'summary' => 'Website đặt tour, phòng khách sạn, quản lý booking, thanh toán online hiện đại.'],
            ['name' => 'Website Nội thất', 'group' => 'website', 'image' => 'interior.webp', 'is_featured' => true, 'detail_url' => '/kho-giao-dien', 'tags' => ['Website', 'Sản phẩm', 'Bán hàng'],
                'summary' => 'Website giới thiệu sản phẩm nội thất, quản lý danh mục, báo giá và đặt hàng trực tuyến.'],
            ['name' => 'Website Doanh nghiệp', 'group' => 'website', 'image' => 'company.webp', 'is_featured' => false, 'detail_url' => '/thiet-ke-website/website-doanh-nghiep', 'tags' => ['Website', 'Giới thiệu', 'Tin tức'],
                'summary' => 'Website giới thiệu công ty, dịch vụ, dự án, tin tức giúp nâng cao thương hiệu và uy tín doanh nghiệp.'],
            ['name' => 'Website PCCC', 'group' => 'website', 'image' => 'pccc.webp', 'is_featured' => true, 'detail_url' => '/kho-giao-dien', 'tags' => ['Website', 'Sản phẩm', 'Liên hệ'],
                'summary' => 'Website giới thiệu sản phẩm, dịch vụ PCCC, tư vấn giải pháp và nhận báo giá nhanh chóng.'],
            ['name' => 'CRM Khách hàng', 'group' => 'crm', 'image' => 'crm.webp', 'is_featured' => true, 'detail_url' => '/giai-phap', 'tags' => ['CRM', 'Marketing', 'Báo cáo'],
                'summary' => 'Phần mềm quản lý khách hàng, chăm sóc tự động, theo dõi cơ hội và doanh số hiệu quả.'],
            ['name' => 'Booking System', 'group' => 'booking', 'image' => 'booking.webp', 'is_featured' => true, 'detail_url' => '/giai-phap', 'tags' => ['Booking', 'Lịch hẹn', 'Thanh toán'],
                'summary' => 'Hệ thống đặt lịch, đặt phòng, đặt dịch vụ trực tuyến, quản lý lịch hẹn và khách hàng.'],
            ['name' => 'Mini ERP', 'group' => 'erp', 'image' => 'erp.webp', 'is_featured' => true, 'detail_url' => '/giai-phap', 'tags' => ['ERP', 'Bán hàng', 'Kho hàng'],
                'summary' => 'Phần mềm quản lý tổng thể: bán hàng, kho, nhân sự, tài chính... phù hợp doanh nghiệp vừa và nhỏ.'],
            ['name' => 'Phần mềm theo yêu cầu', 'group' => 'custom', 'image' => 'custom.webp', 'is_featured' => false, 'detail_url' => '/dich-vu#phan-mem', 'tags' => ['Tùy chỉnh', 'API', 'Tích hợp'],
                'summary' => 'Phát triển phần mềm theo đặc thù nghiệp vụ, tích hợp hệ thống và mở rộng tính năng linh hoạt.'],
        ];
    }
}
