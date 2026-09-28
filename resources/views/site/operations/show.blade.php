@extends('layouts.site')

@section('content')
    <x-hero :eyebrow="$service->eyebrow_label" :title="$service->title" :lead="$service->description" :image="$service->image_url" :image-alt="$service->title">
        <a class="btn btn-primary" href="#lien-he-dich-vu">{{ $service->cta_label }} <x-icon name="arrow-right" /></a>
        <a class="btn btn-outline-dark" href="{{ route('operations.index') }}">Dịch vụ vận hành khác</a>
        <x-slot:meta>
            @if ($service->highlight)
                <p class="fw-semibold text-gold-dark mt-3 mb-0">{{ $service->highlight }}</p>
            @endif
            <div class="page-hero__meta">
                <span>Chi phí tham khảo <strong>{{ $service->price_label }}</strong></span>
                <span>Chu kỳ <strong>{{ $service->cadence_label }}</strong></span>
            </div>
        </x-slot:meta>
    </x-hero>

    @if ($service->audiences)
        <section class="section">
            <div class="container">
                <div class="row g-4 g-lg-5 align-items-center">
                    <div class="col-lg-5">
                        <p class="eyebrow">Dịch vụ này phù hợp khi</p>
                        <h2>Doanh nghiệp đang gặp một trong các tình huống sau</h2>
                    </div>
                    <div class="col-lg-7"><ul class="check-list check-list--gold">@foreach ($service->audiences as $audience)<li>{{ $audience }}</li>@endforeach</ul></div>
                </div>
            </div>
        </section>
    @endif

    @if ($service->scope)
        <section class="section section--soft">
            <div class="container">
                <x-section-head eyebrow="Phạm vi công việc" title="Những hạng mục có thể triển khai" center />
                <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-3">
                    @foreach ($service->scope as $item)
                        <div class="col"><x-feature-card :icon="$item['icon'] ?? 'check'" :title="$item['title']" :text="$item['text'] ?? null" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($service->deliverables)
        <section class="section">
            <div class="container">
                <div class="media-split">
                    <div class="media-split__image"><img src="{{ $service->secondary_image_url }}" alt="Minh họa {{ $service->title }}" width="720" height="540" loading="lazy"></div>
                    <div>
                        <p class="eyebrow">Kết quả bàn giao</p>
                        <h2 class="mb-4">Không chỉ nói chung chung là “đã làm”</h2>
                        <ul class="check-list">@foreach ($service->deliverables as $item)<li>{{ $item }}</li>@endforeach</ul>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if ($service->process)
        <section class="section section--dark">
            <div class="container">
                <x-section-head eyebrow="Quy trình thực hiện" title="Triển khai theo từng bước rõ ràng" center />
                <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-{{ min(4, count($service->process)) }}">
                    @foreach ($service->process as $step)
                        <div class="col"><x-feature-card :number="$step['step'] ?? str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT)" :title="$step['title']" :text="$step['text'] ?? null" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($service->packages)
        <section class="section section--cream">
            <div class="container">
                <x-section-head eyebrow="Mức đầu tư tham khảo" title="Chọn theo khối lượng và mức độ hỗ trợ" center />
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
                        <h2>Làm rõ trước khi triển khai</h2>
                        <button class="btn btn-outline-primary mt-3" type="button" data-consult="Câu hỏi về {{ $service->title }}">Gửi câu hỏi khác</button>
                    </div>
                    <div class="col-lg-7"><x-faq :items="$service->faqs" :columns="1" /></div>
                </div>
            </div>
        </section>
    @endif

    <x-lead-section id="lien-he-dich-vu" :title="$service->cta_label" text="Cho biết website hiện tại và phần đang thiếu, chúng tôi sẽ đề xuất phạm vi và chi phí phù hợp." :need="$service->need_label" />
@endsection
