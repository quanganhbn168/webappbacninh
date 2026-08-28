<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Slug;
use App\Models\Template;
use App\Models\Project;
use App\Models\Post;
use App\Models\OperationService;
use App\Settings\ContactSettings;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class FrontendSiteTest extends TestCase
{
    use DatabaseTransactions;

    public function test_all_static_and_listing_pages_render(): void
    {
        foreach (['/', '/gioi-thieu', '/lien-he', '/bang-gia', '/hop-tac-agency', '/thiet-ke-website', '/kho-giao-dien', '/du-an', '/kien-thuc', '/dich-vu-van-hanh'] as $uri) {
            $this->get($uri)->assertOk()->assertSee('WEBAPP', false);
        }

        $this->get('/')
            ->assertSee('/frontend/assets/images/hero-industrial.webp', false)
            ->assertDontSee('src="public/assets/', false)
            ->assertSee('application/ld+json', false)
            ->assertSee('"@type": "WebSite"', false)
            ->assertSee('"@type": "ProfessionalService"', false);
    }

    public function test_optional_secondary_phone_is_rendered_in_desktop_contact_areas_and_schema(): void
    {
        $contact = app(ContactSettings::class);
        $originalPhone = $contact->phone_secondary;
        $originalPhoneHref = $contact->phone_secondary_href;

        try {
            $contact->phone_secondary = '0222 333 444';
            $contact->phone_secondary_href = '0222333444';
            $contact->save();
            site_settings(refresh: true);

            $home = $this->get('/')->assertOk();
            $home->assertSee('0222 333 444');
            $home->assertSee('"telephone": "0222333444"', false);
            $this->assertSame(2, substr_count($home->getContent(), 'tel:0222333444'));

            $contactPage = $this->get('/lien-he')->assertOk();
            $contactPage->assertSee('Điện thoại thứ hai');
            $this->assertSame(3, substr_count($contactPage->getContent(), 'tel:0222333444'));
        } finally {
            $contact->phone_secondary = $originalPhone;
            $contact->phone_secondary_href = $originalPhoneHref;
            $contact->save();
            site_settings(refresh: true);
        }
    }

    public function test_all_dynamic_pages_render(): void
    {
        foreach (config('website_services') as $item) {
            $this->get('/thiet-ke-website/'.$item['slug'])->assertOk();
        }
        foreach (config('themes') as $item) {
            $this->get('/kho-giao-dien/'.$item['slug'])->assertOk();
        }
        foreach (config('projects') as $item) {
            $this->get('/du-an/'.$item['slug'])->assertOk();
        }
        foreach (config('articles') as $item) {
            $this->get('/kien-thuc/'.$item['slug'])->assertOk();
        }
        foreach (config('operation_services') as $item) {
            $this->get('/dich-vu-van-hanh/'.pathinfo($item['route'], PATHINFO_FILENAME))->assertOk();
        }
        foreach (config('legal_pages') as $item) {
            $this->get('/'.$item['slug'])->assertOk();
        }

        $this->get(route('services.show', 'website-doanh-nghiep'))
            ->assertSee('"@type": "Service"', false)
            ->assertSee('"@type": "FAQPage"', false);
    }

    public function test_unknown_dynamic_pages_return_404(): void
    {
        $this->get('/kho-giao-dien/khong-ton-tai')->assertNotFound();
        $this->get('/du-an/khong-ton-tai')->assertNotFound();
        $this->get('/kien-thuc/khong-ton-tai')->assertNotFound();
    }

    public function test_navigation_uses_named_routes_and_renders_mega_menus(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('mega-menu--services', false)
            ->assertSee('mega-menu--operations', false)
            ->assertSee(route('services.index'), false)
            ->assertSee(route('services.show', 'website-doanh-nghiep'), false)
            ->assertSee(route('operations.index'), false)
            ->assertSee(route('operations.show', 'hosting-bao-tri-website'), false)
            ->assertDontSee('href="website-doanh-nghiep.php"', false)
            ->assertDontSee('href="hosting-bao-tri-website.php"', false);
    }

    public function test_service_categories_and_non_landing_services_resolve_through_the_slug_registry(): void
    {
        $suffix = Str::lower((string) Str::uuid());
        $category = ServiceCategory::create([
            'name' => 'Nhóm kiểm thử '.$suffix,
            'slug' => 'nhom-kiem-thu-'.$suffix,
            'description' => 'Danh sách dịch vụ kiểm thử theo category.',
            'is_active' => true,
        ]);
        $service = Service::create([
            'service_category_id' => $category->id,
            'title' => 'Dịch vụ kiểm thử '.$suffix,
            'slug' => 'dich-vu-kiem-thu-'.$suffix,
            'icon' => 'fa-solid fa-code',
            'description' => 'Dịch vụ không có landing chuyên biệt.',
            'content' => '<p>Nội dung dịch vụ kiểm thử.</p>',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('slugs', ['key' => $category->slug, 'reference_type' => ServiceCategory::class, 'reference_id' => $category->id]);
        $this->assertDatabaseHas('slugs', ['key' => $service->slug, 'reference_type' => Service::class, 'reference_id' => $service->id]);

        $this->get('/'.$category->slug)
            ->assertOk()
            ->assertSee($category->name)
            ->assertSee($service->title);

        $this->get('/'.$service->slug)
            ->assertOk()
            ->assertSee($service->title)
            ->assertSee('Nội dung dịch vụ kiểm thử.', false);
    }

    public function test_known_service_landing_is_not_registered_as_a_root_slug(): void
    {
        $service = Service::firstOrCreate(
            ['slug' => 'website-doanh-nghiep'],
            [
                'title' => 'Website doanh nghiệp',
                'icon' => 'fa-solid fa-building',
                'description' => 'Landing page chuyên biệt.',
                'is_active' => true,
            ]
        );
        $service->save();

        $this->assertDatabaseMissing('slugs', [
            'key' => 'website-doanh-nghiep',
            'reference_type' => Service::class,
            'reference_id' => $service->id,
        ]);

        $this->get('/website-doanh-nghiep')
            ->assertNotFound();

        $this->get(route('services.show', 'website-doanh-nghiep'))
            ->assertOk();
    }

    public function test_lead_form_stores_a_lead(): void
    {
        $this->postJson('/lien-he', [
            'name' => 'Khách kiểm thử',
            'phone' => '0986123168',
            'need' => 'Thiết kế website',
            'source' => '/kiem-thu',
        ])->assertCreated();

        $this->assertDatabaseHas('leads', ['phone' => '0986123168', 'status' => 'new']);
    }

    public function test_public_frontend_uses_content_from_the_admin_models(): void
    {
        $suffix = Str::lower((string) Str::uuid());

        $theme = Template::active()->firstOrFail();
        $theme->update(['name' => 'Giao diện quản trị '.$suffix]);
        $this->get('/kho-giao-dien')->assertOk()->assertSee($theme->name);

        $project = Project::query()->where('is_active', true)->firstOrFail();
        $project->update(['title' => 'Dự án quản trị '.$suffix]);
        $this->get('/du-an')->assertOk()->assertSee($project->title);

        $post = Post::published()->firstOrFail();
        $post->update(['title' => 'Bài viết quản trị '.$suffix]);
        $this->get('/kien-thuc')->assertOk()->assertSee($post->title);

        $operation = OperationService::query()->where('is_active', true)->firstOrFail();
        $operation->update(['title' => 'Vận hành quản trị '.$suffix]);
        $this->get('/dich-vu-van-hanh')->assertOk()->assertSee($operation->title);
        $this->get('/dich-vu-van-hanh/'.$operation->slug)->assertOk()->assertSee($operation->title);

        $landing = Service::active()->firstOrFail();
        $landing->update(['title' => 'Dịch vụ quản trị '.$suffix]);
        $this->get('/thiet-ke-website/'.$landing->slug)->assertOk()->assertSee($landing->title);
    }

    public function test_public_seo_endpoints_use_current_named_routes(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Sitemap: '.route('sitemap'), false);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<urlset', false)
            ->assertSee(route('articles.index'), false)
            ->assertSee(route('themes.index'), false)
            ->assertSee(route('services.index'), false)
            ->assertDontSee('/templates', false);
    }
}
