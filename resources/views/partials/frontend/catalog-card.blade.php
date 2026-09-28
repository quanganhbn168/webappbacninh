<article class="card {{ $catalogType === 'projects' ? 'project-card' : 'article' }}" data-catalog-item data-cat="{{ $item->category_slug }}" data-title="{{ $item->title }}">
    <a class="card-media" href="{{ $item->url }}">
        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" loading="lazy" decoding="async">
        <span class="image-tag">{{ $item->category_label }}</span>
    </a>
    <div class="card-body">
        @if ($catalogType === 'articles')
            <div class="meta">{{ $item->published_label }} · {{ $item->read_time_label }}</div>
        @endif
        <h3><a href="{{ $item->url }}">{{ $item->title }}</a></h3>
        <p>{{ $item->excerpt }}</p>
        <a class="text-link" href="{{ $item->url }}">{{ $catalogType === 'articles' ? 'Đọc bài viết' : 'Xem chi tiết dự án' }} →</a>
    </div>
</article>
