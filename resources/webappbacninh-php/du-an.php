<?php
require __DIR__ . '/app/bootstrap.php';

$pageTitle = 'Dự án website và phần mềm đã triển khai | WebApp Bắc Ninh';
$pageDescription = 'Tham khảo các dự án website doanh nghiệp, website bán hàng, landing page, website giáo dục, du lịch và hệ thống quản lý của WebApp Bắc Ninh.';
$canonicalUrl = config('site_url') . '/du-an';
$ogImage = absolute_url('public/assets/images/project-corporate.webp');
$activeMenu = 'projects';
$headerCta = '#projectContact';
$floatingCta = '#projectContact';
$extraStyles = ['projects.css'];
$extraScripts = ['projects.js'];
$bodyClass = 'page-projects';
$projectItems = projects();

$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => 'Dự án WebApp Bắc Ninh',
    'url' => $canonicalUrl,
    'description' => $pageDescription,
    'mainEntity' => [
        '@type' => 'ItemList',
        'itemListElement' => array_map(
            static fn(array $project, int $index): array => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $project['title'],
                'url' => absolute_url('chi-tiet-du-an.php?slug=' . rawurlencode($project['slug'])),
            ],
            $projectItems,
            array_keys($projectItems)
        ),
    ],
];

view('layouts.head', compact('pageTitle','pageDescription','canonicalUrl','ogImage','extraStyles','jsonLd','bodyClass'));
view('layouts.header', compact('activeMenu','headerCta'));
view('projects.index', compact('projectItems'));
view('layouts.footer', compact('floatingCta'));
view('layouts.scripts', compact('extraScripts'));
