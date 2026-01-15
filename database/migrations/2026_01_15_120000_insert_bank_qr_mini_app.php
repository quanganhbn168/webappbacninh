<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert Bank QR mini app
        DB::table('mini_apps')->insert([
            'name' => 'Tạo QR Ngân Hàng',
            'icon' => 'fas fa-university',
            'description' => 'Tạo QR chuyển khoản VietQR tự động, chính xác cho mọi ngân hàng.',
            'link' => '/tools/ngan-hang',
            'badge' => 'Hot',
            'is_active' => true,
            'order' => 8,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Update generic QR app description/name if needed
        DB::table('mini_apps')->where('link', '/tools/qr-code')->update([
            'name' => 'Tạo QR Code',
            'description' => 'Tạo mã QR cho Link, Văn bản, Wifi...',
            'badge' => null,
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('mini_apps')->where('link', '/tools/ngan-hang')->delete();
    }
};
