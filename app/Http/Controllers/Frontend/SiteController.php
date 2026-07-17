<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class SiteController extends FrontendController
{
    public function home(): View
    {
        return $this->page('frontend.site.pages.home', [
            'pageTitle' => 'Thiết kế website tại Bắc Ninh | WebApp Bắc Ninh',
            'pageDescription' => 'WebApp Bắc Ninh thiết kế website doanh nghiệp, website bán hàng, landing page và đồng hành hosting, bảo trì, đăng bài, SEO, nội dung Facebook sau bàn giao.',
            'activeMenu' => 'home',
            'headerCta' => route('home').'#contact',
            'floatingCta' => route('home').'#contact',
            'ogImage' => frontend_asset('assets/images/hero-industrial.webp'),
        ]);
    }

    public function about(): View
    {
        return $this->simplePage('frontend.site.pages.about', 'Giới thiệu WebApp Bắc Ninh', 'WebApp Bắc Ninh tập trung thiết kế website, đồng hành vận hành nội dung và triển khai kỹ thuật phù hợp doanh nghiệp nhỏ và vừa.', 'about', ['content-pages.css'], 'page-about');
    }

    public function contact(): View
    {
        return $this->simplePage('frontend.site.pages.contact', 'Liên hệ tư vấn website | WebApp Bắc Ninh', 'Liên hệ WebApp Bắc Ninh để tư vấn thiết kế website, kho giao diện, hosting, bảo trì, SEO, nội dung và hợp tác kỹ thuật.', 'contact', ['content-pages.css'], 'page-contact', ['site-forms.js'], '#contactForm');
    }

    public function pricing(): View
    {
        return $this->simplePage('frontend.site.pages.pricing', 'Bảng giá thiết kế website và vận hành | WebApp Bắc Ninh', 'Tham khảo các gói thiết kế website, hosting, bảo trì, đăng bài, SEO và vận hành nội dung của WebApp Bắc Ninh.', 'pricing', ['content-pages.css'], 'page-pricing', ['site-forms.js'], '#pricingContact');
    }

    public function agency(): View
    {
        return $this->simplePage('frontend.site.pages.agency', 'Hợp tác kỹ thuật và gia công website cho Agency | WebApp Bắc Ninh', 'Nhận triển khai website, landing page, bảo trì và module kỹ thuật theo hình thức giới thiệu khách, đồng triển khai hoặc white-label.', 'agency', ['content-pages.css'], 'page-agency', ['site-forms.js'], '#agencyContact');
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
