<?php
$footerLogoUrl = site_asset_url(site_config('site_logo_white') ?: site_config('site_logo_wide'));
$secondaryPhone = trim((string) site_config('phone_secondary'));
$secondaryPhoneHref = trim((string) site_config('phone_secondary_href'));
$footerSocials = array_values(array_filter([
    ['label' => 'Facebook', 'url' => trim((string) site_config('facebook')), 'icon' => 'fa-brands fa-facebook-f'],
    ['label' => 'YouTube', 'url' => trim((string) site_config('youtube')), 'icon' => 'fa-brands fa-youtube'],
    ['label' => 'Zalo', 'url' => trim((string) site_config('zalo')), 'icon' => null],
], static fn (array $social): bool => $social['url'] !== ''));
?>
<footer class="site-footer">
  <div class="container">
    <div class="row gy-5">
      <div class="col-lg-4">
        <a class="brand brand--footer" href="<?= e(frontend_url('index.php')) ?>"><?php if ($footerLogoUrl !== ''): ?><img class="brand__image brand__image--footer" src="<?= e($footerLogoUrl) ?>" alt="<?= e(site_config('name')) ?>"><?php else: ?><span class="brand__mark"><i class="fa-solid fa-code"></i></span><span class="brand__text"><strong>WEBAPP</strong><small>BẮC NINH</small></span><?php endif; ?></a>
        <p class="footer-about">Thiết kế website và đồng hành vận hành nội dung cho doanh nghiệp. Tập trung vào giải pháp vừa đủ, dễ dùng và có khả năng phát triển lâu dài.</p>
        <?php if ($footerSocials !== []): ?>
        <div class="footer-socials">
          <?php foreach ($footerSocials as $social): ?>
          <a href="<?= e($social['url']) ?>" aria-label="<?= e($social['label']) ?>">
            <?php if ($social['icon'] !== null): ?><i class="<?= e($social['icon']) ?>"></i><?php else: ?><img class="zalo-icon" src="<?= e(frontend_asset('assets/images/zalo.svg')) ?>" alt="" aria-hidden="true" width="22" height="22"><?php endif; ?>
          </a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="col-6 col-lg-2"><h3>Thiết kế website</h3><ul><li><a href="<?= e(frontend_url('website-doanh-nghiep.php')) ?>">Website doanh nghiệp</a></li><li><a href="<?= e(frontend_url('website-ban-hang.php')) ?>">Website bán hàng</a></li><li><a href="<?= e(frontend_url('landing-page.php')) ?>">Landing page</a></li><li><a href="<?= e(frontend_url('thiet-ke-lai-website.php')) ?>">Thiết kế lại website</a></li><li><a href="<?= e(frontend_url('kho-giao-dien.php')) ?>">Kho giao diện</a></li></ul></div>
      <div class="col-6 col-lg-2"><h3>Dịch vụ duy trì</h3><ul><li><a href="<?= e(frontend_url('hosting-bao-tri-website.php')) ?>">Hosting và bảo trì</a></li><li><a href="<?= e(frontend_url('quan-tri-dang-bai-website.php')) ?>">Quản trị website</a></li><li><a href="<?= e(frontend_url('seo-website.php')) ?>">SEO website</a></li><li><a href="<?= e(frontend_url('noi-dung-facebook.php')) ?>">Nội dung Facebook</a></li><li><a href="<?= e(frontend_url('nang-cap-tich-hop-website.php')) ?>">Nâng cấp và tích hợp</a></li><li><a href="<?= e(frontend_url('do-luong-bao-cao-website.php')) ?>">Đo lường và báo cáo</a></li></ul></div>
      <div class="col-6 col-lg-2"><h3>Thông tin</h3><ul><li><a href="<?= e(frontend_url('gioi-thieu.php')) ?>">Giới thiệu</a></li><li><a href="<?= e(frontend_url('du-an.php')) ?>">Dự án</a></li><li><a href="<?= e(frontend_url('bang-gia.php')) ?>">Bảng giá</a></li><li><a href="<?= e(frontend_url('hop-tac-agency.php')) ?>">Hợp tác Agency</a></li><li><a href="<?= e(frontend_url('kien-thuc.php')) ?>">Kiến thức</a></li></ul></div>
      <div class="col-6 col-lg-2">
        <h3>Liên hệ</h3>
        <ul class="footer-contact">
          <li><i class="fa-solid fa-phone"></i><a href="tel:<?= e(site_config('phone_href')) ?>"><?= e(site_config('phone')) ?></a></li>
          <?php if ($secondaryPhone !== '' && $secondaryPhoneHref !== ''): ?>
          <li><i class="fa-solid fa-phone"></i><a href="tel:<?= e($secondaryPhoneHref) ?>"><?= e($secondaryPhone) ?></a></li>
          <?php endif; ?>
          <li><i class="fa-regular fa-envelope"></i><a href="mailto:<?= e(site_config('email')) ?>"><?= e(site_config('email')) ?></a></li>
          <li><i class="fa-solid fa-location-dot"></i><span><?= e(site_config('address')) ?></span></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom"><span>© <span id="currentYear"></span> WebApp Bắc Ninh. All rights reserved.</span><div><a href="<?= e(frontend_url('chinh-sach-bao-mat.php')) ?>">Chính sách bảo mật</a><a href="<?= e(frontend_url('dieu-khoan-su-dung.php')) ?>">Điều khoản sử dụng</a><a href="<?= e(frontend_url('chinh-sach-bao-hanh.php')) ?>">Bảo hành</a><a href="<?= e(frontend_url('quy-trinh-thanh-toan.php')) ?>">Thanh toán</a></div></div>
  </div>
</footer>

<div class="floating-actions">
  <a class="floating-actions__zalo" href="<?= e($floatingCta ?? frontend_url('lien-he.php')) ?>" aria-label="Liên hệ Zalo"><img class="zalo-icon" src="<?= e(frontend_asset('assets/images/zalo.svg')) ?>" alt="" aria-hidden="true" width="28" height="28"></a>
  <a class="floating-actions__phone" href="tel:<?= e(site_config('phone_href')) ?>" aria-label="Gọi điện"><i class="fa-solid fa-phone"></i></a>
  <button class="floating-actions__top" id="backToTop" aria-label="Lên đầu trang"><i class="fa-solid fa-arrow-up"></i></button>
</div>


