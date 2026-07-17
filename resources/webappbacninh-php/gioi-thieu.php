<?php
require __DIR__ . '/app/bootstrap.php';
$pageTitle = 'Giới thiệu WebApp Bắc Ninh';
$pageDescription = 'WebApp Bắc Ninh tập trung thiết kế website, đồng hành vận hành nội dung và triển khai kỹ thuật phù hợp doanh nghiệp nhỏ và vừa.';
$canonicalUrl = config('site_url') . '/gioi-thieu';
$ogImage = absolute_url('public/assets/images/about-bacninh.webp');
$activeMenu = 'about'; $headerCta = url('lien-he.php'); $floatingCta = url('lien-he.php');
$extraStyles = ['content-pages.css']; $bodyClass = 'page-about';
$jsonLd = ['@context'=>'https://schema.org','@type'=>'AboutPage','name'=>'Giới thiệu WebApp Bắc Ninh','url'=>$canonicalUrl,'description'=>$pageDescription];
view('layouts.head', compact('pageTitle','pageDescription','canonicalUrl','ogImage','extraStyles','jsonLd','bodyClass'));
view('layouts.header', compact('activeMenu','headerCta'));
view('pages.about');
view('layouts.footer', compact('floatingCta'));
view('layouts.scripts');
