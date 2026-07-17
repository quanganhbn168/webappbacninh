@extends('layouts.master')

@section('title', 'Kho Giao Diện Website - WebApp Bắc Ninh')

@section('content')
{{-- Hero Section --}}
<section class="position-relative py-5 bg-dark text-white overflow-hidden" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
        <div style="background-image: radial-gradient(#444cf7 1px, transparent 1px); background-size: 30px 30px; width: 100%; height: 100%;"></div>
    </div>
    <div class="container position-relative z-1 text-center py-5">
        <span class="badge bg-primary bg-opacity-25 text-primary-light border border-primary border-opacity-25 px-3 py-2 rounded-pill mb-3">
            <i class="fas fa-layer-group me-2"></i>Kho Giao Diện Đa Ngành
        </span>
        <h1 class="display-4 fw-bold mb-3">Chọn "Ngôi Nhà Số" Cho Doanh Nghiệp</h1>
        <p class="lead text-white-50 mb-4 mx-auto" style="max-width: 700px;">
            Hơn 200+ mẫu giao diện được thiết kế tối ưu cho trải nghiệm người dùng, chuẩn SEO và tương thích mọi thiết bị.
        </p>
        
        {{-- Search Box --}}
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <form action="{{ route('templates.index') }}" method="GET" class="position-relative">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-lg rounded-pill ps-5 pe-5 shadow-lg border-0" placeholder="Tìm kiếm giao diện (vd: Bất động sản, Thời trang...)" style="height: 60px;">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-4 text-muted fa-lg"></i>
                    <button type="submit" class="btn btn-primary rounded-pill position-absolute top-50 end-0 translate-middle-y me-2 fw-bold px-4">
                        Tìm kiếm
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- Filter Section --}}
<section class="py-4 border-bottom bg-light sticky-top" style="top: 70px; z-index: 1020;">
    <div class="container">
        <div class="d-flex justify-content-center flex-wrap gap-2">
            <a href="{{ route('templates.index') }}" class="btn btn-{{ !request('category') ? 'dark' : 'outline-muted bg-white' }} rounded-pill px-4 fw-bold border">
                Tất cả
            </a>
            @foreach($categories as $cat)
                @if($cat)
                <a href="{{ route('templates.index', ['category' => $cat]) }}" class="btn btn-{{ request('category') == $cat ? 'primary' : 'outline-muted bg-white' }} rounded-pill px-4 fw-bold border">
                    {{ ucfirst($cat) }}
                </a>
                @endif
            @endforeach
        </div>
    </div>
</section>

{{-- Templates Grid --}}
<section class="py-5 bg-white min-vh-100">
    <div class="container">
        @if(request('q'))
            <p class="text-muted mb-4">Kết quả tìm kiếm cho: <strong>"{{ request('q') }}"</strong></p>
        @endif

        <div class="row g-4">
            @forelse($templates as $template)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-translate-up transition rounded-4 overflow-hidden group">
                        {{-- Image Wrapper --}}
                        <div class="position-relative overflow-hidden bg-light ratio ratio-4x3">
                            <img src="{{ $template->image_url }}" alt="{{ $template->name }}" class="w-100 h-100 object-fit-cover transition-transform duration-500 group-hover:scale-110" style="transition: transform 0.5s;">
                            
                            {{-- Overlay Actions --}}
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center opacity-0 group-hover:opacity-100 transition" style="transition: opacity 0.3s;">
                                <div class="d-flex gap-2">
                                    <a href="{{ $template->demo_url ?? '#' }}" target="_blank" class="btn btn-light rounded-pill px-3 fw-bold btn-sm">
                                        <i class="far fa-eye me-1"></i> Demo
                                    </a>
                                    <a href="{{ route('templates.show', $template->slug) }}" class="btn btn-primary rounded-pill px-3 fw-bold btn-sm">
                                        <i class="fas fa-info-circle me-1"></i> Chi tiết
                                    </a>
                                </div>
                            </div>
                            
                            {{-- Badges --}}
                            <div class="position-absolute top-0 start-0 p-3">
                                @if($template->is_premium)
                                    <span class="badge bg-warning text-dark shadow-sm">
                                        <i class="fas fa-crown me-1"></i> Premium
                                    </span>
                                @else
                                    <span class="badge bg-success shadow-sm">Miễn phí</span>
                                @endif
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small text-uppercase fw-bold ls-1">{{ $template->category ?? 'General' }}</span>
                                <div class="rating text-warning small">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                            </div>
                            <h5 class="card-title fw-bold mb-1 text-truncate">
                                <a href="{{ route('templates.show', $template->slug) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ $template->name }}
                                </a>
                            </h5>
                        </div>
                        
                        {{-- Card Footer --}}
                        <div class="card-footer bg-white border-top-0 p-3 pt-0">
                            <div class="d-grid">
                                <form action="{{ url('/create-tenant') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="template_id" value="{{ $template->id }}">
                                    <button type="submit" class="btn btn-outline-primary w-100 rounded-pill fw-bold">
                                        <i class="fas fa-magic me-2"></i> Dùng giao diện này
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="mb-4 text-muted opacity-50">
                        <i class="fas fa-box-open fa-5x"></i>
                    </div>
                    <h3 class="fw-bold">Chưa tìm thấy mẫu nào!</h3>
                    <p class="text-muted">Hệ thống đang cập nhật kho giao diện. Vui lòng quay lại sau.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 mt-3">Về trang chủ</a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $templates->withQueryString()->links() }}
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-5 bg-primary text-white text-center">
    <div class="container">
        <h2 class="fw-bold mb-3">Chưa tìm thấy mẫu ưng ý?</h2>
        <p class="lead opacity-75 mb-4">Đội ngũ kỹ thuật của chúng tôi sẵn sàng thiết kế riêng theo yêu cầu của bạn.</p>
        <a href="{{ route('contact') }}" class="btn btn-light btn-lg rounded-pill px-5 fw-bold text-primary shadow-lg">
            Liên hệ tư vấn (Miễn phí)
        </a>
    </div>
</section>

<style>
    .hover-translate-up:hover {
        transform: translateY(-5px);
    }
    .group:hover .group-hover\:scale-110 {
        transform: scale(1.1);
    }
    .group:hover .group-hover\:opacity-100 {
        opacity: 1 !important;
    }
</style>
@endsection
