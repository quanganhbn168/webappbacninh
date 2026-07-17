<?php

declare(strict_types=1);

require __DIR__ . '/../bootstrap.php';

$serviceKey = $serviceKey ?? '';
$services = require base_path('app/Data/website_services.php');
$service = $services[$serviceKey] ?? null;

if ($service === null) {
    http_response_code(404);
    exit('Không tìm thấy trang dịch vụ.');
}

$pageTitle = $service['meta_title'];
$pageDescription = $service['meta_description'];
$canonicalUrl = rtrim((string) config('site_url'), '/') . '/' . $service['slug'];
$ogImage = absolute_url('public/' . $service['image']);
$activeMenu = 'website-service';
$activeSubmenu = $service['menu_key'];
$headerCta = '#serviceContact';
$floatingCta = '#serviceContact';
$extraStyles = ['website-service-detail.css'];
$bodyClass = 'page-service-detail page-service-' . $service['menu_key'];

$faqEntities = array_map(static fn(array $item): array => [
    '@type' => 'Question',
    'name' => $item['q'],
    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
], $service['faqs']);

$jsonLd = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Service',
            '@id' => $canonicalUrl . '#service',
            'name' => $service['title'],
            'serviceType' => $service['eyebrow'],
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
            'mainEntity' => $faqEntities,
        ],
    ],
];

view('layouts.head', compact('pageTitle', 'pageDescription', 'canonicalUrl', 'ogImage', 'extraStyles', 'jsonLd', 'bodyClass'));
view('layouts.header', compact('activeMenu', 'activeSubmenu', 'headerCta'));
view('services.show', compact('service'));
view('layouts.footer', compact('floatingCta'));
view('layouts.scripts');
