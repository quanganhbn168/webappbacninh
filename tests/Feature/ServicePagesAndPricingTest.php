<?php

namespace Tests\Feature;

use App\Domain\Pricing\ImportServicePrices;
use App\Enums\PricingGroup;
use App\Filament\Resources\PricingPlans\Pages\CreatePricingPlan;
use App\Filament\Resources\PricingPlans\Pages\EditPricingPlan;
use App\Filament\Resources\PricingPlans\Pages\ListPricingPlans;
use App\Filament\Resources\Services\Pages\EditService;
use App\Models\PricingPlan;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
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

    public function test_a_price_typed_once_in_the_table_shows_on_the_service_and_in_the_shared_table(): void
    {
        $service = Service::query()->active()->where('is_landing', true)->firstOrFail();
        $service->pricingPlans()->delete();
        $this->get($service->url)->assertOk()->assertSee('Liên hệ để tư vấn')->assertDontSee('price-card', false);

        $name = 'Gói thử '.Str::random(8);
        Livewire::actingAs($this->admin(), 'admin')->test(CreatePricingPlan::class)
            ->fillForm([
                'name' => $name, 'price_prefix' => 'Từ', 'price' => '1.111.000đ', 'features' => 'Hạng mục A',
                'priceable_type' => Service::class, 'priceable_id' => $service->id, 'show_in_table' => false,
            ])
            ->call('create')->assertHasNoFormErrors();
        $plan = PricingPlan::query()->where('name', $name)->firstOrFail();

        $this->assertTrue($plan->priceable->is($service));
        $this->assertSame('Từ 1.111.000đ', $service->fresh()->price_label);
        $this->get($service->url)->assertSee($name)->assertSee('Hạng mục A')->assertSee('Từ 1.111.000đ');
        $this->get(route('services.index'))->assertSee('Từ 1.111.000đ');
        $this->get(route('pricing'))->assertDontSee($name);

        $plan->update(['show_in_table' => true, 'group' => 'website', 'price' => '2.222.000đ', 'order' => 0]);
        foreach ([$service->url, route('pricing'), route('home')] as $url) {
            $this->get($url)->assertSee($name)->assertSee('2.222.000đ')->assertDontSee('1.111.000đ');
        }

        Livewire::actingAs($this->admin(), 'admin')->test(EditService::class, ['record' => $service->getRouteKey()])
            ->assertSee($name.': Từ 2.222.000đ');
        Livewire::actingAs($this->admin(), 'admin')->test(ListPricingPlans::class)->assertCanSeeTableRecords([$plan]);
    }

    public function test_plans_of_a_deleted_service_leave_with_it_unless_the_shared_table_shows_them(): void
    {
        $service = Service::query()->create(['title' => 'Dịch vụ tạm', 'slug' => 'dich-vu-tam-'.Str::lower(Str::random(6)), 'is_active' => true]);
        $own = $service->pricingPlans()->create(['name' => 'Riêng', 'price' => '1đ', 'show_in_table' => false]);
        $shared = $service->pricingPlans()->create(['name' => 'Chung', 'price' => '2đ', 'group' => 'website']);

        $service->delete();

        $this->assertNull(PricingPlan::find($own->id));
        $this->assertNull($shared->fresh()->priceable_id);
    }

    public function test_old_service_prices_move_into_the_table_once(): void
    {
        $service = Service::query()->create(['title' => 'Dịch vụ cũ', 'slug' => 'dich-vu-cu-'.Str::lower(Str::random(6)), 'is_active' => true]);
        $packages = [
            ['name' => 'Cơ bản', 'price' => 'Từ 500.000đ/tháng', 'desc' => 'Mô tả', 'items' => ['A'], 'featured' => true],
            ['name' => 'Riêng', 'price' => 'Báo giá riêng'],
        ];

        app(ImportServicePrices::class)->execute($service, $packages, 'Từ 9 triệu');
        app(ImportServicePrices::class)->execute($service, $packages, 'Từ 9 triệu');

        $plans = $service->plans()->get();
        $this->assertSame(['Cơ bản', 'Riêng'], $plans->pluck('name')->all());
        $this->assertSame(['Từ', '500.000đ', '/tháng'], [$plans[0]->price_prefix, $plans[0]->price, $plans[0]->price_suffix]);
        $this->assertSame('Báo giá riêng', $plans[1]->price);
        $this->assertSame('Từ 500.000đ/tháng', $service->price_label);
        $this->assertFalse($plans[0]->show_in_table);
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
