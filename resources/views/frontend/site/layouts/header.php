<?php $topbarAbout = $headerNavigation['topbar'] ?? ['title' => 'Giới thiệu', 'url' => route('about'), 'target' => '_self']; ?>

<div class="topbar d-none d-lg-block">
  <div class="container d-flex justify-content-between align-items-center">
    <div class="topbar__left">
      <span><i class="fa-solid fa-location-dot"></i> Phục vụ doanh nghiệp tại Bắc Ninh và khu vực lân cận</span>
      <span><i class="fa-regular fa-clock"></i> <?= e(site_config('working_time')) ?></span>
    </div>
    <div class="topbar__right">
      <a href="<?= e($topbarAbout['url']) ?>" target="<?= e($topbarAbout['target'] ?? '_self') ?>"><i class="fa-regular fa-building"></i> <?= e($topbarAbout['title']) ?></a>
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
          <?php foreach (($headerNavigation['top'] ?? []) as $item): ?>
          <?php if (($item['mega'] ?? null) === 'website'): ?>
          <li class="nav-item dropdown desktop-dropdown desktop-mega-dropdown">
            <a class="nav-link dropdown-toggle <?= ($activeMenu ?? '') === ($item['active_key'] ?? '') ? 'active' : '' ?>" href="<?= e($item['url']) ?>" target="<?= e($item['target']) ?>" aria-haspopup="true" aria-expanded="false"><?= e($item['title']) ?></a>
            <div class="dropdown-menu mega-menu mega-menu--services">
              <div class="mega-menu__intro">
                <span class="mega-menu__eyebrow">GIẢI PHÁP WEBSITE</span>
                <h2>Website đúng nhu cầu, dễ phát triển</h2>
                <p>Chọn phạm vi phù hợp với mục tiêu kinh doanh hiện tại của doanh nghiệp.</p>
                <a class="mega-menu__overview" href="<?= e(route('services.index')) ?>">Xem toàn bộ dịch vụ <i class="fa-solid fa-arrow-right"></i></a>
              </div>
              <div class="mega-menu__links" aria-label="Dịch vụ thiết kế website">
                <?php foreach (($headerNavigation['website'] ?? []) as $service): ?>
                  <a class="mega-menu__link <?= ($activeSubmenu ?? '') === ($service['submenu_key'] ?? '') ? 'active' : '' ?>" href="<?= e($service['url']) ?>" target="<?= e($service['target']) ?>">
                    <span class="mega-menu__icon"><i class="<?= e($service['icon']) ?>"></i></span>
                    <span><strong><?= e($service['label']) ?></strong><small><?= e($service['description']) ?></small></span>
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
          <?php elseif (($item['mega'] ?? null) === 'operations'): ?>
          <li class="nav-item dropdown desktop-dropdown desktop-mega-dropdown">
            <a class="nav-link dropdown-toggle <?= ($activeMenu ?? '') === ($item['active_key'] ?? '') ? 'active' : '' ?>" href="<?= e($item['url']) ?>" target="<?= e($item['target']) ?>" aria-haspopup="true" aria-expanded="false"><?= e($item['title']) ?></a>
            <div class="dropdown-menu mega-menu mega-menu--operations">
              <div class="mega-menu__intro">
                <span class="mega-menu__eyebrow">ĐỒNG HÀNH SAU BÀN GIAO</span>
                <h2>Website được chăm sóc và cải thiện liên tục</h2>
                <p>Từ hạ tầng, nội dung đến đo lường hiệu quả — chọn đúng phần doanh nghiệp cần.</p>
                <a class="mega-menu__overview" href="<?= e(route('operations.index')) ?>">Xem dịch vụ vận hành <i class="fa-solid fa-arrow-right"></i></a>
              </div>
              <div class="mega-menu__links" aria-label="Dịch vụ vận hành website">
                <?php foreach (($headerNavigation['operations'] ?? []) as $service): ?>
                  <a class="mega-menu__link <?= ($activeSubmenu ?? '') === ($service['submenu_key'] ?? '') ? 'active' : '' ?>" href="<?= e($service['url']) ?>" target="<?= e($service['target']) ?>">
                    <span class="mega-menu__icon"><i class="<?= e($service['icon']) ?>"></i></span>
                    <span><strong><?= e($service['label']) ?></strong><small><?= e($service['description']) ?></small></span>
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
          <?php else: ?>
          <li class="nav-item"><a class="nav-link <?= ($activeMenu ?? '') === ($item['active_key'] ?? '') ? 'active' : '' ?>" href="<?= e($item['url']) ?>" target="<?= e($item['target']) ?>"><?= e($item['title']) ?></a></li>
          <?php endif; ?>
          <?php endforeach; ?>
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
      <a class="mobile-nav__single" href="<?= e($topbarAbout['url']) ?>" target="<?= e($topbarAbout['target'] ?? '_self') ?>"><?= e($topbarAbout['title']) ?></a>
      <?php foreach (($headerNavigation['top'] ?? []) as $index => $item): ?>
        <?php if (($item['mega'] ?? null) === 'website' || ($item['mega'] ?? null) === 'operations'): ?>
          <?php $mobileMenuId = ($item['mega'] ?? '') === 'website' ? 'mobileWebsiteMenu' : 'mobileOperationMenu'; ?>
          <?php $mobileChildren = ($item['mega'] ?? '') === 'website' ? ($headerNavigation['website'] ?? []) : ($headerNavigation['operations'] ?? []); ?>
          <div class="mobile-nav__item">
            <div class="mobile-nav__row">
              <a href="<?= e($item['url']) ?>" target="<?= e($item['target']) ?>"><?= e($item['title']) ?></a>
              <button class="mobile-nav__toggle" type="button" data-bs-toggle="collapse" data-bs-target="#<?= e($mobileMenuId) ?>" aria-expanded="<?= ($activeMenu ?? '') === ($item['active_key'] ?? '') ? 'true' : 'false' ?>" aria-controls="<?= e($mobileMenuId) ?>" aria-label="Mở <?= e($item['title']) ?>"><i class="fa-solid fa-angle-down"></i></button>
            </div>
            <div class="collapse <?= ($activeMenu ?? '') === ($item['active_key'] ?? '') ? 'show' : '' ?>" id="<?= e($mobileMenuId) ?>" data-bs-parent="#mobileNavigation">
              <div class="mobile-nav__submenu">
                <?php foreach ($mobileChildren as $child): ?>
                  <a href="<?= e($child['url']) ?>" target="<?= e($child['target']) ?>" class="<?= ($activeSubmenu ?? '') === ($child['submenu_key'] ?? '') ? 'active' : '' ?>"><?= e($child['label']) ?></a>
                <?php endforeach; ?>
                <?php if (($item['mega'] ?? null) === 'website'): ?>
                  <a href="<?= e($item['url']) ?>#theo-nganh">Website theo ngành</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php else: ?>
          <a class="mobile-nav__single" href="<?= e($item['url']) ?>" target="<?= e($item['target']) ?>"><?= e($item['title']) ?></a>
        <?php endif; ?>
      <?php endforeach; ?>
    </nav>
    <div class="mobile-contact">
      <a class="btn btn-primary w-100" href="<?= e($headerCta ?? route('contact')) ?>">Yêu cầu tư vấn</a>
      <a class="btn btn-outline-primary w-100" href="tel:<?= e(site_config('phone_href')) ?>"><i class="fa-solid fa-phone"></i> <?= e(site_config('phone')) ?></a>
    </div>
  </div>
</div>
