<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Contracts\View\View;

class OperationServiceController extends FrontendController
{
    public function index(): View
    {
        return $this->simplePage('frontend.site.pages.operations', 'Dịch vụ vận hành website, SEO và nội dung | WebApp Bắc Ninh', 'Hosting, bảo trì, quản trị website, đăng bài, SEO, nội dung Facebook và nâng cấp chức năng theo nhu cầu doanh nghiệp.', 'operations', ['content-pages.css', 'operation-service-detail.css'], 'page-operations', ['site-forms.js'], '#operationContact');
    }

    public function detail(string $slug): View
    {
        $service = collect(config('operation_services'))->first(
            fn (array $item): bool => pathinfo($item['route'], PATHINFO_FILENAME) === $slug
        );
        abort_if($service === null, 404);

        return $this->page('frontend.site.operations.show', [
            'service' => $service,
            'pageTitle' => $service['meta_title'],
            'pageDescription' => $service['meta_description'],
            'activeMenu' => 'operations',
            'activeSubmenu' => $service['menu_key'],
            'headerCta' => '#operationServiceContact',
            'floatingCta' => '#operationServiceContact',
            'extraStyles' => ['content-pages.css', 'operation-service-detail.css'],
            'extraScripts' => ['site-forms.js'],
            'bodyClass' => 'page-operation-service page-operation-'.$service['menu_key'],
            'ogImage' => frontend_asset($service['image']),
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
