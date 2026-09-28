<?php

namespace Tests\Feature;

use App\Enums\BannerSlot;
use App\Models\AdBanner;
use App\Models\MiniApp;
use App\Models\PricingPlan;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\User;
use App\Support\Icons;
use Awcodes\Curator\Models\Media;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SiteContentTest extends TestCase
{
    use DatabaseTransactions;

    public function test_home_reads_products_plans_and_testimonials_from_the_admin(): void
    {
        Product::query()->create(['name' => 'Sản phẩm kiểm thử', 'group' => 'crm', 'is_featured' => true, 'order' => 0, 'is_active' => true]);
        PricingPlan::query()->create(['group' => 'website', 'name' => 'Gói kiểm thử', 'price' => '1.234.000đ', 'order' => 0, 'is_active' => true]);
        Testimonial::query()->create(['name' => 'Khách kiểm thử', 'quote' => 'Nhận xét kiểm thử', 'order' => 0, 'is_active' => true]);

        $this->get('/')->assertOk()
            ->assertSee('Sản phẩm kiểm thử')
            ->assertSee('Gói kiểm thử')
            ->assertSee('1.234.000đ')
            ->assertSee('Nhận xét kiểm thử');
    }

    public function test_hidden_records_do_not_appear(): void
    {
        Product::query()->create(['name' => 'Sản phẩm ẩn', 'group' => 'crm', 'is_featured' => true, 'is_active' => false]);
        PricingPlan::query()->create(['group' => 'website', 'name' => 'Gói ẩn', 'price' => '1đ', 'order' => 0, 'is_active' => false]);

        $this->get('/')->assertDontSee('Sản phẩm ẩn')->assertDontSee('Gói ẩn');
        $this->get(route('products'))->assertDontSee('Sản phẩm ẩn');
        $this->get(route('pricing'))->assertDontSee('Gói ẩn');
    }

    public function test_pricing_page_groups_plans_into_tabs(): void
    {
        $this->get(route('pricing'))->assertOk()
            ->assertSee('data-bs-target="#plans-website"', false)
            ->assertSee('data-bs-target="#plans-hosting"', false)
            ->assertSee('So sánh nhanh các gói Website')
            ->assertSee('"@type":"FAQPage"', false);
    }

    public function test_tools_hub_lists_active_mini_apps_and_tool_pages_link_back(): void
    {
        MiniApp::query()->create(['name' => 'Công cụ ẩn', 'link' => '/an', 'is_active' => false, 'order' => 99]);

        $this->get(route('tools.index'))->assertOk()
            ->assertSee('Tính thuế TNCN')
            ->assertSee('href="'.url('/tinh-thue-tncn').'"', false)
            ->assertDontSee('Công cụ ẩn');

        $this->get(route('tools.tax'))->assertOk()
            ->assertSee('Công cụ miễn phí khác')
            ->assertSee('"@type":"WebApplication"', false);
    }

    public function test_ad_banners_show_only_in_their_slot_and_schedule(): void
    {
        $media = Media::query()->firstOrFail();
        AdBanner::query()->create(['name' => 'Banner chạy', 'slot' => BannerSlot::HOMEPAGE_HERO, 'image_id' => $media->id, 'alt_text' => 'Banner đang chạy', 'is_active' => true]);
        AdBanner::query()->create(['name' => 'Banner hết hạn', 'slot' => BannerSlot::HOMEPAGE_HERO, 'image_id' => $media->id, 'alt_text' => 'Banner đã hết hạn', 'is_active' => true, 'ends_at' => now()->subDay()]);

        $this->get('/')->assertSee('Banner đang chạy')->assertDontSee('Banner đã hết hạn');
        $this->get(route('pricing'))->assertDontSee('Banner đang chạy');
    }

    public function test_lead_form_accepts_json_from_the_consult_modal(): void
    {
        $this->postJson(route('leads.store'), ['name' => 'Khách JSON', 'phone' => '0900000000', 'need' => 'Thiết kế website'])
            ->assertCreated()->assertJsonStructure(['message']);

        $this->assertDatabaseHas('leads', ['name' => 'Khách JSON', 'need' => 'Thiết kế website', 'status' => 'new']);
    }

    public function test_icons_render_known_names_only(): void
    {
        $this->assertStringContainsString('<svg class="icon"', (string) Icons::svg('phone'));
        $this->assertStringContainsString('fill="currentColor"', (string) Icons::svg('brand-zalo'));
        $this->assertSame('', (string) Icons::svg('fa-solid fa-phone'));
        $this->assertArrayHasKey('phone', Icons::options());
    }

    public function test_public_pages_no_longer_load_font_awesome(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertStringNotContainsString('fa-solid', $html);
        $this->assertStringNotContainsString('fontawesome', $html);
    }

    public function test_new_admin_resources_render(): void
    {
        $admin = User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();

        foreach (['products', 'pricing-plans', 'testimonials', 'mini-apps', 'ad-banners', 'pages'] as $resource) {
            $this->actingAs($admin, 'admin')->get('/admin/'.$resource)->assertOk();
        }
        foreach (['products', 'pricing-plans', 'testimonials', 'mini-apps', 'ad-banners'] as $resource) {
            $this->actingAs($admin, 'admin')->get('/admin/'.$resource.'/create')->assertOk();
        }
    }
}
