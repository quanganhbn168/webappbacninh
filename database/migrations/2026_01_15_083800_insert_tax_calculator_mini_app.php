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
        // Insert Tax Calculator mini app
        DB::table('mini_apps')->insert([
            'name' => 'Tính thuế TNCN',
            'icon' => 'fas fa-calculator',
            'description' => 'Công cụ tính thuế thu nhập cá nhân theo Luật mới 2026 (5 bậc) và 2025 (7 bậc)',
            'link' => '/tinh-thue-tncn',
            'badge' => 'Mới',
            'is_active' => true,
            'order' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('mini_apps')->where('link', '/tinh-thue-tncn')->delete();
    }
};
