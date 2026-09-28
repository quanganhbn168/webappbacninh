<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Contracts\View\View;

class StaticPageController extends FrontendController
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
        $catalogItems = match ($page) {
            'du-an' => Project::query()->forCatalog()->get(),
            'tin-tuc', 'index' => Post::query()->forCatalog()->get(),
            default => collect(),
        };

        return $this->page('frontend.pages.'.$page, [
            'pageTitle' => $seo['title'],
            'pageDescription' => $seo['description'],
            'canonicalUrl' => $seo['canonical_url'] ?? request()->url(),
            'ogImage' => $seo['og_image'] ?? asset('frontend/images/hero-home.webp'),
            'bodyClass' => 'page-'.($page === 'index' ? 'home' : $page),
            'catalogItems' => $catalogItems,
            'featuredArticles' => $catalogItems->sortByDesc('is_featured')->take(4)->values(),
            'catalogCategories' => $catalogItems->unique('category_slug')->map(fn (Post|Project $item): array => ['slug' => $item->category_slug, 'label' => $item->category_label])->values()->all(),
        ]);
    }
}
