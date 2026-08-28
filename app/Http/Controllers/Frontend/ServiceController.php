<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Support\FrontendContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ServiceController extends FrontendController
{
    public function __construct(private readonly FrontendContent $content)
    {
    }

    public function index(): View
    {
        return $this->simplePage('frontend.site.pages.website-service', 'Dịch vụ thiết kế website tại Bắc Ninh | WebApp Bắc Ninh', 'Thiết kế website doanh nghiệp, website bán hàng, landing page và website theo ngành tại Bắc Ninh. Giao diện phù hợp, dễ quản trị, SEO nền tảng và hỗ trợ lâu dài.', 'website-service', ['website-service.css'], 'page-website-service', [], '#websiteConsult');
    }

    public function detail(string|Service $service): View|RedirectResponse
    {
        if ($service instanceof Service) {
            return $this->dynamicDetail($service);
        }

        $landing = $this->content->websiteServiceBySlug($service);
        abort_if($landing === null, 404);

        return $this->page('frontend.site.services.show', [
            'service' => $landing,
            'pageTitle' => $landing['meta_title'],
            'pageDescription' => $landing['meta_description'],
            'pageKeywords' => $landing['meta_keywords'],
            'canonicalUrl' => $landing['canonical_url'] ?: request()->url(),
            'robots' => $landing['robots'],
            'activeMenu' => 'website-service',
            'activeSubmenu' => $landing['menu_key'],
            'headerCta' => '#serviceContact',
            'floatingCta' => '#serviceContact',
            'extraStyles' => ['website-service-detail.css'],
            'bodyClass' => 'page-service-detail page-service-'.$landing['menu_key'],
            'ogImage' => $landing['og_image_url'],
            'schemaType' => 'Service',
            'schemaData' => ['serviceType' => $landing['title']],
            'schemaFaqs' => $landing['faqs'],
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => 'Thiết kế website', 'url' => route('services.index')],
                ['name' => $landing['title'], 'url' => request()->url()],
            ],
        ]);
    }

    public function servicesByCate(ServiceCategory $category): View
    {
        abort_unless($category->is_active, 404);

        $services = $category->services()->active()->ordered()->get();

        return $this->page('frontend.site.services.category', [
            'category' => $category,
            'services' => $services,
            'pageTitle' => $category->meta_title ?: $category->name.' | '.site_config('name'),
            'pageDescription' => $category->meta_description ?: ($category->description ?: ''),
            'activeMenu' => 'website-service',
            'headerCta' => '#categoryContact',
            'floatingCta' => '#categoryContact',
            'extraStyles' => ['content-pages.css'],
            'bodyClass' => 'page-service-category',
            'schemaType' => 'CollectionPage',
            'schemaItems' => $services->map(fn (Service $service): array => [
                'name' => $service->title,
                'url' => route('slug.handle', ['slug' => $service->slug]),
            ])->all(),
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => $category->name, 'url' => request()->url()],
            ],
        ]);
    }

    private function dynamicDetail(Service $service): View|RedirectResponse
    {
        $landing = collect(config('website_services'))->firstWhere('slug', $service->slug);
        if ($landing !== null) {
            return redirect()->route('services.show', $landing['slug'], 301);
        }

        abort_unless($service->is_active, 404);

        $service->loadMissing('category');
        return $this->page('frontend.site.services.dynamic', [
            'service' => $service,
            'pageTitle' => $service->meta_title ?: $service->title.' | '.site_config('name'),
            'pageDescription' => $service->meta_description ?: ($service->description ?: ''),
            'pageKeywords' => $service->meta_keywords ?? '',
            'canonicalUrl' => data_get($service->data, 'seo.canonical_url') ?: request()->url(),
            'robots' => data_get($service->data, 'seo.robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'),
            'activeMenu' => 'website-service',
            'headerCta' => '#serviceContact',
            'floatingCta' => '#serviceContact',
            'extraStyles' => ['content-pages.css'],
            'bodyClass' => 'page-dynamic-service',
            'ogImage' => $service->image_url,
            'schemaType' => 'Service',
            'schemaData' => ['serviceType' => $service->title],
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => $service->category?->name ?? 'Dịch vụ', 'url' => $service->category ? route('slug.handle', ['slug' => $service->category->slug]) : route('services.index')],
                ['name' => $service->title, 'url' => request()->url()],
            ],
        ]);
    }
}
