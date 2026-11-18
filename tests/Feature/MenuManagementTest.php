<?php

namespace Tests\Feature;

use App\Filament\Resources\Menus\Pages\ManageMenus;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class MenuManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_header_and_footer_render_the_menus_from_the_database(): void
    {
        $this->get(route('about'))->assertOk()
            ->assertSee('id="site-nav"', false)
            ->assertSee('Giải pháp')
            ->assertSee('<h2>Về chúng tôi</h2>', false)
            ->assertSee('href="/hop-tac-agency"', false);
    }

    public function test_current_page_and_its_parent_are_marked_active(): void
    {
        $html = $this->get('/du-an')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('#class="nav-link active" href="/du-an"\s+aria-current="page"#', $html);
        $this->assertDoesNotMatchRegularExpression('#class="nav-link active" href="/bang-gia"#', $html);
    }

    public function test_menu_edited_in_the_admin_updates_the_site_immediately(): void
    {
        $menu = Menu::query()->where('location', Menu::HEADER)->firstOrFail();
        $this->get('/')->assertDontSee('Tuyển dụng gấp');

        $item = $menu->items()->firstOrFail();
        $item->update(['title' => 'Tuyển dụng gấp']);
        $this->get('/')->assertSee('Tuyển dụng gấp');

        $menu->allItems()->create(['title' => 'Mục mới', 'url' => '/moi', 'parent_id' => $item->id, 'icon' => 'star']);
        $this->get('/')->assertSee('Mục mới')->assertSee('M11.525 2.295', false);

        $menu->update(['is_active' => false]);
        $this->get('/')->assertDontSee('Tuyển dụng gấp');
    }

    public function test_admin_can_open_and_save_a_menu(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')->get('/admin/menus')->assertOk()->assertSee('Footer – cột 3')->assertSee('Thêm trang có sẵn');
        $this->actingAs($admin, 'admin')->get('/admin/menus?menu='.Menu::FOOTER_ABOUT)->assertOk();

        Livewire::actingAs($admin, 'admin')->test(ManageMenus::class)
            ->call('switchTo', Menu::FOOTER_ABOUT)
            ->set('name', 'Công ty')
            ->call('save')
            ->assertHasNoErrors();

        $this->get('/')->assertSee('<h2>Công ty</h2>', false);
    }

    public function test_builder_adds_pages_nests_reorders_and_removes_items(): void
    {
        $page = Livewire::actingAs($this->admin(), 'admin')->test(ManageMenus::class);
        $items = $page->get('items');
        $count = count($items);

        // Add the "Bảng giá" fixed page and a custom link from the left panel.
        $pricing = collect($page->instance()->sources['pages']['links'])->search(fn (array $link): bool => $link['url'] === '/bang-gia');
        $page->set('picked', ['pages:'.$pricing])->call('addPicked')
            ->set('customTitle', 'Gọi ngay')->set('customUrl', 'tel:0900000000')->call('addCustom')
            ->assertSet('dirty', true);

        $items = $page->get('items');
        $this->assertCount($count + 2, $items);
        $added = $items[$count];
        $this->assertSame(['Bảng giá', '/bang-gia'], [$added['title'], $added['url']]);

        // Nest the new item under the one above it, then move the custom link to the top.
        $page->call('indent', $added['key']);
        $items = $page->get('items');
        $this->assertSame('Bảng giá', last($items[$count - 1]['children'])['title']);

        $keys = array_column($items, 'key');
        $custom = array_pop($keys);
        $page->call('reorder', [$custom, ...$keys], null)->call('save')->assertHasNoErrors()->assertSet('dirty', false);

        $menu = Menu::query()->where('location', Menu::HEADER)->firstOrFail();
        $this->assertSame('Gọi ngay', $menu->items()->first()->title);
        $this->assertTrue($menu->allItems()->where('url', '/bang-gia')->whereNotNull('parent_id')->exists());

        // Removing the custom link deletes it on save.
        $first = $page->get('items')[0]['key'];
        $page->call('remove', $first)->call('save');
        $this->assertFalse($menu->allItems()->where('title', 'Gọi ngay')->exists());
    }

    public function test_invalid_links_are_refused_and_footer_columns_stay_flat(): void
    {
        $page = Livewire::actingAs($this->admin(), 'admin')->test(ManageMenus::class)
            ->set('customTitle', 'Sai')->set('customUrl', 'javascript:alert(1)')->call('addCustom')
            ->assertHasErrors('customUrl');

        $page->set('items.0.url', 'khong-co-gach')->call('save')->assertHasErrors('items.0.url');

        $page->call('switchTo', Menu::FOOTER_SERVICES);
        $second = $page->get('items')[1]['key'];
        $page->call('indent', $second);
        $this->assertSame([], $page->get('items')[0]['children']);
    }

    private function admin(): User
    {
        return User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }
}
