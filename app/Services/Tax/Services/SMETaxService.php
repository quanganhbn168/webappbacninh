<?php

namespace App\Services\Tax\Services;

use App\Services\Tax\Contracts\TaxServiceInterface;
use App\Services\Tax\DTO\TaxInput;
use App\Services\Tax\DTO\TaxResult;
use App\Services\Tax\Enums\TaxYear;

class SMETaxService implements TaxServiceInterface
{
    /**
     * Corporate Income Tax rates by revenue tier (from 2025).
     */
    private const CIT_RATES = [
        ['max_revenue' => 3000000000, 'rate' => 0.15],     // ≤ 3 tỷ: 15%
        ['max_revenue' => 50000000000, 'rate' => 0.17],   // 3-50 tỷ: 17%
        ['max_revenue' => PHP_INT_MAX, 'rate' => 0.20],   // > 50 tỷ: 20%
    ];

    /**
     * VAT rate (reduced 2% until end of 2026).
     */
    private const VAT_RATE_REDUCED = 0.08; // 8% (giảm 2%)
    private const VAT_RATE_NORMAL = 0.10;  // 10%

    /**
     * Years exempted for new businesses.
     */
    private const NEW_BUSINESS_EXEMPT_YEARS = 3;

    public function calculate(TaxInput $input): TaxResult
    {
        $revenue = $input->amount;
        $expenses = $input->expenses ?? ($revenue * 0.7); // Default 70% expense ratio if not provided
        $profit = max(0, $revenue - $expenses);

        // Check new business exemption
        if ($input->isNewBusiness && $input->yearsInBusiness < self::NEW_BUSINESS_EXEMPT_YEARS) {
            return $this->createExemptResult($input, $profit, $expenses);
        }

        // Determine CIT rate based on previous year revenue
        $citRate = $this->getCITRate($revenue);
        $citTax = round($profit * $citRate);

        // VAT calculation (on revenue, reduced rate until 2026)
        $vatRate = $input->year === TaxYear::Y2026 ? self::VAT_RATE_REDUCED : self::VAT_RATE_REDUCED;
        $vatTax = round($revenue * $vatRate);

        // Total tax
        $totalTax = $citTax + $vatTax;

        // Net profit
        $netProfit = $profit - $citTax;

        // Effective rate on revenue
        $effectiveRate = $revenue > 0 ? round(($totalTax / $revenue) * 100, 2) : 0;

        return new TaxResult(
            grossAmount: $revenue,
            taxableAmount: $profit,
            totalTax: $totalTax,
            netAmount: $netProfit,
            effectiveRate: $effectiveRate,
            breakdown: [
                [
                    'type' => 'cit',
                    'label' => 'Thuế TNDN',
                    'amount' => $profit,
                    'rate' => $citRate * 100,
                    'tax' => $citTax,
                ],
                [
                    'type' => 'vat',
                    'label' => 'Thuế GTGT (ước tính)',
                    'amount' => $revenue,
                    'rate' => $vatRate * 100,
                    'tax' => $vatTax,
                ],
            ],
            deductions: [
                'expenses' => $expenses,
                'profit' => $profit,
            ],
            meta: [
                'year' => $input->year->value,
                'cit_rate' => $citRate * 100,
                'vat_rate' => $vatRate * 100,
                'revenue_tier' => $this->getRevenueTierLabel($revenue),
                'is_new_business' => $input->isNewBusiness,
                'years_in_business' => $input->yearsInBusiness,
            ],
        );
    }

    /**
     * Create result for exempt new business.
     */
    private function createExemptResult(TaxInput $input, float $profit, float $expenses): TaxResult
    {
        $vatRate = self::VAT_RATE_REDUCED;
        $vatTax = round($input->amount * $vatRate);

        return new TaxResult(
            grossAmount: $input->amount,
            taxableAmount: $profit,
            totalTax: $vatTax, // Only VAT, no CIT
            netAmount: $profit,
            effectiveRate: $input->amount > 0 ? round(($vatTax / $input->amount) * 100, 2) : 0,
            breakdown: [
                [
                    'type' => 'cit',
                    'label' => 'Thuế TNDN',
                    'amount' => $profit,
                    'rate' => 0,
                    'tax' => 0,
                    'note' => 'Miễn thuế 3 năm đầu',
                ],
                [
                    'type' => 'vat',
                    'label' => 'Thuế GTGT (ước tính)',
                    'amount' => $input->amount,
                    'rate' => $vatRate * 100,
                    'tax' => $vatTax,
                ],
            ],
            deductions: [
                'expenses' => $expenses,
                'profit' => $profit,
            ],
            meta: [
                'year' => $input->year->value,
                'cit_rate' => 0,
                'vat_rate' => $vatRate * 100,
                'is_new_business' => true,
                'years_in_business' => $input->yearsInBusiness,
                'exempt_years_remaining' => self::NEW_BUSINESS_EXEMPT_YEARS - $input->yearsInBusiness,
                'message' => 'Doanh nghiệp mới được miễn thuế TNDN trong 3 năm đầu',
            ],
        );
    }

    /**
     * Get CIT rate based on revenue.
     */
    private function getCITRate(float $revenue): float
    {
        foreach (self::CIT_RATES as $tier) {
            if ($revenue <= $tier['max_revenue']) {
                return $tier['rate'];
            }
        }
        return 0.20; // Default to 20%
    }

    /**
     * Get human-readable revenue tier label.
     */
    private function getRevenueTierLabel(float $revenue): string
    {
        if ($revenue <= 3000000000) {
            return 'Doanh nghiệp siêu nhỏ (≤ 3 tỷ)';
        } elseif ($revenue <= 50000000000) {
            return 'Doanh nghiệp nhỏ (3 - 50 tỷ)';
        } else {
            return 'Doanh nghiệp vừa (> 50 tỷ)';
        }
    }

    public function getType(): string
    {
        return 'sme';
    }

    public function getSupportedYears(): array
    {
        return [TaxYear::Y2025, TaxYear::Y2026];
    }
}
