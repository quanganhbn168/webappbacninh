<?php
require __DIR__ . '/app/bootstrap.php';
$legalPages = require base_path('app/Data/legal_pages.php');
$page = $legalPages['payment'];
$pageTitle = $page['title'] . ' | WebApp Bắc Ninh';
$pageDescription = $page['description'];
$canonicalUrl = rtrim((string) config('site_url'), '/') . '/' . $page['slug'];
$ogImage = absolute_url('public/assets/images/about-bacninh.webp');
$headerCta = url('lien-he.php');
$floatingCta = url('lien-he.php');
$extraStyles = ['legal-pages.css'];
$bodyClass = 'page-legal page-payment';
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    'name' => $page['title'],
    'url' => $canonicalUrl,
    'description' => $pageDescription,
    'dateModified' => '2026-07-11',
];
view('layouts.head', compact('pageTitle','pageDescription','canonicalUrl','ogImage','extraStyles','jsonLd','bodyClass'));
view('layouts.header', compact('headerCta'));
view('pages.legal', compact('page'));
view('layouts.footer', compact('floatingCta'));
view('layouts.scripts');
