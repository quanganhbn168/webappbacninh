@extends('layouts.site')

@section('content')
    <x-hero title="Dự án tiêu biểu" highlight="Từ ý tưởng đến giải pháp thực tế"
        lead="Khám phá các hướng triển khai website và phần mềm cho doanh nghiệp. Từ những ý tưởng ban đầu đến các giải pháp website, phần mềm hiệu quả và bền vững."
        :image="asset('frontend/images/projects-hero.webp')" image-alt="Website và phần mềm đã triển khai trên nhiều thiết bị">
        <a class="btn btn-primary" href="#du-an">Khám phá dự án <x-icon name="arrow-right" /></a>
        <button class="btn btn-outline-dark" type="button" data-consult="Trao đổi dự án">Trao đổi dự án của bạn</button>
    </x-hero>

    @if ($featured = $projects->firstWhere('is_featured', true) ?? $projects->first())
        <section class="section">
            <div class="container">
                <x-section-head title="Dự án nổi bật" lead="Một dự án tiêu biểu cho cách chúng tôi triển khai giao diện và giải pháp." />
                <article class="featured-card">
                    <img class="featured-card__image" src="{{ $featured->image_url }}" alt="{{ $featured->title }}" width="760" height="480" loading="lazy">
                    <div class="featured-card__body">
                        <span class="tag tag--gold">{{ $featured->category_label }}</span>
                        <h3 class="h2">{{ $featured->title }}</h3>
                        <p class="lead-text">{{ $featured->excerpt }}</p>
                        <a class="btn btn-primary align-self-start" href="{{ $featured->url }}">Xem chi tiết <x-icon name="arrow-right" /></a>
                    </div>
                </article>
            </div>
        </section>
    @endif

    <section class="section section--soft" id="du-an">
        <div class="container" data-catalog>
            <x-section-head title="Khám phá các dự án" lead="Đa dạng lĩnh vực, nhiều hướng triển khai cho doanh nghiệp." />
            @if ($categories->count() > 1)
                <div class="chip-list mb-4" role="group" aria-label="Lọc dự án">
                    <button class="chip active" type="button" data-catalog-filter="all" aria-pressed="true">Tất cả</button>
                    @foreach ($categories as $category)
                        <button class="chip" type="button" data-catalog-filter="{{ $category->slug }}" aria-pressed="false">{{ $category->name }}</button>
                    @endforeach
                </div>
            @endif
            <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                @foreach ($projects as $project)
                    <div class="col" data-catalog-item data-categories="{{ $project->category_slug }}" data-title="{{ $project->title }}">
                        <x-card.project :project="$project" />
                    </div>
                @endforeach
            </div>
            <p class="empty-state mt-4" data-catalog-empty @if ($projects->isNotEmpty()) hidden @endif>Danh mục này chưa có dự án.</p>
        </div>
    </section>

    <section class="section section--cream">
        <div class="container">
            <x-section-head title="Những giá trị trong từng dự án" lead="Chúng tôi không chỉ bàn giao sản phẩm, mà còn mang lại giá trị lâu dài cho doanh nghiệp." />
            <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                @foreach ([
                    ['palette', 'Đúng nhận diện', 'Thiết kế theo đúng ngành nghề, truyền tải rõ giá trị thương hiệu.'],
                    ['mouse-pointer-click', 'Dễ sử dụng', 'Giao diện thân thiện, dễ quản trị ngay cả với người không chuyên.'],
                    ['layers', 'Dễ mở rộng', 'Tích hợp linh hoạt, nâng cấp theo nhu cầu phát triển.'],
                    ['handshake', 'Đồng hành lâu dài', 'Hỗ trợ kỹ thuật, tư vấn và cải tiến liên tục.'],
                ] as [$icon, $title, $text])
                    <div class="col"><x-feature-card :icon="$icon" :title="$title" :text="$text" /></div>
                @endforeach
            </div>
        </div>
    </section>

    <x-cta-banner title="Dự án tiếp theo có thể là của bạn" text="Hãy để chúng tôi đồng hành cùng bạn biến ý tưởng thành giải pháp thực tế." />
@endsection
