<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MiniAppSeeder extends Seeder
{
    public function run(): void
    {
        $apps = [
            [
                'name' => 'Web Builder Pro',
                'icon' => 'fas fa-layer-group',
                'description' => 'Tạo website bán hàng, giới thiệu công ty tự động. Tích hợp sẵn giao diện Mobile.',
                'link' => '#register-section',
                'badge' => 'HOT',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Tải Ảnh Cover',
                'icon' => 'fas fa-image',
                'description' => 'Công cụ tải ảnh cover Shopee/Lazada chất lượng cao, hỗ trợ tải hàng loạt.',
                'link' => 'tools/cover', 
                'badge' => 'Free',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Tính thuế TNCN',
                'icon' => 'fas fa-user-tie',
                'description' => 'Công cụ tính thuế thu nhập cá nhân theo Luật mới 2026 (5 bậc) và 2025 (7 bậc)',
                'link' => '/tinh-thue-tncn',
                'badge' => null,
                'is_active' => true,
                'order' => 3,
            ],
             [
                'name' => 'Tính thuế Hộ KD',
                'icon' => 'fas fa-store',
                'description' => 'Tính thuế GTGT và TNCN cho hộ kinh doanh theo ngành nghề (2026)',
                'link' => '/tinh-thue-ho-kinh-doanh',
                'badge' => 'Mới',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'Tính thuế DNNVV',
                'icon' => 'fas fa-building',
                'description' => 'Tính thuế TNDN cho doanh nghiệp nhỏ và vừa (15%/17%/20%)',
                'link' => '/tinh-thue-doanh-nghiep',
                'badge' => 'Mới',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'name' => 'Tạo QR Ngân Hàng',
                'icon' => 'fas fa-university',
                'description' => 'Tạo mã QR chuyển khoản VietQR chuẩn xác, kèm logo ngân hàng.',
                'link' => '/tools/ngan-hang',
                'badge' => 'HOT', // Kept HOT as per fix migration
                'is_active' => true,
                'order' => 6,
            ],
            [
                'name' => 'Tạo QR Code',
                'icon' => 'fas fa-qrcode',
                'description' => 'Tạo mã QR cho Link, Văn bản, Wifi nhanh chóng.',
                'link' => '/tools/qr-code',
                'badge' => null,
                'is_active' => true,
                'order' => 7,
            ],
            [
                'name' => 'AI Viết Content',
                'icon' => 'fas fa-robot',
                'description' => 'Trợ lý AI giúp viết bài đăng Facebook, mô tả sản phẩm tự động.',
                'link' => '#',
                'badge' => 'Premium',
                'is_active' => true,
                'order' => 8,
            ],
            [
                'name' => 'Hỗ trợ 24/7',
                'icon' => 'fas fa-headset',
                'description' => 'Đội ngũ kỹ thuật viên người Bắc Ninh hỗ trợ trực tiếp, không qua tổng đài.',
                'link' => '#',
                'badge' => null,
                'is_active' => true,
                'order' => 9,
            ],
        ];

        foreach ($apps as $app) {
            DB::table('mini_apps')->updateOrInsert(
                ['link' => $app['link']],
                array_merge($app, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
