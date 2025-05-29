<section class="hero-section py-4 bg-white border-bottom">
  <div class="container">
    <div class="row g-3">
      {{-- CỘT 1: Danh mục website --}}
      <div class="col-lg-3 d-none d-lg-block">
        <div class="bg-light rounded shadow-sm p-3 h-100">
          <h6 class="text-uppercase fw-bold text-primary mb-3">Danh mục</h6>
          <ul class="list-unstyled small">
            <li class="mb-2">
              <a href="{{ route('templates.show', 'thuong-mai') }}" class="text-decoration-none text-dark">
                <i class="fas fa-store me-2 text-primary"></i> Website thương mại
            </a>
        </li>
        <li class="mb-2">
          <a href="{{ route('templates.show', 'bat-dong-san') }}" class="text-decoration-none text-dark">
            <i class="fas fa-building me-2 text-primary"></i> Bất động sản
        </a>
    </li>
    <li class="mb-2">
      <a href="{{ route('templates.show', 'tuyen-sinh') }}" class="text-decoration-none text-dark">
        <i class="fas fa-user-graduate me-2 text-primary"></i> Tuyển sinh
    </a>
</li>
<li class="mb-2">
  <a href="{{ route('templates.show', 'nong-san') }}" class="text-decoration-none text-dark">
    <i class="fas fa-leaf me-2 text-primary"></i> Nông sản / Thực phẩm
</a>
</li>
<li class="mb-2">
  <a href="{{ route('templates.show', 'thoi-trang') }}" class="text-decoration-none text-dark">
    <i class="fas fa-tshirt me-2 text-primary"></i> Thời trang
</a>
</li>
<li class="mb-2">
  <a href="{{ route('templates.show', 'doanh-nghiep') }}" class="text-decoration-none text-dark">
    <i class="fas fa-industry me-2 text-primary"></i> Công nghiệp / Doanh nghiệp
</a>
</li>
<li class="mt-3">
  <a href="{{ route('templates.index') }}" class="text-success text-decoration-none">
    + Xem tất cả
</a>
</li>
</ul>
</div>
</div>

{{-- CỘT 2: SLIDE Swiper --}}
@include('partials.frontend.slide')


{{-- CỘT 3: Banner dịch vụ --}}
<div class="col-lg-3">
    <div class="d-flex flex-column gap-3">
      <a href="{{ route('services.show', 'hosting') }}" class="d-block position-relative overflow-hidden rounded shadow-sm">
        <img src="{{ asset('images/banner-hosting.png') }}" class="img-fluid rounded" alt="Hosting giá rẻ">
    </a>
    <a href="{{ route('services.show', 'domain') }}" class="d-block position-relative overflow-hidden rounded shadow-sm">
        <img src="{{ asset('images/banner-domain.png') }}" class="img-fluid rounded" alt="Tên miền khuyến mãi">
    </a>
</div>
</div>
</div>
</div>
</section>
