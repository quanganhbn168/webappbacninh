<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Service;
use App\Models\Template;
use App\Models\MiniApp;
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
            $sitemap->add(Url::create('/templates')->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
            $sitemap->add(Url::create('/anh-cover')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

            // 2. Blog Posts
            $posts = Post::published()->get();
            foreach ($posts as $post) {
                $sitemap->add(
                    Url::create(route('blog.show', $post->slug))
                        ->setLastModificationDate($post->updated_at)
                        ->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                );
            }

            // 3. Services
            $services = Service::active()->get();
            foreach ($services as $service) {
                 $sitemap->add(
                    Url::create(route('services.show', $service->slug))
                        ->setLastModificationDate($service->updated_at)
                        ->setPriority(0.9)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                );
            }

            // 4. Templates (Categories/Items)
            // Assuming categories for templates are mapped to /templates/{slug}
            $templates = Template::active()->get();
             foreach ($templates as $template) {
                 $sitemap->add(
                    Url::create(route('templates.show', $template->slug))
                        ->setLastModificationDate($template->updated_at)
                        ->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                );
            }

            // 5. Mini Apps (Using direct links if internal)
            $miniApps = MiniApp::active()->get();
            foreach ($miniApps as $app) {
                // Only add if link is internal and not just hash
                if ($app->link && !str_starts_with($app->link, '#') && !str_starts_with($app->link, 'http')) {
                     $sitemap->add(
                        Url::create(url($app->link))
                            ->setPriority(0.8)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    );
                }
            }

            return $sitemap->render();
        });

        return $sitemapContext;
    }
}
