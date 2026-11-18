<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminPanelLayoutTest extends TestCase
{
    use DatabaseTransactions;

    public function test_topbar_has_toggle_home_link_search_create_menu_bell_and_user(): void
    {
        $admin = $this->admin();

        $html = $this->actingAs($admin, 'admin')->get('/admin')->assertOk()->getContent();

        $this->assertStringContainsString('Tìm bài viết, dự án, dịch vụ', $html);
        $this->assertStringContainsString('Tạo mới', $html);
        $this->assertStringContainsString('/admin/posts/create', $html);
        $this->assertStringContainsString('Quản trị cấp cao', $html);
        $this->assertStringContainsString(e($admin->name), $html);
        $this->assertMatchesRegularExpression('#href="'.preg_quote(config('app.url'), '#').'"[^>]*target="_blank"#', $html);
        $this->assertStringContainsString('Trang chủ', $html);
        $this->assertStringContainsString('fi-body-has-sidebar-collapsible-on-desktop', $html);
    }

    public function test_a_new_lead_rings_the_admin_bell(): void
    {
        config(['services.telegram.bot_token' => null]);
        $admin = $this->admin();
        $before = $admin->notifications()->count();

        $this->postJson('/lien-he', ['name' => 'Khách chuông', 'phone' => '0900000001', 'need' => 'Website'])->assertCreated();

        $this->assertSame($before + 1, $admin->notifications()->count());
        $this->assertSame('Khách mới: Khách chuông', $admin->notifications()->latest()->first()->data['title']);
    }

    private function admin(): User
    {
        return User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }
}
