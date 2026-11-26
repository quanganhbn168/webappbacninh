<?php

namespace Tests\Feature;

use App\Domain\Content\Faqs;
use App\Filament\Auth\EditProfile;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class FaqPasteTest extends TestCase
{
    use DatabaseTransactions;

    public function test_questions_ending_with_a_question_mark_are_recognised_and_answers_keep_links(): void
    {
        $result = Faqs::parse('<p>Câu hỏi thường gặp</p><p>1. Giá bao nhiêu?</p><p>Xem <a href="https://example.org/bang-gia">bảng giá</a>.</p><ul><li><p>Gói cơ bản</p></li></ul><p>2. Bao lâu thì xong?</p><p>Trả lời: Khoảng hai tuần.</p>');

        $this->assertSame(['Giá bao nhiêu?', 'Bao lâu thì xong?'], array_column($result['items'], 'q'));
        $this->assertStringContainsString('href="https://example.org/bang-gia"', $result['items'][0]['a']);
        $this->assertStringContainsString('<li>', $result['items'][0]['a']);
        $this->assertSame('Khoảng hai tuần.', Faqs::plain($result['items'][1]['a']));
        // The line above the first question is reported, not silently dropped.
        $this->assertCount(1, $result['problems']);
    }

    public function test_pasted_shapes_with_headings_bold_lines_line_breaks_and_lists(): void
    {
        $expected = ['Có bảo hành không?', 'Thanh toán thế nào?'];
        $shapes = [
            'headings' => '<h2>Câu hỏi thường gặp</h2><h3>Có bảo hành không?</h3><p>Có.</p><p>Cần hỏi gì thêm?</p><h3>Thanh toán thế nào?</h3><p>Chuyển khoản.</p>',
            'bold' => '<p><strong>Có bảo hành không?</strong></p><p>Có.</p><p>Bạn còn lo gì nữa?</p><p><strong>Thanh toán thế nào?</strong></p><p>Chuyển khoản.</p>',
            'line breaks' => '<p>Có bảo hành không?<br>Có.<br><br>Thanh toán thế nào?<br>Chuyển khoản.</p>',
            'list' => '<ol><li><p>Có bảo hành không?</p><p>Có.</p></li><li><p>Thanh toán thế nào?</p><p>Chuyển khoản.</p></li></ol>',
            'prefixes' => '<p>Hỏi: Có bảo hành không?</p><p>Đáp: Có.</p><p>Hỏi: Thanh toán thế nào?</p><p>Đáp: Chuyển khoản.</p>',
        ];

        foreach ($shapes as $name => $html) {
            $result = Faqs::parse($html);
            $this->assertSame($expected, array_column($result['items'], 'q'), $name);
            $this->assertSame([], $result['problems'], $name);
        }
    }

    public function test_what_cannot_be_understood_is_reported(): void
    {
        $this->assertNotEmpty(Faqs::parse('<p>Một đoạn không có câu hỏi.</p>')['problems']);
        $this->assertNotEmpty(Faqs::parse('<p>Câu hỏi không có trả lời?</p>')['problems']);
        $this->assertSame(['items' => [], 'problems' => []], Faqs::parse('<p></p>'));
    }

    public function test_the_admin_pastes_one_text_and_saving_again_changes_nothing(): void
    {
        $title = 'Bài hỏi đáp '.Str::random(8);
        Livewire::actingAs($this->admin(), 'admin')->test(CreatePost::class)
            ->fillForm(['title' => $title, 'slug' => '', 'content' => '<p>Nội dung.</p>', 'is_published' => true,
                'faqs' => '<p>Xem giá ở đâu?</p><p>Tại <a href="https://example.org/gia">trang giá</a>.</p><p>Có hỗ trợ không?</p><p>Có.</p>'])
            ->call('create')->assertHasNoFormErrors();
        $post = Post::where('title', $title)->firstOrFail();

        $this->assertSame(['Xem giá ở đâu?', 'Có hỗ trợ không?'], array_column($post->faqs, 'q'));
        $this->get($post->url)->assertOk()
            ->assertSee('<a href="https://example.org/gia">trang giá</a>', false)
            ->assertSee('"text":"Tại trang giá ."', false);

        $stored = $post->faqs;
        Livewire::actingAs($this->admin(), 'admin')->test(EditPost::class, ['record' => $post->id])->call('save')->assertHasNoFormErrors();
        $this->assertEquals($stored, $post->fresh()->faqs);
        $this->assertNull($post->fresh()->content_updated_at);
    }

    public function test_questions_saved_before_as_plain_text_open_in_the_box_and_survive(): void
    {
        $post = Post::create(['title' => 'Bài cũ '.Str::random(8), 'content' => '<p>Nội dung.</p>', 'is_published' => true,
            'faqs' => [['q' => 'Phí duy trì', 'a' => "Tính theo năm.\nXem https://example.org/phi."]]]);

        $this->get($post->url)->assertSee('<a href="https://example.org/phi">https://example.org/phi</a>', false);

        Livewire::actingAs($this->admin(), 'admin')->test(EditPost::class, ['record' => $post->id])->call('save')->assertHasNoFormErrors();
        $faq = $post->fresh()->faqs[0];

        $this->assertSame('Phí duy trì', $faq['q']);
        $this->assertSame('Tính theo năm. Xem https://example.org/phi .', Faqs::plain($faq['a']));
    }

    public function test_text_that_belongs_to_no_question_stops_the_save(): void
    {
        $post = Post::create(['title' => 'Bài '.Str::random(8), 'content' => '<p>Nội dung.</p>']);

        Livewire::actingAs($this->admin(), 'admin')->test(EditPost::class, ['record' => $post->id])
            ->fillForm(['faqs' => '<p>Chỉ có một đoạn văn.</p>'])
            ->call('save')->assertHasFormErrors(['faqs']);
        $this->assertEmpty($post->fresh()->faqs);
    }

    public function test_changing_the_name_in_the_profile_changes_the_byline(): void
    {
        $writer = $this->admin();
        $post = Post::create(['title' => 'Bài '.Str::random(8), 'content' => '<p>Nội dung.</p>', 'is_published' => true, 'user_id' => $writer->id]);

        $this->actingAs($writer, 'admin')->get('/admin/profile')->assertOk()->assertSee('Tác giả');
        Livewire::actingAs($writer, 'admin')->test(EditProfile::class)
            ->fillForm(['name' => 'Trần Văn Tên Mới'])->call('save')->assertHasNoFormErrors();

        $this->get($post->url)->assertSee('Tác giả: Trần Văn Tên Mới');
    }

    private function admin(): User
    {
        return User::whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }
}
