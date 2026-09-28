<?php

namespace Tests\Feature;

use App\Domain\Pages\SitePages;
use App\Filament\Resources\Pages\PageResource;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class PageManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_every_fixed_page_has_a_record_and_renders(): void
    {
        foreach (array_keys(SitePages::PAGES) as $key) {
            $page = Page::query()->where('key', $key)->firstOrFail();
            $this->get($page->url)->assertOk();
        }

        $this->get(route('legal.privacy'))
            ->assertSee('Chính sách bảo mật thông tin')
            ->assertSee('1. Thông tin được tiếp nhận')
            ->assertSee('Họ tên, số điện thoại, email và tên doanh nghiệp.');
    }

    public function test_seo_and_banner_edited_in_the_admin_change_the_public_page(): void
    {
        Page::query()->where('key', 'pricing')->firstOrFail()->update([
            'meta_title' => 'Bảng giá đã sửa | Kiểm thử',
            'meta_description' => 'Mô tả SEO kiểm thử cho bảng giá.',
            'banner_title' => 'Tiêu đề banner mới',
            'banner_highlight' => 'Dòng nhấn mới',
            'banner_subtitle' => 'Đoạn mô tả banner mới.',
            'noindex' => true,
        ]);

        $this->get(route('pricing'))
            ->assertSee('<title>Bảng giá đã sửa | Kiểm thử</title>', false)
            ->assertSee('<meta name="description" content="Mô tả SEO kiểm thử cho bảng giá.">', false)
            ->assertSee('<meta name="robots" content="noindex, follow">', false)
            ->assertSee('Tiêu đề banner mới')
            ->assertSee('Dòng nhấn mới')
            ->assertSee('Đoạn mô tả banner mới.')
            ->assertDontSee('Minh bạch – Linh hoạt – Phù hợp');
    }

    public function test_empty_fields_fall_back_to_the_view_defaults(): void
    {
        Page::query()->where('key', 'pricing')->firstOrFail()->update(['meta_title' => null, 'meta_description' => null, 'banner_title' => null]);

        $this->get(route('pricing'))
            ->assertSee('<title>Bảng giá | '.site_config('name').'</title>', false)
            ->assertSee(SitePages::description('pricing'))
            ->assertSee('Minh bạch – Linh hoạt – Phù hợp');
    }

    public function test_admin_can_edit_but_not_create_or_delete_pages(): void
    {
        $admin = $this->admin();
        $page = Page::query()->where('key', 'about')->firstOrFail();

        $this->actingAs($admin, 'admin')->get(PageResource::getUrl('index'))->assertOk()->assertSee('Giới thiệu');
        $this->assertFalse(PageResource::canCreate());
        $this->assertFalse($admin->can('delete', $page));

        Livewire::actingAs($admin, 'admin')->test(EditPage::class, ['record' => $page->getRouteKey()])
            ->fillForm(['meta_title' => 'Về chúng tôi | Kiểm thử'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get(route('about'))->assertSee('<title>Về chúng tôi | Kiểm thử</title>', false);
    }

    private function admin(): User
    {
        return User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }
}
