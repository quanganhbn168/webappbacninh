<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Contracts\View\View;

class ArticleController extends FrontendController
{
    public function index(): View
    {
        return $this->page('frontend.site.articles.index', [
            'articleItems' => articles(),
            'pageTitle' => 'Kiến thức website, SEO và vận hành | WebApp Bắc Ninh',
            'pageDescription' => 'Bài viết thực dụng về thiết kế website, SEO nền tảng, hosting, bảo trì, quản trị nội dung và vận hành số cho doanh nghiệp.',
            'activeMenu' => 'knowledge',
            'headerCta' => route('contact'),
            'floatingCta' => route('contact'),
            'extraStyles' => ['knowledge.css'],
            'extraScripts' => ['knowledge.js'],
            'bodyClass' => 'page-knowledge',
            'ogImage' => frontend_asset('assets/images/seo-operation.webp'),
            'schemaItems' => collect(articles())->map(fn (array $article): array => [
                'name' => $article['title'],
                'url' => route('articles.show', $article['slug']),
            ])->all(),
        ]);
    }

    public function detail(string $slug): View
    {
        $article = article_by_slug($slug);
        abort_if($article === null, 404);

        return $this->page('frontend.site.articles.show', [
            'article' => $article,
            'relatedItems' => related_articles($article),
            'pageTitle' => $article['title'].' | WebApp Bắc Ninh',
            'pageDescription' => $article['excerpt'],
            'activeMenu' => 'knowledge',
            'headerCta' => route('contact'),
            'floatingCta' => route('contact'),
            'extraStyles' => ['knowledge.css'],
            'bodyClass' => 'page-article',
            'ogType' => 'article',
            'ogImage' => frontend_asset($article['image']),
            'schemaType' => 'Article',
            'schemaData' => [
                'headline' => $article['title'],
                'image' => frontend_asset($article['image']),
                'author' => ['@id' => rtrim((string) site_config('site_url'), '/').'/#organization'],
            ],
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => 'Kiến thức', 'url' => route('articles.index')],
                ['name' => $article['title'], 'url' => request()->url()],
            ],
        ]);
    }
}
