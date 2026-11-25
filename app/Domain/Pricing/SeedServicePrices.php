<?php

namespace App\Domain\Pricing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Prices of a freshly seeded service. An earlier migration seeds some services before
 * price plans can belong to one: their prices wait in the old columns until
 * the migration that moves every price into the table.
 */
class SeedServicePrices
{
    public function __construct(private readonly ImportServicePrices $import) {}

    /** @param  array<string, mixed>  $row */
    public function execute(Model $service, array $row): void
    {
        if (Schema::hasColumn('pricing_plans', 'priceable_id')) {
            $this->import->execute($service, $row['packages'] ?? [], $row['price_from'] ?? null);

            return;
        }

        DB::table($service->getTable())->where('id', $service->getKey())->update([
            'price_from' => $row['price_from'] ?? null,
            'packages' => json_encode($row['packages'] ?? [], JSON_UNESCAPED_UNICODE),
        ]);
    }
}
