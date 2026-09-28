<?php

use App\Domain\Navigation\DefaultMenus;
use App\Support\FrontendMenuCache;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menu items link to a URL typed in the admin instead of a route name and
 * parameter. The seeded menus were never rendered (the header and footer
 * were hard-coded), so they are replaced by the navigation that is on the
 * site today.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('menu_items')->delete();
        DB::table('menus')->delete();

        Schema::table('menu_items', function (Blueprint $table): void {
            $table->dropColumn(['route_name', 'route_parameter', 'target']);
            $table->boolean('open_in_new_tab')->default(false)->after('icon');
        });

        DefaultMenus::install();
        app(FrontendMenuCache::class)->forgetAll();
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table): void {
            $table->dropColumn('open_in_new_tab');
            $table->string('route_name')->nullable();
            $table->string('route_parameter')->nullable();
            $table->string('target')->default('_self');
        });
    }
};
