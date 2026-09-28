@extends('layouts.site')

@section('content')
    @php($toc = $article->tableOfContents())
    <section class="page-hero page-hero--compact page-hero--text">
        <div class="container">
            <div class="article-hero">
                <x-breadcrumbs :items="$breadcrumbs" />
                <a class="tag tag--gold mb-3" href="{{ $article->category ? route('articles.category', $article->category->slug) : route('articles.index') }}">{{ $article->category_label }}</a>
                <h1>{{ $article->title }}</h1>
                @if ($article->excerpt)
                    <p class="page-hero__lead">{{ $article->excerpt }}</p>
                @endif
                <p class="page-hero__meta mt-0">
                    <span><x-icon name="calendar" class="icon-sm" /> {{ $article->published_label }}</span>
                    <span><x-icon name="clock" class="icon-sm" /> {{ $article->read_time_label }}</span>
                    <span><x-icon name="user-pen" class="icon-sm" /> {{ site_config('name') }}</span>
                </p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="article-layout">
                <article class="article-body">
                    <img class="article-cover" src="{{ $article->image_url }}" alt="{{ $article->title }}" width="960" height="540" fetchpriority="high">
                    @if ($article->intro && $article->intro !== $article->excerpt)
                        <p class="article-intro">{{ $article->intro }}</p>
                    @endif
                    <div class="prose">{!! $article->body_html !!}</div>
                    <div class="article-cta">
                        <span class="icon-tile icon-tile--lg"><x-icon name="messages-square" /></span>
                        <div>
                            <h2 class="h3">Cần áp dụng nội dung này cho website của doanh nghiệp?</h2>
                            <p class="mb-0 text-muted">WebApp Bắc Ninh có thể kiểm tra hiện trạng và đề xuất phạm vi thực tế.</p>
                        </div>
                        <button class="btn btn-primary" type="button" data-consult="Tư vấn: {{ $article->title }}">Nhận tư vấn</button>
                    </div>
                </article>
                <aside class="article-sidebar">
                    @if ($toc !== [])
                        <nav class="legal-toc" aria-label="Nội dung bài viết">
                            <strong>Nội dung bài viết</strong>
                            @foreach ($toc as $anchor => $heading)
                                <a href="#section-{{ $anchor }}">{{ $heading }}</a>
                            @endforeach
                        </nav>
                    @endif
                    <div class="feature-card">
                        <span class="icon-tile"><x-icon name="headset" /></span>
                        <div>
                            <h2 class="h3">Trao đổi dự án</h2>
                            <p>Thiết kế website, vận hành, SEO hoặc hợp tác kỹ thuật.</p>
                            <a class="link-arrow mt-2" href="tel:{{ site_config('phone_href') }}"><x-icon name="phone" /> {{ site_config('phone') }}</a>
                        </div>
                    </div>
                    <x-ad-banners position="sidebar" />
                </aside>
            </div>
        </div>
    </section>

    @if ($relatedItems->isNotEmpty())
        <section class="section section--soft">
            <div class="container">
                <x-section-head eyebrow="Bài viết liên quan" title="Đọc tiếp theo chủ đề" :href="route('articles.index')" link="Xem tất cả" />
                <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                    @foreach ($relatedItems as $item)
                        <div class="col"><x-card.post :post="$item" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
