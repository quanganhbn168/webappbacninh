<?php
require __DIR__ . '/app/bootstrap.php';

$pageTitle = 'Thiết kế website tại Bắc Ninh | WebApp Bắc Ninh';
$pageDescription = 'WebApp Bắc Ninh thiết kế website doanh nghiệp, website bán hàng, landing page và đồng hành hosting, bảo trì, đăng bài, SEO, nội dung Facebook sau bàn giao.';
$canonicalUrl = config('site_url') . '/';
$ogImage = absolute_url('public/assets/images/hero-industrial.webp');
$activeMenu = 'home';
$headerCta = url('index.php#contact');
$floatingCta = url('index.php#contact');
$jsonLd = [
  '@context' => 'https://schema.org',
  '@type' => 'ProfessionalService',
  'name' => config('name'),
  'url' => config('site_url'),
  'description' => $pageDescription,
  'telephone' => '+84' . substr(config('phone_href'), 1),
  'email' => config('email'),
  'areaServed' => ['Bắc Ninh', 'Bắc Giang', 'Hà Nội', 'Hưng Yên', 'Hải Phòng'],
];

view('layouts.head', compact('pageTitle','pageDescription','canonicalUrl','ogImage','jsonLd'));
view('layouts.header', compact('activeMenu','headerCta'));
view('pages.home');
view('layouts.footer', compact('floatingCta'));
view('layouts.scripts');
