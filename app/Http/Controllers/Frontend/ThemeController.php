<?php

namespace App\Http\Controllers\Frontend;

use App\Support\FrontendContent;
use Illuminate\Contracts\View\View;

class ThemeController extends FrontendController
{
    public function __construct(private readonly FrontendContent $content)
    {
    }

    public function index(): View
    {
        $themes = $this->content->themes();
        $seo = site_page_seo('themes', [
            'title' => 'Kho giao diện website theo ngành | WebApp Bắc Ninh',
            'description' => 'Khám phá kho giao diện website doanh nghiệp, bán hàng, landing page và website theo ngành. Lọc nhanh theo nhu cầu, lĩnh vực và mức đầu tư.',
        ]);

        return $this->page('frontend.site.themes.index', [
            'themes' => $themes,
            'pageTitle' => $seo['title'],
            'pageDescription' => $seo['description'],
            'pageKeywords' => $seo['keywords'] ?? '',
            'canonicalUrl' => $seo['canonical_url'] ?? request()->url(),
            'robots' => $seo['robots'] ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
            'activeMenu' => 'themes',
            'headerCta' => '#themeContact',
            'floatingCta' => '#themeContact',
            'extraStyles' => ['theme-library.css'],
            'extraScripts' => ['theme-library.js'],
            'ogImage' => $seo['og_image'] ?? frontend_asset('assets/images/project-corporate.webp'),
            'schemaItems' => collect($themes)->map(fn (array $theme): array => [
                'name' => $theme['name'],
                'url' => route('themes.show', $theme['slug']),
            ])->all(),
        ]);
    }

    public function detail(string $slug): View
    {
        $theme = $this->content->themeBySlug($slug);
        abort_if($theme === null, 404);

        return $this->page('frontend.site.themes.show', [
            'theme' => $theme,
            'relatedThemes' => $this->content->relatedThemes($theme),
            'pageTitle' => $theme['meta_title'],
            'pageDescription' => $theme['meta_description'].' Xem chi tiết phạm vi bàn giao, chức năng, thời gian và chi phí triển khai.',
            'pageKeywords' => $theme['meta_keywords'],
            'canonicalUrl' => $theme['canonical_url'] ?: request()->url(),
            'robots' => $theme['robots'],
            'activeMenu' => 'themes',
            'headerCta' => '#themeConsult',
            'floatingCta' => '#themeConsult',
            'extraStyles' => ['theme-library.css', 'theme-detail.css'],
            'extraScripts' => ['theme-detail.js'],
            'ogType' => 'product',
            'ogImage' => $theme['og_image_url'],
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
