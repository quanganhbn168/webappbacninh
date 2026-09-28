@extends('layouts.site')

@section('content')
    <x-hero eyebrow="Nhóm dịch vụ" :title="$category->name" :lead="$category->description ?: 'Các dịch vụ được tổng hợp theo cùng một nhu cầu triển khai.'">
        <a class="btn btn-primary" href="#lien-he-nhom">Nhận tư vấn <x-icon name="arrow-right" /></a>
        <x-slot:aside>
            <div class="d-grid gap-3">
                <x-feature-card icon="layers" :title="$services->count().' dịch vụ'" text="Đang hiển thị trong nhóm" class="feature-card--row" />
                <x-feature-card icon="headset" title="Tư vấn theo nhu cầu" text="Chọn hạng mục cần thiết, không bắt buộc trọn gói" class="feature-card--row" />
            </div>
        </x-slot:aside>
    </x-hero>

    <section class="section">
        <div class="container">
            @if ($category->content)
                <div class="prose mb-5">{!! str($category->content)->sanitizeHtml() !!}</div>
            @endif
            <x-section-head eyebrow="Danh sách dịch vụ" title="Chọn hạng mục phù hợp với nhu cầu hiện tại" center />
            @if ($services->isEmpty())
                <p class="empty-state">Nhóm này đang được cập nhật dịch vụ. Hãy gửi nhu cầu để được tư vấn trực tiếp.</p>
            @else
                <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-3">
                    @foreach ($services as $service)
                        <div class="col"><x-feature-card :icon="$service->icon ?: 'wrench'" :title="$service->title" :text="$service->highlight ?: $service->description" :href="$service->url" /></div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <x-lead-section id="lien-he-nhom" :title="'Tư vấn '.$category->name" text="Gửi nhu cầu, đội ngũ sẽ gợi ý các hạng mục phù hợp với doanh nghiệp." :need="$category->name" />
@endsection
