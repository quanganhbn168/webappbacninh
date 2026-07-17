<?php
require __DIR__ . '/app/bootstrap.php';
$pageTitle = 'Liên hệ tư vấn website | WebApp Bắc Ninh';
$pageDescription = 'Liên hệ WebApp Bắc Ninh để tư vấn thiết kế website, kho giao diện, hosting, bảo trì, SEO, nội dung và hợp tác kỹ thuật.';
$canonicalUrl = config('site_url') . '/lien-he';
$ogImage = absolute_url('public/assets/images/hero-industrial.webp');
$activeMenu = 'contact'; $headerCta = '#contactForm'; $floatingCta = '#contactForm';
$extraStyles = ['content-pages.css']; $extraScripts = ['site-forms.js']; $bodyClass = 'page-contact';
$jsonLd = ['@context'=>'https://schema.org','@type'=>'ContactPage','name'=>'Liên hệ WebApp Bắc Ninh','url'=>$canonicalUrl,'mainEntity'=>['@type'=>'ProfessionalService','name'=>config('name'),'telephone'=>config('phone'),'email'=>config('email'),'address'=>config('address')]];
view('layouts.head', compact('pageTitle','pageDescription','canonicalUrl','ogImage','extraStyles','jsonLd','bodyClass'));
view('layouts.header', compact('activeMenu','headerCta'));
view('pages.contact');
view('layouts.footer', compact('floatingCta'));
view('layouts.scripts', compact('extraScripts'));
