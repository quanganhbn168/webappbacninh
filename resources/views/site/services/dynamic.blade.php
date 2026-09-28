@extends('layouts.site')

@section('content')
    <x-hero :eyebrow="$service->eyebrow" :title="$service->title" :lead="$service->highlight ?: $service->description" :image="$service->image_url" :image-alt="$service->title">
        <a class="btn btn-primary" href="#lien-he-dich-vu">Nhận tư vấn <x-icon name="arrow-right" /></a>
        @if ($service->category)
            <a class="btn btn-outline-dark" href="{{ route('slug.handle', ['slug' => $service->category->slug]) }}">Xem nhóm dịch vụ</a>
        @endif
    </x-hero>

    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <article class="col-lg-9">
                    <x-section-head eyebrow="Giới thiệu dịch vụ" :title="$service->title" :lead="$service->description" center />
                    @if ($service->content)
                        <div class="prose">{!! str($service->content)->sanitizeHtml() !!}</div>
                    @else
                        <p class="empty-state">Nội dung chi tiết đang được cập nhật. Liên hệ để nhận tư vấn theo nhu cầu của anh/chị.</p>
                    @endif
                </article>
            </div>
        </div>
    </section>

    <x-lead-section id="lien-he-dich-vu" :title="'Trao đổi về '.$service->title" text="Gửi nhu cầu, mục tiêu và thông tin hiện có. WebApp Bắc Ninh sẽ tư vấn phạm vi phù hợp." :need="$service->title" />
@endsection
