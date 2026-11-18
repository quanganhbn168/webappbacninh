<?php

namespace Tests\Feature;

use App\Enums\PricingGroup;
use App\Filament\Resources\PricingPlans\Pages\EditPricingPlan;
use App\Filament\Resources\PricingPlans\Pages\ListPricingPlans;
use App\Models\PricingPlan;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class ServicePagesAndPricingTest extends TestCase
{
    use DatabaseTransactions;

    public function test_software_crm_and_seo_have_their_own_pages_under_dich_vu(): void
    {
        foreach (['phan-mem', 'crm-booking', 'seo-quang-cao'] as $slug) {
            $service = Service::query()->where('slug', $slug)->firstOrFail();

            $this->assertSame(url('/dich-vu/'.$slug), $service->url);
            $this->get($service->url)->assertOk()->assertSee($service->title)->assertSee('Câu hỏi thường gặp')->assertDontSee('Xem kho giao diện');
        }
    }

    public function test_a_page_requested_under_the_wrong_prefix_redirects_to_its_own(): void
    {
        $this->get('/thiet-ke-website/phan-mem')->assertRedirect(url('/dich-vu/phan-mem'))->assertStatus(301);
        $this->get('/dich-vu/website-doanh-nghiep')->assertRedirect(url('/thiet-ke-website/website-doanh-nghiep'))->assertStatus(301);
        $this->get('/dich-vu/khong-ton-tai')->assertNotFound();
    }

    public function test_the_overview_links_to_the_pages_and_the_design_page_lists_only_website_services(): void
    {
        $this->get(route('services.overview'))->assertOk()->assertSee(url('/dich-vu/crm-booking'), false);
        $this->get(route('services.index'))->assertOk()->assertDontSee(url('/dich-vu/phan-mem'), false);
    }

    public function test_hosting_add_ons_come_from_the_price_plans(): void
    {
        $plan = PricingPlan::query()->where('group', PricingGroup::HostingAddon)->where('name', 'SSL cao cấp')->firstOrFail();
        $plan->update(['price' => '123.000đ']);

        $this->get(route('hosting'))->assertOk()->assertSee('SSL cao cấp')->assertSee('123.000đ');
    }

    public function test_prices_edit_in_the_list_and_features_in_one_text_box(): void
    {
        $plan = PricingPlan::query()->where('group', PricingGroup::Website)->firstOrFail();

        Livewire::actingAs($this->admin(), 'admin')->test(ListPricingPlans::class)
            ->assertCanSeeTableRecords([$plan])
            ->assertTableColumnExists('price');

        Livewire::actingAs($this->admin(), 'admin')->test(EditPricingPlan::class, ['record' => $plan->getRouteKey()])
            ->assertFormSet(['features' => implode("\n", $plan->features)])
            ->fillForm(['features' => "Hạng mục một\n\n  Hạng mục hai  \n"])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame(['Hạng mục một', 'Hạng mục hai'], $plan->fresh()->features);
    }

    public function test_a_plan_can_be_duplicated_hidden_from_the_list(): void
    {
        $plan = PricingPlan::query()->where('group', PricingGroup::Website)->firstOrFail();

        Livewire::actingAs($this->admin(), 'admin')->test(ListPricingPlans::class)
            ->callTableAction('replicate', $plan);

        $copy = PricingPlan::query()->where('name', $plan->name.' (bản sao)')->firstOrFail();
        $this->assertFalse($copy->is_active);
        $this->assertSame($plan->price, $copy->price);
    }

    public function test_a_project_cost_shows_on_its_page_and_card_and_is_hidden_when_empty(): void
    {
        $project = Project::query()->active()->firstOrFail();

        $project->update(['cost' => 'Từ 25 triệu']);
        $this->get($project->url)->assertOk()->assertSee('Chi phí tham khảo')->assertSee('Từ 25 triệu');
        $this->get(route('projects.index'))->assertOk()->assertSee('Từ 25 triệu');

        $project->update(['cost' => null]);
        $this->get($project->url)->assertOk()->assertDontSee('Chi phí tham khảo');
    }

    private function admin(): User
    {
        return User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }
}
