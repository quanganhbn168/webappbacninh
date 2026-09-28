<?php

namespace Tests\Feature;

use App\Filament\Resources\Menus\Pages\EditMenu;
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
        $admin = User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
        $menu = Menu::query()->where('location', Menu::FOOTER_ABOUT)->firstOrFail();

        $this->actingAs($admin, 'admin')->get('/admin/menus')->assertOk()->assertSee('Footer – cột 3');

        Livewire::actingAs($admin, 'admin')->test(EditMenu::class, ['record' => $menu->getRouteKey()])
            ->fillForm(['name' => 'Công ty'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get('/')->assertSee('<h2>Công ty</h2>', false);
    }
}
