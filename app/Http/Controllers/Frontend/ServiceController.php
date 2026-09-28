<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ServiceController extends FrontendController
{
    public function index(): View
    {
        return $this->simplePage('frontend.site.pages.website-service', 'Dịch vụ thiết kế website tại Bắc Ninh | WebApp Bắc Ninh', 'Thiết kế website doanh nghiệp, website bán hàng, landing page và website theo ngành tại Bắc Ninh. Giao diện phù hợp, dễ quản trị, SEO nền tảng và hỗ trợ lâu dài.', 'services', 'page-website-service');
    }

    public function detail(string|Service $service): View|RedirectResponse
    {
        if ($service instanceof Service) {
            return $this->dynamicDetail($service);
        }

        $landing = Service::query()->active()->where('is_landing', true)->with(['image', 'secondaryImage'])->where('slug', $service)->firstOrFail();

        return $this->page('frontend.site.services.show', [
            'service' => $landing,
            'pageTitle' => $landing->meta_title ?: $landing->title.' | '.site_config('name'),
            'pageDescription' => $landing->meta_description ?: (string) $landing->description,
            'bodyClass' => 'page-service-detail page-service-'.($landing->menu_key ?: $landing->slug),
            'ogImage' => $landing->image_url,
            'schemaType' => 'Service',
            'schemaData' => ['serviceType' => $landing->title],
            'schemaFaqs' => $landing->faqs ?? [],
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => 'Thiết kế website', 'url' => route('services.index')],
                ['name' => $landing->title, 'url' => $landing->url],
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
            'bodyClass' => 'page-service-category',
            'schemaType' => 'CollectionPage',
            'schemaItems' => $services->map(fn (Service $service): array => [
                'name' => $service->title,
                'url' => $service->url,
            ])->all(),
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => $category->name, 'url' => request()->url()],
            ],
        ]);
    }

    private function dynamicDetail(Service $service): View|RedirectResponse
    {
        if ($service->is_landing) {
            return redirect()->to($service->url, 301);
        }

        abort_unless($service->is_active, 404);

        $service->loadMissing('category');

        return $this->page('frontend.site.services.dynamic', [
            'service' => $service,
            'pageTitle' => $service->meta_title ?: $service->title.' | '.site_config('name'),
            'pageDescription' => $service->meta_description ?: ($service->description ?: ''),
            'canonicalUrl' => $service->url,
            'bodyClass' => 'page-dynamic-service',
            'ogImage' => $service->image_url,
            'schemaType' => 'Service',
            'schemaData' => ['serviceType' => $service->title],
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => $service->category?->name ?? 'Dịch vụ', 'url' => $service->category ? route('slug.handle', ['slug' => $service->category->slug]) : route('services.index')],
                ['name' => $service->title, 'url' => $service->url],
            ],
        ]);
    }
}
