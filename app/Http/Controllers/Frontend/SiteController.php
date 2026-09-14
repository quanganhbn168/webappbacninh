<?php

namespace App\Http\Controllers\Frontend;

use App\Support\FrontendContent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class SiteController extends FrontendController
{
    public function __construct(private readonly FrontendContent $content) {}

    public function about(): View
    {
        return $this->simplePage('frontend.site.pages.about', 'Giới thiệu WebApp Bắc Ninh', 'WebApp Bắc Ninh tập trung thiết kế website, đồng hành vận hành nội dung và triển khai kỹ thuật phù hợp doanh nghiệp nhỏ và vừa.', 'about', ['content-pages.css'], 'page-about');
    }

    public function agency(): View
    {
        return $this->simplePage('frontend.site.pages.agency', 'Hợp tác kỹ thuật và gia công website cho Agency | WebApp Bắc Ninh', 'Nhận triển khai website, landing page, bảo trì và module kỹ thuật theo hình thức giới thiệu khách, đồng triển khai hoặc white-label.', 'agency', ['content-pages.css'], 'page-agency', [], '#agencyContact');
    }

    public function legal(string $slug): View
    {
        $page = collect(config('legal_pages'))->firstWhere('slug', $slug);
        abort_if($page === null, 404);

        return $this->page('frontend.site.pages.legal', [
            'page' => $page,
            'pageTitle' => $page['title'].' | WebApp Bắc Ninh',
            'pageDescription' => $page['description'],
            'headerCta' => route('contact'),
            'floatingCta' => route('contact'),
            'extraStyles' => ['legal-pages.css'],
            'bodyClass' => 'page-legal page-'.Str::before($slug, '-'),
            'ogImage' => frontend_asset('assets/images/about-bacninh.webp'),
            'schemaType' => 'WebPage',
        ]);
    }
}
