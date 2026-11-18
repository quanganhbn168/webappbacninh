<?php

namespace Tests\Feature;

use App\Filament\NavigationGroup;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminNavigationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_sidebar_groups_follow_the_enum_order(): void
    {
        $html = $this->actingAs($this->admin(), 'admin')->get('/admin')->assertOk()->getContent();

        $labels = array_map(fn (NavigationGroup $group): string => $group->getLabel(), NavigationGroup::cases());
        $this->assertSame($labels, array_values(array_intersect($this->groupLabels($html), $labels)));
        $this->assertNotContains('Filament Shield', $this->groupLabels($html));
        $this->assertNotContains('Nội dung', $this->groupLabels($html));
    }

    public function test_new_leads_are_counted_on_the_sidebar(): void
    {
        Lead::query()->where('status', 'new')->update(['status' => 'contacted']);
        Lead::query()->forceCreate(['name' => 'Khách thử', 'phone' => '0900000000', 'status' => 'new']);

        $this->actingAs($this->admin(), 'admin')->get('/admin')->assertOk()
            ->assertSeeInOrder(['Yêu cầu liên hệ', 'fi-badge', '1'], false);
    }

    public function test_media_library_keeps_its_label(): void
    {
        $this->actingAs($this->admin(), 'admin')->get('/admin/media')->assertOk()
            ->assertSee('Thư viện ảnh')
            ->assertDontSee('Thư Viện Ảnh');
    }

    public function test_breadcrumbs_keep_vietnamese_labels_as_written(): void
    {
        $this->actingAs($this->admin(), 'admin')->get('/admin/service-categories/create')->assertOk()
            ->assertSee('Danh mục dịch vụ')
            ->assertDontSee('Danh Mục Dịch Vụ');
    }

    /** @return list<string> */
    private function groupLabels(string $html): array
    {
        $dom = new \DOMDocument;
        @$dom->loadHTML('<?xml encoding="utf-8"?>'.$html);
        $labels = [];
        foreach ((new \DOMXPath($dom))->query('//*[contains(@class, "fi-sidebar-group-label")]') as $node) {
            $labels[] = trim(preg_replace('/\s+/u', ' ', $node->textContent));
        }

        return $labels;
    }

    private function admin(): User
    {
        return User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }
}
