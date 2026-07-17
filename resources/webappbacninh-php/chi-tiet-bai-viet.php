<?php
require __DIR__ . '/app/bootstrap.php';
$slug = trim((string)($_GET['slug'] ?? ''));
$article = article_by_slug($slug);
if ($article === null) {
    http_response_code(404);
    $pageTitle = 'Không tìm thấy bài viết | WebApp Bắc Ninh';
    $pageDescription = 'Bài viết không tồn tại hoặc đã được thay đổi.';
    $canonicalUrl = config('site_url') . '/kien-thuc';
    $activeMenu = 'knowledge'; $extraStyles = ['knowledge.css']; $bodyClass = 'page-article';
    view('layouts.head', compact('pageTitle','pageDescription','canonicalUrl','extraStyles','bodyClass'));
    view('layouts.header', compact('activeMenu'));
    view('articles.not-found');
    view('layouts.footer');
    view('layouts.scripts');
    exit;
}
$pageTitle = $article['title'] . ' | WebApp Bắc Ninh';
$pageDescription = $article['excerpt'];
$canonicalUrl = config('site_url') . '/kien-thuc/' . $article['slug'];
$ogImage = absolute_url('public/' . $article['image']);
$ogType = 'article'; $activeMenu = 'knowledge'; $headerCta = url('lien-he.php'); $floatingCta = url('lien-he.php');
$extraStyles = ['knowledge.css']; $bodyClass = 'page-article'; $relatedItems = related_articles($article);
$jsonLd = ['@context'=>'https://schema.org','@type'=>'Article','headline'=>$article['title'],'description'=>$article['excerpt'],'image'=>$ogImage,'datePublished'=>'2026-07-10','author'=>['@type'=>'Organization','name'=>config('name')],'publisher'=>['@type'=>'Organization','name'=>config('name')],'mainEntityOfPage'=>$canonicalUrl];
view('layouts.head', compact('pageTitle','pageDescription','canonicalUrl','ogImage','ogType','extraStyles','jsonLd','bodyClass'));
view('layouts.header', compact('activeMenu','headerCta'));
view('articles.show', compact('article','relatedItems'));
view('layouts.footer', compact('floatingCta'));
view('layouts.scripts');
