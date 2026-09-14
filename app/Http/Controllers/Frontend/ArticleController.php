<?php

namespace App\Http\Controllers\Frontend;

use App\Models\PostCategory;
use App\Support\FrontendContent;
use Illuminate\Contracts\View\View;

class ArticleController extends FrontendController
{
    public function __construct(private readonly FrontendContent $content) {}

    public function category(string $slug): View
    {
        $category = PostCategory::active()->with(['image', 'ogMedia'])->where('slug', $slug)->firstOrFail();
        $items = collect($this->content->articles())->where('category', $category->slug)->values()->all();

        return $this->page('frontend.pages.article-category', [
            'category' => $category,
            'catalogItems' => $items,
            'pageTitle' => $category->meta_title ?: $category->name.' | '.site_config('name'),
            'pageDescription' => $category->meta_description ?: ($category->description ?? ''),
            'ogImage' => $category->ogMedia?->url ?: ($category->image?->url ?: site_config('default_og_image')),
            'schemaType' => 'CollectionPage',
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
