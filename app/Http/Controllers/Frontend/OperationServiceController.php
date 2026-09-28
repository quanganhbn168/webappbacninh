<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\PricingGroup;
use App\Models\OperationService;
use App\Models\PricingPlan;
use Illuminate\Contracts\View\View;

class OperationServiceController extends FrontendController
{
    public function index(): View
    {
        $services = OperationService::query()->active()->ordered()->get();

        return $this->sitePage('operations', 'site.operations.index', [
            'operationServices' => $services,
            'plans' => PricingPlan::query()->inGroup(PricingGroup::Care)->get(),
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

        return $this->page('site.operations.show', [
            'service' => $service,
            'pageTitle' => $service->meta_title ?: $service->title.' | '.site_config('name'),
            'pageDescription' => $service->meta_description ?: (string) $service->description,
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
