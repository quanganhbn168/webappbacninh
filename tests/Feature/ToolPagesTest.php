<?php

namespace Tests\Feature;

use Tests\TestCase;

class ToolPagesTest extends TestCase
{
    public function test_tool_pages_use_the_bootstrap_layout_and_are_indexable(): void
    {
        foreach (['tools.tax', 'tools.tax.household', 'tools.tax.sme', 'tools.qr', 'tools.bank-qr', 'tools.calendar', 'tools.food-wheel', 'cover.page', 'cover.bulk.page'] as $route) {
            $this->get(route($route))->assertOk()
                ->assertSee('/build/assets/tools-', false)
                ->assertSee('<meta name="robots" content="index, follow">', false)
                ->assertDontSee('alpinejs', false)
                ->assertDontSee('x-data', false);
        }
    }

    public function test_tax_pages_link_to_the_other_tax_tools(): void
    {
        $this->get(route('tools.tax'))->assertOk()
            ->assertSee(route('tools.tax.household'), false)
            ->assertSee(route('tools.tax.sme'), false);
    }

    public function test_personal_income_tax_calculation_endpoint(): void
    {
        $this->postJson(route('tools.tax.calculate'), ['gross_income' => 30000000, 'dependents' => 0, 'other_deductions' => 0, 'version' => '2026'])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.total_tax', 635000);
    }
}
