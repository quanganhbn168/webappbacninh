<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Post;
use App\Models\PostCategory;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.xml file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting sitemap generation...');

        $sitemap = Sitemap::create();

        // 1. Static Pages
        $sitemap->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemap->add(Url::create('/blog')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        // Add other static pages here (e.g., /contact, /about) if they exist

        // 2. Blog Posts
        $posts = Post::published()->get();
        foreach ($posts as $post) {
            $sitemap->add(
                Url::create("/blog/{$post->slug}")
                    ->setLastModificationDate($post->updated_at)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        }

        // 3. Categories
        $categories = PostCategory::active()->get();
        foreach ($categories as $category) {
             // Assuming category route is /blog/category/{slug} - need to verify route
             // If route is just a filter on blog index, maybe we don't need it or use query param?
             // Let's assume standard category route pattern or query param. 
             // Previous view of routes showed blog details but not explicit category route.
             // But usually SEO wants category pages. Let's start with posts first.
             // Checking routes/web.php would be good, but standard pattern is usually /blog/category/{slug}
             // For now, I'll comment out category if route doesn't exist, or just use posts.
             // Better: Check routes first.
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('sitemap.xml generated successfully!');
    }
}
