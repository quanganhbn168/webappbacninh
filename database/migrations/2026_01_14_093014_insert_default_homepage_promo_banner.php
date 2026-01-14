<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Enums\BannerSlot; // We can't really use Enums easily in migrations normally unless autoloaded, but simple string is safer for raw SQL migrations.

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw string for slot to avoid dependency issues if Enum changes or class loading issues
        $slotName = 'homepage_promo'; 

        DB::table('ad_banners')->insert([
            'name' => 'Summer Promotion',
            'slot' => $slotName,
            'image' => 'https://source.unsplash.com/random/1920x600?tech',
            'link' => '#',
            'alt_text' => 'Khuyến mãi Mùa Hè Sôi Động',
            'order' => 1,
            'is_active' => true,
            'open_new_tab' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('ad_banners')->where('name', 'Summer Promotion')->where('slot', 'homepage_promo')->delete();
    }
};
