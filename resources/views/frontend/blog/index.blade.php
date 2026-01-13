@extends('layouts.master')

@section('meta_title', 'Blog & Tin tức - WebApp Bắc Ninh')
@section('meta_description', 'Cập nhật tin tức mới nhất về thiết kế website và công nghệ tại Bắc Ninh.')

@section('content')
<div class="bg-light py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h1 class="fw-bold mb-3">Blog & Tin tức</h1>
            <p class="text-muted mx-auto" style="max-width: 600px;">
                Chia sẻ kiến thức, kinh nghiệm và những xu hướng công nghệ mới nhất giúp doanh nghiệp của bạn phát triển vượt trội.
            </p>
        </div>

        <div class="row g-4">
            @forelse($posts as $post)
                <div class="col-md-4">
                    <div class="card border-0 h-100 hover-shadow transition rounded-4 overflow-hidden shadow-sm">
                        <div class="ratio ratio-16x9 mb-3 bg-secondary bg-opacity-10">
                            @if($post->featured_image)
                                <img src="{{ asset($post->featured_image) }}" class="img-fluid object-fit-cover w-100 h-100" alt="{{ $post->title }}">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                    <i class="far fa-newspaper fa-2x"></i>
                                </div>
                            @endif
                        </div>
                        <div class="card-body p-3">
                            <div class="text-muted small mb-2"><i class="far fa-calendar-alt me-1"></i> {{ $post->published_at->format('d/m/Y') }}</div>
                            <h5 class="fw-bold mb-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none hover-text-primary">
                                    {{ $post->title }}
                                </a>
                            </h5>
                            <p class="card-text text-muted small line-clamp-3">{{ $post->summary ?? Str::limit(strip_tags($post->content), 120) }}</p>
                            <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-link p-0 text-primary fw-bold small text-decoration-none">
                                Đọc thêm <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="far fa-folder-open fa-3x mb-3"></i>
                    <p>Chưa có bài viết nào được đăng.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
