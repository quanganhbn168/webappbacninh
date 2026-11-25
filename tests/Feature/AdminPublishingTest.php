<?php

namespace Tests\Feature;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Services\Pages\CreateService;
use App\Models\Product;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPublishingTest extends TestCase
{
    use DatabaseTransactions;

    public function test_every_admin_resource_list_create_and_edit_page_renders(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin, 'admin');
        Filament::setCurrentPanel('admin');

        $failures = [];
        foreach (Filament::getResources() as $resource) {
            foreach (['index' => null, 'create' => null, 'edit' => 'first'] as $page => $needsRecord) {
                if (! $resource::hasPage($page)) {
                    continue;
                }
                $params = [];
                if ($needsRecord) {
                    $record = $resource::getModel()::query()->first();
                    if (! $record) {
                        continue;
                    }
                    $params['record'] = $record->getKey();
                }
                $status = $this->get($resource::getUrl($page, $params, panel: 'admin'))->status();
                if ($status !== 200) {
                    $failures[] = class_basename($resource)." {$page} => {$status}";
                }
            }
        }

        $this->assertSame([], $failures);
    }

    public function test_admin_created_product_shows_on_the_products_page(): void
    {
        $name = 'Sản phẩm thử '.Str::lower(Str::random(6));

        Livewire::actingAs($this->admin(), 'admin')->test(CreateProduct::class)
            ->fillForm([
                'name' => $name,
                'group' => 'website',
                'summary' => 'Mô tả ngắn của sản phẩm thử.',
                'tags' => ['Website', 'Booking'],
                'is_featured' => true,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertTrue(Product::query()->where('name', $name)->exists());
        $this->get(route('products'))->assertOk()->assertSee($name)->assertSee('Mô tả ngắn của sản phẩm thử.');
        $this->get('/')->assertOk()->assertSee($name);
    }

    public function test_admin_created_services_work_as_simple_page_and_as_landing(): void
    {
        $suffix = Str::lower(Str::random(6));
        $simple = 'dich-vu-gian-don-'.$suffix;
        $landing = 'dich-vu-landing-'.$suffix;

        Livewire::actingAs($this->admin(), 'admin')->test(CreateService::class)
            ->fillForm([
                'title' => 'Dịch vụ giản đơn '.$suffix,
                'slug' => $simple,
                'is_landing' => false,
                'content' => '<p>Nội dung trang giản đơn</p>',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        Livewire::actingAs($this->admin(), 'admin')->test(CreateService::class)
            ->fillForm([
                'title' => 'Dịch vụ landing '.$suffix,
                'slug' => $landing,
                'is_landing' => true,
                'pages' => [['value' => 'Trang giới thiệu thử']],
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->get(route('slug.handle', $simple))->assertOk()->assertSee('Nội dung trang giản đơn', false);
        $this->get(route('services.show', $landing))->assertOk()
            ->assertSee('Dịch vụ landing '.$suffix)
            ->assertSee('Trang giới thiệu thử');
    }

    private function admin(): User
    {
        return User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }
}
