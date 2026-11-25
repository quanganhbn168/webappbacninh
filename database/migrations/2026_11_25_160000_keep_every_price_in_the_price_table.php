<?php

use App\Domain\Pricing\ImportServicePrices;
use App\Models\OperationService;
use App\Models\Service;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Prices are typed in one place, the price table. A plan may belong to a service,
 * whose page then shows it, and may or may not appear in the shared table. The
 * packages and "Giá từ" of every service move there; the old columns are no longer
 * read but stay as they were.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('pricing_plans', 'priceable_id')) {
            Schema::table('pricing_plans', function (Blueprint $table): void {
                $table->nullableMorphs('priceable');
                $table->boolean('show_in_table')->default(true)->after('group');
                // A plan shown only on its service page belongs to no tab of the table.
                $table->string('group', 30)->nullable()->change();
            });
        }

        foreach ([Service::class, OperationService::class] as $model) {
            $model::query()->orderBy('id')->get()->each(function ($service): void {
                $row = DB::table($service->getTable())->where('id', $service->id)->first(['price_from', 'packages']);

                app(ImportServicePrices::class)->execute($service, json_decode((string) $row->packages, true) ?: [], $row->price_from);
            });
        }
    }

    public function down(): void {}
};
