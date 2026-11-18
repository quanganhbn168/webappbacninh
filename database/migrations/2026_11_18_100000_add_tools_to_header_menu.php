<?php

use App\Models\Menu;
use App\Support\FrontendMenuCache;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/** Adds "Công cụ" to the header menu of existing installs, just before Blog. */
return new class extends Migration
{
    public function up(): void
    {
        $menu = DB::table('menus')->where('location', Menu::HEADER)->first();
        if (! $menu || DB::table('menu_items')->where('menu_id', $menu->id)->where('url', '/cong-cu')->exists()) {
            return;
        }

        $blog = DB::table('menu_items')->where('menu_id', $menu->id)->whereNull('parent_id')->where('url', '/kien-thuc')->first();
        $position = $blog->position ?? (int) DB::table('menu_items')->where('menu_id', $menu->id)->whereNull('parent_id')->max('position') + 1;

        if ($blog) {
            DB::table('menu_items')->where('menu_id', $menu->id)->whereNull('parent_id')->where('position', '>=', $position)->increment('position');
        }

        DB::table('menu_items')->insert([
            'menu_id' => $menu->id, 'parent_id' => null, 'title' => 'Công cụ', 'url' => '/cong-cu',
            'position' => $position, 'is_active' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);
        app(FrontendMenuCache::class)->forgetAll();
    }

    public function down(): void {}
};
