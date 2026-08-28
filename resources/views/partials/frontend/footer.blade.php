<footer class="bg-dark text-white pt-5 pb-4 mt-auto border-top border-3 border-primary">
  <div class="container">
    <div class="row g-4">
      
      {{-- Brand Column --}}
      <div class="col-lg-4 col-md-6">
        <a href="{{ url('/') }}" class="d-flex align-items-center text-white text-decoration-none mb-3">
             <img src="{{ site_asset_url(setting('site_logo_white') ?: setting('site_logo_wide'), 'images/webapp-logo.png') }}" alt="{{ setting('site_name', 'WebApp Bắc Ninh') }}" height="50" class="transition-all hover-scale">
        </a>
        <p class="text-white-50 small mb-4 pr-lg-5">
          Nền tảng công nghệ All-in-One giúp doanh nghiệp Bắc Ninh chuyển đổi số thành công. Thiết kế Website, App Mobile, CRM và Marketing tự động.
        </p>
        <div class="d-flex gap-3 social-icons">
          <a href="https://www.facebook.com/webappbacninh" target="_blank" class="btn btn-outline-light btn-sm rounded-circle"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="fab fa-youtube"></i></a>
          <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="fab fa-tiktok"></i></a>
          <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="fas fa-envelope"></i></a>
        </div>
      </div>

      {{-- Services Column --}}
      <div class="col-lg-2 col-md-6">
        <h6 class="fw-bold text-uppercase text-primary mb-3 ls-1">Dịch vụ</h6>
        <ul class="list-unstyled d-flex flex-column gap-2 small text-white-50">
          <li><a href="#" class="text-reset text-decoration-none hover-white"><i class="fas fa-chevron-right me-2 text-primary" style="font-size: 0.7rem;"></i>Thiết kế Website</a></li>
          <li><a href="#" class="text-reset text-decoration-none hover-white"><i class="fas fa-chevron-right me-2 text-primary" style="font-size: 0.7rem;"></i>Lập trình App</a></li>
          <li><a href="#" class="text-reset text-decoration-none hover-white"><i class="fas fa-chevron-right me-2 text-primary" style="font-size: 0.7rem;"></i>Hosting & VPS</a></li>
          <li><a href="#" class="text-reset text-decoration-none hover-white"><i class="fas fa-chevron-right me-2 text-primary" style="font-size: 0.7rem;"></i>Tên miền .VN</a></li>
        </ul>
      </div>

      {{-- Support Column --}}
      <div class="col-lg-3 col-md-6">
        <h6 class="fw-bold text-uppercase text-primary mb-3 ls-1">Hỗ trợ khách hàng</h6>
        <ul class="list-unstyled d-flex flex-column gap-2 small text-white-50">
          <li><a href="#" class="text-reset text-decoration-none hover-white">Trung tâm trợ giúp</a></li>
          <li><a href="#" class="text-reset text-decoration-none hover-white">Chính sách bảo mật</a></li>
          <li><a href="#" class="text-reset text-decoration-none hover-white">Điều khoản sử dụng</a></li>
          <li><a href="#" class="text-reset text-decoration-none hover-white">Gửi yêu cầu hỗ trợ</a></li>
        </ul>
        <div class="mt-4 p-3 bg-white bg-opacity-10 rounded-3 border border-white-10">
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-headset fa-2x text-primary me-3"></i>
                <div class="lh-1">
                    <span class="d-block small text-white-50 mb-1">Hotline tư vấn 24/7</span>
                    <span class="fw-bold fs-5 text-white">0856 843 891</span>
                </div>
            </div>
        </div>
      </div>

      {{-- Contact Column --}}
      <div class="col-lg-3 col-md-6">
        <h6 class="fw-bold text-uppercase text-primary mb-3 ls-1">Liên hệ</h6>
        <ul class="list-unstyled small text-white-50 mb-4">
            <li class="mb-3 d-flex">
                <i class="fas fa-user-circle mt-1 me-3 text-primary"></i>
                <div>
                    <strong class="text-white d-block">{{ setting('contact_name', 'Mr. Trần Quang Anh') }}</strong>
                    <span>Founder & CEO</span>
                </div>
            </li>
             <li class="mb-3 d-flex">
                <i class="fas fa-map-marker-alt mt-1 me-3 text-primary"></i>
                <span>{{ setting('contact_address', 'Nhị Trai, Trung Chính, Lương Tài, Bắc Ninh, Việt Nam') }}</span>
            </li>
             <li class="d-flex">
                <i class="fas fa-envelope mt-1 me-3 text-primary"></i>
                <span>{{ setting('contact_email', 'webappbacninh@gmail.com') }}</span>
            </li>
        </ul>
        <form action="#" class="position-relative">
            <input type="email" class="form-control form-control-sm rounded-pill pe-5 bg-dark border-secondary text-white placeholder-white-50" placeholder="Email nhận tin...">
            <button class="btn btn-primary btn-sm rounded-circle position-absolute top-50 end-0 translate-middle-y me-1" style="width: 30px; height: 30px;"><i class="fas fa-paper-plane small"></i></button>
        </form>
      </div>
    </div>

    <hr class="border-secondary my-4 opacity-25">

    <div class="row align-items-center small text-white-50">
      <div class="col-md-6 text-center text-md-start">
        &copy; {{ now()->year }} <strong class="text-white">WebApp Bắc Ninh</strong>. All rights reserved.
      </div>
      <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
        <span class="me-3"><i class="fas fa-shield-alt text-success me-1"></i> Bảo mật SSL</span>
        <span><i class="fas fa-rocket text-danger me-1"></i> Tốc độ cao</span>
      </div>
    </div>
  </div>
</footer>
