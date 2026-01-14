<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $apps = [
            [
                'name' => 'Web Builder Pro',
                'icon' => 'fas fa-layer-group',
                'description' => 'Tạo website bán hàng, giới thiệu công ty tự động. Tích hợp sẵn giao diện Mobile.',
                'link' => '#register-section',
                'badge' => 'HOT',
                'is_active' => true,
                'order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tải Ảnh Cover',
                'icon' => 'fas fa-image',
                'description' => 'Công cụ tải ảnh cover Shopee/Lazada chất lượng cao, hỗ trợ tải hàng loạt.',
                'link' => 'tools/cover', 
                'badge' => 'Free',
                'is_active' => true,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tính Thuế Online',
                'icon' => 'fas fa-calculator',
                'description' => 'Công cụ tính thuế TNCN, BHXH dành cho kế toán và nhân viên văn phòng.',
                'link' => '#',
                'badge' => 'Coming Soon',
                'is_active' => true,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tạo QR Ngân Hàng',
                'icon' => 'fas fa-qrcode',
                'description' => 'Tạo mã QR chuyển khoản VietQR nhanh chóng, in dán ngay tại quầy.',
                'link' => '#',
                'badge' => null,
                'is_active' => true,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AI Viết Content',
                'icon' => 'fas fa-robot',
                'description' => 'Trợ lý AI giúp viết bài đăng Facebook, mô tả sản phẩm tự động.',
                'link' => '#',
                'badge' => 'Premium',
                'is_active' => true,
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hỗ trợ 24/7',
                'icon' => 'fas fa-headset',
                'description' => 'Đội ngũ kỹ thuật viên người Bắc Ninh hỗ trợ trực tiếp, không qua tổng đài.',
                'link' => '#',
                'badge' => null,
                'is_active' => true,
                'order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Ensure we don't duplicate if run multiple times (though migrations run once)
        // But for safety, we can check or use insertOrIgnore if supported DB, 
        // or just insert since migration table tracks execution.
        DB::table('mini_apps')->insert($apps);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('mini_apps')->whereIn('name', [
            'Web Builder Pro',
            'Tải Ảnh Cover',
            'Tính Thuế Online',
            'Tạo QR Ngân Hàng',
            'AI Viết Content',
            'Hỗ trợ 24/7'
        ])->delete();
    }
};
