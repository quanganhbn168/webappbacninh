<?php

namespace App\Services\Tax\DTO;

class TaxResult
{
    public function __construct(
        public readonly float $grossAmount,
        public readonly float $taxableAmount,
        public readonly float $totalTax,
        public readonly float $netAmount,
        public readonly float $effectiveRate,
        public readonly array $breakdown = [],
        public readonly array $deductions = [],
        public readonly array $meta = [],
    ) {}

    /**
     * Convert to array for JSON response.
     */
    public function toArray(): array
    {
        return [
            'gross_amount' => $this->grossAmount,
            'taxable_amount' => $this->taxableAmount,
            'total_tax' => $this->totalTax,
            'net_amount' => $this->netAmount,
            'effective_rate' => $this->effectiveRate,
            'breakdown' => $this->breakdown,
            'deductions' => $this->deductions,
            'meta' => $this->meta,
        ];
    }

    /**
     * Create a zero result (for below threshold cases).
     */
    public static function zero(float $grossAmount): self
    {
        return new self(
            grossAmount: $grossAmount,
            taxableAmount: 0,
            totalTax: 0,
            netAmount: $grossAmount,
            effectiveRate: 0,
            breakdown: [],
            deductions: [],
            meta: ['message' => 'Doanh thu dưới ngưỡng chịu thuế'],
        );
    }
}
