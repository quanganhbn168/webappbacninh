<article class="card {{ $catalogType === 'projects' ? 'project-card' : 'article' }}" data-catalog-item data-cat="{{ $item['category'] }}" data-title="{{ $item['title'] }}" x-show="matches($el)" :style="{order: rank($el)}">
    <a class="card-media" href="{{ route($catalogType.'.show', $item['slug']) }}">
        <img src="{{ $item['image_url'] }}" alt="{{ $item['title'] }}" loading="lazy" decoding="async">
        <span class="image-tag">{{ $item['category_label'] }}</span>
    </a>
    <div class="card-body">
        @if ($catalogType === 'articles')
            <div class="meta">{{ $item['published_at'] }} · {{ $item['read_time'] }}</div>
        @endif
        <h3><a href="{{ route($catalogType.'.show', $item['slug']) }}">{{ $item['title'] }}</a></h3>
        <p>{{ $item['excerpt'] }}</p>
        <a class="text-link" href="{{ route($catalogType.'.show', $item['slug']) }}">{{ $catalogType === 'articles' ? 'Đọc bài viết' : 'Xem chi tiết dự án' }} →</a>
    </div>
</article>
