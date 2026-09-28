@extends('layouts.site')

@section('content')
    <x-hero :eyebrow="$service->eyebrow_label" :title="$service->title" :lead="$service->description" :image="$service->image_url" :image-alt="$service->title">
        <a class="btn btn-primary" href="#lien-he-dich-vu">{{ $service->cta_label }} <x-icon name="arrow-right" /></a>
        <a class="btn btn-outline-dark" href="{{ route('themes.index') }}">Xem kho giao diện</a>
        <x-slot:meta>
            @if ($service->highlight)
                <p class="fw-semibold text-gold-dark mt-3 mb-0">{{ $service->highlight }}</p>
            @endif
            <div class="page-hero__meta">
                <span>Chi phí tham khảo <strong>{{ $service->price_label }}</strong></span>
                <span>Thời gian dự kiến <strong>{{ $service->timeline_label }}</strong></span>
            </div>
        </x-slot:meta>
    </x-hero>

    @if ($service->audiences)
        <div class="trust-row">
            <div class="container">
                <ul class="check-list trust-row__checks">@foreach ($service->audiences as $audience)<li>{{ $audience }}</li>@endforeach</ul>
            </div>
        </div>
    @endif

    @if ($service->problems)
        <section class="section">
            <div class="container">
                <div class="media-split">
                    <div class="media-split__image">
                        <img src="{{ $service->secondary_image_url }}" alt="Minh họa {{ $service->eyebrow_label }}" width="720" height="540" loading="lazy">
                        <div class="note-card"><strong>Không làm theo cảm tính</strong>Cấu trúc được xác định từ mục tiêu, dữ liệu và hành vi khách hàng.</div>
                    </div>
                    <div>
                        <p class="eyebrow">Bài toán thường gặp</p>
                        <h2 class="mb-4">Website cần xử lý đúng vấn đề trước khi thêm hiệu ứng.</h2>
                        <ul class="info-list">
                            @foreach ($service->problems as $problem)
                                <li><span class="icon-tile"><x-icon :name="$problem['icon'] ?? 'circle-help'" /></span><div><h3>{{ $problem['title'] }}</h3><p>{{ $problem['text'] ?? '' }}</p></div></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if ($service->pages)
        <section class="section section--soft">
            <div class="container">
                <x-section-head eyebrow="Phạm vi triển khai" title="Các trang và nội dung cốt lõi" lead="Danh sách được điều chỉnh theo dữ liệu thực tế. Không bắt doanh nghiệp mua những trang không dùng đến." />
                <ol class="numbered-grid">@foreach ($service->pages as $page)<li>{{ $page }}</li>@endforeach</ol>
            </div>
        </section>
    @endif

    @if ($service->features)
        <section class="section section--dark">
            <div class="container">
                <x-section-head eyebrow="Giá trị bàn giao" title="Không chỉ đẹp khi xem bản demo" lead="Website phải dễ sử dụng, dễ cập nhật và có nền tảng để doanh nghiệp tiếp tục phát triển." center />
                <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-3">
                    @foreach ($service->features as $feature)
                        <div class="col"><x-feature-card :icon="$feature['icon'] ?? 'check'" :title="$feature['title']" :text="$feature['text'] ?? null" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section">
        <div class="container">
            <x-section-head eyebrow="Quy trình" title="Từng bước rõ ràng trước khi bàn giao" center />
            <x-steps :items="[
                ['messages-square', 'Khảo sát nhu cầu', 'Mục tiêu, khách hàng, dữ liệu hiện có và vấn đề cần xử lý.'],
                ['network', 'Chốt cấu trúc', 'Sơ đồ trang, chức năng, nội dung và hướng thiết kế.'],
                ['pencil-ruler', 'Thiết kế & phát triển', 'Triển khai giao diện, chức năng và dữ liệu mẫu.'],
                ['test-tube-diagonal', 'Kiểm thử', 'Responsive, form, tốc độ, SEO và các tình huống sử dụng.'],
                ['graduation-cap', 'Bàn giao', 'Đưa lên hosting, hướng dẫn quản trị và thống nhất bảo hành.'],
            ]" />
        </div>
    </section>

    @if ($service->packages)
        <section class="section section--cream">
            <div class="container">
                <x-section-head eyebrow="Mức đầu tư tham khảo" title="Chọn theo phạm vi thực tế" lead="Chi phí cuối cùng được xác định sau khi chốt dữ liệu, chức năng và tiến độ." center />
                <div class="row g-4 row-cols-1 row-cols-md-{{ min(3, count($service->packages)) }} justify-content-center">
                    @foreach ($service->packages as $package)
                        <div class="col"><x-card.package :package="$package" :number="$loop->iteration" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($service->faqs)
        <section class="section">
            <div class="container">
                <div class="row g-4 g-lg-5">
                    <div class="col-lg-5">
                        <p class="eyebrow">Câu hỏi thường gặp</p>
                        <h2>Làm rõ trước khi bắt đầu</h2>
                        <p class="lead-text my-3">Phần còn phụ thuộc dữ liệu hoặc hệ thống hiện tại sẽ được khảo sát trước khi báo giá chính thức.</p>
                        <button class="btn btn-outline-primary" type="button" data-consult="Câu hỏi về {{ $service->title }}">Gửi câu hỏi khác</button>
                    </div>
                    <div class="col-lg-7"><x-faq :items="$service->faqs" :columns="1" /></div>
                </div>
            </div>
        </section>
    @endif

    <x-lead-section id="lien-he-dich-vu" :title="$service->cta_label" text="Gửi ngành nghề, mục tiêu và dữ liệu đang có. WebApp Bắc Ninh sẽ đề xuất cấu trúc, tiến độ và mức đầu tư phù hợp." :need="$service->need_label" />
@endsection
