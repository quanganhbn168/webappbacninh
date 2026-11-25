<?php

use Database\Seeders\ServiceSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The service cards on the home and services pages come from the services
 * instead of a list written in the view. The cards shown until now are switched
 * on, as is every other landing page outside website design.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('services', 'show_in_grid')) {
            Schema::table('services', function (Blueprint $table): void {
                $table->boolean('show_in_grid')->default(false)->after('is_active');
                $table->string('card_title', 60)->nullable()->after('show_in_grid');
                $table->string('card_text', 120)->nullable()->after('card_title');
            });
        }

        DB::table('services')->where('is_landing', true)->where('section', 'general')->update(['show_in_grid' => true]);

        foreach (ServiceSeeder::CARDS as $slug => $card) {
            DB::table('services')->where('slug', $slug)->update($card + ['show_in_grid' => true]);
        }
    }

    public function down(): void
    {
        Schema::table('services', fn (Blueprint $table) => $table->dropColumn(['show_in_grid', 'card_title', 'card_text']));
    }
};
