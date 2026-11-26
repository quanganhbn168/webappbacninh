<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/** One address per page, as Google Search Central describes it. */
class SeoUrlTest extends TestCase
{
    use DatabaseTransactions;

    public function test_each_page_of_the_article_list_is_its_own_indexable_page(): void
    {
        $this->publishPosts(12);
        $list = route('articles.index');

        $this->get('/kien-thuc?page=2')->assertOk()
            ->assertSee('<link rel="canonical" href="'.$list.'?page=2">', false)
            ->assertSee('<meta name="robots" content="index, follow', false)
            ->assertSee('href="'.$list.'"', false)
            ->assertDontSee('page=1"', false);
        $this->get('/kien-thuc?page=1&utm_source=x')->assertOk()->assertSee('<link rel="canonical" href="'.$list.'">', false);
        $this->get('/kien-thuc?page=999')->assertNotFound();
    }

    public function test_search_results_stay_out_of_google_and_the_admin_setting_applies_to_the_list(): void
    {
        $this->get('/kien-thuc?q=website')->assertOk()
            ->assertSee('<meta name="robots" content="noindex, follow">', false)
            ->assertSee('<link rel="canonical" href="'.route('articles.index').'?q=website">', false);

        Page::for('articles')->update(['noindex' => true]);
        $this->get('/kien-thuc')->assertOk()->assertSee('<meta name="robots" content="noindex, follow">', false);
    }

    public function test_sitemap_lists_only_addresses_meant_for_search(): void
    {
        [$shown, $hidden, $elsewhere] = $this->publishPosts(3);
        $hidden->update(['data' => ['seo' => ['robots' => 'noindex, follow']]]);
        $elsewhere->update(['data' => ['seo' => ['canonical_url' => 'https://example.com/ban-goc']]]);
        Cache::forget('sitemap:v5');

        $this->get('/sitemap.xml')->assertOk()
            ->assertSee('<loc>'.$shown->url.'</loc>', false)
            ->assertDontSee('<loc>'.$hidden->url.'</loc>', false)
            ->assertDontSee('<loc>'.$elsewhere->url.'</loc>', false)
            ->assertDontSee('<priority>', false)
            ->assertDontSee('<changefreq>', false);
    }

    /** @return array<int, Post> */
    private function publishPosts(int $count): array
    {
        $source = Post::query()->published()->firstOrFail();

        return collect(range(1, $count))->map(function (int $number) use ($source): Post {
            $post = $source->replicate();
            $post->forceFill([
                'title' => "Bài thử SEO {$number}",
                'slug' => "bai-thu-seo-{$number}",
                'published_at' => now()->subMinute(),
                'data' => null,
            ])->save();

            return $post;
        })->all();
    }
}
