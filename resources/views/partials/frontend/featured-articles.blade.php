@if(count($featuredArticles))
<div class="news-featured" data-featured-articles>
    @php($featuredMain = $featuredArticles[0])
    <article class="card article featured-main">
        <a href="{{ route('articles.show', $featuredMain['slug']) }}"><img src="{{ $featuredMain['image_url'] }}" alt="{{ $featuredMain['title'] }}" loading="lazy"></a>
        <div class="card-body">
            <span class="image-tag">{{ $featuredMain['category_label'] }}</span>
            <h2><a href="{{ route('articles.show', $featuredMain['slug']) }}">{{ $featuredMain['title'] }}</a></h2>
            <p>{{ $featuredMain['excerpt'] }}</p>
            <a class="text-link" href="{{ route('articles.show', $featuredMain['slug']) }}">Đọc bài viết →</a>
        </div>
    </article>
    <div class="featured-side">
        @foreach(array_slice($featuredArticles, 1, 3) as $article)
        <article class="card side-article">
            <a class="side-article__image" href="{{ route('articles.show', $article['slug']) }}"><img src="{{ $article['image_url'] }}" alt="{{ $article['title'] }}" loading="lazy"></a>
            <div class="card-body">
                <span class="image-tag">{{ $article['category_label'] }}</span>
                <h3><a href="{{ route('articles.show', $article['slug']) }}">{{ $article['title'] }}</a></h3>
                <small>{{ $article['published_at'] }} · {{ $article['read_time'] }}</small>
            </div>
        </article>
        @endforeach
    </div>
</div>
@endif
