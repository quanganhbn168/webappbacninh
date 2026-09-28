<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\PricingGroup;
use App\Enums\ProductGroup;
use App\Models\OperationService;
use App\Models\PricingPlan;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Contracts\View\View;

/**
 * Fixed pages whose content is written in their Blade views (resources/views/site/pages).
 */
class PageController extends FrontendController
{
    public function services(): View
    {
        return $this->sitePage('services', 'site.pages.services', [
            'landingServices' => Service::query()->active()->where('is_landing', true)->ordered()->get(),
            'operationServices' => OperationService::query()->active()->ordered()->get(),
            'plans' => PricingPlan::query()->inGroup(PricingGroup::Website)->take(4)->get(),
            'schemaType' => 'Service',
        ]);
    }

    public function hosting(): View
    {
        return $this->sitePage('hosting', 'site.pages.hosting', [
            'plans' => PricingPlan::query()->inGroup(PricingGroup::Hosting)->get(),
            'schemaType' => 'Service',
        ]);
    }

    public function solutions(): View
    {
        return $this->sitePage('solutions', 'site.pages.solutions', [
            'projects' => Project::query()->forCatalog()->take(3)->get(),
        ]);
    }

    public function products(): View
    {
        $products = Product::query()->forCatalog()->get();

        return $this->sitePage('products', 'site.pages.products', [
            'products' => $products,
            'groups' => collect(ProductGroup::cases())->filter(fn (ProductGroup $group): bool => $products->contains('group', $group))->values(),
            'schemaType' => 'CollectionPage',
            'schemaItems' => $products->map(fn (Product $product): array => ['name' => $product->name, 'url' => $product->detail_url ? url($product->detail_url) : route('products')])->all(),
        ]);
    }

    public function pricing(): View
    {
        $plans = PricingPlan::query()->active()->get()->groupBy(fn (PricingPlan $plan): string => $plan->group->value);

        return $this->sitePage('pricing', 'site.pages.pricing', [
            'plans' => $plans,
            'tabs' => collect(PricingGroup::tabs())->filter(fn (PricingGroup $group): bool => $plans->has($group->value))->values(),
            'schemaType' => 'Service',
            'schemaFaqs' => self::PRICING_FAQS,
        ]);
    }

    public function contact(): View
    {
        return $this->sitePage('contact', 'site.pages.contact', ['schemaType' => 'ContactPage']);
    }

    public function about(): View
    {
        return $this->sitePage('about', 'site.pages.about', [
            'projects' => Project::query()->forCatalog()->take(3)->get(),
            'schemaType' => 'AboutPage',
        ]);
    }

    public function agency(): View
    {
        return $this->sitePage('agency', 'site.pages.agency', ['schemaType' => 'Service']);
    }

    public function legal(string $key): View
    {
        return $this->sitePage($key, 'site.pages.legal.'.$key);
    }

    /** Shown on /bang-gia and in its FAQ schema. */
    public const PRICING_FAQS = [
        ['q' => 'Bảng giá đã bao gồm những gì?', 'a' => 'Giá tham khảo gồm thiết kế, lập trình và bàn giao theo phạm vi của từng gói. Tên miền, hosting và thuế (nếu có) được ghi rõ trong báo giá chính thức.'],
        ['q' => 'Có phát sinh chi phí trong quá trình triển khai không?', 'a' => 'Mọi yêu cầu ngoài phạm vi đã chốt sẽ được trao đổi và xác nhận chi phí trước khi thực hiện.'],
        ['q' => 'Thời gian hoàn thành website là bao lâu?', 'a' => 'Thời gian phụ thuộc thiết kế, tính năng, mức độ tích hợp và tiến độ cung cấp nội dung.'],
        ['q' => 'Tôi có thể yêu cầu thêm tính năng riêng không?', 'a' => 'Có. Các tính năng theo nghiệp vụ riêng sẽ được khảo sát, đánh giá và báo giá bổ sung.'],
        ['q' => 'Sau khi bàn giao có được hỗ trợ kỹ thuật không?', 'a' => 'Có thể lựa chọn gói hỗ trợ phù hợp. Phạm vi và thời gian hỗ trợ được quy định trong hợp đồng.'],
        ['q' => 'Có ưu đãi nào cho doanh nghiệp, tổ chức không?', 'a' => 'Hãy gửi phạm vi dự án và nhu cầu hợp tác để nhận phương án chi phí phù hợp.'],
    ];
}
