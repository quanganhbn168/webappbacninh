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
        // 1. Fix the original QR Code tool (Generic)
        // Ensure it has the correct name, icon, and description
        DB::table('mini_apps')
            ->where('link', '/tools/qr-code')
            ->update([
                'name' => 'Tạo QR Code',
                'icon' => 'fas fa-qrcode',
                'description' => 'Tạo mã QR cho Link, Văn bản, Wifi nhanh chóng.',
                'badge' => null, // Remove any 'Hot' or 'Mới' if leftovers exist/confusing
                'updated_at' => now(),
            ]);

        // 2. Fix the new Bank QR tool (VietQR)
        // Ensure it exists and has the correct details
        DB::table('mini_apps')
            ->where('link', '/tools/ngan-hang')
            ->update([
                'name' => 'Tạo QR Ngân Hàng',
                'icon' => 'fas fa-university',
                'description' => 'Tạo mã QR chuyển khoản VietQR chuẩn xác, kèm logo ngân hàng.',
                'badge' => 'Hot',
                'updated_at' => now(),
            ]);
            
        // 3. Cleanup: In case there are still multiple entries for the SAME link
        // We'll group by link and keep the max id
        $links = ['/tools/qr-code', '/tools/ngan-hang'];
        
        foreach ($links as $link) {
            $ids = DB::table('mini_apps')
                ->where('link', $link)
                ->pluck('id')
                ->toArray();
                
            if (count($ids) > 1) {
                // Sort desc
                rsort($ids);
                // Keep the first (largest ID), delete the rest
                $keep = array_shift($ids);
                
                if (!empty($ids)) {
                    DB::table('mini_apps')
                        ->whereIn('id', $ids)
                        ->delete();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No specific rollback needed
    }
};
