<?php

namespace App\Services\Tax\DTO;

use App\Services\Tax\Enums\BusinessSector;
use App\Services\Tax\Enums\TaxYear;

class TaxInput
{
    public function __construct(
        public readonly float $amount,
        public readonly TaxYear $year = TaxYear::Y2026,
        public readonly ?BusinessSector $sector = null,
        public readonly int $dependents = 0,
        public readonly ?float $insurance = null,
        public readonly float $otherDeductions = 0,
        public readonly ?float $expenses = null, // Chi phí đầu vào (nếu có)
        public readonly bool $isNewBusiness = false, // DN mới thành lập
        public readonly int $yearsInBusiness = 0, // Số năm hoạt động
    ) {}

    /**
     * Create from array (for API requests).
     */
    public static function fromArray(array $data): self
    {
        return new self(
            amount: (float) ($data['amount'] ?? $data['gross_income'] ?? 0),
            year: TaxYear::tryFrom($data['year'] ?? $data['version'] ?? '2026') ?? TaxYear::Y2026,
            sector: isset($data['sector']) ? BusinessSector::tryFrom($data['sector']) : null,
            dependents: (int) ($data['dependents'] ?? 0),
            insurance: isset($data['insurance']) ? (float) $data['insurance'] : null,
            otherDeductions: (float) ($data['other_deductions'] ?? 0),
            expenses: isset($data['expenses']) ? (float) $data['expenses'] : null,
            isNewBusiness: (bool) ($data['is_new_business'] ?? false),
            yearsInBusiness: (int) ($data['years_in_business'] ?? 0),
        );
    }
}
