@extends('layouts.master')

@section('title', $template->name . ' - Kho Giao Diện WebApp Bắc Ninh')

@section('content')
<div class="bg-light py-5">
    <div class="container">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('templates.index') }}" class="text-decoration-none">Kho giao diện</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $template->name }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            {{-- Left Column: Image Preview --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="bg-dark text-center position-relative">
                        <img src="{{ $template->image_url }}" alt="{{ $template->name }}" class="img-fluid w-100" style="object-fit: cover; max-height: 500px;">
                        <a href="{{ $template->demo_url ?? '#' }}" target="_blank" class="btn btn-light rounded-pill position-absolute top-50 start-50 translate-middle px-4 py-2 shadow-lg fw-bold">
                            <i class="far fa-eye me-2"></i> Xem Demo Trực Tiếp
                        </a>
                    </div>
                </div>

                {{-- Description --}}
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h4 class="fw-bold mb-3">Mô tả giao diện</h4>
                    <div class="content text-muted">
                        <p>Giao diện <strong>{{ $template->name }}</strong> được thiết kế tối ưu cho lĩnh vực {{ $template->category }}.</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Thiết kế Responsive (Tương thích Mobile/Tablet)</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Tối ưu SEO Google</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Tốc độ tải trang nhanh</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Dễ dàng tùy biến nội dung</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Right Column: Action & Info --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 100px;">
                    <h3 class="fw-bold mb-1">{{ $template->name }}</h3>
                    <div class="mb-3">
                        <span class="badge bg-info bg-opacity-10 text-info">{{ $template->category }}</span>
                        @if($template->is_premium)
                            <span class="badge bg-warning text-dark"><i class="fas fa-crown me-1"></i> Premium</span>
                        @else
                            <span class="badge bg-success">Miễn phí</span>
                        @endif
                    </div>

                    <div class="d-grid gap-2 mb-4">
                        <form action="{{ url('/create-tenant') }}" method="POST">
                            @csrf
                            <input type="hidden" name="template_id" value="{{ $template->id }}">
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm">
                                <i class="fas fa-rocket me-2"></i> Sử dụng giao diện này
                            </button>
                        </form>
                        <a href="{{ $template->demo_url ?? '#' }}" target="_blank" class="btn btn-outline-dark btn-lg w-100 rounded-pill fw-bold">
                            <i class="far fa-eye me-2"></i> Xem Demo
                        </a>
                    </div>

                    <hr class="text-muted opacity-25">

                    <div class="features">
                        <h6 class="fw-bold text-uppercase small text-muted mb-3">Thông tin chi tiết</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Mã giao diện:</span>
                            <span class="fw-bold">#{{ $template->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Ngày cập nhật:</span>
                            <span class="fw-bold">{{ $template->updated_at->format('d/m/Y') }}</span>
                        </div>
                         <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Lượt cài đặt:</span>
                            <span class="fw-bold">1,205+</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
