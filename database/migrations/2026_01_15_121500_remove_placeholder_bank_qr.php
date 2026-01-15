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
        // Remove the old placeholder "Tạo QR Ngân Hàng" with link '#'
        DB::table('mini_apps')
            ->where('name', 'Tạo QR Ngân Hàng')
            ->where('link', '#')
            ->delete();
            
        // Also safeguard remove any other placeholders if needed
        // (Optional, just sticking to the request)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No restore needed
    }
};
