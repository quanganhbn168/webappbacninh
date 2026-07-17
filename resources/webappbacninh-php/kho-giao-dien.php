<?php
require __DIR__ . '/app/bootstrap.php';

$themes = themes();
$pageTitle = 'Kho giao diện website theo ngành | WebApp Bắc Ninh';
$pageDescription = 'Khám phá kho giao diện website doanh nghiệp, bán hàng, landing page và website theo ngành. Lọc nhanh theo nhu cầu, lĩnh vực và mức đầu tư.';
$canonicalUrl = config('site_url') . '/kho-giao-dien';
$ogImage = absolute_url('public/assets/images/project-corporate.webp');
$activeMenu = 'themes';
$headerCta = '#themeContact';
$floatingCta = '#themeContact';
$extraStyles = ['theme-library.css'];
$extraScripts = ['theme-library.js'];

view('layouts.head', compact('pageTitle','pageDescription','canonicalUrl','ogImage','extraStyles'));
view('layouts.header', compact('activeMenu','headerCta'));
view('themes.index', compact('themes'));
view('layouts.footer', compact('floatingCta'));
view('layouts.scripts', compact('extraScripts'));
