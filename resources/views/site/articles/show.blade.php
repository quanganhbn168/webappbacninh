@extends('layouts.master')

@section('content')
    @php
        $faqs = $article->faqItems();
        $contents = collect($article->tableOfContents())->mapWithKeys(fn (string $heading, int $anchor): array => ['section-'.$anchor => $heading])
            ->when($faqs !== [], fn ($list) => $list->put('cau-hoi-thuong-gap', 'Câu hỏi thường gặp'))->all();
    @endphp
    <section class="section article-section">
        <div class="container article-layout">
            <article class="article-main">
                @if ($isPreview)
                    <p class="article-preview" role="status"><x-icon name="info" /> Bản xem trước: bài chưa xuất bản, chỉ quản trị viên nhìn thấy.</p>
                @endif
                <header class="article-head">
                    <h1>{{ $article->title }}</h1>
                    <x-breadcrumbs :items="$breadcrumbs" />
                    <p class="article-meta">
                        <span class="article-author">Tác giả: {{ $article->author_name }}</span> <span aria-hidden="true">·</span>
                        @if ($article->published_at)
                            <x-icon name="calendar" class="icon-sm" /> <span>Đăng ngày <time datetime="{{ $article->published_at->toDateString() }}">{{ $article->published_label }}</time></span> <span aria-hidden="true">·</span>
                            @if ($article->was_updated)
                                <span>Cập nhật <time datetime="{{ $article->modified_at->toDateString() }}">{{ $article->modified_at->format('d/m/Y') }}</time></span> <span aria-hidden="true">·</span>
                            @endif
                        @endif
                        <span>{{ $article->read_time_label }}</span>
                    </p>
                </header>
                @if ($article->featuredMedia)
                    <x-img :src="$article->cover" sizes="(min-width: 1400px) 896px, (min-width: 1200px) 764px, (min-width: 992px) 600px, 100vw" class="article-cover" :alt="$article->title" width="960" height="540" fetchpriority="high" />
                @endif
                @if ($article->summary)
                    <p class="article-intro">{{ $article->summary }}</p>
                @endif

                @if ($contents !== [])
                    <details class="article-toc" data-toc open>
                        <summary><x-icon name="list-ordered" /> Nội dung bài viết <x-icon name="chevron-down" class="article-toc__caret" /></summary>
                        <div class="article-toc__body" data-toc-body>
                            <ol>
                                @foreach ($contents as $anchor => $heading)
                                    <li><a href="#{{ $anchor }}">{{ $heading }}</a></li>
                                @endforeach
                            </ol>
                        </div>
                        {{-- Shown only when the list is long enough to be cut short. --}}
                        <button class="article-toc__more" type="button" aria-expanded="false" data-toc-more hidden>
                            <span>Xem thêm</span> <x-icon name="chevron-down" />
                        </button>
                    </details>
                @endif

                <div class="prose">{!! $article->body_html !!}</div>

                @if ($faqs !== [])
                    <section class="article-faq" id="cau-hoi-thuong-gap" aria-labelledby="article-faq-title">
                        <h2 id="article-faq-title">Câu hỏi thường gặp</h2>
                        <x-faq :items="$faqs" :columns="1" name="article-faq" />
                    </section>
                @endif

                <x-company-card :channels="$socialChannels['floating']" />
            </article>

            {{-- Beside the article on a large screen, below it on a small one. --}}
            <aside class="article-aside" aria-label="Thông tin thêm">
                {{-- The sticky box stays in view for the length of this track; what follows sits at its end. --}}
                <div class="article-aside__track d-none d-lg-block">
                    <div class="article-aside__sticky">
                        <div class="article-aside__box article-aside__contact">
                            <h2>Cần tư vấn?</h2>
                            @if (site_config('phone'))
                                <a class="btn btn-outline-dark w-100" href="tel:{{ site_config('phone_href') }}"><x-icon name="phone" /> {{ site_config('phone') }}</a>
                            @endif
                            <button class="btn btn-primary w-100" type="button" data-bs-toggle="modal" data-bs-target="#consult-modal">Nhận tư vấn</button>
                        </div>
                    </div>
                </div>

                @if ($categories->isNotEmpty())
                    <nav class="article-aside__box article-aside__categories" aria-label="Chủ đề bài viết">
                        <h2>Chủ đề</h2>
                        <ul>
                            @foreach ($categories as $category)
                                <li><a href="{{ $category->url }}" @if ($category->id === $article->category_id) aria-current="true" @endif><span>{{ $category->name }}</span> <span>{{ $category->posts_count }}</span></a></li>
                            @endforeach
                        </ul>
                    </nav>
                @endif
                <x-ad-banners position="sidebar" />
            </aside>
        </div>
    </section>

    @if ($contents !== [])
        {{-- Once the table of contents scrolls away, this round button keeps it one tap from anywhere. --}}
        <div class="toc-float" data-toc-float hidden>
            <nav class="toc-float__panel" id="toc-float-panel" aria-label="Nội dung bài viết" data-toc-panel hidden>
                <strong>Nội dung bài viết</strong>
                <ol>
                    @foreach ($contents as $anchor => $heading)
                        <li><a href="#{{ $anchor }}">{{ $heading }}</a></li>
                    @endforeach
                </ol>
            </nav>
            <button class="toc-float__button" type="button" aria-expanded="false" aria-controls="toc-float-panel" aria-label="Mở nội dung bài viết" data-toc-button>
                <x-icon name="list-ordered" />
            </button>
        </div>
    @endif

    @if ($relatedItems->isNotEmpty())
        <section class="section section--soft">
            <div class="container">
                <x-section-head eyebrow="Bài viết liên quan" title="Đọc tiếp theo chủ đề" :href="route('articles.index')" link="Xem tất cả" />
                <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                    @foreach ($relatedItems as $item)
                        {{-- No other dates on an article page, so Google reads only the article's own. --}}
                        <div class="col"><x-card.post :post="$item" :dated="false" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
