@extends('layouts.site')

@section('content')
    @php($gallery = $project->gallery_urls ?: [$project->image_url])
    <section class="page-hero page-hero--compact">
        <div class="container">
            <div class="page-hero__grid">
                <div class="page-hero__copy">
                    <x-breadcrumbs :items="$breadcrumbs" />
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="tag tag--gold">{{ $project->category_label }}</span>
                        @if ($project->industry)<span class="tag">{{ $project->industry }}</span>@endif
                        @if ($project->year)<span class="tag">{{ $project->year }}</span>@endif
                    </div>
                    <h1>{{ $project->title }}</h1>
                    <p class="page-hero__lead">{{ $project->excerpt }}</p>
                    <dl class="fact-list">
                        @foreach (['Khách hàng' => $project->client, 'Loại dự án' => $project->website_type, 'Thời gian' => $project->duration] as $label => $value)
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
                <div class="gallery" data-gallery>
                    <img class="gallery__main" src="{{ $gallery[0] }}" alt="{{ $project->title }}" width="800" height="520" data-gallery-main fetchpriority="high">
                    @if (count($gallery) > 1)
                        <div class="gallery__thumbs">
                            @foreach ($gallery as $index => $url)
                                <button type="button" @class(['gallery__thumb', 'active' => $index === 0]) data-gallery-image="{{ $url }}" aria-label="Xem ảnh {{ $index + 1 }}" aria-pressed="{{ $index === 0 ? 'true' : 'false' }}">
                                    <img src="{{ $url }}" alt="" width="120" height="80" loading="lazy">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if ($project->challenge || $project->solution)
        <section class="section section--soft">
            <div class="container">
                <div class="row g-4">
                    @foreach ([['Bài toán ban đầu', $project->challenge], ['Giải pháp triển khai', $project->solution]] as [$title, $text])
                        @if ($text)
                            <div class="col-lg-6"><x-feature-card :number="str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT)" :title="$title" :text="$text" /></div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($project->deliverables || $project->technologies)
        <section class="section">
            <div class="container">
                <div class="row g-4 g-lg-5">
                    @if ($project->deliverables)
                        <div class="col-lg-7">
                            <p class="eyebrow">Phạm vi bàn giao</p>
                            <h2 class="mb-4">Những hạng mục chính trong dự án</h2>
                            <ol class="numbered-grid numbered-grid--3">
                                @foreach ($project->deliverables as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                    @if ($project->technologies)
                        <div class="col-lg-5">
                            <div class="feature-card">
                                <h2 class="h3 mb-0">Công nghệ và nền tảng</h2>
                                <p>Ưu tiên dễ vận hành và có khả năng mở rộng.</p>
                                <div class="media-card__tags">
                                    @foreach ($project->technologies as $technology)
                                        <span class="tag tag--gold">{{ $technology }}</span>
                                    @endforeach
                                </div>
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
