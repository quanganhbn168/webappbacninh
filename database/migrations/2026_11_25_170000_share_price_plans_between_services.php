<?php

use Database\Seeders\PricingPlanSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Prices are typed in one place, the price table, and a service picks the plans
 * its page shows; one plan can serve several services. Only the website and
 * landing page offers are split into plans so far: everything else asks visitors
 * to get in touch. The old price columns on services are no longer read.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('pricing_plans', 'show_in_table')) {
            Schema::table('pricing_plans', fn (Blueprint $table) => $table->boolean('show_in_table')->default(true)->after('group'));
        }
        // A plan shown only on service pages belongs to no tab of the table.
        Schema::table('pricing_plans', fn (Blueprint $table) => $table->string('group', 30)->nullable()->change());

        if (! Schema::hasTable('priceables')) {
            Schema::create('priceables', function (Blueprint $table): void {
                $table->foreignId('pricing_plan_id')->constrained()->cascadeOnDelete();
                $table->morphs('priceable');
                $table->unique(['pricing_plan_id', 'priceable_type', 'priceable_id'], 'priceables_unique');
            });
        }

        // A first version gave each plan one service and copied every package into the table.
        if (Schema::hasColumn('pricing_plans', 'priceable_id')) {
            DB::table('pricing_plans')->where('show_in_table', false)->whereNotNull('priceable_id')->delete();
            Schema::table('pricing_plans', fn (Blueprint $table) => $table->dropMorphs('priceable'));
        }

        DB::table('pricing_plans')->whereIn('group', ['care', 'software'])
            ->update(['price' => 'Liên hệ', 'price_prefix' => null, 'price_suffix' => null]);

        // The landing packages keep the wording the admin gave them, at the new prices.
        $packages = DB::table('services')->where('slug', 'landing-page')->value('packages');
        (new PricingPlanSeeder)->linkServices(json_decode((string) $packages, true) ?: []);
    }

    public function down(): void {}
};
