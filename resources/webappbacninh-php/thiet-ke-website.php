<?php
require __DIR__ . '/app/bootstrap.php';

$pageTitle = 'Dịch vụ thiết kế website tại Bắc Ninh | WebApp Bắc Ninh';
$pageDescription = 'Thiết kế website doanh nghiệp, website bán hàng, landing page và website theo ngành tại Bắc Ninh. Giao diện phù hợp, dễ quản trị, SEO nền tảng và hỗ trợ lâu dài.';
$canonicalUrl = config('site_url') . '/thiet-ke-website';
$ogImage = absolute_url('public/assets/images/hero-industrial.webp');
$activeMenu = 'website-service';
$headerCta = '#websiteConsult';
$floatingCta = '#websiteConsult';
$extraStyles = ['website-service.css'];
$bodyClass = 'page-website-service';

$jsonLd = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Service',
            '@id' => $canonicalUrl . '#service',
            'name' => 'Dịch vụ thiết kế website tại Bắc Ninh',
            'serviceType' => 'Thiết kế và phát triển website',
            'provider' => [
                '@type' => 'ProfessionalService',
                'name' => config('name'),
                'url' => config('site_url'),
                'telephone' => '+84' . substr((string) config('phone_href'), 1),
                'email' => config('email'),
            ],
            'areaServed' => ['Bắc Ninh', 'Bắc Giang', 'Hà Nội', 'Hưng Yên', 'Hải Phòng'],
            'description' => $pageDescription,
            'url' => $canonicalUrl,
        ],
        [
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'Thời gian thiết kế một website thường mất bao lâu?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Website giới thiệu cơ bản thường cần khoảng 2 đến 4 tuần. Website bán hàng hoặc có chức năng riêng cần khảo sát và chia giai đoạn cụ thể.'],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Doanh nghiệp có tự cập nhật nội dung được không?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Có. Website được bàn giao khu vực quản trị và hướng dẫn cập nhật bài viết, dịch vụ, sản phẩm, hình ảnh và thông tin liên hệ.'],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Sau bàn giao có được hỗ trợ tiếp không?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Có. WebApp Bắc Ninh có bảo hành và các gói hosting, bảo trì, đăng bài, SEO, nội dung Facebook và nâng cấp chức năng.'],
                ],
            ],
        ],
    ],
];

view('layouts.head', compact('pageTitle','pageDescription','canonicalUrl','ogImage','extraStyles','jsonLd','bodyClass'));
view('layouts.header', compact('activeMenu','headerCta'));
view('pages.website-service');
view('layouts.footer', compact('floatingCta'));
view('layouts.scripts');
