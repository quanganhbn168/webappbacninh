<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        // Cache sitemap for 60 minutes
        $sitemapContext = Cache::remember('sitemap', 60, function () {
            $sitemap = Sitemap::create();

            // 1. Static Pages
            $sitemap->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
            $sitemap->add(Url::create('/blog')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

            // 2. Blog Posts
            $posts = Post::published()->get();
            foreach ($posts as $post) {
                // Ensure correct full URL
                $sitemap->add(
                    Url::create(route('blog.show', $post->slug))
                        ->setLastModificationDate($post->updated_at)
                        ->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                );
            }

            // 3. Categories (Optional - if needed for SEO)
            $categories = PostCategory::active()->get();
            // Assuming we will have a category route later, usually /blog/category/{slug}
            // For now, skipping to avoid broken links if route doesn't exist.

            return $sitemap->render();
        });

        return $sitemapContext;
    }
}
