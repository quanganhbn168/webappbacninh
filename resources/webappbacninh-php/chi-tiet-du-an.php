<?php
require __DIR__ . '/app/bootstrap.php';

$slug = trim((string) ($_GET['slug'] ?? ''));
$project = project_by_slug($slug);

if ($project === null) {
    http_response_code(404);
    $pageTitle = 'Không tìm thấy dự án | WebApp Bắc Ninh';
    $pageDescription = 'Dự án anh/chị đang tìm không tồn tại hoặc đã được thay đổi.';
    $canonicalUrl = config('site_url') . '/du-an';
    $activeMenu = 'projects';
    $headerCta = url('du-an.php');
    $floatingCta = url('index.php#contact');
    $extraStyles = ['project-detail.css'];
    $bodyClass = 'page-project-detail';

    view('layouts.head', compact('pageTitle','pageDescription','canonicalUrl','extraStyles','bodyClass'));
    view('layouts.header', compact('activeMenu','headerCta'));
    view('projects.not-found');
    view('layouts.footer', compact('floatingCta'));
    view('layouts.scripts');
    exit;
}

$pageTitle = $project['title'] . ' | Dự án WebApp Bắc Ninh';
$pageDescription = $project['excerpt'];
$canonicalUrl = config('site_url') . '/du-an/' . $project['slug'];
$ogImage = absolute_url('public/' . $project['image']);
$activeMenu = 'projects';
$headerCta = '#projectConsult';
$floatingCta = '#projectConsult';
$extraStyles = ['project-detail.css'];
$extraScripts = ['project-detail.js'];
$bodyClass = 'page-project-detail';
$relatedItems = related_projects($project, 3);

$jsonLd = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'CreativeWork',
            'name' => $project['title'],
            'description' => $project['excerpt'],
            'image' => $ogImage,
            'dateCreated' => (string) $project['year'],
            'creator' => [
                '@type' => 'Organization',
                'name' => config('name'),
                'url' => config('site_url'),
            ],
            'url' => $canonicalUrl,
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Trang chủ', 'item' => config('site_url')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Dự án', 'item' => config('site_url') . '/du-an'],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $project['title'], 'item' => $canonicalUrl],
            ],
        ],
    ],
];

view('layouts.head', compact('pageTitle','pageDescription','canonicalUrl','ogImage','extraStyles','jsonLd','bodyClass'));
view('layouts.header', compact('activeMenu','headerCta'));
view('projects.show', compact('project','relatedItems'));
view('layouts.footer', compact('floatingCta'));
view('layouts.scripts', compact('extraScripts'));
