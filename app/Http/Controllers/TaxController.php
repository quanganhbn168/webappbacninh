<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Tax\DTO\TaxInput;
use App\Services\Tax\Services\PersonalIncomeTaxService;
use App\Services\Tax\Services\HouseholdBusinessTaxService;
use App\Services\Tax\Services\SMETaxService;
use App\Services\Tax\Enums\BusinessSector;

class TaxController extends Controller
{
    public function __construct(
        private readonly PersonalIncomeTaxService $personalTaxService,
        private readonly HouseholdBusinessTaxService $householdTaxService,
        private readonly SMETaxService $smeTaxService,
    ) {}

    // =============================================
    // PERSONAL INCOME TAX (TNCN)
    // =============================================

    public function showPersonalTax()
    {
        return view('tools.tinh-thue-tncn');
    }

    public function calculatePersonalTax(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'gross_income' => 'required|numeric|min:0',
            'dependents' => 'required|integer|min:0|max:20',
            'insurance' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'version' => 'required|in:2025,2026',
        ]);

        $input = TaxInput::fromArray([
            'amount' => $validated['gross_income'],
            'year' => $validated['version'],
            'dependents' => $validated['dependents'],
            'insurance' => $validated['insurance'] ?? null,
            'other_deductions' => $validated['other_deductions'] ?? 0,
        ]);

        $result = $this->personalTaxService->calculate($input);

        return response()->json([
            'success' => true,
            'data' => array_merge($result->toArray(), [
                // Legacy fields for backward compatibility
                'gross_income' => $result->grossAmount,
                'taxable_income' => $result->taxableAmount,
                'total_tax' => $result->totalTax,
                'net_income' => $result->netAmount,
                'effective_rate' => $result->effectiveRate,
                'tax_breakdown' => $result->breakdown,
                'personal_deduction' => $result->deductions['personal'] ?? 0,
                'dependent_deduction' => $result->deductions['dependent'] ?? 0,
                'insurance' => $result->deductions['insurance'] ?? 0,
                'total_deductions' => $result->deductions['total'] ?? 0,
                'version' => $result->meta['year'] ?? '2026',
            ]),
        ]);
    }

    // =============================================
    // HOUSEHOLD BUSINESS TAX (Hộ kinh doanh)
    // =============================================

    public function showHouseholdTax()
    {
        $sectors = BusinessSector::toArray();
        return view('tools.tinh-thue-ho-kinh-doanh', compact('sectors'));
    }

    public function calculateHouseholdTax(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'revenue' => 'required|numeric|min:0',
            'sector' => 'required|string',
            'version' => 'required|in:2025,2026',
        ]);

        $input = TaxInput::fromArray([
            'amount' => $validated['revenue'],
            'year' => $validated['version'],
            'sector' => $validated['sector'],
        ]);

        $result = $this->householdTaxService->calculate($input);

        return response()->json([
            'success' => true,
            'data' => $result->toArray(),
        ]);
    }

    // =============================================
    // SME TAX (Doanh nghiệp nhỏ và vừa)
    // =============================================

    public function showSMETax()
    {
        return view('tools.tinh-thue-doanh-nghiep');
    }

    public function calculateSMETax(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'revenue' => 'required|numeric|min:0',
            'expenses' => 'nullable|numeric|min:0',
            'is_new_business' => 'nullable|boolean',
            'years_in_business' => 'nullable|integer|min:0|max:100',
            'version' => 'required|in:2025,2026',
        ]);

        $input = TaxInput::fromArray([
            'amount' => $validated['revenue'],
            'year' => $validated['version'],
            'expenses' => $validated['expenses'] ?? null,
            'is_new_business' => $validated['is_new_business'] ?? false,
            'years_in_business' => $validated['years_in_business'] ?? 0,
        ]);

        $result = $this->smeTaxService->calculate($input);

        return response()->json([
            'success' => true,
            'data' => $result->toArray(),
        ]);
    }
}
