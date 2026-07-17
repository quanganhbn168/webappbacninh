<?php
require __DIR__ . '/app/bootstrap.php';
$pageTitle = 'Dịch vụ vận hành website, SEO và nội dung | WebApp Bắc Ninh';
$pageDescription = 'Hosting, bảo trì, quản trị website, đăng bài, SEO, nội dung Facebook và nâng cấp chức năng theo nhu cầu doanh nghiệp.';
$canonicalUrl = config('site_url') . '/dich-vu-van-hanh';
$ogImage = absolute_url('public/assets/images/seo-operation.webp');
$activeMenu = 'operations'; $headerCta = '#operationContact'; $floatingCta = '#operationContact';
$extraStyles = ['content-pages.css', 'operation-service-detail.css']; $extraScripts = ['site-forms.js']; $bodyClass = 'page-operations';
$jsonLd = ['@context'=>'https://schema.org','@type'=>'Service','name'=>'Dịch vụ vận hành website','serviceType'=>['Hosting và bảo trì','Quản trị nội dung','SEO website','Nội dung Facebook'],'provider'=>['@type'=>'ProfessionalService','name'=>config('name'),'url'=>config('site_url')],'url'=>$canonicalUrl,'description'=>$pageDescription];
view('layouts.head', compact('pageTitle','pageDescription','canonicalUrl','ogImage','extraStyles','jsonLd','bodyClass'));
view('layouts.header', compact('activeMenu','headerCta'));
view('pages.operations');
view('layouts.footer', compact('floatingCta'));
view('layouts.scripts', compact('extraScripts'));
