<header class="shadow-sm-soft transition-all" id="mainHeader" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
  
  {{-- Top Bar (Premium Slim) --}}
  <div class="bg-dark text-white py-1 small shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <span class="fw-bold"><i class="fas fa-phone-alt me-1 text-primary"></i> {{ setting('contact_phone', '0856 843 891') }}</span>
            <span class="d-none d-md-inline border-start border-white-25 ps-3"><i class="fas fa-envelope me-1 text-primary"></i> {{ setting('contact_email', 'webappbacninh@gmail.com') }}</span>
        </div>
        <div class="d-flex align-items-center gap-3">
             <a href="{{ setting('social_facebook', '#') }}" class="text-white-50 hover-text-white transition" target="_blank"><i class="fab fa-facebook-f"></i></a>
             <a href="{{ setting('social_youtube', '#') }}" class="text-white-50 hover-text-white transition" target="_blank"><i class="fab fa-youtube"></i></a>
             <span class="d-none d-lg-inline text-white-50 ms-2">#ChuyênNghiệp #TậnTâm</span>
        </div>
    </div>
  </div>

  {{-- Main Navbar --}}
  <nav class="navbar navbar-expand-lg py-3">
    <div class="container">
      {{-- Logo --}}
      <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
        <img src="{{ asset(setting('site_logo_wide', 'images/logo-wide.png')) }}" alt="{{ setting('site_name', 'WebApp Bắc Ninh') }}" height="65" class="transition-all hover-scale">
      </a>

      <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fw-bold gap-lg-3 text-uppercase small">
          <li class="nav-item">
            <a class="nav-link text-dark px-2 {{ Request::is('/') ? 'active border-bottom border-primary border-3' : '' }}" href="{{ url('/') }}">Trang chủ</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark px-2 hvr-underline" href="#services">Dịch vụ</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark px-2 hvr-underline" href="#ecosystem">Giao diện</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark px-2 hvr-underline" href="{{ route('blog.index') }}">Blog</a>
          </li>
           <li class="nav-item">
            <a class="nav-link text-dark px-2 hvr-underline" href="{{ route('contact') }}">Liên hệ</a>
          </li>
        </ul>

        {{-- Action Buttons --}}
        <div class="d-flex align-items-center gap-2 ms-lg-4">
           @auth
            <div class="dropdown">
              <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark fw-bold" data-bs-toggle="dropdown">
                <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" class="rounded-circle border border-2 border-primary me-2" width="40" height="40">
                <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-2">
                <li><a class="dropdown-item rounded" href="{{ route('filament.admin.pages.dashboard') }}"><i class="fas fa-tachometer-alt me-2 text-primary"></i> Dashboard</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="dropdown-item rounded text-danger"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</button>
                  </form>
                </li>
              </ul>
            </div>
          @else
            <a href="{{ route('login') }}" class="btn btn-outline-dark btn-sm px-4 fw-bold transition">Đăng nhập</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-4 fw-bold shadow-sm transition">Đăng ký</a>
          @endauth
        </div>
      </div>
    </div>
  </nav>
</header>
