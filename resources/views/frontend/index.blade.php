@extends('layouts.master')

@section('title', 'Nền tảng Website & Công cụ Số Bắc Ninh - WebAppBacNinh')

@push('head')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "@id": "{{ url('/') }}/#organization",
      "name": "WebApp Bắc Ninh",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/webapp-logo.png') }}",
      "email": "webappbacninh@gmail.com",
      "founder": {
        "@type": "Person",
        "name": "Trần Quang Anh"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "0856-843-891",
        "contactType": "customer service",
        "areaServed": "VN",
        "availableLanguage": "Vietnamese"
      },
      "sameAs": [
        "https://www.facebook.com/webappbacninh",
        "https://webappbacninh.vn"
      ]
    },
    {
      "@type": "LocalBusiness",
      "@id": "{{ url('/') }}/#localbusiness",
      "name": "WebApp Bắc Ninh - Thiết kế Web & Phần mềm",
      "image": "{{ asset('images/og-image.jpg') }}",
      "priceRange": "$$",
      "telephone": "0856-843-891",
      "email": "webappbacninh@gmail.com",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Nhị Trai, Trung Chính",
        "addressLocality": "Lương Tài",
        "addressRegion": "Bắc Ninh",
        "addressCountry": "VN"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 21.0478, 
        "longitude": 106.1824 
      },
      "url": "{{ url('/') }}",
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
          "Monday",
          "Tuesday",
          "Wednesday",
          "Thursday",
          "Friday",
          "Saturday"
        ],
        "opens": "08:00",
        "closes": "18:00"
      }
    },
    {
      "@type": "WebSite",
      "@id": "{{ url('/') }}/#website",
      "url": "{{ url('/') }}",
      "name": "WebApp Bắc Ninh",
      "description": "Nền tảng Website & Công cụ Số hàng đầu Bắc Ninh",
      "publisher": {
        "@id": "{{ url('/') }}/#organization"
      },
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/') }}/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
  ]
}
</script>
@endpush

@section('content')

{{-- Hero Section --}}
<section class="position-relative overflow-hidden py-5 text-white animated-bg" style="min-height: 80vh; display: flex; align-items: center;">
    {{-- Decorative Shapes --}}
    <div class="hero-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>

    <div class="container hero-content position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <span class="badge bg-light text-primary px-3 py-2 mb-3 shadow-sm fw-bold">
                    <i class="fas fa-rocket me-2"></i> {{ $settings['hero_badge_text']->value ?? 'Phiên bản Beta 1.0' }}
                </span>
                <h1 class="display-3 fw-bold mb-4" style="text-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                    {!! $settings['hero_title']->value ?? 'Khởi tạo Website <br><span style="color: #ffd700;">Chỉ trong 5 giây</span>' !!}
                </h1>
                <p class="lead mb-5" style="opacity: 0.9; max-width: 600px;">
                    {{ $settings['hero_description']->value ?? 'Nền tảng công nghệ "All-in-One". Không chỉ là website bán hàng, mà là cả một hệ sinh thái công cụ hỗ trợ kinh doanh, tính toán và quản trị dành riêng cho người Bắc Ninh.' }}
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ $settings['hero_btn_primary_url']->value ?? '#register-section' }}" class="btn btn-light btn-lg px-5 shadow-lg fw-bold text-primary">
                        <i class="fas fa-magic me-2"></i> {{ $settings['hero_btn_primary_text']->value ?? 'Tạo Website Ngay' }}
                    </a>
                    <a href="{{ $settings['hero_btn_secondary_url']->value ?? '#ecosystem' }}" class="btn btn-outline-light btn-lg px-5">
                        {{ $settings['hero_btn_secondary_text']->value ?? 'Khám phá Hệ sinh thái' }}
                    </a>
                </div>
            </div>
            
            <div class="col-lg-5">
                <div class="glass-card p-4 text-dark">
                    <h4 class="fw-bold mb-3 text-center">Đăng ký dùng thử</h4>
                    <form action="{{ url('/create-tenant') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Tên cửa hàng (Viết liền không dấu)</label>
                            <div class="input-group">
                                <input type="text" name="id" class="form-control border-end-0" placeholder="vd: shopquanao" required pattern="[a-z0-9]+" title="Chỉ dùng chữ thường và số">
                                <span class="input-group-text bg-white border-start-0 text-muted">.webappbacninh.test</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Email của bạn</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-premium">
                                Khởi tạo ngay <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                        <p class="text-center mt-3 mb-0 small text-muted">Không cần thẻ tín dụng. Miễn phí trọn đời gói cơ bản.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Ad Slot: After Hero --}}
<x-frontend.ad-slot position="after_hero" class="container py-4" />

{{-- Theme Library Section --}}
<x-frontend.templates-section />

{{-- Services Section --}}
<x-frontend.services-section />

{{-- Promo Banner --}}
<x-frontend.ad-slot position="homepage_promo" class="w-100" />

{{-- Projects / Portfolio Section --}}
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase ls-2">Dự án tiêu biểu</h6>
            <h2 class="fw-bold display-6">Khách hàng thành công</h2>
        </div>
        
        <div class="row g-4">
            @forelse($projects as $project)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 hover-top">
                    <div class="bg-light ratio ratio-4x3 rounded-top overflow-hidden">
                        @if($project->image)
                            <img src="{{ $project->image_url }}" alt="{{ $project->title }}" class="img-fluid object-fit-cover w-100 h-100">
                        @else
                            <div class="d-flex align-items-center justify-content-center text-muted bg-secondary bg-opacity-10 h-100">
                                <i class="fas fa-image fa-3x"></i>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($project->category)
                            <span class="badge bg-info bg-opacity-10 text-info mb-2">{{ $project->category->label() }}</span>
                        @endif
                        <h5 class="fw-bold card-title">{{ $project->title }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($project->description, 80) }}</p>
                        @if($project->link)
                            <a href="{{ $project->link }}" target="_blank" class="text-primary fw-bold small text-decoration-none">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            {{-- Fallback: Placeholder cards khi chưa có dự án --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 hover-top">
                    <div class="bg-light ratio ratio-4x3 rounded-top overflow-hidden">
                        <div class="d-flex align-items-center justify-content-center text-muted bg-secondary bg-opacity-10 h-100">
                            <i class="fas fa-image fa-3x"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold card-title">Chuỗi thời trang NEM</h5>
                        <p class="card-text text-muted small">Website bán hàng tích hợp quản lý kho và Loyalty App.</p>
                        <a href="#" class="text-primary fw-bold small text-decoration-none">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 hover-top">
                    <div class="bg-light ratio ratio-4x3 rounded-top overflow-hidden">
                        <div class="d-flex align-items-center justify-content-center text-muted bg-secondary bg-opacity-10 h-100">
                            <i class="fas fa-laptop-code fa-3x"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold card-title">Sàn BĐS Bắc Ninh Land</h5>
                        <p class="card-text text-muted small">Cổng thông tin bất động sản với bản đồ quy hoạch số.</p>
                        <a href="#" class="text-primary fw-bold small text-decoration-none">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 hover-top">
                    <div class="bg-light ratio ratio-4x3 rounded-top overflow-hidden">
                        <div class="d-flex align-items-center justify-content-center text-muted bg-secondary bg-opacity-10 h-100">
                            <i class="fas fa-utensils fa-3x"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold card-title">Nhà hàng Phượng Hoàng</h5>
                        <p class="card-text text-muted small">Website đặt bàn và menu điện tử QR Code.</p>
                        <a href="#" class="text-primary fw-bold small text-decoration-none">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
        
        <div class="text-center mt-5">
            <a href="#" class="btn btn-outline-primary px-4">Xem tất cả dự án</a>
        </div>
    </div>
</section>

{{-- Ecosystem Section --}}
<x-frontend.mini-apps-section />

{{-- Ad Slot: Before Blog --}}
<x-frontend.ad-slot position="before_blog" class="container py-4" />

{{-- Blog / News Section --}}
<section class="py-5 bg-white border-top">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h6 class="text-primary fw-bold text-uppercase ls-2">Tin tức & Sự kiện</h6>
                <h2 class="fw-bold display-6">Bài viết mới nhất</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="btn btn-outline-dark rounded-pill px-4 d-none d-md-block">Xem Blog</a>
        </div>

        <div class="row g-4">
            @forelse($posts as $post)
                <div class="col-md-4">
                    <div class="card border-0 h-100 hover-shadow transition rounded-4 overflow-hidden shadow-sm">
                        <div class="ratio ratio-16x9 mb-3 bg-secondary bg-opacity-10">
                        @if($post->hasMedia('featured') || $post->featured_image)
                            <img src="{{ $post->featured_image_url }}" class="img-fluid object-fit-cover w-100 h-100" alt="{{ $post->title }}">
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
                            <p class="card-text text-muted small line-clamp-2">{{ $post->summary ?? Str::limit(strip_tags($post->content), 100) }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <p>Hiện chưa có bài viết nào mới. Anh quay lại sau nhé!</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Tech Stack / Trust Section --}}
<section class="py-5 bg-white border-top">
    <div class="container text-center">
        <p class="text-muted small fw-bold text-uppercase mb-4">Công nghệ lõi</p>
        <div class="d-flex justify-content-center gap-5 align-items-center opacity-50 grayscale-hover transition">
            <i class="fab fa-laravel fa-3x" title="Laravel"></i>
            <i class="fab fa-vuejs fa-3x" title="Vue.js"></i>
            <i class="fab fa-bootstrap fa-3x" title="Bootstrap"></i>
            <i class="fab fa-aws fa-3x" title="Cloud Server"></i>
        </div>
    </div>
</section>

{{-- Call to Action --}}
<section id="register-section" class="py-5 text-white text-center position-relative overflow-hidden" style="background: #1a1a2e;">
    <div class="container position-relative z-1 py-5">
        <h2 class="display-5 fw-bold mb-4">Bạn có ý tưởng? Chúng tôi có công nghệ.</h2>
        <p class="lead text-white-50 mb-5">Đừng để ý tưởng của bạn chỉ nằm trên giấy. Hãy bắt đầu ngay hôm nay.</p>
        <a href="#" class="btn btn-premium btn-lg px-5">
            Đăng ký thành viên ngay
        </a>
    </div>
</section>

@endsection
