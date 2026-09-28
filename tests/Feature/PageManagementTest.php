<?php

namespace Tests\Feature;

use App\Enums\PageTemplate;
use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class PageManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_default_pages_render_from_the_database(): void
    {
        foreach (['about', 'agency', 'legal.privacy', 'legal.terms', 'legal.warranty', 'legal.payment'] as $route) {
            $this->get(route($route))->assertOk();
        }

        $this->get(route('legal.privacy'))
            ->assertSee('Chính sách bảo mật thông tin')
            ->assertSee('1. Thông tin được tiếp nhận')
            ->assertSee('Họ tên, số điện thoại, email và tên doanh nghiệp.');
    }

    public function test_edits_in_the_admin_change_the_public_page(): void
    {
        Page::query()->where('slug', 'chinh-sach-bao-mat')->firstOrFail()->update([
            'title' => 'Chính sách bảo mật đã sửa',
            'content' => [['type' => 'section', 'data' => ['heading' => 'Mục mới', 'content' => 'Đoạn mới', 'items' => "Ý một\nÝ hai"]]],
        ]);

        $this->get(route('legal.privacy'))
            ->assertSee('Chính sách bảo mật đã sửa')
            ->assertSee('Mục mới')
            ->assertSee('Ý hai')
            ->assertDontSee('1. Thông tin được tiếp nhận');
    }

    public function test_new_page_is_served_from_the_site_root_and_hidden_when_inactive(): void
    {
        $slug = 'huong-dan-'.Str::lower(Str::random(8));

        Livewire::actingAs($this->admin(), 'admin')->test(CreatePage::class)
            ->fillForm([
                'title' => 'Hướng dẫn thanh toán',
                'slug' => $slug,
                'template' => PageTemplate::Document->value,
                'summary' => 'Các bước thanh toán đơn hàng.',
                'content' => [['type' => 'section', 'data' => ['heading' => 'Bước 1', 'content' => 'Quét mã QR', 'items' => '']]],
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->get('/'.$slug)->assertOk()->assertSee('Hướng dẫn thanh toán')->assertSee('Quét mã QR');

        Page::query()->where('slug', $slug)->firstOrFail()->update(['is_active' => false]);

        $this->get('/'.$slug)->assertNotFound();
    }

    public function test_slug_cannot_take_a_fixed_route_or_another_page(): void
    {
        foreach (['lien-he', 'chinh-sach-bao-mat', 'Có Dấu'] as $slug) {
            Livewire::actingAs($this->admin(), 'admin')->test(CreatePage::class)
                ->fillForm(['title' => 'Trang thử', 'slug' => $slug, 'template' => PageTemplate::Document->value])
                ->call('create')
                ->assertHasFormErrors(['slug']);
        }
    }

    public function test_existing_page_keeps_its_own_named_route_slug(): void
    {
        $page = Page::query()->where('slug', 'gioi-thieu')->firstOrFail();

        Livewire::actingAs($this->admin(), 'admin')->test(EditPage::class, ['record' => $page->getRouteKey()])
            ->fillForm(['meta_title' => 'Về chúng tôi | WebApp Bắc Ninh'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get(route('about'))->assertOk()->assertSee('<title>Về chúng tôi | WebApp Bắc Ninh</title>', false);
    }

    public function test_admin_page_list_renders(): void
    {
        $this->actingAs($this->admin(), 'admin')->get('/admin/pages')->assertOk()->assertSee('Chính sách bảo mật thông tin');
    }

    private function admin(): User
    {
        return User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }
}
