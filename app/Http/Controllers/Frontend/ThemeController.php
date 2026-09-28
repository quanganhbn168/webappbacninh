<?php

namespace App\Http\Controllers\Frontend;

use App\Models\OperationService;
use App\Models\Template;
use App\Models\ThemeFeature;
use Illuminate\Contracts\View\View;

class ThemeController extends FrontendController
{
    public function index(): View
    {
        $themes = Template::query()->forCatalog()->get();

        return $this->sitePage('themes', 'site.themes.index', [
            'themes' => $themes,
            'industries' => $themes->pluck('category')->filter()->unique('id')->sortBy('name')->values(),
            'features' => ThemeFeature::query()->whereHas('templates', fn ($query) => $query->active())->orderBy('name')->get(),
            'schemaType' => 'CollectionPage',
            'schemaItems' => $themes->map(fn (Template $theme): array => [
                'name' => $theme->name,
                'url' => $theme->url,
            ])->all(),
        ]);
    }

    public function detail(string $slug): View
    {
        $theme = Template::query()->active()->with(['category', 'features', 'image'])->where('slug', $slug)->firstOrFail();
        $description = $theme->meta_description ?: $theme->description;

        return $this->page('site.themes.show', [
            'theme' => $theme,
            'relatedThemes' => $theme->related(),
            'operationServices' => OperationService::query()->active()->ordered()->take(4)->get(),
            'pageTitle' => $theme->meta_title ?: $theme->name.' | '.site_config('name'),
            'pageDescription' => trim($description.' Xem chi tiết phạm vi bàn giao, chức năng, thời gian và chi phí triển khai.'),
            'ogType' => 'product',
            'ogImage' => $theme->image_url,
            'schemaType' => 'Product',
            'schemaData' => [
                'brand' => ['@type' => 'Brand', 'name' => site_config('name')],
                'offers' => [
                    '@type' => 'Offer',
                    'price' => (string) $theme->price,
                    'priceCurrency' => 'VND',
                    'availability' => 'https://schema.org/InStock',
                ],
            ],
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => 'Kho giao diện', 'url' => route('themes.index')],
                ['name' => $theme->name, 'url' => $theme->url],
            ],
        ]);
    }
}
