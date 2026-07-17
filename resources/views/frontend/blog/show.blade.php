@extends('layouts.master')

@section('meta_title', $post->meta_title ?? $post->title . ' - WebApp Bắc Ninh')
@section('meta_description', $post->meta_description ?? Str::limit(strip_tags($post->content), 160))
@section('meta_keywords', $post->meta_keywords)
@section('meta_image', $post->og_image_url)

@section('content')
<article class="py-5 bg-white">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                {{-- Breadcrumb --}}
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb bg-transparent p-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
                        <li class="breadcrumb-item active text-truncate" aria-current="page" style="max-width: 200px;">{{ $post->title }}</li>
                    </ol>
                </nav>

                {{-- Header --}}
                <header class="mb-5">
                    <h1 class="display-5 fw-bold mb-3">{{ $post->title }}</h1>
                    <div class="d-flex align-items-center text-muted small">
                        <div class="me-3">
                            <i class="far fa-calendar-alt me-1"></i> {{ $post->published_at->format('d/m/Y') }}
                        </div>
                        <div class="me-3">
                            <i class="far fa-user me-1"></i> Admin
                        </div>
                        <div>
                            <i class="far fa-eye me-1"></i> {{ rand(100, 500) }} lượt xem
                        </div>
                    </div>
                </header>

                {{-- Featured Image --}}
                @if($post->hasMedia('featured') || $post->featured_image)
                    <div class="mb-5 rounded-4 overflow-hidden shadow-sm">
                        <img src="{{ $post->featured_image_url }}" class="img-fluid w-100" alt="{{ $post->title }}">
                    </div>
                @endif

                {{-- Summary --}}
                @if($post->summary)
                    <div class="lead mb-5 fw-medium text-dark italic border-start border-4 border-primary ps-4 py-1">
                        {{ $post->summary }}
                    </div>
                @endif

                {{-- Main Content --}}
                <div class="blog-content mb-5" style="line-height: 1.8; font-size: 1.1rem; color: #333;">
                    {!! $post->content !!}
                </div>

                <hr class="my-5">

                {{-- Social Share --}}
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="fw-bold">Chia sẻ bài viết:</div>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="btn btn-outline-primary btn-sm px-3">
                            <i class="fab fa-facebook-f me-2"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $post->title }}" target="_blank" class="btn btn-outline-info btn-sm px-3">
                            <i class="fab fa-twitter me-2"></i> Twitter
                        </a>
                        <button onclick="navigator.clipboard.writeText('{{ url()->current() }}')" class="btn btn-outline-secondary btn-sm px-3">
                            <i class="fas fa-link me-2"></i> Sao chép link
                        </button>
                    </div>
                </div>
            </div>

            {{-- Sidebar (optional) --}}
            <div class="col-lg-4 mt-5 mt-lg-0">
                <div class="sticky-top" style="top: 100px;">
                    <div class="card border-0 bg-light rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-3">Đăng ký tư vấn</h5>
                        <p class="text-muted small">Nhận tư vấn miễn phí về giải pháp công nghệ cho doanh nghiệp của bạn.</p>
                        <form action="{{ route('subscribe.email') }}" method="POST">
                            @csrf
                            <input type="email" name="email" class="form-control mb-3" placeholder="Email của anh..." required>
                            <button type="submit" class="btn btn-primary w-100 fw-bold">Gửi ngay</button>
                        </form>
                    </div>

                    <div class="card border-0 rounded-4 p-4 shadow-sm bg-dark text-white overflow-hidden position-relative">
                        <div class="position-relative z-1">
                            <h5 class="fw-bold mb-3">Thiết kế Web Siêu tốc</h5>
                            <p class="small opacity-75">Tạo ngay website chỉ với vài bước đơn giản. Miễn phí tên miền .webappbacninh.test</p>
                            <a href="{{ route('home') }}#register-section" class="btn btn-warning btn-sm fw-bold px-4 mt-2">Dùng thử ngay</a>
                        </div>
                        <i class="fas fa-rocket position-absolute bottom-0 end-0 m-3 fa-4x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

<style>
    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 1rem;
        margin: 2rem 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .blog-content h2, .blog-content h3 {
        font-weight: 800;
        margin-top: 2.5rem;
        margin-bottom: 1.5rem;
        color: #1a1a2e;
    }
    .blog-content p {
        margin-bottom: 1.5rem;
    }
    .blog-content ul, .blog-content ol {
        margin-bottom: 2rem;
    }
    .blog-content li {
        margin-bottom: 0.5rem;
    }
</style>
@endsection
