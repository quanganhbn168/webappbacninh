@extends('layouts.site')

@section('content')
    @php
        $faqs = $article->faqItems();
        $contents = collect($article->tableOfContents())->mapWithKeys(fn (string $heading, int $anchor): array => ['section-'.$anchor => $heading])
            ->when($faqs !== [], fn ($list) => $list->put('cau-hoi-thuong-gap', 'Câu hỏi thường gặp'))->all();
    @endphp
    <header class="article-head">
        <div class="container">
            <div class="article-column">
                <h1>{{ $article->title }}</h1>
                <x-breadcrumbs :items="$breadcrumbs" />
            </div>
        </div>
    </header>

    <section class="section article-section">
        <div class="container">
            <article class="article-column">
                <x-img :src="$article->cover" sizes="(min-width: 992px) 832px, 100vw" class="article-cover" :alt="$article->title" width="960" height="540" fetchpriority="high" />
                @if ($article->intro && $article->intro !== $article->excerpt)
                    <p class="article-intro">{{ $article->intro }}</p>
                @endif

                @if ($contents !== [])
                    <details class="article-toc" data-toc open>
                        <summary><x-icon name="list-ordered" /> Nội dung bài viết <x-icon name="chevron-down" class="article-toc__caret" /></summary>
                        <ol>
                            @foreach ($contents as $anchor => $heading)
                                <li><a href="#{{ $anchor }}">{{ $heading }}</a></li>
                            @endforeach
                        </ol>
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
                <x-ad-banners position="sidebar" class="article-banners" />
            </article>
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
                        <div class="col"><x-card.post :post="$item" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
