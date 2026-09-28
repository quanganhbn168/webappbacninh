<?php

namespace App\Http\Controllers\Frontend;

use App\Models\OperationService;
use Illuminate\Contracts\View\View;

class OperationServiceController extends FrontendController
{
    public function index(): View
    {
        $services = OperationService::query()->active()->ordered()->get();
        $seo = site_page_seo('operations', [
            'title' => 'Dịch vụ vận hành website, SEO và nội dung | WebApp Bắc Ninh',
            'description' => 'Hosting, bảo trì, quản trị website, đăng bài, SEO, nội dung Facebook và nâng cấp chức năng theo nhu cầu doanh nghiệp.',
        ]);

        return $this->page('frontend.site.pages.operations', [
            'operationServices' => $services,
            'pageTitle' => $seo['title'],
            'pageDescription' => $seo['description'],
            'pageKeywords' => $seo['keywords'] ?? '',
            'canonicalUrl' => $seo['canonical_url'] ?? request()->url(),
            'robots' => $seo['robots'] ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
            'activeMenu' => 'operations',
            'headerCta' => '#operationContact',
            'floatingCta' => '#operationContact',
            'extraStyles' => ['content-pages.css', 'operation-service-detail.css'],
            'bodyClass' => 'page-operations',
            'ogImage' => $seo['og_image'] ?? frontend_asset('assets/images/seo-operation.webp'),
            'schemaType' => 'CollectionPage',
            'schemaItems' => $services->map(fn (OperationService $service): array => [
                'name' => $service->title,
                'url' => $service->url,
            ])->all(),
        ]);
    }

    public function detail(string $slug): View
    {
        $service = OperationService::query()->active()->with(['image', 'secondaryImage'])->where('slug', $slug)->firstOrFail();

        return $this->page('frontend.site.operations.show', [
            'service' => $service,
            'pageTitle' => $service->meta_title ?: $service->title.' | '.site_config('name'),
            'pageDescription' => $service->meta_description ?: (string) $service->description,
            'activeMenu' => 'operations',
            'activeSubmenu' => $service->menu_key ?: $service->slug,
            'headerCta' => '#operationServiceContact',
            'floatingCta' => '#operationServiceContact',
            'extraStyles' => ['content-pages.css', 'operation-service-detail.css'],
            'bodyClass' => 'page-operation-service page-operation-'.($service->menu_key ?: $service->slug),
            'ogImage' => $service->image_url,
            'schemaType' => 'Service',
            'schemaData' => ['serviceType' => $service->title],
            'schemaFaqs' => $service->faqs ?? [],
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => 'Dịch vụ vận hành', 'url' => route('operations.index')],
                ['name' => $service->title, 'url' => $service->url],
            ],
        ]);
    }
}
