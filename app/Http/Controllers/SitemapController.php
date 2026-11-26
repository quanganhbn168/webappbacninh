<?php

namespace App\Http\Controllers;

use App\Models\MiniApp;
use App\Models\OperationService;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Project;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Template;
use App\Models\TemplateCategory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = Cache::remember('sitemap:v5', now()->addHour(), function (): string {
            $sitemap = Sitemap::create();

            // Fixed pages, except those hidden from search engines in the admin.
            Page::query()->where('noindex', false)->get()->each(function (Page $page) use ($sitemap): void {
                if ($page->url) {
                    $sitemap->add($this->url($page->url, $page->updated_at));
                }
            });

            Post::published()
                ->where('published_at', '<=', now())
                ->get()
                // Not a post hidden from Google, nor one whose canonical address is somewhere else.
                ->reject(fn (Post $post): bool => str_contains((string) data_get($post->data, 'seo.robots'), 'noindex')
                    || (data_get($post->data, 'seo.canonical_url') ?: $post->url) !== $post->url)
                ->each(fn (Post $post) => $sitemap->add($this->url($post->url, $post->modified_at)));

            // Empty categories are left out until they have something to read.
            PostCategory::active()->whereHas('posts', fn ($query) => $query->published())->get()
                ->each(fn (PostCategory $category) => $sitemap->add($this->url($category->url, $category->updated_at)));

            Service::active()->get()->each(fn (Service $service) => $sitemap->add($this->url($service->url, $service->updated_at)));

            OperationService::where('is_active', true)->get()->each(fn (OperationService $service) => $sitemap->add($this->url(
                route('operations.show', ['slug' => $service->slug]),
                $service->updated_at,
            )));

            Project::where('is_active', true)->get()->each(fn (Project $project) => $sitemap->add($this->url(
                route('projects.show', ['slug' => $project->slug]),
                $project->updated_at,
            )));

            Template::active()->get()->each(fn (Template $template) => $sitemap->add($this->url(
                route('themes.show', ['slug' => $template->slug]),
                $template->updated_at,
            )));

            // Industry pages that are ready for search.
            TemplateCategory::query()->indexable()->get()
                ->each(fn (TemplateCategory $industry) => $sitemap->add($this->url($industry->url, $industry->updated_at)));

            ServiceCategory::where('is_active', true)->get()->each(fn (ServiceCategory $category) => $sitemap->add($this->url(
                route('slug.handle', ['slug' => $category->slug]),
                $category->updated_at,
            )));

            MiniApp::active()->get()->each(function (MiniApp $app) use ($sitemap): void {
                $link = trim((string) $app->link);

                if ($link === '' || str_starts_with($link, '#') || preg_match('/^(https?:|mailto:|tel:)/i', $link)) {
                    return;
                }

                $sitemap->add($this->url(url('/'.ltrim($link, '/'))));
            });

            // A tool page that has no mini app entry of its own.
            $sitemap->add($this->url(route('cover.bulk.page')));

            return $sitemap->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(): Response
    {
        return response(
            "User-agent: *\nDisallow:\nSitemap: ".route('sitemap')."\n",
            200,
            ['Content-Type' => 'text/plain; charset=UTF-8'],
        );
    }

    private function url(string $url, mixed $updatedAt = null): Url
    {
        $tag = Url::create(canonical_url($url));

        if ($updatedAt !== null) {
            $tag->setLastModificationDate($updatedAt);
        }

        return $tag;
    }
}
