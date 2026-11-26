<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ArticleController extends FrontendController
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));
        $posts = Post::query()->forCatalog()
            ->when($search !== '', fn ($query) => $query->where(fn ($query) => $query->where('title', 'like', "%{$search}%")->orWhere('summary', 'like', "%{$search}%")))
            ->paginate(9)->withQueryString();

        return $this->sitePage('articles', 'site.articles.index', [
            'posts' => $posts,
            'featured' => $search === '' && $posts->onFirstPage() ? Post::query()->forCatalog()->reorder()->orderByDesc('is_featured')->orderByDesc('published_at')->take(4)->get() : collect(),
            'categories' => $this->categories(),
            'search' => $search,
            'canonicalUrl' => $this->paginatedUrl($posts),
            // Search results stay out of Google. Without a search the page's own admin setting applies.
            ...($search !== '' ? ['robots' => 'noindex, follow'] : []),
            'schemaType' => 'CollectionPage',
            'schemaItems' => $posts->getCollection()->map(fn (Post $post): array => ['name' => $post->title, 'url' => $post->url])->all(),
        ]);
    }

    public function category(string $slug): View
    {
        $category = PostCategory::active()->with(['image', 'ogMedia'])->where('slug', $slug)->firstOrFail();
        $posts = Post::query()->forCatalog()->where('category_id', $category->id)->paginate(9);

        return $this->page('site.articles.category', [
            'category' => $category,
            'posts' => $posts,
            'canonicalUrl' => $this->paginatedUrl($posts),
            'categories' => $this->categories(),
            'pageTitle' => $category->meta_title ?: $category->name.' | '.site_config('name'),
            'pageDescription' => $category->meta_description ?: ($category->description ?: 'Tổng hợp bài viết chủ đề '.$category->name.' của '.site_config('name').': kinh nghiệm và hướng dẫn thực tế cho doanh nghiệp.'),
            'ogImage' => $category->ogMedia?->url ?: ($category->image?->url ?: $this->defaultOgImage()),
            'schemaType' => 'CollectionPage',
            'breadcrumbs' => $this->breadcrumbs($category),
        ]);
    }

    public function detail(string $slug): View
    {
        $article = Post::query()->published()->with(['category', 'featuredMedia', 'ogMedia'])->where('slug', $slug)->firstOrFail();

        return $this->page('site.articles.show', [
            'article' => $article,
            'relatedItems' => $article->related(),
            'pageTitle' => $article->meta_title ?: data_get($article->data, 'seo.meta_title', $article->title.' | '.site_config('name')),
            'pageDescription' => $article->meta_description ?: data_get($article->data, 'seo.meta_description', $article->excerpt),
            'pageKeywords' => (string) $article->meta_keywords,
            'canonicalUrl' => data_get($article->data, 'seo.canonical_url') ?: $article->url,
            'robots' => data_get($article->data, 'seo.robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'),
            'ogType' => 'article',
            'ogImage' => $article->og_image_url,
            'schemaType' => 'Article',
            'schemaFaqs' => $article->faqItems(),
            'schemaData' => [
                'headline' => $article->title,
                'image' => $article->og_image_url,
                'datePublished' => $article->published_at?->toIso8601String(),
                'dateModified' => $article->updated_at?->toIso8601String(),
                'author' => ['@id' => rtrim((string) site_config('site_url'), '/').'/#organization'],
            ],
            'breadcrumbs' => $this->breadcrumbs($article->category, $article),
        ]);
    }

    /**
     * Trang chủ → Kiến thức → danh mục → bài viết, shown on the page and in the BreadcrumbList.
     * URLs stay flat, so the hierarchy exists only here. A hidden category has no page to link to.
     *
     * @return array<int, array{name: string, url: string}>
     */
    private function breadcrumbs(?PostCategory $category, ?Post $article = null): array
    {
        return array_values(array_filter([
            ['name' => 'Trang chủ', 'url' => route('home')],
            ['name' => Page::for('articles')->title, 'url' => route('articles.index')],
            $category?->is_active ? ['name' => $category->name, 'url' => $category->url] : null,
            $article ? ['name' => $article->title, 'url' => $article->url] : null,
        ]));
    }

    private function categories()
    {
        return PostCategory::active()->withCount(['posts' => fn ($query) => $query->published()])->orderBy('name')->get()
            ->filter(fn (PostCategory $category): bool => $category->posts_count > 0)->values();
    }
}
