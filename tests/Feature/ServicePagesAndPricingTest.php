<?php

namespace Tests\Feature;

use App\Enums\PricingGroup;
use App\Filament\Resources\PricingPlans\Pages\CreatePricingPlan;
use App\Filament\Resources\PricingPlans\Pages\EditPricingPlan;
use App\Filament\Resources\PricingPlans\Pages\ListPricingPlans;
use App\Filament\Resources\Services\Pages\EditService;
use App\Models\PricingPlan;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
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

    public function test_one_plan_typed_in_the_table_serves_several_services(): void
    {
        [$first, $second] = Service::query()->active()->where('is_landing', true)->ordered()->take(2)->get()->all();
        $first->pricingPlans()->detach();
        $second->pricingPlans()->detach();
        $this->get($first->url)->assertOk()->assertSee('Liên hệ để tư vấn')->assertDontSee('price-card', false);

        $name = 'Gói thử '.Str::random(8);
        Livewire::actingAs($this->admin(), 'admin')->test(CreatePricingPlan::class)
            ->fillForm([
                'name' => $name, 'price_prefix' => 'Từ', 'price' => '1.111.000đ', 'features' => 'Hạng mục A',
                'services' => [$first->id], 'show_in_table' => false,
            ])
            ->call('create')->assertHasNoFormErrors();
        $plan = PricingPlan::query()->where('name', $name)->firstOrFail();

        // The second service picks the same plan from its own form.
        Livewire::actingAs($this->admin(), 'admin')->test(EditService::class, ['record' => $second->getRouteKey()])
            ->fillForm(['pricingPlans' => [$plan->id]])
            ->call('save')->assertHasNoFormErrors();

        $this->assertEqualsCanonicalizing([$first->id, $second->id], $plan->services()->pluck('services.id')->all());
        foreach ([$first, $second] as $service) {
            $this->assertSame('Từ 1.111.000đ', $service->fresh()->price_label);
            $this->get($service->url)->assertSee($name)->assertSee('Hạng mục A')->assertSee('Từ 1.111.000đ');
        }
        $this->get(route('pricing'))->assertDontSee($name);

        $plan->update(['show_in_table' => true, 'group' => 'website', 'price' => '2.222.000đ', 'order' => 0]);
        foreach ([$first->url, $second->url, route('pricing'), route('home')] as $url) {
            $this->get($url)->assertSee($name)->assertSee('2.222.000đ')->assertDontSee('1.111.000đ');
        }
        Livewire::actingAs($this->admin(), 'admin')->test(ListPricingPlans::class)
            ->assertCanSeeTableRecords([$plan])
            ->set('activeTab', 'service')->assertCanSeeTableRecords([$plan]);
    }

    public function test_deleting_a_service_keeps_its_plans_for_the_others(): void
    {
        $service = Service::query()->create(['title' => 'Dịch vụ tạm', 'slug' => 'dich-vu-tam-'.Str::lower(Str::random(6)), 'is_active' => true]);
        $plan = PricingPlan::query()->create(['name' => 'Gói dùng chung', 'price' => '1đ', 'show_in_table' => false]);
        $service->pricingPlans()->attach($plan);

        $service->delete();

        $this->assertNotNull($plan->fresh());
        $this->assertSame(0, $plan->services()->count());
    }

    public function test_the_website_and_landing_services_start_with_their_plans(): void
    {
        $landing = Service::query()->where('slug', 'landing-page')->firstOrFail();
        $business = Service::query()->where('slug', 'website-doanh-nghiep')->firstOrFail();
        $landing->pricingPlans()->detach();
        $business->pricingPlans()->detach();

        (new PricingPlanSeeder)->linkServices();
        (new PricingPlanSeeder)->linkServices();

        $this->assertSame(['Landing Page', 'Landing cơ bản', 'Landing quảng cáo'], $landing->plans()->pluck('name')->all());
        $this->assertSame(['Website Cơ bản', 'Website Nâng cao', 'Website Cao cấp / WebApp'], $business->plans()->pluck('name')->all());
        $this->get($landing->url)->assertSee('Từ 6.000.000đ')->assertSee('Từ 9.000.000đ');
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
