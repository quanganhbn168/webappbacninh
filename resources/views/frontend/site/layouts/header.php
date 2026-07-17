<?php
  $websiteMenuItems = array_values(config('website_services', []));
  $operationMenuItems = array_values(config('operation_services', []));
?>

<div class="topbar d-none d-lg-block">
  <div class="container d-flex justify-content-between align-items-center">
    <div class="topbar__left">
      <span><i class="fa-solid fa-location-dot"></i> Phục vụ doanh nghiệp tại Bắc Ninh và khu vực lân cận</span>
      <span><i class="fa-regular fa-clock"></i> <?= e(site_config('working_time')) ?></span>
    </div>
    <div class="topbar__right">
      <a href="mailto:<?= e(site_config('email')) ?>"><i class="fa-regular fa-envelope"></i> <?= e(site_config('email')) ?></a>
      <a class="topbar__hotline" href="tel:<?= e(site_config('phone_href')) ?>"><i class="fa-solid fa-phone-volume"></i> <?= e(site_config('phone')) ?></a>
    </div>
  </div>
</div>

<header class="site-header sticky-top" id="siteHeader">
  <nav class="navbar navbar-expand-xl navbar-light bg-white">
    <div class="container">
      <a class="navbar-brand brand" href="<?= e(route('home')) ?>" aria-label="WebApp Bắc Ninh">
        <span class="brand__mark"><i class="fa-solid fa-code"></i></span>
        <span class="brand__text"><strong>WEBAPP</strong><small>BẮC NINH</small></span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-label="Mở menu"><span class="navbar-toggler-icon"></span></button>

      <div class="collapse navbar-collapse d-none d-xl-flex">
        <ul class="navbar-nav ms-auto align-items-xl-center">
          <li class="nav-item"><a class="nav-link <?= ($activeMenu ?? '') === 'home' ? 'active' : '' ?>" href="<?= e(route('home')) ?>">Trang chủ</a></li>

          <li class="nav-item dropdown desktop-dropdown desktop-mega-dropdown">
            <a class="nav-link dropdown-toggle <?= ($activeMenu ?? '') === 'website-service' ? 'active' : '' ?>" href="<?= e(route('services.index')) ?>" aria-haspopup="true" aria-expanded="false">Thiết kế website</a>
            <div class="dropdown-menu mega-menu mega-menu--services">
              <div class="mega-menu__intro">
                <span class="mega-menu__eyebrow">GIẢI PHÁP WEBSITE</span>
                <h2>Website đúng nhu cầu, dễ phát triển</h2>
                <p>Chọn phạm vi phù hợp với mục tiêu kinh doanh hiện tại của doanh nghiệp.</p>
                <a class="mega-menu__overview" href="<?= e(route('services.index')) ?>">Xem toàn bộ dịch vụ <i class="fa-solid fa-arrow-right"></i></a>
              </div>
              <div class="mega-menu__links" aria-label="Dịch vụ thiết kế website">
                <?php foreach ($websiteMenuItems as $service): ?>
                  <a class="mega-menu__link <?= ($activeSubmenu ?? '') === ($service['menu_key'] ?? '') ? 'active' : '' ?>" href="<?= e(route('services.show', $service['slug'])) ?>">
                    <span class="mega-menu__icon"><i class="<?= e($service['icon']) ?>"></i></span>
                    <span><strong><?= e($service['eyebrow']) ?></strong><small><?= e($service['highlight']) ?></small></span>
                  </a>
                <?php endforeach; ?>
                <a class="mega-menu__link mega-menu__link--anchor" href="<?= e(route('services.index')) ?>#theo-nganh">
                  <span class="mega-menu__icon"><i class="fa-solid fa-layer-group"></i></span>
                  <span><strong>WEBSITE THEO NGÀNH</strong><small>Chọn cấu trúc phù hợp lĩnh vực kinh doanh.</small></span>
                </a>
              </div>
              <aside class="mega-menu__aside">
                <i class="fa-solid fa-compass-drafting"></i>
                <strong>Chưa rõ nên bắt đầu từ đâu?</strong>
                <span>Gửi nhu cầu, đội ngũ sẽ gợi ý cấu trúc website phù hợp.</span>
                <a href="<?= e(route('contact')) ?>">Nhận tư vấn</a>
              </aside>
            </div>
          </li>

          <li class="nav-item"><a class="nav-link <?= ($activeMenu ?? '') === 'themes' ? 'active' : '' ?>" href="<?= e(route('themes.index')) ?>">Kho giao diện</a></li>

          <li class="nav-item dropdown desktop-dropdown desktop-mega-dropdown">
            <a class="nav-link dropdown-toggle <?= ($activeMenu ?? '') === 'operations' ? 'active' : '' ?>" href="<?= e(route('operations.index')) ?>" aria-haspopup="true" aria-expanded="false">Dịch vụ vận hành</a>
            <div class="dropdown-menu mega-menu mega-menu--operations">
              <div class="mega-menu__intro">
                <span class="mega-menu__eyebrow">ĐỒNG HÀNH SAU BÀN GIAO</span>
                <h2>Website được chăm sóc và cải thiện liên tục</h2>
                <p>Từ hạ tầng, nội dung đến đo lường hiệu quả — chọn đúng phần doanh nghiệp cần.</p>
                <a class="mega-menu__overview" href="<?= e(route('operations.index')) ?>">Xem dịch vụ vận hành <i class="fa-solid fa-arrow-right"></i></a>
              </div>
              <div class="mega-menu__links" aria-label="Dịch vụ vận hành website">
                <?php foreach ($operationMenuItems as $service): ?>
                  <?php $operationSlug = pathinfo($service['route'], PATHINFO_FILENAME); ?>
                  <a class="mega-menu__link <?= ($activeSubmenu ?? '') === ($service['menu_key'] ?? '') ? 'active' : '' ?>" href="<?= e(route('operations.show', $operationSlug)) ?>">
                    <span class="mega-menu__icon"><i class="<?= e($service['icon']) ?>"></i></span>
                    <span><strong><?= e($service['eyebrow']) ?></strong><small><?= e($service['highlight']) ?></small></span>
                  </a>
                <?php endforeach; ?>
              </div>
              <aside class="mega-menu__aside">
                <i class="fa-solid fa-headset"></i>
                <strong>Cần một đầu mối kỹ thuật?</strong>
                <span>Chọn gói vận hành vừa đủ để website luôn ổn định và có người hỗ trợ.</span>
                <a href="<?= e(route('contact')) ?>">Trao đổi nhu cầu</a>
              </aside>
            </div>
          </li>

          <li class="nav-item"><a class="nav-link <?= ($activeMenu ?? '') === 'projects' ? 'active' : '' ?>" href="<?= e(route('projects.index')) ?>">Dự án</a></li>
          <li class="nav-item"><a class="nav-link <?= ($activeMenu ?? '') === 'pricing' ? 'active' : '' ?>" href="<?= e(route('pricing')) ?>">Bảng giá</a></li>
          <li class="nav-item"><a class="nav-link <?= ($activeMenu ?? '') === 'agency' ? 'active' : '' ?>" href="<?= e(route('agency')) ?>">Hợp tác Agency</a></li>
          <li class="nav-item"><a class="nav-link <?= ($activeMenu ?? '') === 'knowledge' ? 'active' : '' ?>" href="<?= e(route('articles.index')) ?>">Kiến thức</a></li>
        </ul>
        <a class="btn btn-primary btn-header ms-xl-3" href="<?= e($headerCta ?? route('contact')) ?>">Nhận tư vấn</a>
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
      <a class="mobile-nav__single" href="<?= e(route('home')) ?>">Trang chủ</a>

      <div class="mobile-nav__item">
        <div class="mobile-nav__row">
          <a href="<?= e(route('services.index')) ?>">Thiết kế website</a>
          <button class="mobile-nav__toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mobileWebsiteMenu" aria-expanded="<?= ($activeMenu ?? '') === 'website-service' ? 'true' : 'false' ?>" aria-controls="mobileWebsiteMenu" aria-label="Mở menu thiết kế website"><i class="fa-solid fa-angle-down"></i></button>
        </div>
        <div class="collapse <?= ($activeMenu ?? '') === 'website-service' ? 'show' : '' ?>" id="mobileWebsiteMenu" data-bs-parent="#mobileNavigation">
          <div class="mobile-nav__submenu">
            <?php foreach ($websiteMenuItems as $service): ?>
              <a href="<?= e(route('services.show', $service['slug'])) ?>" class="<?= ($activeSubmenu ?? '') === ($service['menu_key'] ?? '') ? 'active' : '' ?>"><?= e($service['eyebrow']) ?></a>
            <?php endforeach; ?>
            <a href="<?= e(route('services.index')) ?>#theo-nganh">Website theo ngành</a>
          </div>
        </div>
      </div>

      <a class="mobile-nav__single" href="<?= e(route('themes.index')) ?>">Kho giao diện</a>

      <div class="mobile-nav__item">
        <div class="mobile-nav__row">
          <a href="<?= e(route('operations.index')) ?>">Dịch vụ vận hành</a>
          <button class="mobile-nav__toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mobileOperationMenu" aria-expanded="<?= ($activeMenu ?? '') === 'operations' ? 'true' : 'false' ?>" aria-controls="mobileOperationMenu" aria-label="Mở menu dịch vụ vận hành"><i class="fa-solid fa-angle-down"></i></button>
        </div>
        <div class="collapse <?= ($activeMenu ?? '') === 'operations' ? 'show' : '' ?>" id="mobileOperationMenu" data-bs-parent="#mobileNavigation">
          <div class="mobile-nav__submenu">
            <?php foreach ($operationMenuItems as $service): ?>
              <?php $operationSlug = pathinfo($service['route'], PATHINFO_FILENAME); ?>
              <a href="<?= e(route('operations.show', $operationSlug)) ?>" class="<?= ($activeSubmenu ?? '') === ($service['menu_key'] ?? '') ? 'active' : '' ?>"><?= e($service['eyebrow']) ?></a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <a class="mobile-nav__single" href="<?= e(route('projects.index')) ?>">Dự án</a>
      <a class="mobile-nav__single" href="<?= e(route('pricing')) ?>">Bảng giá</a>
      <a class="mobile-nav__single" href="<?= e(route('agency')) ?>">Hợp tác Agency</a>
      <a class="mobile-nav__single" href="<?= e(route('articles.index')) ?>">Kiến thức</a>
      <a class="mobile-nav__single" href="<?= e(route('about')) ?>">Giới thiệu</a>
      <a class="mobile-nav__single" href="<?= e(route('contact')) ?>">Liên hệ</a>
    </nav>
    <div class="mobile-contact">
      <a class="btn btn-primary w-100" href="<?= e($headerCta ?? route('contact')) ?>">Yêu cầu tư vấn</a>
      <a class="btn btn-outline-primary w-100" href="tel:<?= e(site_config('phone_href')) ?>"><i class="fa-solid fa-phone"></i> <?= e(site_config('phone')) ?></a>
    </div>
  </div>
</div>
