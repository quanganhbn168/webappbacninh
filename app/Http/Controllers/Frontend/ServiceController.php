<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\PricingGroup;
use App\Models\OperationService;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ServiceController extends FrontendController
{
    public function index(): View
    {
        return $this->sitePage('website-design', 'site.services.website', [
            'landingServices' => Service::query()->active()->where('is_landing', true)->ordered()->get(),
            'operationServices' => OperationService::query()->active()->ordered()->take(4)->get(),
            'plans' => PricingPlan::query()->inGroup(PricingGroup::Website)->take(4)->get(),
            'schemaType' => 'Service',
            'schemaFaqs' => array_map(fn (array $faq): array => ['q' => $faq[0], 'a' => $faq[1]], self::WEBSITE_FAQS),
        ]);
    }

    public function detail(string|Service $service): View|RedirectResponse
    {
        if ($service instanceof Service) {
            return $this->dynamicDetail($service);
        }

        $landing = Service::query()->active()->where('is_landing', true)->with(['image', 'secondaryImage'])->where('slug', $service)->firstOrFail();

        return $this->page('site.services.show', [
            'service' => $landing,
            'pageTitle' => $landing->meta_title ?: $landing->title.' | '.site_config('name'),
            'pageDescription' => $landing->meta_description ?: (string) $landing->description,
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

        return $this->page('site.services.category', [
            'category' => $category,
            'services' => $services,
            'pageTitle' => $category->meta_title ?: $category->name.' | '.site_config('name'),
            'pageDescription' => $category->meta_description ?: ($category->description ?: ''),
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

        return $this->page('site.services.dynamic', [
            'service' => $service,
            'pageTitle' => $service->meta_title ?: $service->title.' | '.site_config('name'),
            'pageDescription' => $service->meta_description ?: ($service->description ?: ''),
            'canonicalUrl' => $service->url,
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

    /** Shown on /thiet-ke-website and in its FAQ schema. */
    public const WEBSITE_FAQS = [
        ['Thời gian thiết kế website mất bao lâu?', 'Website giới thiệu cơ bản thường cần khoảng 2 đến 4 tuần. Website bán hàng, đa ngôn ngữ hoặc có chức năng riêng cần khảo sát và chia tiến độ cụ thể.'],
        ['Doanh nghiệp cần chuẩn bị những gì?', 'Logo, thông tin công ty, danh sách dịch vụ hoặc sản phẩm, hình ảnh, dự án, thông tin liên hệ và một số website tham khảo nếu có. Trường hợp chưa có nội dung, WebApp Bắc Ninh có thể hỗ trợ xây dựng.'],
        ['Có thể tự cập nhật website sau bàn giao không?', 'Có. Website được bàn giao khu vực quản trị và hướng dẫn các thao tác cập nhật bài viết, dịch vụ, sản phẩm, hình ảnh và thông tin cơ bản.'],
        ['Website có hỗ trợ SEO không?', 'Có SEO nền tảng gồm cấu trúc URL, heading, title, description, sitemap, schema cơ bản và tối ưu hiển thị. SEO tăng trưởng từ khóa là dịch vụ vận hành riêng.'],
        ['Sau bàn giao có hỗ trợ tiếp không?', 'Có. Ngoài thời gian bảo hành, doanh nghiệp có thể dùng gói hosting, bảo trì, quản trị nội dung, SEO, Facebook hoặc nâng cấp chức năng theo nhu cầu.'],
    ];
}
