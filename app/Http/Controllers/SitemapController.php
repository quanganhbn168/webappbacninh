<?php

namespace App\Http\Controllers;

use App\Models\MiniApp;
use App\Models\OperationService;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Template;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = Cache::remember('sitemap:v2', now()->addHour(), function (): string {
            $sitemap = Sitemap::create();

            foreach ([
                ['home', [], 1.0, Url::CHANGE_FREQUENCY_DAILY],
                ['about', [], 0.7, Url::CHANGE_FREQUENCY_MONTHLY],
                ['contact', [], 0.7, Url::CHANGE_FREQUENCY_MONTHLY],
                ['pricing', [], 0.8, Url::CHANGE_FREQUENCY_WEEKLY],
                ['agency', [], 0.7, Url::CHANGE_FREQUENCY_MONTHLY],
                ['services.index', [], 0.9, Url::CHANGE_FREQUENCY_WEEKLY],
                ['themes.index', [], 0.8, Url::CHANGE_FREQUENCY_WEEKLY],
                ['projects.index', [], 0.8, Url::CHANGE_FREQUENCY_WEEKLY],
                ['articles.index', [], 0.8, Url::CHANGE_FREQUENCY_DAILY],
                ['operations.index', [], 0.8, Url::CHANGE_FREQUENCY_WEEKLY],
                ['legal.privacy', [], 0.3, Url::CHANGE_FREQUENCY_YEARLY],
                ['legal.terms', [], 0.3, Url::CHANGE_FREQUENCY_YEARLY],
                ['legal.warranty', [], 0.3, Url::CHANGE_FREQUENCY_YEARLY],
                ['legal.payment', [], 0.3, Url::CHANGE_FREQUENCY_YEARLY],
                ['cover.page', [], 0.6, Url::CHANGE_FREQUENCY_MONTHLY],
                ['cover.bulk.page', [], 0.6, Url::CHANGE_FREQUENCY_MONTHLY],
            ] as [$route, $parameters, $priority, $frequency]) {
                $sitemap->add($this->url(route($route, $parameters), null, $priority, $frequency));
            }

            Post::published()
                ->where('published_at', '<=', now())
                ->get()
                ->each(fn (Post $post) => $sitemap->add($this->url(
                    route('articles.show', ['slug' => $post->slug]),
                    $post->updated_at,
                    0.8,
                    Url::CHANGE_FREQUENCY_WEEKLY,
                )));

            Service::active()->get()->each(fn (Service $service) => $sitemap->add($this->url(
                route('services.show', ['slug' => $service->slug]),
                $service->updated_at,
                0.9,
                Url::CHANGE_FREQUENCY_WEEKLY,
            )));

            OperationService::where('is_active', true)->get()->each(fn (OperationService $service) => $sitemap->add($this->url(
                route('operations.show', ['slug' => $service->slug]),
                $service->updated_at,
                0.8,
                Url::CHANGE_FREQUENCY_WEEKLY,
            )));

            Project::where('is_active', true)->get()->each(fn (Project $project) => $sitemap->add($this->url(
                route('projects.show', ['slug' => $project->slug]),
                $project->updated_at,
                0.8,
                Url::CHANGE_FREQUENCY_MONTHLY,
            )));

            Template::active()->get()->each(fn (Template $template) => $sitemap->add($this->url(
                route('themes.show', ['slug' => $template->slug]),
                $template->updated_at,
                0.8,
                Url::CHANGE_FREQUENCY_WEEKLY,
            )));

            ServiceCategory::where('is_active', true)->get()->each(fn (ServiceCategory $category) => $sitemap->add($this->url(
                route('slug.handle', ['slug' => $category->slug]),
                $category->updated_at,
                0.7,
                Url::CHANGE_FREQUENCY_MONTHLY,
            )));

            MiniApp::active()->get()->each(function (MiniApp $app) use ($sitemap): void {
                $link = trim((string) $app->link);

                if ($link === '' || str_starts_with($link, '#') || preg_match('/^(https?:|mailto:|tel:)/i', $link)) {
                    return;
                }

                $sitemap->add($this->url(url('/'.ltrim($link, '/')), null, 0.6, Url::CHANGE_FREQUENCY_MONTHLY));
            });

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

    private function url(string $url, mixed $updatedAt, float $priority, string $frequency): Url
    {
        $tag = Url::create($url)
            ->setPriority($priority)
            ->setChangeFrequency($frequency);

        if ($updatedAt !== null) {
            $tag->setLastModificationDate($updatedAt);
        }

        return $tag;
    }
}
