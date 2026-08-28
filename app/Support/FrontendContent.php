<?php

namespace App\Support;

use App\Models\OperationService;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Template;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class FrontendContent
{
    /** @return array<int, array<string, mixed>> */
    public function themes(): array
    {
        return Template::query()
            ->active()
            ->ordered()
            ->get()
            ->map(fn (Template $theme): array => $this->theme($theme))
            ->all();
    }

    /** @return array<string, mixed>|null */
    public function themeBySlug(string $slug): ?array
    {
        $theme = Template::query()->active()->where('slug', $slug)->first();

        return $theme ? $this->theme($theme) : null;
    }

    /** @return array<int, array<string, mixed>> */
    public function projects(): array
    {
        return Project::query()
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderBy('order')
            ->get()
            ->map(fn (Project $project): array => $this->project($project))
            ->all();
    }

    /** @return array<string, mixed>|null */
    public function projectBySlug(string $slug): ?array
    {
        $project = Project::query()->where('is_active', true)->where('slug', $slug)->first();

        return $project ? $this->project($project) : null;
    }

    /** @return array<int, array<string, mixed>> */
    public function articles(): array
    {
        return Post::query()
            ->published()
            ->with('category')
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->get()
            ->map(fn (Post $post): array => $this->article($post))
            ->all();
    }

    /** @return array<string, mixed>|null */
    public function articleBySlug(string $slug): ?array
    {
        $article = Post::query()->published()->with('category')->where('slug', $slug)->first();

        return $article ? $this->article($article) : null;
    }

    /** @return array<int, array<string, mixed>> */
    public function websiteServices(): array
    {
        return Service::query()
            ->active()
            ->ordered()
            ->get()
            ->map(fn (Service $service): array => $this->service($service))
            ->all();
    }

    /** @return array<string, mixed>|null */
    public function websiteServiceBySlug(string $slug): ?array
    {
        $service = Service::query()->active()->where('slug', $slug)->first();

        return $service ? $this->service($service) : null;
    }

    /** @return array<int, array<string, mixed>> */
    public function operationServices(): array
    {
        return OperationService::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(fn (OperationService $service): array => $this->operationService($service))
            ->all();
    }

    /** @param array<int, array<string, mixed>> $websiteServices
     *  @param array<int, array<string, mixed>> $operationServices
     *  @return array{top: array<int, array<string, mixed>>, topbar: array<string, mixed>, website: array<int, array<string, mixed>>, operations: array<int, array<string, mixed>>}
     */
    public function headerNavigation(array $websiteServices = [], array $operationServices = []): array
    {
        $serviceIndex = collect($websiteServices)->keyBy('slug')->all();
        $operationIndex = collect($operationServices)->keyBy('slug')->all();
        $items = $this->menuTree('header');

        if ($items === []) {
            $items = $this->defaultHeaderMenu();
        }

        $top = array_map(
            fn (array $item): array => $this->normalizeMenuItem($item, $serviceIndex, $operationIndex),
            $items,
        );

        $topbar = collect($top)->firstWhere('active_key', 'about')
            ?? $this->normalizeMenuItem(['title' => 'Giới thiệu', 'route_name' => 'about', 'children' => []], $serviceIndex, $operationIndex);
        $top = collect($top)
            ->reject(fn (array $item): bool => in_array($item['active_key'] ?? '', ['home', 'about'], true))
            ->values()
            ->all();
        $website = collect($top)->firstWhere('mega', 'website');
        $operations = collect($top)->firstWhere('mega', 'operations');

        return [
            'top' => $top,
            'topbar' => $topbar,
            'website' => $website['children'] ?? [],
            'operations' => $operations['children'] ?? [],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function menuTree(string $location): array
    {
        return Cache::rememberForever(app(FrontendMenuCache::class)->key($location), function () use ($location): array {
            try {
                $menu = DB::table('menus')
                    ->where('location', $location)
                    ->where('is_active', true)
                    ->first(['id']);

                if (! $menu) {
                    return [];
                }

                $items = DB::table('menu_items')
                    ->where('menu_id', $menu->id)
                    ->where('is_active', true)
                    ->orderBy('position')
                    ->orderBy('id')
                    ->get();

                $itemsByParent = $items->groupBy(fn ($item): int => (int) ($item->parent_id ?? 0));
                $build = function (int $parentId) use (&$build, $itemsByParent): array {
                    return $itemsByParent->get($parentId, collect())
                        ->map(fn ($item): array => [
                            'title' => (string) $item->title,
                            'route_name' => $item->route_name,
                            'route_parameter' => $item->route_parameter,
                            'url' => $item->url,
                            'icon' => $item->icon,
                            'target' => $item->target ?: '_self',
                            'children' => $build((int) $item->id),
                        ])
                        ->values()
                        ->all();
                };

                return $build(0);
            } catch (\Throwable) {
                return [];
            }
        });
    }

    /** @param array<string, array<string, mixed>> $serviceIndex
     *  @param array<string, array<string, mixed>> $operationIndex
     *  @return array<string, mixed>
     */
    private function normalizeMenuItem(array $item, array $serviceIndex, array $operationIndex): array
    {
        $routeName = (string) ($item['route_name'] ?? '');
        $mega = match ($routeName) {
            'services.index' => 'website',
            'operations.index' => 'operations',
            default => null,
        };
        $lookup = $mega === 'website' ? $serviceIndex : ($mega === 'operations' ? $operationIndex : []);
        $parameter = (string) ($item['route_parameter'] ?? '');
        $content = $lookup[$parameter] ?? [];

        $item['url'] = $this->menuUrl($item);
        $item['target'] = $item['target'] ?: '_self';
        $item['active_key'] = $this->menuActiveKey($routeName);
        $item['mega'] = $mega;
        $item['label'] = $content['eyebrow'] ?? $item['title'];
        $item['description'] = $content['highlight'] ?? '';
        $item['icon'] = $content['icon'] ?? $item['icon'] ?? ($mega === 'operations' ? 'fa-solid fa-gears' : 'fa-solid fa-layer-group');
        $item['submenu_key'] = $content['menu_key'] ?? $parameter;
        $item['children'] = array_map(
            fn (array $child): array => $this->normalizeMenuItem($child, $serviceIndex, $operationIndex),
            $item['children'] ?? [],
        );

        return $item;
    }

    /** @param array<string, mixed> $item */
    private function menuUrl(array $item): string
    {
        if (filled($item['url'] ?? null)) {
            return (string) $item['url'];
        }

        $routeName = (string) ($item['route_name'] ?? '');
        if ($routeName === '' || ! Route::has($routeName)) {
            return '#';
        }

        return filled($item['route_parameter'] ?? null)
            ? route($routeName, $item['route_parameter'])
            : route($routeName);
    }

    private function menuActiveKey(string $routeName): string
    {
        return match (true) {
            $routeName === 'home' => 'home',
            str_starts_with($routeName, 'services.') => 'website-service',
            str_starts_with($routeName, 'operations.') => 'operations',
            str_starts_with($routeName, 'themes.') => 'themes',
            str_starts_with($routeName, 'projects.') => 'projects',
            $routeName === 'pricing' => 'pricing',
            $routeName === 'agency' => 'agency',
            str_starts_with($routeName, 'articles.') => 'knowledge',
            $routeName === 'about' => 'about',
            $routeName === 'contact' => 'contact',
            default => '',
        };
    }

    /** @return array<int, array<string, mixed>> */
    private function defaultHeaderMenu(): array
    {
        return [
            ['title' => 'Trang chủ', 'route_name' => 'home', 'children' => []],
            ['title' => 'Thiết kế website', 'route_name' => 'services.index', 'children' => [
                ['title' => 'Website doanh nghiệp', 'route_name' => 'services.show', 'route_parameter' => 'website-doanh-nghiep', 'children' => []],
                ['title' => 'Website bán hàng', 'route_name' => 'services.show', 'route_parameter' => 'website-ban-hang', 'children' => []],
                ['title' => 'Landing page', 'route_name' => 'services.show', 'route_parameter' => 'landing-page', 'children' => []],
                ['title' => 'Thiết kế lại website', 'route_name' => 'services.show', 'route_parameter' => 'thiet-ke-lai-website', 'children' => []],
            ]],
            ['title' => 'Kho giao diện', 'route_name' => 'themes.index', 'children' => []],
            ['title' => 'Dịch vụ vận hành', 'route_name' => 'operations.index', 'children' => []],
            ['title' => 'Dự án', 'route_name' => 'projects.index', 'children' => []],
            ['title' => 'Bảng giá', 'route_name' => 'pricing', 'children' => []],
            ['title' => 'Hợp tác Agency', 'route_name' => 'agency', 'children' => []],
            ['title' => 'Kiến thức', 'route_name' => 'articles.index', 'children' => []],
            ['title' => 'Giới thiệu', 'route_name' => 'about', 'children' => []],
            ['title' => 'Liên hệ', 'route_name' => 'contact', 'children' => []],
        ];
    }

    /** @return array<string, mixed>|null */
    public function operationServiceBySlug(string $slug): ?array
    {
        $service = OperationService::query()->where('is_active', true)->where('slug', $slug)->first();

        return $service ? $this->operationService($service) : null;
    }

    /** @return array<int, array<string, mixed>> */
    public function relatedThemes(array $theme, int $limit = 3): array
    {
        return collect($this->themes())
            ->reject(fn (array $item): bool => $item['id'] === $theme['id'])
            ->sortByDesc(fn (array $item): int => ($item['industry'] === $theme['industry'] ? 1000 : 0) + (int) $item['featured'])
            ->take($limit)
            ->values()
            ->all();
    }

    /** @return array<int, array<string, mixed>> */
    public function relatedProjects(array $project, int $limit = 3): array
    {
        return collect($this->projects())
            ->reject(fn (array $item): bool => $item['id'] === $project['id'])
            ->sortByDesc(fn (array $item): int => ($item['category'] === $project['category'] ? 1000 : 0) + (int) $item['featured'])
            ->take($limit)
            ->values()
            ->all();
    }

    /** @return array<int, array<string, mixed>> */
    public function relatedArticles(array $article, int $limit = 3): array
    {
        return collect($this->articles())
            ->reject(fn (array $item): bool => $item['id'] === $article['id'])
            ->sortByDesc(fn (array $item): int => ($item['category'] === $article['category'] ? 1000 : 0) + (int) $item['featured'])
            ->take($limit)
            ->values()
            ->all();
    }

    /** @return array<string, mixed> */
    private function theme(Template $theme): array
    {
        $data = $theme->data ?? [];
        $includedFeatures = $this->jsonList($theme->content);
        if ($includedFeatures !== []) {
            $data['includedFeatures'] = $includedFeatures;
        } elseif (filled($theme->content)) {
            $data['includedFeatures'] = [trim(strip_tags($theme->content))];
        }
        $gallery = $this->galleryUrls($theme, Arr::get($data, 'gallery', []), $theme->image_url);
        $seo = $this->seoData($theme, $theme->name.' | '.site_config('name'), $theme->description ?: '', $theme->image_url);

        return array_replace([
            'id' => $theme->id,
            'code' => $theme->code ?: 'WABN-'.str_pad((string) $theme->id, 3, '0', STR_PAD_LEFT),
            'name' => $theme->name,
            'slug' => $theme->slug,
            'type' => $theme->type ?: 'dich-vu',
            'typeLabel' => $this->label($theme->type ?: 'dich-vu'),
            'industry' => $theme->industry ?: 'khac',
            'industryLabel' => $this->label($theme->industry ?: 'Khác'),
            'price' => (int) ($theme->price ?? 0),
            'year' => $theme->year ?: now()->year,
            'duration' => $theme->duration ?: 'Liên hệ để tư vấn',
            'description' => $theme->description ?: '',
            'badge' => '',
            'tags' => [],
            'featureKeys' => [],
            'featured' => $theme->is_featured ? 100 : 0,
            'audiences' => [],
            'pages' => [],
            'includedFeatures' => [],
            'customizations' => [],
        ], $data, [
            'id' => $theme->id,
            'code' => $theme->code ?: Arr::get($data, 'code', 'WABN-'.str_pad((string) $theme->id, 3, '0', STR_PAD_LEFT)),
            'name' => $theme->name,
            'slug' => $theme->slug,
            'type' => $theme->type ?: Arr::get($data, 'type', 'dich-vu'),
            'industry' => $theme->industry ?: Arr::get($data, 'industry', 'khac'),
            'price' => (int) ($theme->price ?? Arr::get($data, 'price', 0)),
            'year' => $theme->year ?: Arr::get($data, 'year', now()->year),
            'duration' => $theme->duration ?: Arr::get($data, 'duration', 'Liên hệ để tư vấn'),
            'description' => $theme->description ?: Arr::get($data, 'description', ''),
            'image_url' => $theme->image_url,
            'gallery_urls' => $gallery,
            'meta_title' => $seo['meta_title'],
            'meta_description' => $seo['meta_description'],
            'meta_keywords' => $seo['meta_keywords'],
            'canonical_url' => $seo['canonical_url'],
            'robots' => $seo['robots'],
            'og_image_url' => $seo['og_image_url'],
        ]);
    }

    /** @return array<string, mixed> */
    private function project(Project $project): array
    {
        $data = $project->data ?? [];
        $gallery = $this->galleryUrls($project, $project->gallery ?: Arr::get($data, 'gallery', []), $project->image_url);
        $category = $project->category ?: Arr::get($data, 'category', 'dich-vu');
        $industry = $project->industry ?: Arr::get($data, 'industry', 'khac');
        $seo = $this->seoData($project, $project->title.' | Dự án '.site_config('name'), $project->description ?: $project->excerpt ?: '', $project->image_url);

        return array_replace([
            'id' => $project->id,
            'code' => $project->code ?: 'DA-'.str_pad((string) $project->id, 3, '0', STR_PAD_LEFT),
            'title' => $project->title,
            'slug' => $project->slug,
            'category' => $category,
            'category_label' => $this->label($category),
            'industry' => $industry,
            'industry_label' => $this->label($industry),
            'year' => $project->year ?: now()->year,
            'featured' => $project->is_featured ? 100 : 0,
            'excerpt' => $project->description ?: $project->excerpt ?: '',
            'client' => 'Đang cập nhật',
            'duration' => 'Liên hệ để tư vấn',
            'website_type' => 'Website theo yêu cầu',
            'challenge' => '',
            'solution' => '',
            'deliverables' => [],
            'technologies' => [],
            'results' => [],
        ], $data, [
            'id' => $project->id,
            'code' => $project->code ?: Arr::get($data, 'code', 'DA-'.str_pad((string) $project->id, 3, '0', STR_PAD_LEFT)),
            'title' => $project->title,
            'slug' => $project->slug,
            'category' => $category,
            'category_label' => Arr::get($data, 'category_label', $this->label($category)),
            'industry' => $industry,
            'industry_label' => Arr::get($data, 'industry_label', $this->label($industry)),
            'year' => $project->year ?: Arr::get($data, 'year', now()->year),
            'featured' => $project->is_featured ? max(1, (int) Arr::get($data, 'featured', 0)) : (int) Arr::get($data, 'featured', 0),
            'excerpt' => $project->description ?: $project->excerpt ?: Arr::get($data, 'excerpt', ''),
            'client' => $project->client ?: Arr::get($data, 'client', 'Đang cập nhật'),
            'duration' => $project->duration ?: Arr::get($data, 'duration', 'Liên hệ để tư vấn'),
            'website_type' => $project->website_type ?: Arr::get($data, 'website_type', 'Website theo yêu cầu'),
            'challenge' => $project->challenge ?: Arr::get($data, 'challenge', ''),
            'solution' => $project->solution ?: Arr::get($data, 'solution', ''),
            'deliverables' => $project->deliverables ?: Arr::get($data, 'deliverables', []),
            'technologies' => $project->technologies ?: Arr::get($data, 'technologies', []),
            'results' => $project->results ?: Arr::get($data, 'results', []),
            'image_url' => $project->image_url,
            'gallery_urls' => $gallery,
            'meta_title' => $seo['meta_title'],
            'meta_description' => $seo['meta_description'],
            'meta_keywords' => $seo['meta_keywords'],
            'canonical_url' => $seo['canonical_url'],
            'robots' => $seo['robots'],
            'og_image_url' => $seo['og_image_url'],
        ]);
    }

    /** @return array<string, mixed> */
    private function article(Post $post): array
    {
        $data = $post->data ?? [];
        $category = $post->category?->slug ?: Arr::get($data, 'category', 'kien-thuc');
        $sections = $this->jsonList($post->content);
        $htmlContent = null;
        if ($sections === [] && filled($post->content)) {
            $htmlContent = $post->content;
        } elseif ($sections === []) {
            $sections = Arr::get($data, 'sections', []);
        }
        if ($sections === []) {
            $sections = [[
                'title' => 'Nội dung',
                'paragraphs' => [trim(strip_tags($post->content))],
                'bullets' => [],
            ]];
        }
        $seo = $this->seoData($post, $post->title.' | '.site_config('name'), $post->summary ?: '', $post->og_image_url);

        return array_replace([
            'id' => $post->id,
            'slug' => $post->slug,
            'title' => $post->title,
            'excerpt' => $post->summary ?: '',
            'intro' => $post->summary ?: '',
            'category' => $category,
            'category_label' => $post->category?->name ?: $this->label($category),
            'published_at' => $post->published_at?->format('d/m/Y') ?: '',
            'read_time' => $post->read_time ? $post->read_time.' phút đọc' : 'Đang cập nhật',
            'featured' => $post->is_featured ? 100 : 0,
            'sections' => $sections,
            'html_content' => $htmlContent,
        ], $data, [
            'id' => $post->id,
            'slug' => $post->slug,
            'title' => $post->title,
            'excerpt' => $post->summary ?: Arr::get($data, 'excerpt', ''),
            'intro' => Arr::get($data, 'intro', $post->summary ?: ''),
            'category' => $category,
            'category_label' => $post->category?->name ?: Arr::get($data, 'category_label', $this->label($category)),
            'published_at' => $post->published_at?->format('d/m/Y') ?: Arr::get($data, 'published_at', ''),
            'read_time' => $post->read_time ? $post->read_time.' phút đọc' : Arr::get($data, 'read_time', 'Đang cập nhật'),
            'featured' => $post->is_featured ? max(1, (int) Arr::get($data, 'featured', 0)) : (int) Arr::get($data, 'featured', 0),
            'sections' => $sections,
            'html_content' => $htmlContent,
            'image_url' => $post->featured_image_url,
            'meta_title' => $seo['meta_title'],
            'meta_description' => $seo['meta_description'],
            'meta_keywords' => $seo['meta_keywords'],
            'canonical_url' => $seo['canonical_url'],
            'robots' => $seo['robots'],
            'og_image_url' => $seo['og_image_url'],
        ]);
    }

    /** @return array<string, mixed> */
    private function service(Service $service): array
    {
        $data = $service->data ?? [];
        $features = $this->jsonList($service->content);
        if ($features !== []) {
            $data['features'] = $features;
        } elseif (filled($service->content)) {
            $data['features'] = [[
                'icon' => $service->icon ?: 'fa-solid fa-layer-group',
                'title' => 'Nội dung chi tiết',
                'text' => trim(strip_tags($service->content)),
            ]];
        }

        return $this->serviceData($service, $data, $service->image_url, $service->secondary_image_url);
    }

    /** @return array<string, mixed> */
    private function operationService(OperationService $service): array
    {
        return $this->serviceData($service, $service->data ?? [], $service->image_url, $service->secondary_image_url, 'Theo gói hoặc theo tháng');
    }

    /** @param Service|OperationService $service
     *  @return array<string, mixed>
     */
    private function serviceData(Service|OperationService $service, array $data, string $imageUrl, string $secondaryImageUrl, string $defaultTimeline = 'Liên hệ để tư vấn'): array
    {
        $title = $service->title;
        $menuKey = $service->menu_key ?: Arr::get($data, 'menu_key', $service->slug);
        $seo = $this->seoData($service, $title.' | '.site_config('name'), $service->description ?: '', $imageUrl);

        return array_replace([
            'id' => $service->id,
            'slug' => $service->slug,
            'menu_key' => $menuKey,
            'title' => $title,
            'eyebrow' => $service->eyebrow ?: Str::upper($title),
            'highlight' => $service->highlight ?: '',
            'description' => $service->description ?: '',
            'icon' => $service->icon ?: 'fa-solid fa-layer-group',
            'price_from' => $service->price_from ?: 'Liên hệ để tư vấn',
            'meta_title' => $service->meta_title ?: $title,
            'meta_description' => $service->meta_description ?: '',
            'timeline' => $service instanceof Service ? ($service->timeline ?: $defaultTimeline) : $defaultTimeline,
            'cadence' => $service instanceof OperationService ? ($service->cadence ?: $defaultTimeline) : $defaultTimeline,
            'cta' => 'Nhận tư vấn',
            'need_value' => $title,
            'audiences' => [],
            'problems' => [],
            'features' => [],
            'pages' => [],
            'packages' => [],
            'faqs' => [],
            'scope' => [],
            'deliverables' => [],
            'process' => [],
        ], $data, [
            'id' => $service->id,
            'slug' => $service->slug,
            'menu_key' => $menuKey,
            'title' => $title,
            'eyebrow' => $service->eyebrow ?: Arr::get($data, 'eyebrow', Str::upper($title)),
            'highlight' => $service->highlight ?: Arr::get($data, 'highlight', ''),
            'description' => $service->description ?: Arr::get($data, 'description', ''),
            'icon' => $service->icon ?: Arr::get($data, 'icon', 'fa-solid fa-layer-group'),
            'price_from' => $service->price_from ?: Arr::get($data, 'price_from', 'Liên hệ để tư vấn'),
            'meta_title' => $service->meta_title ?: Arr::get($data, 'meta_title', $title),
            'meta_description' => $service->meta_description ?: Arr::get($data, 'meta_description', ''),
            'timeline' => $service instanceof Service ? ($service->timeline ?: Arr::get($data, 'timeline', $defaultTimeline)) : $defaultTimeline,
            'cadence' => $service instanceof OperationService ? ($service->cadence ?: Arr::get($data, 'cadence', $defaultTimeline)) : $defaultTimeline,
            'image_url' => $imageUrl,
            'secondary_image_url' => $secondaryImageUrl,
            'meta_title' => $seo['meta_title'],
            'meta_description' => $seo['meta_description'],
            'meta_keywords' => $seo['meta_keywords'],
            'canonical_url' => $seo['canonical_url'],
            'robots' => $seo['robots'],
            'og_image_url' => $seo['og_image_url'],
        ]);
    }

    /** @return array<string, string|null> */
    private function seoData(object $model, string $fallbackTitle, string $fallbackDescription, string $fallbackImage): array
    {
        $data = $model->getAttribute('data');
        $seo = is_array($data) && is_array(Arr::get($data, 'seo')) ? Arr::get($data, 'seo') : [];
        $ogImage = $model->getAttribute('og_image') ?: Arr::get($seo, 'og_image');

        if (blank($ogImage) && isset($model->og_image_url)) {
            $ogImage = $model->og_image_url;
        }

        return [
            'meta_title' => (string) ($model->getAttribute('meta_title') ?: Arr::get($seo, 'meta_title', Arr::get($seo, 'title', $fallbackTitle))),
            'meta_description' => (string) ($model->getAttribute('meta_description') ?: Arr::get($seo, 'meta_description', Arr::get($seo, 'description', $fallbackDescription))),
            'meta_keywords' => (string) ($model->getAttribute('meta_keywords') ?: Arr::get($seo, 'meta_keywords', '')),
            'canonical_url' => Arr::get($seo, 'canonical_url'),
            'robots' => (string) Arr::get($seo, 'robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'),
            'og_image_url' => $this->imageUrl((string) ($ogImage ?: $fallbackImage)),
        ];
    }

    /** @param array<int, string> $fallbackGallery
     *  @return array<int, string>
     */
    private function galleryUrls(object $model, array $fallbackGallery, string $fallbackImage): array
    {
        $media = method_exists($model, 'getMedia')
            ? $model->getMedia('gallery')->map(fn ($item): string => $item->getUrl())->all()
            : [];

        $gallery = $media !== []
            ? $media
            : collect($fallbackGallery)->map(fn (string $image): string => $this->imageUrl($image))->all();

        return $gallery !== [] ? $gallery : [$fallbackImage];
    }

    private function imageUrl(string $path): string
    {
        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        $path = ltrim($path, '/');
        if (Str::startsWith($path, ['frontend/', 'storage/', 'images/', 'uploads/'])) {
            return asset($path);
        }

        if (Str::startsWith($path, 'assets/')) {
            return frontend_asset($path);
        }

        return frontend_asset('assets/images/'.$path);
    }

    private function label(string $value): string
    {
        return Str::of($value)->replace(['-', '_'], ' ')->title()->toString();
    }

    /** @return array<int, mixed> */
    private function jsonList(?string $value): array
    {
        if (! is_string($value) || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) && array_is_list($decoded) ? $decoded : [];
    }
}
