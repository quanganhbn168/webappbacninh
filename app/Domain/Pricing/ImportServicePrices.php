<?php

namespace App\Domain\Pricing;

use App\Models\PricingPlan;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;

/**
 * Turns the prices once typed on a service (its packages, or just "Giá từ") into
 * price plans of that service. Used to move existing data and to seed new installs.
 */
class ImportServicePrices
{
    /** Where the shared price table already had a plan for the same offer: its price wins over the package. */
    private const SHARED = [
        'landing-page' => ['plan' => 'Landing Page', 'replaces' => 'Landing cơ bản'],
    ];

    /** @param  array<int, array<string, mixed>>  $packages */
    public function execute(Model $service, array $packages, ?string $priceFrom = null): void
    {
        if (PricingPlan::query()->whereMorphedTo('priceable', $service)->exists()) {
            return;
        }

        $shared = $service instanceof Service ? (self::SHARED[$service->slug] ?? null) : null;
        $sharedPlan = $shared === null ? null : PricingPlan::query()
            ->where('group', 'website')->where('name', $shared['plan'])->whereNull('priceable_id')->first();
        $sharedPlan?->priceable()->associate($service)->save();

        if ($packages === [] && filled($priceFrom)) {
            $packages = [['name' => (string) $service->title, 'price' => $priceFrom]];
        }

        foreach (array_values($packages) as $index => $package) {
            if (blank($package['name'] ?? null) || blank($package['price'] ?? null)) {
                continue;
            }

            $featured = ! empty($package['featured']);

            PricingPlan::query()->create($this->price((string) $package['price']) + [
                'priceable_type' => $service->getMorphClass(),
                'priceable_id' => $service->getKey(),
                'show_in_table' => false,
                'name' => $package['name'],
                'summary' => $package['desc'] ?? null,
                'features' => array_values($package['items'] ?? []),
                'is_featured' => $featured,
                'badge' => $featured ? 'Được quan tâm' : null,
                'cta_label' => 'Nhận báo giá',
                // After the shared plans, so a linked one stays first and gives "Giá từ".
                'order' => 100 + $index,
                // The replaced package is kept, hidden, for the admin to review.
                'is_active' => ! ($sharedPlan && $package['name'] === $shared['replaces']),
            ]);
        }
    }

    /**
     * "Từ 500.000đ/tháng" becomes prefix, price and suffix, as the price table stores them.
     *
     * @return array{price_prefix: ?string, price: string, price_suffix: ?string}
     */
    private function price(string $text): array
    {
        $parts = ['price_prefix' => null, 'price' => trim($text), 'price_suffix' => null];

        if (preg_match('~^(?:(Từ)\s+)?(\d.*?)(/(?:tháng|năm))?$~u', $parts['price'], $match)) {
            $parts = ['price_prefix' => $match[1] ?: null, 'price' => $match[2], 'price_suffix' => $match[3] ?? null];
        }

        $parts['price'] = mb_substr($parts['price'], 0, 60);

        return $parts;
    }
}
