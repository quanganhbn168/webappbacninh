<?php

namespace Tests\Feature;

use App\Filament\Resources\OperationServices\Pages\CreateOperationService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class ServiceManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_landing_packages_and_faqs_edited_in_the_admin_appear_on_the_service_page(): void
    {
        $service = Service::query()->where('is_landing', true)->firstOrFail();

        Livewire::actingAs($this->admin(), 'admin')->test(EditService::class, ['record' => $service->getRouteKey()])
            ->fillForm([
                'packages' => [['name' => 'Gói Thử Nghiệm', 'price' => 'Từ 1 triệu', 'desc' => 'Mô tả gói', 'items' => ['Hạng mục A'], 'featured' => true]],
                'faqs' => [['q' => 'Câu hỏi kiểm thử?', 'a' => 'Câu trả lời kiểm thử.']],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get($service->url)->assertOk()
            ->assertSee('Gói Thử Nghiệm')
            ->assertSee('Hạng mục A')
            ->assertSee('Được quan tâm')
            ->assertSee('Câu hỏi kiểm thử?')
            ->assertSee('"@type":"FAQPage"', false);
    }

    public function test_a_service_without_landing_is_served_from_the_root_and_landing_ones_redirect(): void
    {
        $landing = Service::query()->where('is_landing', true)->firstOrFail();
        $simple = Service::query()->create(['title' => 'Dịch vụ đơn giản', 'slug' => 'dich-vu-don-gian-'.Str::lower(Str::random(6)), 'content' => '<p>Nội dung đơn giản</p>', 'is_active' => true]);

        $this->assertSame(route('slug.handle', $simple->slug), $simple->url);
        $this->get($simple->url)->assertOk()->assertSee('Nội dung đơn giản', false);
        $this->get(route('services.show', $simple->slug))->assertNotFound();
        $this->assertSame(route('services.show', $landing->slug), $landing->url);
    }

    public function test_admin_created_operation_service_has_a_public_page(): void
    {
        $slug = 'van-hanh-thu-'.Str::lower(Str::random(6));

        Livewire::actingAs($this->admin(), 'admin')->test(CreateOperationService::class)
            ->fillForm([
                'title' => 'Vận hành thử',
                'slug' => $slug,
                'price_from' => 'Từ 300.000đ/tháng',
                'scope' => [['icon' => 'check', 'title' => 'Kiểm tra định kỳ', 'text' => 'Mỗi tuần một lần']],
                'process' => [['step' => '01', 'title' => 'Khảo sát', 'text' => 'Rà soát hiện trạng']],
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->get(route('operations.show', $slug))->assertOk()
            ->assertSee('Vận hành thử')
            ->assertSee('Kiểm tra định kỳ')
            ->assertSee('Khảo sát');
        $this->get(route('operations.index'))->assertOk()->assertSee('Vận hành thử');
    }

    private function admin(): User
    {
        return User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }
}
