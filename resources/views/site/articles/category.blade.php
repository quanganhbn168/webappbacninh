@extends('layouts.site')

@section('content')
    <x-hero eyebrow="Kiến thức" :title="$category->name" :lead="$category->description" :image="$category->image?->url" :image-alt="$category->name" class="page-hero--compact" />

    <section class="section">
        <div class="container">
            @include('site.articles._filters')
            @if ($posts->isEmpty())
                <p class="empty-state">Danh mục này chưa có bài viết.</p>
            @else
                <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                    @foreach ($posts as $post)
                        <div class="col"><x-card.post :post="$post" /></div>
                    @endforeach
                </div>
                <div class="mt-5">{{ $posts->links('partials.site.pagination') }}</div>
            @endif
        </div>
    </section>

    <x-cta-banner title="Cần tư vấn cho website của bạn?" text="Trao đổi với WebApp Bắc Ninh để nhận đề xuất phù hợp với doanh nghiệp." />
@endsection
