<?php
require __DIR__ . '/app/bootstrap.php';
$pageTitle = 'Kiến thức website, SEO và vận hành | WebApp Bắc Ninh';
$pageDescription = 'Bài viết thực dụng về thiết kế website, SEO nền tảng, hosting, bảo trì, quản trị nội dung và vận hành số cho doanh nghiệp.';
$canonicalUrl = config('site_url') . '/kien-thuc';
$ogImage = absolute_url('public/assets/images/seo-operation.webp');
$activeMenu = 'knowledge'; $headerCta = url('lien-he.php'); $floatingCta = url('lien-he.php');
$extraStyles = ['knowledge.css']; $extraScripts = ['knowledge.js']; $bodyClass = 'page-knowledge'; $articleItems = articles();
$jsonLd = ['@context'=>'https://schema.org','@type'=>'CollectionPage','name'=>'Kiến thức WebApp Bắc Ninh','url'=>$canonicalUrl,'description'=>$pageDescription,'mainEntity'=>['@type'=>'ItemList','itemListElement'=>array_map(static fn(array $a,int $i)=>['@type'=>'ListItem','position'=>$i+1,'name'=>$a['title'],'url'=>absolute_url('chi-tiet-bai-viet.php?slug='.rawurlencode($a['slug']))],$articleItems,array_keys($articleItems))]];
view('layouts.head', compact('pageTitle','pageDescription','canonicalUrl','ogImage','extraStyles','jsonLd','bodyClass'));
view('layouts.header', compact('activeMenu','headerCta'));
view('articles.index', compact('articleItems'));
view('layouts.footer', compact('floatingCta'));
view('layouts.scripts', compact('extraScripts'));
