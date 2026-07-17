<div class="topbar d-none d-lg-block">
  <div class="container d-flex justify-content-between align-items-center">
    <div class="topbar__left">
      <span><i class="fa-solid fa-location-dot"></i> Phục vụ doanh nghiệp tại Bắc Ninh và khu vực lân cận</span>
      <span><i class="fa-regular fa-clock"></i> <?= e(config('working_time')) ?></span>
    </div>
    <div class="topbar__right">
      <a href="mailto:<?= e(config('email')) ?>"><i class="fa-regular fa-envelope"></i> <?= e(config('email')) ?></a>
      <a class="topbar__hotline" href="tel:<?= e(config('phone_href')) ?>"><i class="fa-solid fa-phone-volume"></i> <?= e(config('phone')) ?></a>
    </div>
  </div>
</div>

<header class="site-header sticky-top" id="siteHeader">
  <nav class="navbar navbar-expand-xl navbar-light bg-white">
    <div class="container">
      <a class="navbar-brand brand" href="<?= e(url('index.php')) ?>" aria-label="WebApp Bắc Ninh">
        <span class="brand__mark"><i class="fa-solid fa-code"></i></span>
        <span class="brand__text"><strong>WEBAPP</strong><small>BẮC NINH</small></span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-label="Mở menu"><span class="navbar-toggler-icon"></span></button>

      <div class="collapse navbar-collapse d-none d-xl-flex">
        <ul class="navbar-nav ms-auto align-items-xl-center">
          <li class="nav-item"><a class="nav-link <?= ($activeMenu ?? '') === 'home' ? 'active' : '' ?>" href="<?= e(url('index.php')) ?>">Trang chủ</a></li>
          <li class="nav-item dropdown desktop-dropdown">
            <a class="nav-link dropdown-toggle <?= ($activeMenu ?? '') === 'website-service' ? 'active' : '' ?>" href="<?= e(url('thiet-ke-website.php')) ?>" aria-haspopup="true">Thiết kế website</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="<?= e(url('thiet-ke-website.php')) ?>"><i class="fa-solid fa-border-all me-2"></i>Tổng quan dịch vụ</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item <?= ($activeSubmenu ?? '') === 'business' ? 'active' : '' ?>" href="<?= e(url('website-doanh-nghiep.php')) ?>">Website doanh nghiệp</a></li>
              <li><a class="dropdown-item <?= ($activeSubmenu ?? '') === 'ecommerce' ? 'active' : '' ?>" href="<?= e(url('website-ban-hang.php')) ?>">Website bán hàng</a></li>
              <li><a class="dropdown-item <?= ($activeSubmenu ?? '') === 'landing' ? 'active' : '' ?>" href="<?= e(url('landing-page.php')) ?>">Landing page</a></li>
              <li><a class="dropdown-item" href="<?= e(url('thiet-ke-website.php#theo-nganh')) ?>">Website theo ngành</a></li>
              <li><a class="dropdown-item <?= ($activeSubmenu ?? '') === 'redesign' ? 'active' : '' ?>" href="<?= e(url('thiet-ke-lai-website.php')) ?>">Thiết kế lại website cũ</a></li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link <?= ($activeMenu ?? '') === 'themes' ? 'active' : '' ?>" href="<?= e(url('kho-giao-dien.php')) ?>">Kho giao diện</a></li>
          <li class="nav-item dropdown desktop-dropdown">
            <a class="nav-link dropdown-toggle <?= ($activeMenu ?? '') === 'operations' ? 'active' : '' ?>" href="<?= e(url('dich-vu-van-hanh.php')) ?>" aria-haspopup="true">Dịch vụ vận hành</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="<?= e(url('dich-vu-van-hanh.php')) ?>"><i class="fa-solid fa-border-all me-2"></i>Tổng quan dịch vụ</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item <?= ($activeSubmenu ?? '') === 'hosting' ? 'active' : '' ?>" href="<?= e(url('hosting-bao-tri-website.php')) ?>">Hosting và bảo trì</a></li>
              <li><a class="dropdown-item <?= ($activeSubmenu ?? '') === 'content' ? 'active' : '' ?>" href="<?= e(url('quan-tri-dang-bai-website.php')) ?>">Quản trị và đăng bài</a></li>
              <li><a class="dropdown-item <?= ($activeSubmenu ?? '') === 'seo' ? 'active' : '' ?>" href="<?= e(url('seo-website.php')) ?>">SEO website</a></li>
              <li><a class="dropdown-item <?= ($activeSubmenu ?? '') === 'facebook' ? 'active' : '' ?>" href="<?= e(url('noi-dung-facebook.php')) ?>">Nội dung Facebook</a></li>
              <li><a class="dropdown-item <?= ($activeSubmenu ?? '') === 'upgrade' ? 'active' : '' ?>" href="<?= e(url('nang-cap-tich-hop-website.php')) ?>">Nâng cấp và tích hợp</a></li>
              <li><a class="dropdown-item <?= ($activeSubmenu ?? '') === 'analytics' ? 'active' : '' ?>" href="<?= e(url('do-luong-bao-cao-website.php')) ?>">Đo lường và báo cáo</a></li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link <?= ($activeMenu ?? '') === 'projects' ? 'active' : '' ?>" href="<?= e(url('du-an.php')) ?>">Dự án</a></li>
          <li class="nav-item"><a class="nav-link <?= ($activeMenu ?? '') === 'pricing' ? 'active' : '' ?>" href="<?= e(url('bang-gia.php')) ?>">Bảng giá</a></li>
          <li class="nav-item"><a class="nav-link <?= ($activeMenu ?? '') === 'agency' ? 'active' : '' ?>" href="<?= e(url('hop-tac-agency.php')) ?>">Hợp tác Agency</a></li>
          <li class="nav-item"><a class="nav-link <?= ($activeMenu ?? '') === 'knowledge' ? 'active' : '' ?>" href="<?= e(url('kien-thuc.php')) ?>">Kiến thức</a></li>
        </ul>
        <a class="btn btn-primary btn-header ms-xl-3" href="<?= e($headerCta ?? url('lien-he.php')) ?>">Nhận tư vấn</a>
      </div>
    </div>
  </nav>
</header>

<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
  <div class="offcanvas-header">
    <div class="brand" id="mobileMenuLabel"><span class="brand__mark"><i class="fa-solid fa-code"></i></span><span class="brand__text"><strong>WEBAPP</strong><small>BẮC NINH</small></span></div>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
  </div>
  <div class="offcanvas-body">
    <nav class="mobile-nav" id="mobileNavigation">
      <a class="mobile-nav__single" href="<?= e(url('index.php')) ?>">Trang chủ</a>

      <div class="mobile-nav__item">
        <div class="mobile-nav__row">
          <a href="<?= e(url('thiet-ke-website.php')) ?>">Thiết kế website</a>
          <button class="mobile-nav__toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mobileWebsiteMenu" aria-expanded="<?= ($activeMenu ?? '') === 'website-service' ? 'true' : 'false' ?>" aria-controls="mobileWebsiteMenu" aria-label="Mở menu thiết kế website"><i class="fa-solid fa-angle-down"></i></button>
        </div>
        <div class="collapse <?= ($activeMenu ?? '') === 'website-service' ? 'show' : '' ?>" id="mobileWebsiteMenu" data-bs-parent="#mobileNavigation">
          <div class="mobile-nav__submenu">
            <a href="<?= e(url('website-doanh-nghiep.php')) ?>" class="<?= ($activeSubmenu ?? '') === 'business' ? 'active' : '' ?>">Website doanh nghiệp</a>
            <a href="<?= e(url('website-ban-hang.php')) ?>" class="<?= ($activeSubmenu ?? '') === 'ecommerce' ? 'active' : '' ?>">Website bán hàng</a>
            <a href="<?= e(url('landing-page.php')) ?>" class="<?= ($activeSubmenu ?? '') === 'landing' ? 'active' : '' ?>">Landing page</a>
            <a href="<?= e(url('thiet-ke-website.php#theo-nganh')) ?>">Website theo ngành</a>
            <a href="<?= e(url('thiet-ke-lai-website.php')) ?>" class="<?= ($activeSubmenu ?? '') === 'redesign' ? 'active' : '' ?>">Thiết kế lại website cũ</a>
          </div>
        </div>
      </div>

      <a class="mobile-nav__single" href="<?= e(url('kho-giao-dien.php')) ?>">Kho giao diện</a>

      <div class="mobile-nav__item">
        <div class="mobile-nav__row">
          <a href="<?= e(url('dich-vu-van-hanh.php')) ?>">Dịch vụ vận hành</a>
          <button class="mobile-nav__toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mobileOperationMenu" aria-expanded="<?= ($activeMenu ?? '') === 'operations' ? 'true' : 'false' ?>" aria-controls="mobileOperationMenu" aria-label="Mở menu dịch vụ vận hành"><i class="fa-solid fa-angle-down"></i></button>
        </div>
        <div class="collapse <?= ($activeMenu ?? '') === 'operations' ? 'show' : '' ?>" id="mobileOperationMenu" data-bs-parent="#mobileNavigation">
          <div class="mobile-nav__submenu">
            <a href="<?= e(url('hosting-bao-tri-website.php')) ?>" class="<?= ($activeSubmenu ?? '') === 'hosting' ? 'active' : '' ?>">Hosting và bảo trì</a>
            <a href="<?= e(url('quan-tri-dang-bai-website.php')) ?>" class="<?= ($activeSubmenu ?? '') === 'content' ? 'active' : '' ?>">Quản trị và đăng bài</a>
            <a href="<?= e(url('seo-website.php')) ?>" class="<?= ($activeSubmenu ?? '') === 'seo' ? 'active' : '' ?>">SEO website</a>
            <a href="<?= e(url('noi-dung-facebook.php')) ?>" class="<?= ($activeSubmenu ?? '') === 'facebook' ? 'active' : '' ?>">Nội dung Facebook</a>
            <a href="<?= e(url('nang-cap-tich-hop-website.php')) ?>" class="<?= ($activeSubmenu ?? '') === 'upgrade' ? 'active' : '' ?>">Nâng cấp và tích hợp</a>
            <a href="<?= e(url('do-luong-bao-cao-website.php')) ?>" class="<?= ($activeSubmenu ?? '') === 'analytics' ? 'active' : '' ?>">Đo lường và báo cáo</a>
          </div>
        </div>
      </div>

      <a class="mobile-nav__single" href="<?= e(url('du-an.php')) ?>">Dự án</a>
      <a class="mobile-nav__single" href="<?= e(url('bang-gia.php')) ?>">Bảng giá</a>
      <a class="mobile-nav__single" href="<?= e(url('hop-tac-agency.php')) ?>">Hợp tác Agency</a>
      <a class="mobile-nav__single" href="<?= e(url('kien-thuc.php')) ?>">Kiến thức</a>
      <a class="mobile-nav__single" href="<?= e(url('gioi-thieu.php')) ?>">Giới thiệu</a>
      <a class="mobile-nav__single" href="<?= e(url('lien-he.php')) ?>">Liên hệ</a>
    </nav>
    <div class="mobile-contact">
      <a class="btn btn-primary w-100" href="<?= e($headerCta ?? url('lien-he.php')) ?>">Yêu cầu tư vấn</a>
      <a class="btn btn-outline-primary w-100" href="tel:<?= e(config('phone_href')) ?>"><i class="fa-solid fa-phone"></i> <?= e(config('phone')) ?></a>
    </div>
  </div>
</div>
