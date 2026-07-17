<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Contracts\View\View;

class ThemeController extends FrontendController
{
    public function index(): View
    {
        return $this->page('frontend.site.themes.index', [
            'themes' => themes(),
            'pageTitle' => 'Kho giao diện website theo ngành | WebApp Bắc Ninh',
            'pageDescription' => 'Khám phá kho giao diện website doanh nghiệp, bán hàng, landing page và website theo ngành. Lọc nhanh theo nhu cầu, lĩnh vực và mức đầu tư.',
            'activeMenu' => 'themes',
            'headerCta' => '#themeContact',
            'floatingCta' => '#themeContact',
            'extraStyles' => ['theme-library.css'],
            'extraScripts' => ['theme-library.js'],
            'ogImage' => frontend_asset('assets/images/project-corporate.webp'),
            'schemaItems' => collect(themes())->map(fn (array $theme): array => [
                'name' => $theme['name'],
                'url' => route('themes.show', $theme['slug']),
            ])->all(),
        ]);
    }

    public function detail(string $slug): View
    {
        $theme = theme_by_slug($slug);
        abort_if($theme === null, 404);

        return $this->page('frontend.site.themes.show', [
            'theme' => $theme,
            'relatedThemes' => related_themes($theme),
            'pageTitle' => $theme['name'].' | '.site_config('name'),
            'pageDescription' => $theme['description'].' Xem chi tiết phạm vi bàn giao, chức năng, thời gian và chi phí triển khai.',
            'activeMenu' => 'themes',
            'headerCta' => '#themeConsult',
            'floatingCta' => '#themeConsult',
            'extraStyles' => ['theme-library.css', 'theme-detail.css'],
            'extraScripts' => ['theme-detail.js'],
            'ogType' => 'product',
            'ogImage' => frontend_asset('assets/images/'.$theme['image']),
            'schemaType' => 'Product',
            'schemaData' => [
                'brand' => ['@type' => 'Brand', 'name' => site_config('name')],
                'offers' => [
                    '@type' => 'Offer',
                    'price' => (string) $theme['price'],
                    'priceCurrency' => 'VND',
                    'availability' => 'https://schema.org/InStock',
                ],
            ],
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => 'Kho giao diện', 'url' => route('themes.index')],
                ['name' => $theme['name'], 'url' => request()->url()],
            ],
        ]);
    }
}
