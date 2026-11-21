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

class BlogManagementTest extends TestCase
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

    public function test_blog_lives_at_blog_and_old_kien_thuc_links_redirect_to_the_new_addresses(): void
    {
        $post = Post::query()->published()->firstOrFail();

        $this->assertSame(url('/'.$post->slug), $post->url);
        $this->get('/blog')->assertOk()->assertSee($post->title);
        $this->get('/blog/'.$post->slug)->assertNotFound();
        $this->get('/kien-thuc')->assertRedirect('/blog')->assertStatus(301);
        $this->get('/tin-tuc')->assertRedirect('/blog')->assertStatus(301);
        $this->get('/kien-thuc/'.$post->slug)->assertRedirect($post->url)->assertStatus(301);
        $this->get('/kien-thuc/khong-con-bai-nay')->assertRedirect(route('articles.index'))->assertStatus(301);
    }

    public function test_an_old_category_link_finds_the_category_renamed_away_from_a_fixed_page(): void
    {
        $category = PostCategory::create(['name' => 'Thiết kế website', 'is_active' => true]);

        $this->assertSame('thiet-ke-website-2', $category->slug);
        $this->get('/kien-thuc/danh-muc/thiet-ke-website')->assertRedirect($category->url)->assertStatus(301);
    }

    public function test_index_php_paths_redirect_to_the_clean_url(): void
    {
        $this->get('/index.php/anh-cover')->assertStatus(301)->assertRedirect(url('/anh-cover'));
        $this->get('/index.php/lien-he?x=1')->assertStatus(301)->assertRedirect(url('/lien-he?x=1'));
        $this->get('/index.php')->assertStatus(301)->assertHeader('Location', url('/').'/');
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
        $post->update(['og_image' => 'legacy/old-og.jpg']);
        $this->get($post->url)->assertOk()->assertSee('property="og:image" content="'.$og->url.'"', false)->assertDontSee('legacy/old-og.jpg');
        Livewire::actingAs($this->admin(), 'admin')->test(EditPost::class, ['record' => $post->id])
            ->fillForm(['og_media_id' => [], 'title' => $title.' sửa'])
            ->call('save')->assertHasNoFormErrors();
        $post->refresh();
        $this->assertNull($post->og_media_id);
        $this->assertSame(Str::slug($title), $post->slug);
        $this->assertSame($media->url, $post->og_image_url);
        $this->get($post->url)->assertOk()->assertSee($media->url, false)->assertDontSee('legacy/old-og.jpg');
        Livewire::actingAs($this->admin(), 'admin')->test(EditPost::class, ['record' => $post->id])
            ->set('data.featured_media_id', [])->call('save')->assertHasNoFormErrors();
        $this->assertNull($post->fresh()->featured_media_id);
        $this->assertSame(asset('images/placeholder.svg'), $post->fresh()->featured_image_url);
    }

    public function test_featured_layout_has_one_main_and_three_side_articles_and_hides_future_posts(): void
    {
        $response = $this->get('/blog')->assertOk();
        $this->assertSame(1, substr_count($response->getContent(), 'media-card--featured'));
        $this->assertSame(3, substr_count($response->getContent(), 'media-card--row'));
        $post = Post::create(['content' => '<p>Test</p>', 'title' => 'Scheduled '.Str::random(10), 'is_published' => true, 'published_at' => now()->addDay()]);
        $this->get($post->url)->assertNotFound();
        $this->get('/blog')->assertDontSee($post->title);
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

    public function test_legacy_sections_can_be_edited_without_losing_their_text_or_metadata(): void
    {
        $post = Post::create([
            'title' => 'Legacy '.Str::random(12),
            'content' => json_encode([['title' => 'Tiêu đề mục', 'paragraphs' => ['Đoạn văn cũ'], 'bullets' => ['Ý thứ nhất']]]),
            'data' => ['seo' => ['robots' => 'noindex'], 'custom' => 'keep'],
        ]);
        Livewire::actingAs($this->admin(), 'admin')->test(EditPost::class, ['record' => $post->id])
            ->call('save')->assertHasNoFormErrors();
        $post->refresh();
        $this->assertStringContainsString('<h2>Tiêu đề mục</h2>', $post->content);
        $this->assertStringContainsString('Đoạn văn cũ', $post->content);
        $this->assertStringContainsString('Ý thứ nhất', $post->content);
        $this->assertSame('keep', $post->data['custom']);
    }

    public function test_header_and_footer_use_configured_brand_logo(): void
    {
        $settings = app(WebsiteSettings::class);
        $settings->site_logo_wide = 'images/favicon.svg';
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
