<?php

namespace App\Services\Tax\Services;

use App\Services\Tax\Contracts\TaxServiceInterface;
use App\Services\Tax\DTO\TaxInput;
use App\Services\Tax\DTO\TaxResult;
use App\Services\Tax\Enums\TaxYear;

class PersonalIncomeTaxService implements TaxServiceInterface
{
    /**
     * Tax brackets for 2026 (5 levels) - Luật 109/2025/QH15
     */
    private const BRACKETS_2026 = [
        ['threshold' => 10000000, 'rate' => 0.05],
        ['threshold' => 30000000, 'rate' => 0.10],
        ['threshold' => 60000000, 'rate' => 0.20],
        ['threshold' => 100000000, 'rate' => 0.30],
        ['threshold' => PHP_INT_MAX, 'rate' => 0.35],
    ];

    /**
     * Tax brackets for 2025 (7 levels) - Old law
     */
    private const BRACKETS_2025 = [
        ['threshold' => 5000000, 'rate' => 0.05],
        ['threshold' => 10000000, 'rate' => 0.10],
        ['threshold' => 18000000, 'rate' => 0.15],
        ['threshold' => 32000000, 'rate' => 0.20],
        ['threshold' => 52000000, 'rate' => 0.25],
        ['threshold' => 80000000, 'rate' => 0.30],
        ['threshold' => PHP_INT_MAX, 'rate' => 0.35],
    ];

    /**
     * Personal deductions by year.
     */
    private const DEDUCTIONS = [
        '2026' => ['personal' => 15500000, 'dependent' => 6200000],
        '2025' => ['personal' => 11000000, 'dependent' => 4400000],
    ];

    /**
     * Default insurance rate (BHXH 8% + BHYT 1.5% + BHTN 1%)
     */
    private const DEFAULT_INSURANCE_RATE = 0.105;

    public function calculate(TaxInput $input): TaxResult
    {
        $year = $input->year->value;
        $deductionConfig = self::DEDUCTIONS[$year];

        // Calculate deductions
        $personalDeduction = $deductionConfig['personal'];
        $dependentDeduction = $deductionConfig['dependent'] * $input->dependents;
        $insurance = $input->insurance ?? ($input->amount * self::DEFAULT_INSURANCE_RATE);
        
        $totalDeductions = $personalDeduction + $dependentDeduction + $insurance + $input->otherDeductions;
        $taxableAmount = max(0, $input->amount - $totalDeductions);

        // Calculate progressive tax
        $brackets = $year === '2026' ? self::BRACKETS_2026 : self::BRACKETS_2025;
        $taxResult = $this->calculateProgressiveTax($taxableAmount, $brackets);

        // Net income
        $netAmount = $input->amount - $insurance - $taxResult['total'];

        // Effective rate
        $effectiveRate = $input->amount > 0 
            ? round(($taxResult['total'] / $input->amount) * 100, 2) 
            : 0;

        return new TaxResult(
            grossAmount: $input->amount,
            taxableAmount: $taxableAmount,
            totalTax: round($taxResult['total']),
            netAmount: $netAmount,
            effectiveRate: $effectiveRate,
            breakdown: $taxResult['breakdown'],
            deductions: [
                'personal' => $personalDeduction,
                'dependent' => $dependentDeduction,
                'insurance' => $insurance,
                'other' => $input->otherDeductions,
                'total' => $totalDeductions,
            ],
            meta: [
                'year' => $year,
                'dependents' => $input->dependents,
                'brackets_count' => $year === '2026' ? 5 : 7,
            ],
        );
    }

    /**
     * Calculate progressive tax with breakdown.
     */
    private function calculateProgressiveTax(float $taxableAmount, array $brackets): array
    {
        $totalTax = 0;
        $breakdown = [];
        $previousThreshold = 0;

        foreach ($brackets as $index => $bracket) {
            if ($taxableAmount <= 0) {
                break;
            }

            $currentThreshold = $bracket['threshold'];
            $rate = $bracket['rate'];
            $bracketWidth = $currentThreshold - $previousThreshold;
            
            $amountInBracket = min($taxableAmount, $bracketWidth);
            $taxInBracket = $amountInBracket * $rate;

            if ($amountInBracket > 0) {
                $breakdown[] = [
                    'bracket' => $index + 1,
                    'from' => $previousThreshold,
                    'to' => $previousThreshold + $amountInBracket,
                    'amount' => $amountInBracket,
                    'rate' => $rate * 100,
                    'tax' => round($taxInBracket),
                ];
            }

            $totalTax += $taxInBracket;
            $taxableAmount -= $amountInBracket;
            $previousThreshold = $currentThreshold;
        }

        return [
            'total' => $totalTax,
            'breakdown' => $breakdown,
        ];
    }

    public function getType(): string
    {
        return 'personal_income';
    }

    public function getSupportedYears(): array
    {
        return [TaxYear::Y2025, TaxYear::Y2026];
    }
}
