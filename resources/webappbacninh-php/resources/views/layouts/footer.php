<footer class="site-footer">
  <div class="container">
    <div class="row gy-5">
      <div class="col-lg-4">
        <a class="brand brand--footer" href="<?= e(url('index.php')) ?>"><span class="brand__mark"><i class="fa-solid fa-code"></i></span><span class="brand__text"><strong>WEBAPP</strong><small>BẮC NINH</small></span></a>
        <p class="footer-about">Thiết kế website và đồng hành vận hành nội dung cho doanh nghiệp. Tập trung vào giải pháp vừa đủ, dễ dùng và có khả năng phát triển lâu dài.</p>
        <div class="footer-socials"><a href="<?= e(config('facebook')) ?>" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a><a href="<?= e(config('youtube')) ?>" aria-label="Youtube"><i class="fa-brands fa-youtube"></i></a><a href="<?= e(config('zalo')) ?>" aria-label="Zalo"><i class="fa-solid fa-comment-dots"></i></a></div>
      </div>
      <div class="col-6 col-lg-2"><h3>Thiết kế website</h3><ul><li><a href="<?= e(url('website-doanh-nghiep.php')) ?>">Website doanh nghiệp</a></li><li><a href="<?= e(url('website-ban-hang.php')) ?>">Website bán hàng</a></li><li><a href="<?= e(url('landing-page.php')) ?>">Landing page</a></li><li><a href="<?= e(url('thiet-ke-lai-website.php')) ?>">Thiết kế lại website</a></li><li><a href="<?= e(url('kho-giao-dien.php')) ?>">Kho giao diện</a></li></ul></div>
      <div class="col-6 col-lg-2"><h3>Dịch vụ duy trì</h3><ul><li><a href="<?= e(url('hosting-bao-tri-website.php')) ?>">Hosting và bảo trì</a></li><li><a href="<?= e(url('quan-tri-dang-bai-website.php')) ?>">Quản trị website</a></li><li><a href="<?= e(url('seo-website.php')) ?>">SEO website</a></li><li><a href="<?= e(url('noi-dung-facebook.php')) ?>">Nội dung Facebook</a></li><li><a href="<?= e(url('nang-cap-tich-hop-website.php')) ?>">Nâng cấp và tích hợp</a></li><li><a href="<?= e(url('do-luong-bao-cao-website.php')) ?>">Đo lường và báo cáo</a></li></ul></div>
      <div class="col-6 col-lg-2"><h3>Thông tin</h3><ul><li><a href="<?= e(url('gioi-thieu.php')) ?>">Giới thiệu</a></li><li><a href="<?= e(url('du-an.php')) ?>">Dự án</a></li><li><a href="<?= e(url('bang-gia.php')) ?>">Bảng giá</a></li><li><a href="<?= e(url('hop-tac-agency.php')) ?>">Hợp tác Agency</a></li><li><a href="<?= e(url('kien-thuc.php')) ?>">Kiến thức</a></li></ul></div>
      <div class="col-6 col-lg-2"><h3>Liên hệ</h3><ul class="footer-contact"><li><i class="fa-solid fa-phone"></i><a href="tel:<?= e(config('phone_href')) ?>"><?= e(config('phone')) ?></a></li><li><i class="fa-regular fa-envelope"></i><a href="mailto:<?= e(config('email')) ?>"><?= e(config('email')) ?></a></li><li><i class="fa-solid fa-location-dot"></i><span><?= e(config('address')) ?></span></li></ul></div>
    </div>
    <div class="footer-bottom"><span>© <span id="currentYear"></span> WebApp Bắc Ninh. All rights reserved.</span><div><a href="<?= e(url('chinh-sach-bao-mat.php')) ?>">Chính sách bảo mật</a><a href="<?= e(url('dieu-khoan-su-dung.php')) ?>">Điều khoản sử dụng</a><a href="<?= e(url('chinh-sach-bao-hanh.php')) ?>">Bảo hành</a><a href="<?= e(url('quy-trinh-thanh-toan.php')) ?>">Thanh toán</a></div></div>
  </div>
</footer>

<div class="floating-actions">
  <a class="floating-actions__zalo" href="<?= e($floatingCta ?? url('lien-he.php')) ?>" aria-label="Liên hệ Zalo"><i class="fa-solid fa-comment-dots"></i></a>
  <a class="floating-actions__phone" href="tel:<?= e(config('phone_href')) ?>" aria-label="Gọi điện"><i class="fa-solid fa-phone"></i></a>
  <button class="floating-actions__top" id="backToTop" aria-label="Lên đầu trang"><i class="fa-solid fa-arrow-up"></i></button>
</div>
