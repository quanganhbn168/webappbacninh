<?php

namespace Tests\Feature;

use App\Domain\Seo\AnalyzePost;
use App\Domain\Seo\SeoText;
use App\Domain\Seo\StoreSeoScore;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Awcodes\Curator\Models\Media;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class SeoScoreTest extends TestCase
{
    use DatabaseTransactions;

    public function test_keyword_matching_ignores_case_and_punctuation_but_not_accents(): void
    {
        $this->assertSame(2, SeoText::occurrences('Thiết kế Website! Giá thiết kế, website rẻ.', 'thiết kế website'));
        $this->assertFalse(SeoText::contains('thiet ke website', 'thiết kế website'));
        $this->assertFalse(SeoText::contains('websites', 'website'));
    }

    public function test_a_well_prepared_article_reaches_the_standard(): void
    {
        $report = app(AnalyzePost::class)->execute($this->goodPost());
        $problems = collect($report['groups'])->flatMap(fn (array $group): array => $group['checks'])
            ->whereIn('status', ['fail', 'warn'])->pluck('message', 'key')->all();

        $this->assertSame([], $problems);
        $this->assertSame(100, $report['score']);
        $this->assertSame('good', $report['grade']);
    }

    public function test_what_google_does_not_ask_for_never_costs_points(): void
    {
        $post = $this->goodPost();
        // No limit on lengths, no word count, and no keyword placement is required.
        $post->update([
            'meta_title' => 'Máy lọc nước gia đình và những điều cần biết trước khi quyết định mua một thiết bị phù hợp cho cả nhà',
            'meta_description' => str_repeat('Bài này giải thích kỹ từng bước chọn thiết bị cho gia đình. ', 6),
            'data' => [],
        ]);
        $report = app(AnalyzePost::class)->execute($post->fresh());

        $this->assertSame(100, $report['score']);
        $this->assertSame('info', $this->verdict($report, 'title_length'));
        $this->assertSame('info', $this->verdict($report, 'description_length'));
        $this->assertSame('tip', $this->verdict($report, 'keyword_set'));

        // Every rule that does score names the Google page it comes from, unless it reports something broken.
        $unsourced = collect($report['groups'])->where('advice', false)->flatMap(fn (array $group): array => $group['checks'])
            ->where('status', '!=', 'info')->whereNull('source')->pluck('key')->sort()->values()->all();
        $this->assertSame(['broken_links', 'image_files'], $unsourced);
    }

    public function test_titles_and_descriptions_follow_googles_advice(): void
    {
        $post = $this->goodPost();
        $post->update([
            'meta_title' => 'Máy lọc nước, máy lọc nước giá rẻ, máy lọc nước tốt',
            'meta_description' => 'máy lọc, lọc nước, giá rẻ, chính hãng, giao nhanh',
        ]);
        $report = app(AnalyzePost::class)->execute($post->fresh());

        $this->assertSame('fail', $this->verdict($report, 'title_stuffing'));
        $this->assertSame('fail', $this->verdict($report, 'description_sentences'));

        $post->update(['meta_title' => 'Trang chủ', 'meta_description' => 'Bút chì kim']);
        $report = app(AnalyzePost::class)->execute($post->fresh());

        $this->assertSame('warn', $this->verdict($report, 'title_descriptive'));
        $this->assertSame('warn', $this->verdict($report, 'description_informative'));
    }

    public function test_a_bare_article_gets_a_poor_score_and_says_why(): void
    {
        $post = Post::create(['title' => 'Bài', 'content' => '<p>Ngắn. Xem <a href="/khong-co-trang-nay">tại đây</a>.</p><p><img src="/storage/khong-co.jpg"></p>']);
        $report = app(AnalyzePost::class)->execute($post);

        foreach (['title_descriptive', 'cover', 'image_alt', 'image_files', 'broken_links', 'anchor_text'] as $key) {
            $this->assertContains($this->verdict($report, $key), ['fail', 'warn'], $key);
        }
        $this->assertSame('poor', $report['grade']);
        $this->assertLessThan(50, $report['score']);
    }

    public function test_a_page_hidden_from_google_cannot_pass(): void
    {
        $post = $this->goodPost();
        $post->update(['data' => array_merge($post->data, ['seo' => ['robots' => 'noindex, follow']])]);
        $report = app(AnalyzePost::class)->execute($post);

        $this->assertSame('fail', $this->verdict($report, 'indexable'));
        $this->assertSame(49, $report['score']);
    }

    public function test_a_title_used_by_another_page_keeps_the_article_below_the_standard(): void
    {
        $post = $this->goodPost();
        Post::create(['title' => $post->title, 'content' => '<p>Nội dung.</p>']);
        $report = app(AnalyzePost::class)->execute($post);

        $this->assertSame('fail', $this->verdict($report, 'title_unique'));
        // One failed rule is enough to fall short of the standard, however high the rest scores.
        $this->assertSame(79, $report['score']);
        $this->assertSame('fair', $report['grade']);
    }

    public function test_a_keyword_used_by_another_article_is_pointed_out(): void
    {
        $post = $this->goodPost();
        Post::create(['title' => 'Bài khác', 'content' => '<p>Nội dung.</p>', 'data' => ['focus_keyword' => 'Máy Lọc Nước']]);

        $this->assertSame('tip', $this->verdict(app(AnalyzePost::class)->execute($post), 'keyword_unique'));
    }

    public function test_one_warning_keeps_the_article_below_the_standard(): void
    {
        $post = $this->goodPost();
        $post->update(['meta_description' => 'Bút chì kim']);
        $report = app(AnalyzePost::class)->execute($post->fresh());

        $this->assertLessThan(100, $report['score']);
        $this->assertSame('fair', $report['grade']);
        $this->assertSame('Chưa đạt: còn 1 mục', $report['verdict']);
    }

    public function test_spam_signals_google_names_are_caught(): void
    {
        $post = $this->goodPost();
        $copy = Post::create(['title' => 'BÍ MẬT GÂY SỐC VỀ NƯỚC UỐNG!!!', 'content' => $post->content
            .'<p><span style="display: none">máy lọc nước giá rẻ</span></p>'
            .'<p>Giao hàng tại Hà Nội, Hải Phòng, Đà Nẵng, Cần Thơ, Bắc Ninh, Bắc Giang, Hải Dương, Hưng Yên, Nam Định.</p>']);
        $report = app(AnalyzePost::class)->execute($copy);

        foreach (['title_clickbait' => 'fail', 'hidden_text' => 'fail', 'duplicate_content' => 'fail', 'place_list' => 'warn'] as $key => $expected) {
            $this->assertSame($expected, $this->verdict($report, $key), $key);
        }
    }

    public function test_a_wide_cover_and_a_detailed_description_of_pictures_are_asked_for(): void
    {
        $post = $this->goodPost();
        $post->featuredMedia->update(['width' => 1200, 'height' => 800]);
        $post->update(['content' => str_replace('alt="Máy lọc nước đặt trong bếp"', 'alt="Máy lọc"', $post->content)]);
        $report = app(AnalyzePost::class)->execute($post->fresh());

        $this->assertSame('warn', $this->verdict($report, 'cover_widescreen'));
        $this->assertSame('warn', $this->verdict($report, 'image_alt_detail'));
    }

    public function test_the_updated_date_moves_only_when_the_text_changes(): void
    {
        $post = $this->goodPost();
        $post->update(['published_at' => now()->subDays(10)]);
        $post->update(['is_featured' => true, 'content' => str_replace('<ul>', '
<ul>', $post->content)]);
        $this->assertTrue($post->fresh()->modified_at->equalTo($post->fresh()->published_at));
        $this->get($post->url)->assertOk()->assertSee('Đăng ngày')->assertDontSee('Cập nhật <time', false);

        $post->update(['content' => $post->content.'<p>Bổ sung một đoạn mới.</p>']);
        $post = $post->fresh();

        $this->assertTrue($post->modified_at->isToday());
        $this->get($post->url)->assertOk()
            ->assertSee('Cập nhật <time datetime="'.now()->toDateString().'"', false)
            ->assertSee('"dateModified":"'.$post->modified_at->toIso8601String().'"', false);
        $this->assertSame('pass', $this->verdict(app(AnalyzePost::class)->execute($post), 'visible_date'));
    }

    public function test_the_author_is_whoever_created_the_article(): void
    {
        $unsigned = $this->goodPost();
        $this->get($unsigned->url)->assertSee('Tác giả: '.site_config('name'))->assertSee('"author":{"@id":', false);

        $writer = $this->admin();
        $title = 'Bài của người viết '.Str::random(8);
        Livewire::actingAs($writer, 'admin')->test(CreatePost::class)
            ->fillForm(['title' => $title, 'slug' => '', 'content' => '<p>Nội dung.</p>', 'is_published' => true])
            ->call('create')->assertHasNoFormErrors();
        $post = Post::where('title', $title)->firstOrFail();

        $this->assertSame($writer->id, $post->user_id);
        $this->assertNotNull($post->published_at);
        $this->get($post->url)->assertSee('Tác giả: '.$writer->name)
            ->assertSee('"author":{"@type":"Person","name":"'.$writer->name.'"}', false);

        // Someone else editing it later does not take the byline.
        $editor = User::factory()->create();
        $editor->assignRole($writer->roles->first());
        Livewire::actingAs($editor, 'admin')->test(EditPost::class, ['record' => $post->id])
            ->fillForm(['title' => $title.' sửa'])->call('save')->assertHasNoFormErrors();
        $this->assertSame($writer->id, $post->fresh()->user_id);
    }

    public function test_the_publication_date_is_set_when_the_article_goes_out(): void
    {
        $post = Post::create(['title' => 'Nháp '.Str::random(8), 'content' => '<p>Nội dung.</p>', 'is_published' => false]);
        app(StoreSeoScore::class)->execute($post);
        $this->assertNull($post->fresh()->published_at, 'Neither saving a draft nor scoring it sets a publication date.');

        $this->travel(3)->days();
        $post->fresh()->update(['is_published' => true]);
        $post = $post->fresh();

        $this->assertTrue($post->published_at->isToday());
        $this->assertFalse($post->published_at->isSameDay($post->created_at));
        $this->assertNull($post->content_updated_at);
    }

    public function test_the_form_is_scored_before_saving_and_the_score_is_kept_after(): void
    {
        $title = 'Kiểm thử chấm điểm '.Str::random(8);
        $form = Livewire::actingAs($this->admin(), 'admin')->test(CreatePost::class)
            ->fillForm(['title' => $title, 'slug' => '', 'content' => '<h2>Từ khóa riêng biệt</h2><p>Nội dung.</p>', 'data.focus_keyword' => 'từ khóa riêng biệt', 'is_published' => false]);
        $count = Post::count();

        $form->call('analyzeSeo')->assertReturned(fn (array $report): bool => $this->verdict($report, 'keyword_heading') === 'pass'
            && str_contains($report['preview']['title'], $title)
            && str_ends_with($report['preview']['url'], '/'.Str::slug($title)));
        $this->assertSame($count, Post::count(), 'Scoring must not save the article.');

        $form->call('create')->assertHasNoFormErrors();
        $post = Post::where('title', $title)->firstOrFail();
        $this->assertIsInt($post->data['seo_score']);
        $this->assertSame('từ khóa riêng biệt', $post->data['focus_keyword']);

        // An edit that is not saved yet counts at once.
        Livewire::actingAs($this->admin(), 'admin')->test(EditPost::class, ['record' => $post->id])
            ->fillForm(['data.focus_keyword' => ''])
            ->call('analyzeSeo')->assertReturned(fn (array $report): bool => $this->verdict($report, 'keyword_set') === 'tip'
                && $this->verdict($report, 'article_schema') === 'pass');
        $this->assertSame('từ khóa riêng biệt', $post->fresh()->data['focus_keyword']);

        Livewire::actingAs($this->admin(), 'admin')->test(ListPosts::class)
            ->assertOk()->callTableBulkAction('seoScore', [$post]);
    }

    public function test_output_is_standard_without_extra_work_from_the_writer(): void
    {
        $media = Media::query()->whereNotNull('width')->firstOrFail();
        $post = Post::create([
            'title' => 'Bài không mô tả '.Str::random(8),
            'content' => '<h1>Tiêu đề dán vào</h1><p>Đoạn đầu của bài dùng làm mô tả khi người viết bỏ trống.</p><p><img src="'.$media->url.'" alt="Ảnh"></p>',
            'is_published' => true, 'published_at' => now()->subMinute(),
        ]);
        $html = $this->get($post->url)->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, '<h1'));
        $this->assertStringContainsString('<meta name="description" content="Tiêu đề dán vào Đoạn đầu của bài', $html);
        $this->assertStringContainsString('width="'.$media->width.'" height="'.$media->height.'"', $html);
        // No picture of its own in the share tags: the website's, never the SVG placeholder.
        $this->assertStringNotContainsString('placeholder.svg', $html);
    }

    /** An article that follows every rule. */
    private function goodPost(): Post
    {
        $category = PostCategory::create(['name' => 'Danh mục '.Str::random(8), 'is_active' => true]);
        Storage::fake('public');
        Storage::disk('public')->put('kiem-thu/may-loc-nuoc-gia-dinh.jpg', 'x');
        $media = Media::create(['disk' => 'public', 'directory' => 'kiem-thu', 'path' => 'kiem-thu/may-loc-nuoc-gia-dinh.jpg', 'name' => 'may-loc-nuoc-gia-dinh', 'ext' => 'jpg', 'type' => 'image/jpeg', 'width' => 1600, 'height' => 900, 'size' => 200000, 'visibility' => 'public']);
        $filler = str_repeat('Người dùng nên xem xét nguồn nước trước khi chọn thiết bị phù hợp. ', 8);
        $sections = collect(range(1, 6))->map(fn (int $i): string => '<h2>'.($i === 1 ? 'Chọn máy lọc nước theo nguồn nước' : 'Phần hướng dẫn số '.$i).'</h2><p>'.$filler.'</p><p>Máy lọc nước cần thay lõi đúng hạn.</p>')->implode('');
        $target = Post::query()->published()->firstOrFail();

        $post = Post::create([
            'title' => 'Máy lọc nước gia đình: cách chọn đúng loại',
            'slug' => 'may-loc-nuoc-gia-dinh-'.Str::lower(Str::random(6)),
            'summary' => 'Cách chọn máy lọc nước theo nguồn nước, số người dùng và ngân sách, kèm những lưu ý khi lắp đặt và thay lõi định kỳ.',
            'category_id' => $category->id,
            'content' => $sections.'<ul><li>Một ý</li></ul><p><img src="'.$media->url.'" alt="Máy lọc nước đặt trong bếp"></p><p>Đọc <a href="'.$target->url.'">'.e($target->title).'</a> và <a href="https://example.org/tieu-chuan">tiêu chuẩn nước ăn uống</a>.</p>',
            'faqs' => [['q' => 'Bao lâu thay lõi?', 'a' => 'Theo khuyến cáo của hãng.'], ['q' => 'Có tốn điện không?', 'a' => 'Rất ít.']],
            'featured_media_id' => $media->id,
            'is_published' => true, 'published_at' => now()->subMinute(),
            'data' => ['focus_keyword' => 'máy lọc nước'],
        ]);
        Post::create(['title' => 'Bài dẫn link '.Str::random(8), 'content' => '<p><a href="'.$post->url.'">Xem bài</a></p>', 'is_published' => true, 'published_at' => now()->subMinute()]);

        return $post->fresh();
    }

    /** @param  array<string, mixed>  $report */
    private function verdict(array $report, string $key): ?string
    {
        return collect($report['groups'])->flatMap(fn (array $group): array => $group['checks'])->firstWhere('key', $key)['status'] ?? null;
    }

    private function admin(): User
    {
        return User::whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }
}
