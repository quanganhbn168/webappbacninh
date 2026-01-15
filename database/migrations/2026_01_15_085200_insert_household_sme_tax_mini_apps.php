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
        // Update TNCN mini app icon
        DB::table('mini_apps')
            ->where('link', '/tinh-thue-tncn')
            ->update([
                'icon' => 'fas fa-user-tie',
                'badge' => null,
                'updated_at' => now(),
            ]);

        // Insert Household Business Tax mini app
        DB::table('mini_apps')->insert([
            'name' => 'Tính thuế Hộ KD',
            'icon' => 'fas fa-store',
            'description' => 'Tính thuế GTGT và TNCN cho hộ kinh doanh theo ngành nghề (2026)',
            'link' => '/tinh-thue-ho-kinh-doanh',
            'badge' => 'Mới',
            'is_active' => true,
            'order' => 6,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert SME Tax mini app
        DB::table('mini_apps')->insert([
            'name' => 'Tính thuế DNNVV',
            'icon' => 'fas fa-building',
            'description' => 'Tính thuế TNDN cho doanh nghiệp nhỏ và vừa (15%/17%/20%)',
            'link' => '/tinh-thue-doanh-nghiep',
            'badge' => 'Mới',
            'is_active' => true,
            'order' => 7,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('mini_apps')->where('link', '/tinh-thue-ho-kinh-doanh')->delete();
        DB::table('mini_apps')->where('link', '/tinh-thue-doanh-nghiep')->delete();
    }
};
