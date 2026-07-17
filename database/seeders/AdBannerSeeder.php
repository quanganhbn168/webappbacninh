<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ad_banners')->updateOrInsert(
            ['slot' => 'homepage_promo', 'name' => 'Summer Promotion'],
            [
                'image' => 'https://source.unsplash.com/random/1920x600?tech',
                'link' => '#',
                'alt_text' => 'Khuyến mãi Mùa Hè Sôi Động',
                'order' => 1,
                'is_active' => true,
                'open_new_tab' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
