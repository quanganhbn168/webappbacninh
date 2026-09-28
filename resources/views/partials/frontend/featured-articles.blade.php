@if($featuredArticles->isNotEmpty())
<div class="news-featured" data-featured-articles>
    @php($featuredMain = $featuredArticles->first())
    <article class="card article featured-main">
        <a href="{{ $featuredMain->url }}"><img src="{{ $featuredMain->image_url }}" alt="{{ $featuredMain->title }}" loading="lazy"></a>
        <div class="card-body">
            <span class="image-tag">{{ $featuredMain->category_label }}</span>
            <h2><a href="{{ $featuredMain->url }}">{{ $featuredMain->title }}</a></h2>
            <p>{{ $featuredMain->excerpt }}</p>
            <a class="text-link" href="{{ $featuredMain->url }}">Đọc bài viết →</a>
        </div>
    </article>
    <div class="featured-side">
        @foreach($featuredArticles->slice(1, 3) as $article)
        <article class="card side-article">
            <a class="side-article__image" href="{{ $article->url }}"><img src="{{ $article->image_url }}" alt="{{ $article->title }}" loading="lazy"></a>
            <div class="card-body">
                <span class="image-tag">{{ $article->category_label }}</span>
                <h3><a href="{{ $article->url }}">{{ $article->title }}</a></h3>
                <small>{{ $article->published_label }} · {{ $article->read_time_label }}</small>
            </div>
        </article>
        @endforeach
    </div>
</div>
@endif
