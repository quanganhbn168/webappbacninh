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
                    <i class="fas fa-rocket me-2"></i> Phiên bản Beta 1.0
                </span>
                <h1 class="display-3 fw-bold mb-4" style="text-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                    Khởi tạo Website <br>
                    <span style="color: #ffd700;">Chỉ trong 5 giây</span>
                </h1>
                <p class="lead mb-5" style="opacity: 0.9; max-width: 600px;">
                    Nền tảng công nghệ "All-in-One". Không chỉ là website bán hàng, mà là cả một hệ sinh thái công cụ hỗ trợ kinh doanh, tính toán và quản trị dành riêng cho người Bắc Ninh.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="#register-section" class="btn btn-light btn-lg px-5 shadow-lg fw-bold text-primary">
                        <i class="fas fa-magic me-2"></i> Tạo Website Ngay
                    </a>
                    <a href="#ecosystem" class="btn btn-outline-light btn-lg px-5">
                        Khám phá Hệ sinh thái
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
<x-frontend.ad-slot slot="after_hero" class="container py-4" />

{{-- Theme Library Section --}}
<section class="py-5 bg-white border-bottom">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h6 class="text-primary fw-bold text-uppercase ls-2">Giao diện mẫu</h6>
                <h2 class="fw-bold display-6">Kho giao diện phong phú</h2>
            </div>
            <a href="{{ route('templates.index') }}" class="btn btn-outline-primary px-4 d-none d-md-block">Xem tất cả</a>
        </div>

        <div class="row g-4">
            {{-- Theme 1 --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 hover-top">
                    <div class="card-header bg-light border-bottom-0 pt-3 px-3 pb-0">
                        <div class="d-flex gap-1 mb-2">
                             <div class="rounded-circle bg-danger" style="width: 8px; height: 8px;"></div>
                             <div class="rounded-circle bg-warning" style="width: 8px; height: 8px;"></div>
                             <div class="rounded-circle bg-success" style="width: 8px; height: 8px;"></div>
                        </div>
                    </div>
                    <div class="ratio ratio-4x3 overflow-hidden bg-secondary bg-opacity-10">
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <i class="fas fa-shopping-bag fa-2x"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="badge bg-info bg-opacity-10 text-info mb-2">Bán hàng</div>
                        <h6 class="fw-bold mb-1">E-Shop Modern</h6>
                        <p class="text-muted small">Phong cách hiện đại, tối ưu chuyển đổi.</p>
                        <a href="#" class="btn btn-sm btn-primary w-100">Xem Demo</a>
                    </div>
                </div>
            </div>

            {{-- Theme 2 --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 hover-top">
                    <div class="card-header bg-light border-bottom-0 pt-3 px-3 pb-0">
                        <div class="d-flex gap-1 mb-2">
                             <div class="rounded-circle bg-danger" style="width: 8px; height: 8px;"></div>
                             <div class="rounded-circle bg-warning" style="width: 8px; height: 8px;"></div>
                             <div class="rounded-circle bg-success" style="width: 8px; height: 8px;"></div>
                        </div>
                    </div>
                    <div class="ratio ratio-4x3 overflow-hidden bg-secondary bg-opacity-10">
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <i class="fas fa-building fa-2x"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="badge bg-warning bg-opacity-10 text-warning mb-2">Doanh nghiệp</div>
                        <h6 class="fw-bold mb-1">BizCorp Pro</h6>
                        <p class="text-muted small">Chuyên nghiệp, tin cậy cho công ty.</p>
                        <a href="#" class="btn btn-sm btn-primary w-100">Xem Demo</a>
                    </div>
                </div>
            </div>

            {{-- Theme 3 --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 hover-top">
                    <div class="card-header bg-light border-bottom-0 pt-3 px-3 pb-0">
                        <div class="d-flex gap-1 mb-2">
                             <div class="rounded-circle bg-danger" style="width: 8px; height: 8px;"></div>
                             <div class="rounded-circle bg-warning" style="width: 8px; height: 8px;"></div>
                             <div class="rounded-circle bg-success" style="width: 8px; height: 8px;"></div>
                        </div>
                    </div>
                    <div class="ratio ratio-4x3 overflow-hidden bg-secondary bg-opacity-10">
                         <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <i class="fas fa-home fa-2x"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="badge bg-success bg-opacity-10 text-success mb-2">Bất động sản</div>
                        <h6 class="fw-bold mb-1">RealEstate Plus</h6>
                        <p class="text-muted small">Bản đồ quy hoạch, lọc tìm kiếm nâng cao.</p>
                        <a href="#" class="btn btn-sm btn-primary w-100">Xem Demo</a>
                    </div>
                </div>
            </div>

            {{-- Theme 4 --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 hover-top">
                    <div class="card-header bg-light border-bottom-0 pt-3 px-3 pb-0">
                        <div class="d-flex gap-1 mb-2">
                             <div class="rounded-circle bg-danger" style="width: 8px; height: 8px;"></div>
                             <div class="rounded-circle bg-warning" style="width: 8px; height: 8px;"></div>
                             <div class="rounded-circle bg-success" style="width: 8px; height: 8px;"></div>
                        </div>
                    </div>
                    <div class="ratio ratio-4x3 overflow-hidden bg-secondary bg-opacity-10">
                         <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <i class="fas fa-utensils fa-2x"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="badge bg-danger bg-opacity-10 text-danger mb-2">F&B</div>
                        <h6 class="fw-bold mb-1">Tasty Food</h6>
                        <p class="text-muted small">Menu điện tử, đặt bàn nhanh.</p>
                        <a href="#" class="btn btn-sm btn-primary w-100">Xem Demo</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Services Section --}}
<section id="services" class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase ls-2">Dịch vụ cốt lõi</h6>
            <h2 class="fw-bold display-6">Giải pháp toàn diện</h2>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="p-4 border rounded-3 h-100 hover-shadow transition">
                    <i class="fas fa-server fa-3x text-info mb-3"></i>
                    <h5 class="fw-bold">Hosting & VPS</h5>
                    <p class="text-muted small">Hạ tầng mạnh mẽ, ổn định, đặt tại Việt Nam. Tối ưu riêng cho Laravel.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 border rounded-3 h-100 hover-shadow transition">
                    <i class="fas fa-globe fa-3x text-success mb-3"></i>
                    <h5 class="fw-bold">Tên miền</h5>
                    <p class="text-muted small">Đăng ký tên miền .vn, .com nhanh chóng. Hỗ trợ đầy đủ thủ tục pháp lý.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 border rounded-3 h-100 hover-shadow transition">
                    <i class="fas fa-paint-brush fa-3x text-warning mb-3"></i>
                    <h5 class="fw-bold">Thiết kế Kiosk/Web</h5>
                    <p class="text-muted small">Giao diện độc quyền, trải nghiệm người dùng tối ưu (UX/UI).</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 border rounded-3 h-100 hover-shadow transition">
                    <i class="fas fa-rocket fa-3x text-danger mb-3"></i>
                    <h5 class="fw-bold">SEO & Marketing</h5>
                    <p class="text-muted small">Tư vấn chiến lược từ khóa, chạy quảng cáo Google/Facebook hiệu quả.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Promo Banner --}}
<section class="py-5 bg-dark position-relative overflow-hidden">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('https://source.unsplash.com/random/1920x600?tech') center/cover; opacity: 0.2;"></div>
    <div class="container position-relative z-1 text-center text-white">
        <span class="badge bg-danger mb-3">HOT DEAL</span>
        <h2 class="fw-bold display-5 mb-4">Khuyến mãi Mùa Hè Sôi Động</h2>
        <p class="lead mb-4">Giảm ngay <strong class="text-warning">50%</strong> phí khởi tạo cho 100 khách hàng đầu tiên đăng ký trong tháng này.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="#" class="btn btn-warning fw-bold text-dark px-4 py-2">Nhận ưu đãi ngay</a>
            <a href="#" class="btn btn-outline-light px-4 py-2">Xem chi tiết</a>
        </div>
    </div>
</section>

{{-- Projects / Portfolio Section --}}
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase ls-2">Dự án tiêu biểu</h6>
            <h2 class="fw-bold display-6">Khách hàng thành công</h2>
        </div>
        
        <div class="row g-4">
            {{-- Project 1 --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 hover-top">
                    <div class="bg-light ratio ratio-4x3 rounded-top overflow-hidden">
                        {{-- Placeholder Image --}}
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

            {{-- Project 2 --}}
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

            {{-- Project 3 --}}
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
        </div>
        
        <div class="text-center mt-5">
            <a href="#" class="btn btn-outline-primary px-4">Xem tất cả dự án</a>
        </div>
    </div>
</section>

{{-- Ecosystem Section --}}
<section id="ecosystem" class="py-5 bg-light">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase ls-2">Hệ sinh thái công cụ</h6>
            <h2 class="fw-bold display-6">Mini-Apps tiện ích</h2>
            <p class="text-muted mx-auto" style="max-width: 500px;">
                Kho công cụ miễn phí giúp bạn vận hành công việc kinh doanh dễ dàng hơn.
            </p>
        </div>

        {{-- Domain Check Tool --}}
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <div class="glass-card p-4 text-center">
                    <h4 class="fw-bold mb-3"><i class="fas fa-search me-2 text-primary"></i>Kiểm tra tên miền</h4>
                    <p class="text-muted small mb-4">Nhập tên miền bạn muốn đăng ký để kiểm tra tình trạng (VD: mybrand.vn, hanoi.com)</p>
                    <form action="{{ route('domain.check') }}" method="GET" class="d-flex gap-2">
                        <input type="text" name="domain" class="form-control form-control-lg" placeholder="Nhập tên miền..." required>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">Kiểm tra</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Core App --}}
            <x-app-card 
                title="Web Builder Pro"
                icon="fas fa-layer-group"
                description="Tạo website bán hàng, giới thiệu công ty tự động. Tích hợp sẵn giao diện Mobile."
                link="#register-section"
                badge="HOT"
            />

            {{-- Thumbnail Tool --}}
            <x-app-card 
                title="Tải Ảnh Cover"
                icon="fas fa-image"
                description="Công cụ tải ảnh cover Shopee/Lazada chất lượng cao, hỗ trợ tải hàng loạt."
                link="{{ route('cover.page') }}"
                badge="Free"
            />

            {{-- Tax Tool --}}
            <x-app-card 
                title="Tính Thuế Online" 
                icon="fas fa-calculator"
                description="Công cụ tính thuế TNCN, BHXH dành cho kế toán và nhân viên văn phòng."
                link="#"
                badge="Coming Soon"
            />
             
             {{-- QR Tool --}}
             <x-app-card 
                title="Tạo QR Ngân Hàng"
                icon="fas fa-qrcode"
                description="Tạo mã QR chuyển khoản VietQR nhanh chóng, in dán ngay tại quầy."
                link="#"
            />

             {{-- AI Content --}}
             <x-app-card 
                title="AI Viết Content"
                icon="fas fa-robot"
                description="Trợ lý AI giúp viết bài đăng Facebook, mô tả sản phẩm tự động."
                link="#"
                badge="Premium"
            />

             {{-- Support --}}
             <x-app-card 
                title="Hỗ trợ 24/7"
                icon="fas fa-headset"
                description="Đội ngũ kỹ thuật viên người Bắc Ninh hỗ trợ trực tiếp, không qua tổng đài."
                link="#"
            />
        </div>
    </div>
</section>

{{-- Ad Slot: Before Blog --}}
<x-frontend.ad-slot slot="before_blog" class="container py-4" />

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
