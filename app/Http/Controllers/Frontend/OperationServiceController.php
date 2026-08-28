<?php

namespace App\Http\Controllers\Frontend;

use App\Support\FrontendContent;
use Illuminate\Contracts\View\View;

class OperationServiceController extends FrontendController
{
    public function __construct(private readonly FrontendContent $content)
    {
    }

    public function index(): View
    {
        $seo = site_page_seo('operations', [
            'title' => 'Dịch vụ vận hành website, SEO và nội dung | WebApp Bắc Ninh',
            'description' => 'Hosting, bảo trì, quản trị website, đăng bài, SEO, nội dung Facebook và nâng cấp chức năng theo nhu cầu doanh nghiệp.',
        ]);

        return $this->page('frontend.site.pages.operations', [
            'operationServices' => $this->content->operationServices(),
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
            'schemaItems' => collect($this->content->operationServices())->map(fn (array $service): array => [
                'name' => $service['title'],
                'url' => route('operations.show', $service['slug']),
            ])->all(),
        ]);
    }

    public function detail(string $slug): View
    {
        $service = $this->content->operationServiceBySlug($slug);
        abort_if($service === null, 404);

        return $this->page('frontend.site.operations.show', [
            'service' => $service,
            'pageTitle' => $service['meta_title'],
            'pageDescription' => $service['meta_description'],
            'pageKeywords' => $service['meta_keywords'],
            'canonicalUrl' => $service['canonical_url'] ?: request()->url(),
            'robots' => $service['robots'],
            'activeMenu' => 'operations',
            'activeSubmenu' => $service['menu_key'],
            'headerCta' => '#operationServiceContact',
            'floatingCta' => '#operationServiceContact',
            'extraStyles' => ['content-pages.css', 'operation-service-detail.css'],
            'extraScripts' => [],
            'bodyClass' => 'page-operation-service page-operation-'.$service['menu_key'],
            'ogImage' => $service['og_image_url'],
            'schemaType' => 'Service',
            'schemaData' => ['serviceType' => $service['title']],
            'schemaFaqs' => $service['faqs'],
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => 'Dịch vụ vận hành', 'url' => route('operations.index')],
                ['name' => $service['title'], 'url' => request()->url()],
            ],
        ]);
    }
}
