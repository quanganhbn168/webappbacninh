<?php

namespace App\Http\Controllers\Frontend;

use App\Support\FrontendContent;
use Illuminate\Contracts\View\View;

class ArticleController extends FrontendController
{
    public function __construct(private readonly FrontendContent $content)
    {
    }

    public function index(): View
    {
        $articles = $this->content->articles();
        $seo = site_page_seo('articles', [
            'title' => 'Kiến thức website, SEO và vận hành | WebApp Bắc Ninh',
            'description' => 'Bài viết thực dụng về thiết kế website, SEO nền tảng, hosting, bảo trì, quản trị nội dung và vận hành số cho doanh nghiệp.',
        ]);

        return $this->page('frontend.site.articles.index', [
            'articleItems' => $articles,
            'pageTitle' => $seo['title'],
            'pageDescription' => $seo['description'],
            'pageKeywords' => $seo['keywords'] ?? '',
            'canonicalUrl' => $seo['canonical_url'] ?? request()->url(),
            'robots' => $seo['robots'] ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
            'activeMenu' => 'knowledge',
            'headerCta' => route('contact'),
            'floatingCta' => route('contact'),
            'extraStyles' => ['knowledge.css'],
            'extraScripts' => ['knowledge.js'],
            'bodyClass' => 'page-knowledge',
            'ogImage' => $seo['og_image'] ?? frontend_asset('assets/images/seo-operation.webp'),
            'schemaItems' => collect($articles)->map(fn (array $article): array => [
                'name' => $article['title'],
                'url' => route('articles.show', $article['slug']),
            ])->all(),
        ]);
    }

    public function detail(string $slug): View
    {
        $article = $this->content->articleBySlug($slug);
        abort_if($article === null, 404);

        return $this->page('frontend.site.articles.show', [
            'article' => $article,
            'relatedItems' => $this->content->relatedArticles($article),
            'pageTitle' => $article['meta_title'],
            'pageDescription' => $article['meta_description'],
            'pageKeywords' => $article['meta_keywords'],
            'canonicalUrl' => $article['canonical_url'] ?: request()->url(),
            'robots' => $article['robots'],
            'activeMenu' => 'knowledge',
            'headerCta' => route('contact'),
            'floatingCta' => route('contact'),
            'extraStyles' => ['knowledge.css'],
            'bodyClass' => 'page-article',
            'ogType' => 'article',
            'ogImage' => $article['og_image_url'],
            'schemaType' => 'Article',
            'schemaData' => [
                'headline' => $article['title'],
                'image' => $article['og_image_url'],
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
