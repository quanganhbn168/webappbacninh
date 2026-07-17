<?php
require __DIR__ . '/app/bootstrap.php';

$slug = trim((string) ($_GET['slug'] ?? ''));
$theme = $slug !== '' ? theme_by_slug($slug) : null;

if ($theme === null) {
    http_response_code(404);
    $pageTitle = 'Không tìm thấy giao diện | WebApp Bắc Ninh';
    $pageDescription = 'Mẫu giao diện không tồn tại hoặc đã được thay đổi.';
    $canonicalUrl = config('site_url') . '/kho-giao-dien';
    $activeMenu = 'themes';
    view('layouts.head', compact('pageTitle','pageDescription','canonicalUrl'));
    view('layouts.header', compact('activeMenu'));
    echo '<main class="section section--light"><div class="container text-center"><span class="section-kicker">404</span><h1 class="section-title">Không tìm thấy mẫu giao diện</h1><p class="section-lead">Mẫu có thể đã được đổi đường dẫn hoặc tạm ẩn.</p><a class="btn btn-primary" href="' . e(url('kho-giao-dien.php')) . '">Quay lại kho giao diện</a></div></main>';
    view('layouts.footer');
    view('layouts.scripts');
    exit;
}

$relatedThemes = related_themes($theme, 3);
$pageTitle = $theme['name'] . ' | ' . config('name');
$pageDescription = $theme['description'] . ' Xem chi tiết phạm vi bàn giao, chức năng, thời gian và chi phí triển khai.';
$canonicalUrl = config('site_url') . '/giao-dien/' . $theme['slug'];
$ogImage = absolute_url('public/assets/images/' . $theme['image']);
$ogType = 'product';
$activeMenu = 'themes';
$headerCta = '#themeConsult';
$floatingCta = '#themeConsult';
$extraStyles = ['theme-library.css', 'theme-detail.css'];
$extraScripts = ['theme-detail.js'];
$jsonLd = [
  '@context' => 'https://schema.org',
  '@type' => 'Product',
  'name' => $theme['name'],
  'sku' => $theme['code'],
  'description' => $theme['description'],
  'image' => $ogImage,
  'brand' => ['@type' => 'Brand', 'name' => config('name')],
  'offers' => [
    '@type' => 'Offer',
    'priceCurrency' => 'VND',
    'price' => $theme['price'],
    'availability' => 'https://schema.org/InStock',
    'url' => $canonicalUrl,
  ],
];

view('layouts.head', compact('pageTitle','pageDescription','canonicalUrl','ogImage','ogType','extraStyles','jsonLd'));
view('layouts.header', compact('activeMenu','headerCta'));
view('themes.show', compact('theme','relatedThemes'));
view('layouts.footer', compact('floatingCta'));
view('layouts.scripts', compact('extraScripts'));
