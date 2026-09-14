<?php

namespace App\Http\Controllers\Frontend;

use App\Support\FrontendContent;
use Illuminate\Contracts\View\View;

class PageController extends FrontendController
{
    public function show(string $page = 'index'): View
    {
        $pages = [
            'index' => ['Website – Phần mềm – Vận hành số', 'home'],
            'dich-vu' => ['Dịch vụ website, phần mềm và vận hành số', 'services'],
            'hosting-domain-email' => ['Hosting, tên miền và email doanh nghiệp', 'hosting'],
            'giai-phap' => ['Giải pháp số cho doanh nghiệp', 'solutions'],
            'san-pham' => ['Sản phẩm website và phần mềm', 'products'],
            'du-an' => ['Dự án tiêu biểu', 'projects'],
            'bang-gia' => ['Bảng giá dịch vụ', 'pricing'],
            'tin-tuc' => ['Blog – Kiến thức website và vận hành số', 'articles'],
            'lien-he' => ['Liên hệ tư vấn', 'contact'],
        ];
        abort_unless(isset($pages[$page]), 404);
        [$title, $key] = $pages[$page];
        $seo = site_page_seo($key, [
            'title' => $title.' | '.site_config('name'),
            'description' => 'WebApp Bắc Ninh đồng hành cùng doanh nghiệp với giải pháp thiết kế website, phát triển phần mềm và vận hành số.',
        ]);
        $content = app(FrontendContent::class);
        $catalogItems = match ($page) {
            'du-an' => $content->projects(),
            'tin-tuc', 'index' => $content->articles(),
            default => [],
        };

        return $this->page('frontend.pages.'.$page, [
            'pageTitle' => $seo['title'],
            'pageDescription' => $seo['description'],
            'canonicalUrl' => $seo['canonical_url'] ?? request()->url(),
            'ogImage' => $seo['og_image'] ?? asset('frontend/images/hero-home.webp'),
            'bodyClass' => 'page-'.($page === 'index' ? 'home' : $page),
            'catalogItems' => $catalogItems,
            'featuredArticles' => collect($catalogItems)->sortByDesc('featured')->take(4)->values()->all(),
            'catalogCategories' => collect($catalogItems)->unique('category')->map(fn (array $item): array => ['slug' => $item['category'], 'label' => $item['category_label']])->values()->all(),
        ]);
    }
}
