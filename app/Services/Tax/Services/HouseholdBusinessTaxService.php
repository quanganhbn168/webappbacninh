<?php

namespace App\Services\Tax\Services;

use App\Services\Tax\Contracts\TaxServiceInterface;
use App\Services\Tax\DTO\TaxInput;
use App\Services\Tax\DTO\TaxResult;
use App\Services\Tax\Enums\BusinessSector;
use App\Services\Tax\Enums\TaxYear;

class HouseholdBusinessTaxService implements TaxServiceInterface
{
    /**
     * Revenue threshold exempt from tax (from 2026).
     */
    private const THRESHOLD_2026 = 500000000; // 500 million VND
    private const THRESHOLD_2025 = 100000000; // 100 million VND

    public function calculate(TaxInput $input): TaxResult
    {
        $threshold = $input->year === TaxYear::Y2026 
            ? self::THRESHOLD_2026 
            : self::THRESHOLD_2025;

        // If revenue below threshold, no tax
        if ($input->amount <= $threshold) {
            return TaxResult::zero($input->amount);
        }

        // Get sector rates
        $sector = $input->sector ?? BusinessSector::SERVICE;
        $vatRate = $sector->vatRate();
        $pitRate = $sector->pitRate();

        // Taxable revenue (amount above threshold)
        $taxableRevenue = $input->amount - $threshold;

        // Calculate taxes
        $vatTax = round($taxableRevenue * $vatRate);
        $pitTax = round($taxableRevenue * $pitRate);
        $totalTax = $vatTax + $pitTax;

        // Net revenue
        $netRevenue = $input->amount - $totalTax;

        // Effective rate
        $effectiveRate = round(($totalTax / $input->amount) * 100, 2);

        return new TaxResult(
            grossAmount: $input->amount,
            taxableAmount: $taxableRevenue,
            totalTax: $totalTax,
            netAmount: $netRevenue,
            effectiveRate: $effectiveRate,
            breakdown: [
                [
                    'type' => 'vat',
                    'label' => 'Thuế GTGT',
                    'amount' => $taxableRevenue,
                    'rate' => $vatRate * 100,
                    'tax' => $vatTax,
                ],
                [
                    'type' => 'pit',
                    'label' => 'Thuế TNCN',
                    'amount' => $taxableRevenue,
                    'rate' => $pitRate * 100,
                    'tax' => $pitTax,
                ],
            ],
            deductions: [
                'threshold' => $threshold,
                'exempt_amount' => $threshold,
            ],
            meta: [
                'year' => $input->year->value,
                'sector' => $sector->value,
                'sector_label' => $sector->label(),
                'threshold' => $threshold,
                'vat_rate' => $vatRate * 100,
                'pit_rate' => $pitRate * 100,
            ],
        );
    }

    public function getType(): string
    {
        return 'household_business';
    }

    public function getSupportedYears(): array
    {
        return [TaxYear::Y2025, TaxYear::Y2026];
    }

    /**
     * Get all available sectors for frontend.
     */
    public function getSectors(): array
    {
        return BusinessSector::toArray();
    }
}
