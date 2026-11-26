<?php

namespace Tests\Feature;

use App\Filament\Resources\PostCategories\Pages\CreatePostCategory;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use App\Settings\WebsiteSettings;
use Awcodes\Curator\Models\Media;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class ArticleManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_blog_slug_is_generated_unique_across_models_and_preserved_on_title_changes(): void
    {
        $name = 'Bài viết kiểm thử '.Str::random(12);
        $category = PostCategory::create(['name' => $name, 'is_active' => true]);
        $post = Post::create(['title' => $name, 'content' => '<p>Test</p>']);
        $this->assertSame(Str::slug($name).'-2', $post->slug);
        $post->update(['title' => 'Tiêu đề mới']);
        $this->assertSame(Str::slug($name).'-2', $post->slug);
        $post->update(['slug' => 'Đường dẫn mới '.Str::random(8)]);
        $this->assertDatabaseHas('slugs', ['key' => $post->slug, 'reference_id' => $post->id, 'reference_type' => Post::class]);
        $this->assertSame(url('/'.$category->slug), $category->url);
        $this->get('/'.$category->slug)->assertOk()->assertSee($name);
    }

    public function test_articles_live_at_kien_thuc_and_old_addresses_are_gone(): void
    {
        $post = Post::query()->published()->firstOrFail();

        $this->assertSame(url('/'.$post->slug), $post->url);
        $this->get('/kien-thuc')->assertOk()->assertSee($post->title);
        $this->get('/blog/'.$post->slug)->assertNotFound();
        foreach (['/blog', '/tin-tuc', '/kien-thuc/'.$post->slug, '/contact', '/frontend/images/hero-home.webp'] as $old) {
            $this->get($old)->assertNotFound();
        }
    }

    public function test_article_shows_its_title_contents_questions_and_company_details(): void
    {
        $post = Post::query()->published()->firstOrFail();
        $post->update([
            'summary' => 'Mô tả ngắn không hiện đầu bài',
            'content' => '<h2>Mục một</h2><p>Nội dung.</p>',
            'faqs' => [['q' => 'Câu hỏi thử?', 'a' => 'Trả lời thử.'], ['q' => 'Thiếu trả lời', 'a' => '']],
        ]);

        $html = $this->get($post->url)->assertOk()
            ->assertSeeInOrder(['<h1>'.e($post->title).'</h1>', 'site-breadcrumb', 'data-toc', 'href="#section-1"', 'href="#cau-hoi-thuong-gap"', 'id="section-1"', 'Câu hỏi thử?', 'Trả lời thử.', 'company-card'], false)
            ->assertSee('data-toc-float', false)
            ->assertSee(site_config('phone'))
            ->assertDontSee('Thiếu trả lời')
            ->assertDontSee('page-hero__lead', false)
            ->getContent();

        preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $html, $match);
        $faqPage = collect(json_decode($match[1], true)['@graph'])->firstWhere('@type', 'FAQPage');
        $this->assertSame(['Câu hỏi thử?'], array_column($faqPage['mainEntity'], 'name'));

        $post->update(['content' => '<p>Không có mục.</p>', 'faqs' => null]);
        $this->get($post->url)->assertOk()->assertDontSee('data-toc', false)->assertDontSee('FAQPage', false);
    }

    public function test_article_lead_falls_back_to_the_short_description_and_shows_date_and_reading_time(): void
    {
        $post = Post::create(['title' => 'Bài '.Str::random(10), 'summary' => 'Mô tả ngắn làm đoạn mở bài', 'content' => '<p>Test</p>', 'is_published' => true, 'published_at' => now()->subMinute()]);

        $this->get($post->url)->assertOk()
            ->assertSee('<p class="article-intro">Mô tả ngắn làm đoạn mở bài</p>', false)
            ->assertSeeInOrder(['article-meta', $post->published_label, $post->read_time_label], false)
            // Without a picture of its own the article starts with its text, not a placeholder.
            ->assertDontSee('article-cover', false);

        $post->update(['data' => ['intro' => 'Đoạn mở bài riêng'], 'featured_media_id' => Media::query()->firstOrFail()->id]);
        $this->get($post->url)->assertOk()
            ->assertSee('<p class="article-intro">Đoạn mở bài riêng</p>', false)
            ->assertSee('article-cover', false);
    }

    public function test_admin_edits_the_lead_without_losing_other_extra_data(): void
    {
        $post = Post::create(['title' => 'Bài '.Str::random(10), 'content' => '<p>Test</p>', 'data' => ['intro' => 'Cũ', 'seo' => ['robots' => 'noindex']]]);

        Livewire::actingAs($this->admin(), 'admin')->test(EditPost::class, ['record' => $post->id])
            ->assertSchemaStateSet(['data.intro' => 'Cũ'])
            ->fillForm(['data.intro' => 'Mới'])
            ->call('save')->assertHasNoFormErrors();

        $this->assertSame('Mới', $post->fresh()->data['intro']);
        $this->assertSame(['robots' => 'noindex'], $post->fresh()->data['seo']);
    }

    public function test_only_an_admin_can_preview_a_draft_or_a_scheduled_article(): void
    {
        $draft = Post::create(['title' => 'Nháp '.Str::random(10), 'content' => '<p>Test</p>', 'is_published' => false]);
        $scheduled = Post::create(['title' => 'Hẹn giờ '.Str::random(10), 'content' => '<p>Test</p>', 'is_published' => true, 'published_at' => now()->addDay()]);

        foreach ([$draft, $scheduled] as $post) {
            $this->get($post->url)->assertNotFound();
        }
        // A signed-in customer is not an admin.
        $this->actingAs(User::factory()->create())->get($draft->url)->assertNotFound();
        $this->actingAs(User::factory()->create(), 'admin')->get($draft->url)->assertNotFound();

        foreach ([$draft, $scheduled] as $post) {
            $this->actingAs($this->admin(), 'admin')->get($post->url)->assertOk()
                ->assertSee('article-preview', false)
                ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
            Livewire::actingAs($this->admin(), 'admin')->test(EditPost::class, ['record' => $post->id])
                ->assertActionHasLabel('view', 'Xem trước')->assertActionHasUrl('view', $post->url);
        }

        $live = Post::query()->published()->firstOrFail();
        $this->get($live->url)->assertOk()->assertDontSee('article-preview', false);
        Livewire::actingAs($this->admin(), 'admin')->test(EditPost::class, ['record' => $live->id])
            ->assertActionHasLabel('view', 'Xem bài');
    }

    public function test_breadcrumbs_run_through_kien_thuc_and_the_category(): void
    {
        $category = PostCategory::create(['name' => 'Danh mục '.Str::random(10), 'is_active' => true]);
        $post = Post::create(['title' => 'Bài '.Str::random(10), 'category_id' => $category->id, 'content' => '<p>Test</p>', 'is_published' => true, 'published_at' => now()->subMinute()]);
        $trail = [
            ['Trang chủ', route('home').'/'],
            ['Kiến thức', route('articles.index')],
            [$category->name, $category->url],
            [$post->title, $post->url],
        ];

        $this->assertSame($trail, $this->breadcrumbList($post->url));
        $this->assertSame(array_slice($trail, 0, 3), $this->breadcrumbList($category->url));
        $this->get($post->url)->assertSee('<a href="'.$category->url.'">'.$category->name.'</a>', false);
        $this->get('/sitemap.xml')->assertOk();

        $category->update(['is_active' => false]);
        $this->assertSame([$trail[0], $trail[1], $trail[3]], $this->breadcrumbList($post->url));
    }

    /** @return array<int, array{0: string, 1: string}> */
    private function breadcrumbList(string $url): array
    {
        preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $this->get($url)->assertOk()->getContent(), $match);
        $list = collect(json_decode($match[1], true)['@graph'])->firstWhere('@type', 'BreadcrumbList');

        return array_map(fn (array $item): array => [$item['name'], $item['item']], $list['itemListElement']);
    }

    public function test_a_category_named_like_a_fixed_page_gets_its_own_path(): void
    {
        $category = PostCategory::create(['name' => 'Thiết kế website', 'is_active' => true]);

        $this->assertSame('thiet-ke-website-2', $category->slug);
    }

    public function test_a_post_or_category_cannot_take_the_path_of_a_fixed_page(): void
    {
        $category = PostCategory::create(['name' => 'Liên hệ', 'is_active' => true]);
        $post = Post::create(['title' => 'Bài', 'slug' => 'bang-gia', 'content' => '<p>Test</p>']);

        $this->assertSame('lien-he-2', $category->slug);
        $this->assertSame('bang-gia-2', $post->slug);
    }

    public function test_admin_forms_and_contact_inbox_render(): void
    {
        foreach (['posts/create', 'post-categories/create', 'leads', 'media'] as $path) {
            $this->actingAs($this->admin(), 'admin')->get('/admin/'.$path)->assertOk();
        }
        $this->get('/admin/posts/create')->assertSee('Ảnh chia sẻ')->assertSee('Chỉnh sửa')
            ->assertDontSee('Thời gian đọc (phút)')
            ->assertDontSee('Từ khóa SEO')->assertDontSee('Dữ liệu bổ sung');
    }

    public function test_admin_can_create_with_automatic_permalink_and_curator_then_remove_og_image(): void
    {
        $media = Media::query()->firstOrFail();
        $og = Media::query()->whereKeyNot($media->id)->firstOrFail();
        $title = 'Kiểm thử Curator '.Str::random(12);
        Livewire::actingAs($this->admin(), 'admin')->test(CreatePost::class)
            ->fillForm([
                'title' => $title, 'slug' => '', 'content' => '<p>Nội dung kiểm thử.</p>',

                'is_published' => true, 'published_at' => now()->subMinute()->format('Y-m-d H:i:s'),
            ])->set('data.featured_media_id', [$media->toArray()])->set('data.og_media_id', [$og->toArray()])->call('create')->assertHasNoFormErrors();
        $post = Post::where('title', $title)->firstOrFail();
        $this->assertSame(Str::slug($title), $post->slug);
        $this->assertEquals($media->id, $post->featured_media_id);
        $this->assertEquals($og->id, $post->og_media_id);
        $this->get($post->url)->assertOk()->assertSee('property="og:image" content="'.$og->url.'"', false);
        Livewire::actingAs($this->admin(), 'admin')->test(EditPost::class, ['record' => $post->id])
            ->fillForm(['og_media_id' => [], 'title' => $title.' sửa'])
            ->call('save')->assertHasNoFormErrors();
        $post->refresh();
        $this->assertNull($post->og_media_id);
        $this->assertSame(Str::slug($title), $post->slug);
        $this->get($post->url)->assertOk()->assertSee('property="og:image" content="'.$media->url.'"', false);
        Livewire::actingAs($this->admin(), 'admin')->test(EditPost::class, ['record' => $post->id])
            ->set('data.featured_media_id', [])->call('save')->assertHasNoFormErrors();
        $this->assertNull($post->fresh()->featured_media_id);
        $this->assertSame(asset('images/placeholder.svg'), $post->fresh()->featured_image_url);
    }

    public function test_featured_layout_has_one_main_and_three_side_articles_and_hides_future_posts(): void
    {
        $response = $this->get('/kien-thuc')->assertOk();
        $this->assertSame(1, substr_count($response->getContent(), 'media-card--featured'));
        $this->assertSame(3, substr_count($response->getContent(), 'media-card--row'));
        $post = Post::create(['content' => '<p>Test</p>', 'title' => 'Scheduled '.Str::random(10), 'is_published' => true, 'published_at' => now()->addDay()]);
        $this->get($post->url)->assertNotFound();
        $this->get('/kien-thuc')->assertDontSee($post->title);
    }

    public function test_category_curator_images_and_seo_are_used_on_its_public_page(): void
    {
        $media = Media::firstOrFail();
        $title = 'Danh mục Curator '.Str::random(10);
        Livewire::actingAs($this->admin(), 'admin')
            ->test(CreatePostCategory::class)
            ->fillForm(['name' => $title, 'slug' => '', 'meta_title' => 'SEO '.$title, 'meta_description' => 'Mô tả danh mục kiểm thử'])
            ->set('data.image_id', [$media->toArray()])->set('data.og_media_id', [$media->toArray()])
            ->call('create')->assertHasNoFormErrors();
        $category = PostCategory::where('name', $title)->firstOrFail();
        $this->assertEquals($media->id, $category->image_id);
        $this->get($category->url)->assertOk()
            ->assertSee('SEO '.$title)->assertSee('Mô tả danh mục kiểm thử')->assertSee($media->url, false);
    }

    public function test_header_and_footer_use_configured_brand_logo(): void
    {
        $settings = app(WebsiteSettings::class);
        $settings->logo_wide_media_id = Media::firstOrFail()->id;
        $settings->save();
        app()->forgetInstance('site.settings');
        $html = $this->get('/')->assertOk()->getContent();
        $this->assertSame(2, substr_count($html, 'class="brand__logo"'));
    }

    public function test_plain_errors_have_correct_status_without_site_chrome_or_exception_details(): void
    {
        $this->get('/missing-'.Str::random(20))->assertNotFound()->assertSee('Không tìm thấy trang')->assertDontSee('site-header');
        config(['app.debug' => false]);
        Route::get('/__test/error-500', fn () => throw new \RuntimeException('Private diagnostic marker'));
        $this->get('/__test/error-500')->assertStatus(500)->assertSee('Hệ thống đang gặp sự cố')
            ->assertDontSee('site-header')->assertDontSee('Private diagnostic marker');
    }

    public function test_native_contact_form_preserves_selected_service(): void
    {
        $name = 'Contact test '.Str::random(10);
        $this->post('/lien-he', ['name' => $name, 'phone' => '0900000000', 'service' => 'Thiết kế Website', 'message' => 'Kiểm thử biểu mẫu liên hệ.'])->assertRedirect();
        $this->assertDatabaseHas('leads', ['name' => $name, 'need' => 'Thiết kế Website', 'status' => 'new']);
    }

    private function admin(): User
    {
        return User::whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }
}
