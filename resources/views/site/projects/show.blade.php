@extends('layouts.site')

@push('head')
    <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
    @vite(['resources/js/projects/gallery.js', 'resources/js/projects/story.js'])
@endpush

@section('content')
    @php($slides = $project->slides)
    <section class="page-hero page-hero--compact">
        <div class="container">
            <div class="page-hero__grid">
                <div class="page-hero__copy">
                    <x-breadcrumbs :items="$breadcrumbs" />
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="tag tag--gold">{{ $project->category_label }}</span>
                        @foreach ($project->industries as $industry)<span class="tag">{{ $industry->name }}</span>@endforeach
                        @if ($project->year)<span class="tag">{{ $project->year }}</span>@endif
                    </div>
                    <h1>{{ $project->title }}</h1>
                    <p class="page-hero__lead">{{ $project->excerpt }}</p>
                    <dl class="fact-table">
                        @foreach (['Khách hàng' => $project->client, 'Loại dự án' => $project->category?->name, 'Thời gian' => $project->duration, 'Chi phí tham khảo' => $project->cost] as $label => $value)
                            @if ($value)
                                <div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>
                            @endif
                        @endforeach
                    </dl>
                    <div class="btn-row">
                        <a class="btn btn-primary" href="#lam-du-an-tuong-tu">Làm dự án tương tự <x-icon name="arrow-right" /></a>
                        @if ($project->link)
                            <a class="btn btn-outline-dark" href="{{ $project->link }}" target="_blank" rel="noopener">Xem website <x-icon name="external-link" /></a>
                        @endif
                    </div>
                </div>
                <div class="project-gallery" data-project-gallery>
                    <div class="swiper project-gallery__main">
                        <div class="swiper-wrapper">
                            @foreach ($slides as $index => $slide)
                                <div class="swiper-slide">
                                    <x-img :src="$slide" sizes="(min-width: 992px) 50vw, 100vw" alt="{{ $project->title }}{{ $index ? ' - ảnh '.($index + 1) : '' }}" width="800" height="500" @if ($index) loading="lazy" @else fetchpriority="high" @endif />
                                </div>
                            @endforeach
                        </div>
                        @if (count($slides) > 1)
                            <button class="project-gallery__nav project-gallery__nav--prev" type="button" aria-label="Ảnh trước"><x-icon name="chevron-left" /></button>
                            <button class="project-gallery__nav project-gallery__nav--next" type="button" aria-label="Ảnh tiếp theo"><x-icon name="chevron-right" /></button>
                            <span class="project-gallery__counter"></span>
                        @endif
                    </div>
                    @if (count($slides) > 1)
                        <div class="swiper project-gallery__thumbs">
                            <div class="swiper-wrapper">
                                @foreach ($slides as $index => $slide)
                                    <div class="swiper-slide"><button type="button" aria-label="Xem ảnh {{ $index + 1 }}"><img src="{{ $slide['thumb'] }}" alt="" width="120" height="80" loading="lazy"></button></div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if ($project->challenge_html || $project->solution_html)
        <section class="section section--soft">
            <div class="container">
                <div class="d-grid gap-4">
                    @foreach ([['challenge', 'target', 'Bài toán ban đầu', $project->challenge_html], ['solution', 'lightbulb', 'Giải pháp triển khai', $project->solution_html]] as [$key, $icon, $title, $html])
                        @if ($html)
                            <article class="story-card">
                                <h2 class="story-card__header">
                                    <button class="story-card__toggle" type="button" data-bs-toggle="collapse" data-bs-target="#story-{{ $key }}" aria-expanded="true" aria-controls="story-{{ $key }}">
                                        <span class="icon-tile"><x-icon :name="$icon" /></span>
                                        <span>{{ $title }}</span>
                                        <x-icon name="chevron-down" class="story-card__chevron" />
                                    </button>
                                </h2>
                                <div class="collapse show" id="story-{{ $key }}">
                                    <div class="story-card__body"><div class="prose">{!! $html !!}</div></div>
                                    <div class="story-card__footer">
                                        <button class="story-card__close" type="button" data-bs-toggle="collapse" data-bs-target="#story-{{ $key }}" aria-expanded="true" aria-controls="story-{{ $key }}">Thu gọn <x-icon name="chevron-up" /></button>
                                    </div>
                                </div>
                            </article>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($project->deliverables || $project->technologies->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="row g-4 g-lg-5">
                    @if ($project->deliverables)
                        <div class="col-lg-7">
                            <p class="eyebrow">Phạm vi bàn giao</p>
                            <h2 class="mb-4">Những hạng mục chính trong dự án</h2>
                            <ol class="scope-grid scope-grid--2">
                                @foreach ($project->deliverables as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                    @if ($project->technologies->isNotEmpty())
                        <div class="col-lg-5">
                            <div class="feature-card h-auto">
                                <h2 class="h3 mb-0">Công nghệ và nền tảng</h2>
                                <p>Ưu tiên dễ vận hành và có khả năng mở rộng.</p>
                                <ul class="tech-list">
                                    @foreach ($project->technologies as $technology)
                                        <li>{{ $technology->logoHtml('1.5rem') }}<span>{{ $technology->name }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    @if ($project->results)
        <section class="section section--dark">
            <div class="container">
                <x-section-head eyebrow="Giá trị sau bàn giao" title="Dự án không dừng ở việc website hiển thị đẹp." center />
                <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-{{ min(4, count($project->results)) }} justify-content-center">
                    @foreach ($project->results as $result)
                        <div class="col"><x-feature-card :number="str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT)" :title="$result" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($relatedItems->isNotEmpty())
        <section class="section">
            <div class="container">
                <x-section-head eyebrow="Dự án liên quan" title="Tham khảo thêm các hướng triển khai khác" :href="route('projects.index')" link="Xem toàn bộ dự án" />
                <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                    @foreach ($relatedItems as $item)
                        <div class="col"><x-card.project :project="$item" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-lead-section id="lam-du-an-tuong-tu" eyebrow="Làm dự án tương tự" title="Không cần giống hoàn toàn. Hãy bắt đầu từ bài toán của doanh nghiệp."
        :text="'Gửi mã dự án '.($project->code ?: $project->title).' và mô tả nội dung, chức năng hoặc website tham khảo. WebApp Bắc Ninh sẽ đề xuất phương án phù hợp.'"
        :need="'Dự án tương tự: '.($project->code ?: $project->title)" />
@endsection
