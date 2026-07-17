<?php
require __DIR__ . '/app/bootstrap.php';
$pageTitle = 'Hợp tác kỹ thuật và gia công website cho Agency | WebApp Bắc Ninh';
$pageDescription = 'Nhận triển khai website, landing page, bảo trì và module kỹ thuật theo hình thức giới thiệu khách, đồng triển khai hoặc white-label.';
$canonicalUrl = config('site_url') . '/hop-tac-agency';
$ogImage = absolute_url('public/assets/images/agency-partnership.webp');
$activeMenu = 'agency'; $headerCta = '#agencyContact'; $floatingCta = '#agencyContact';
$extraStyles = ['content-pages.css']; $extraScripts = ['site-forms.js']; $bodyClass = 'page-agency';
$jsonLd = ['@context'=>'https://schema.org','@type'=>'Service','name'=>'Hợp tác kỹ thuật với Agency','provider'=>['@type'=>'ProfessionalService','name'=>config('name'),'url'=>config('site_url')],'url'=>$canonicalUrl,'description'=>$pageDescription];
view('layouts.head', compact('pageTitle','pageDescription','canonicalUrl','ogImage','extraStyles','jsonLd','bodyClass'));
view('layouts.header', compact('activeMenu','headerCta'));
view('pages.agency');
view('layouts.footer', compact('floatingCta'));
view('layouts.scripts', compact('extraScripts'));
