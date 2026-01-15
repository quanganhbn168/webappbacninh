<?php

namespace App\Services\Tax\Contracts;

use App\Services\Tax\DTO\TaxInput;
use App\Services\Tax\DTO\TaxResult;

interface TaxServiceInterface
{
    /**
     * Calculate tax based on input.
     */
    public function calculate(TaxInput $input): TaxResult;

    /**
     * Get the tax type identifier.
     */
    public function getType(): string;

    /**
     * Get supported years.
     */
    public function getSupportedYears(): array;
}
