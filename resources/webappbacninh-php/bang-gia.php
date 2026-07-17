<?php
require __DIR__ . '/app/bootstrap.php';
$pageTitle = 'Bảng giá thiết kế website và vận hành | WebApp Bắc Ninh';
$pageDescription = 'Tham khảo các gói thiết kế website, hosting, bảo trì, đăng bài, SEO và vận hành nội dung của WebApp Bắc Ninh.';
$canonicalUrl = config('site_url') . '/bang-gia';
$ogImage = absolute_url('public/assets/images/project-ecommerce.webp');
$activeMenu = 'pricing'; $headerCta = '#pricingContact'; $floatingCta = '#pricingContact';
$extraStyles = ['content-pages.css']; $extraScripts = ['site-forms.js']; $bodyClass = 'page-pricing';
$jsonLd = ['@context'=>'https://schema.org','@type'=>'Service','name'=>'Bảng giá thiết kế website và vận hành','provider'=>['@type'=>'ProfessionalService','name'=>config('name'),'url'=>config('site_url')],'areaServed'=>'Bắc Ninh','url'=>$canonicalUrl,'description'=>$pageDescription];
view('layouts.head', compact('pageTitle','pageDescription','canonicalUrl','ogImage','extraStyles','jsonLd','bodyClass'));
view('layouts.header', compact('activeMenu','headerCta'));
view('pages.pricing');
view('layouts.footer', compact('floatingCta'));
view('layouts.scripts', compact('extraScripts'));
