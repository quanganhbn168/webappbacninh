<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Contracts\View\View;

class ArticleController extends FrontendController
{
    public function category(string $slug): View
    {
        $category = PostCategory::active()->with(['image', 'ogMedia'])->where('slug', $slug)->firstOrFail();

        return $this->page('frontend.pages.article-category', [
            'category' => $category,
            'catalogItems' => Post::query()->forCatalog()->where('category_id', $category->id)->get(),
            'pageTitle' => $category->meta_title ?: $category->name.' | '.site_config('name'),
            'pageDescription' => $category->meta_description ?: ($category->description ?? ''),
            'ogImage' => $category->ogMedia?->url ?: ($category->image?->url ?: site_config('default_og_image')),
            'schemaType' => 'CollectionPage',
        ]);
    }

    public function detail(string $slug): View
    {
        $article = Post::query()->published()->with(['category', 'featuredMedia', 'ogMedia', 'media'])->where('slug', $slug)->firstOrFail();

        return $this->page('frontend.site.articles.show', [
            'article' => $article,
            'relatedItems' => $article->related(),
            'pageTitle' => $article->meta_title ?: data_get($article->data, 'seo.meta_title', $article->title.' | '.site_config('name')),
            'pageDescription' => $article->meta_description ?: data_get($article->data, 'seo.meta_description', $article->excerpt),
            'pageKeywords' => (string) $article->meta_keywords,
            'canonicalUrl' => data_get($article->data, 'seo.canonical_url') ?: $article->url,
            'robots' => data_get($article->data, 'seo.robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'),
            'bodyClass' => 'page-article',
            'ogType' => 'article',
            'ogImage' => $article->og_image_url,
            'schemaType' => 'Article',
            'schemaData' => [
                'headline' => $article->title,
                'image' => $article->og_image_url,
                'author' => ['@id' => rtrim((string) site_config('site_url'), '/').'/#organization'],
            ],
            'breadcrumbs' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => 'Kiến thức', 'url' => route('articles.index')],
                ['name' => $article->title, 'url' => $article->url],
            ],
        ]);
    }
}
