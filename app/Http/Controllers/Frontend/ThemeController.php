<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Template;
use App\Models\ThemeFeature;
use Illuminate\Contracts\View\View;

class ThemeController extends FrontendController
{
    public function index(): View
    {
        $themes = Template::query()->forCatalog()->get();
        $seo = site_page_seo('themes', [
            'title' => 'Kho giao diện website theo ngành | WebApp Bắc Ninh',
            'description' => 'Khám phá kho giao diện website doanh nghiệp, bán hàng, landing page và website theo ngành. Lọc nhanh theo nhu cầu, lĩnh vực và mức đầu tư.',
        ]);

        return $this->page('frontend.site.themes.index', [
            'themes' => $themes,
            'industries' => $themes->pluck('category')->filter()->unique('id')->sortBy('name')->values(),
            'features' => ThemeFeature::query()->whereHas('templates', fn ($query) => $query->active())->orderBy('name')->get(),
            'pageTitle' => $seo['title'],
            'pageDescription' => $seo['description'],
            'pageKeywords' => $seo['keywords'] ?? '',
            'canonicalUrl' => $seo['canonical_url'] ?? request()->url(),
            'robots' => $seo['robots'] ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
            'extraScripts' => ['theme-library.js'],
            'ogImage' => $seo['og_image'] ?? frontend_asset('assets/images/project-corporate.webp'),
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

        return $this->page('frontend.site.themes.show', [
            'theme' => $theme,
            'relatedThemes' => $theme->related(),
            'pageTitle' => $theme->meta_title ?: $theme->name.' | '.site_config('name'),
            'pageDescription' => trim($description.' Xem chi tiết phạm vi bàn giao, chức năng, thời gian và chi phí triển khai.'),
            'extraScripts' => ['theme-detail.js'],
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
