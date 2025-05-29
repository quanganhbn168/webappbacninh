<header class="shadow-sm border-bottom">
  {{-- Dòng 1: Liên hệ & đa ngôn ngữ --}}
  <div class="bg-light py-1 px-3 d-flex justify-content-between align-items-center small">
    <div>
      <i class="fas fa-phone-alt me-2"></i> 0988.888.888 |
      <i class="fas fa-envelope ms-3 me-2"></i> support@webappbacninh.vn
    </div>
    <div>
      <select class="form-select form-select-sm d-inline w-auto" aria-label="Ngôn ngữ">
        <option selected>VI</option>
        <option value="en">EN</option>
        <option value="ja">JA</option>
      </select>
    </div>
  </div>
  <div class="container">
  
  {{-- Dòng 2: Logo + tìm kiếm + tài khoản --}}
  <div class="py-2 px-3 d-flex justify-content-between align-items-center flex-wrap">
    {{-- Logo --}}
    <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center">
      <img src="{{ asset('images/webapp-logo.png') }}" alt="WebApp Bắc Ninh" style="height: 40px;">
      <span class="ms-2 fw-bold">WebApp Bắc Ninh</span>
    </a>

    {{-- Search --}}
    <form action="{{ route('search') }}" method="GET" class="d-none d-md-flex flex-grow-1 mx-3">
      <input type="text" name="q" class="form-control" placeholder="Tìm kiếm...">
    </form>

    {{-- Tài khoản --}}
    <div>
      @auth
        <div class="dropdown">
          <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" alt="avatar" class="rounded-circle me-2" width="32" height="32">
            <strong>{{ Auth::user()->name }}</strong>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
            <li><a class="dropdown-item" href="{{ route('dashboard') }}">Bảng điều khiển</a></li>
            <li><a class="dropdown-item" href="{{ route('account.settings') }}">Tài khoản</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="dropdown-item">Đăng xuất</button>
              </form>
            </li>
          </ul>
        </div>
      @else
        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm me-2">Đăng nhập</a>
        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Đăng ký</a>
      @endauth
    </div>
  </div>
  </div>
  {{-- Dòng 3: Menu chính --}}
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary py-1 px-3">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      <div class="container">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Trang chủ</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Website</a></li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dichvuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Dịch vụ</a>
            <ul class="dropdown-menu" aria-labelledby="dichvuDropdown">
              <li><a class="dropdown-item" href="#">Hosting</a></li>
              <li><a class="dropdown-item" href="#">Tên miền</a></li>
              <li><a class="dropdown-item" href="#">VPS</a></li>
            </ul>
          </li>

          <li class="nav-item"><a class="nav-link" href="{{ route('blog.index') }}">Blog</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Liên hệ</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>
