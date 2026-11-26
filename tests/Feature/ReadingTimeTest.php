<?php

namespace Tests\Feature;

use App\Domain\Content\EstimateReadingTime;
use App\Models\Post;
use Tests\TestCase;

class ReadingTimeTest extends TestCase
{
    public function test_reading_time_is_calculated_from_visible_content_and_updates_without_manual_input(): void
    {
        $post = new Post(['content' => '<p>'.str_repeat('website ', 401).'</p>', 'read_time' => 99]);
        $this->assertSame(3, $post->read_time);
        $post->content = '<p>Bài viết ngắn.</p>';
        $this->assertSame(1, $post->read_time);
    }

    public function test_reading_time_counts_visible_text_only(): void
    {
        $this->assertSame(1, app(EstimateReadingTime::class)->execute('<img alt="'.str_repeat('word ', 600).'"> <script>'.str_repeat('word ', 600).'</script>'));
        $this->assertSame(1, app(EstimateReadingTime::class)->execute(null));
    }
}
