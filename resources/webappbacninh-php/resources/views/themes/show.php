<main>
  <section class="theme-detail-hero">
    <div class="container">
      <nav aria-label="breadcrumb" class="theme-breadcrumb" data-aos="fade-up">
        <ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="<?= e(url('index.php')) ?>">Trang chủ</a></li><li class="breadcrumb-item"><a href="<?= e(url('kho-giao-dien.php')) ?>">Kho giao diện</a></li><li class="breadcrumb-item active" aria-current="page"><?= e($theme['code']) ?></li></ol>
      </nav>
      <div class="row gy-5 align-items-start">
        <div class="col-lg-8">
          <div class="theme-detail__badges" data-aos="fade-up">
            <?php if ($theme['badge'] !== ''): ?><span class="theme-detail__badge theme-detail__badge--hot"><?= e($theme['badge']) ?></span><?php endif; ?>
            <span class="theme-detail__badge"><?= e($theme['typeLabel']) ?></span>
            <span class="theme-detail__badge"><?= e($theme['industryLabel']) ?></span>
          </div>
          <h1 data-aos="fade-up" data-aos-delay="60"><?= e($theme['name']) ?></h1>
          <p class="theme-detail__lead" data-aos="fade-up" data-aos-delay="120"><?= e($theme['description']) ?></p>

          <div class="theme-gallery" data-aos="fade-up" data-aos-delay="170">
            <div class="theme-gallery__main"><img id="themeMainImage" src="<?= e(asset('assets/images/' . $theme['gallery'][0])) ?>" alt="<?= e($theme['name']) ?>"></div>
            <div class="theme-gallery__thumbs">
              <?php foreach ($theme['gallery'] as $index => $image): ?>
              <button type="button" class="theme-gallery__thumb <?= $index === 0 ? 'is-active' : '' ?>" data-gallery-image="<?= e(asset('assets/images/' . $image)) ?>"><img src="<?= e(asset('assets/images/' . $image)) ?>" alt="Ảnh <?= e($index + 1) ?> của <?= e($theme['name']) ?>"></button>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <aside class="theme-order-card" data-aos="fade-left">
            <div class="theme-order-card__code"><?= e($theme['code']) ?></div>
            <span class="theme-order-card__label">Chi phí triển khai tham khảo từ</span>
            <strong class="theme-order-card__price"><?= e(money($theme['price'])) ?></strong>
            <p>Giá chính thức phụ thuộc nội dung, chức năng và mức độ tùy chỉnh.</p>
            <div class="theme-order-card__specs">
              <div><i class="fa-regular fa-clock"></i><span><small>Thời gian dự kiến</small><strong><?= e($theme['duration']) ?></strong></span></div>
              <div><i class="fa-solid fa-layer-group"></i><span><small>Loại website</small><strong><?= e($theme['typeLabel']) ?></strong></span></div>
              <div><i class="fa-solid fa-mobile-screen"></i><span><small>Hiển thị</small><strong>Responsive đa thiết bị</strong></span></div>
              <div><i class="fa-solid fa-pen-ruler"></i><span><small>Tùy chỉnh</small><strong>Màu, nội dung, bố cục</strong></span></div>
            </div>
            <div class="d-grid gap-2">
              <a class="btn btn-primary btn-lg" href="#themeConsult" data-select-theme="<?= e($theme['code']) ?>">Chọn mẫu này</a>
              <a class="btn btn-outline-primary" href="tel:<?= e(config('phone_href')) ?>"><i class="fa-solid fa-phone"></i> Gọi <?= e(config('phone')) ?></a>
            </div>
            <div class="theme-order-card__note"><i class="fa-solid fa-circle-check"></i> Không bắt buộc giữ nguyên mẫu. Có thể kết hợp bố cục từ nhiều giao diện.</div>
          </aside>
        </div>
      </div>
    </div>
  </section>

  <section class="detail-section">
    <div class="container">
      <div class="row gy-5">
        <div class="col-lg-5" data-aos="fade-right">
          <span class="section-kicker">MẪU NÀY PHÙ HỢP VỚI AI?</span>
          <h2 class="section-title">Một nền tảng website rõ ràng để tiếp tục bán hàng và phát triển nội dung.</h2>
          <p class="section-lead">Mẫu được sử dụng như điểm xuất phát. Khi triển khai, WebApp Bắc Ninh sẽ thay nhận diện, nội dung và điều chỉnh cấu trúc theo doanh nghiệp.</p>
        </div>
        <div class="col-lg-7">
          <div class="detail-check-grid" data-aos="fade-left">
            <?php foreach ($theme['audiences'] as $item): ?><div><i class="fa-solid fa-check"></i><span><?= e($item) ?></span></div><?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="detail-section detail-section--light">
    <div class="container">
      <div class="section-heading text-center" data-aos="fade-up"><span class="section-kicker">PHẠM VI BÀN GIAO</span><h2>Các trang và chức năng cơ bản đã có trong mẫu</h2><p>Phạm vi có thể điều chỉnh sau khi chốt cấu trúc và dữ liệu thực tế.</p></div>
      <div class="row g-4">
        <div class="col-lg-5" data-aos="fade-up">
          <div class="detail-panel"><h3><i class="fa-regular fa-file-lines"></i> Các trang dự kiến</h3><ul class="detail-list"><?php foreach ($theme['pages'] as $page): ?><li><?= e($page) ?></li><?php endforeach; ?></ul></div>
        </div>
        <div class="col-lg-7" data-aos="fade-up" data-aos-delay="80">
          <div class="detail-panel"><h3><i class="fa-solid fa-gears"></i> Chức năng và tiêu chuẩn bàn giao</h3><ul class="detail-list detail-list--columns"><?php foreach ($theme['includedFeatures'] as $feature): ?><li><?= e($feature) ?></li><?php endforeach; ?></ul></div>
        </div>
      </div>
    </div>
  </section>

  <section class="detail-section">
    <div class="container">
      <div class="row align-items-center gy-5">
        <div class="col-lg-6" data-aos="fade-right"><img class="detail-showcase-image" src="<?= e(asset('assets/images/' . $theme['gallery'][1])) ?>" alt="Tùy chỉnh <?= e($theme['name']) ?>"></div>
        <div class="col-lg-6" data-aos="fade-left">
          <span class="section-kicker">TÙY CHỈNH THEO DOANH NGHIỆP</span>
          <h2 class="section-title">Không phải mua một mẫu đóng khung.</h2>
          <p class="section-lead">Màu sắc, hình ảnh, nội dung và chức năng sẽ được thay đổi để website phù hợp với thương hiệu và cách doanh nghiệp đang vận hành.</p>
          <ul class="detail-icon-list"><?php foreach ($theme['customizations'] as $item): ?><li><i class="fa-solid fa-pen-ruler"></i><span><?= e($item) ?></span></li><?php endforeach; ?></ul>
        </div>
      </div>
    </div>
  </section>

  <section class="detail-section detail-section--navy">
    <div class="container">
      <div class="section-heading text-center" data-aos="fade-up"><span class="section-kicker section-kicker--gold">SAU KHI BÀN GIAO</span><h2 class="text-white">Có thể tiếp tục duy trì và phát triển website theo tháng</h2><p>Không bắt buộc mua cùng lúc. Doanh nghiệp chọn đúng phần đang thiếu.</p></div>
      <div class="addon-grid">
        <article data-aos="fade-up"><i class="fa-solid fa-server"></i><h3>Hosting & bảo trì</h3><p>SSL, backup, cập nhật, theo dõi hoạt động và xử lý lỗi kỹ thuật.</p></article>
        <article data-aos="fade-up" data-aos-delay="70"><i class="fa-solid fa-file-pen"></i><h3>Đăng bài & sản phẩm</h3><p>Cập nhật nội dung, banner, dịch vụ, sản phẩm và bài viết mới.</p></article>
        <article data-aos="fade-up" data-aos-delay="140"><i class="fa-solid fa-magnifying-glass-chart"></i><h3>SEO định kỳ</h3><p>Từ khóa, nội dung, technical SEO, internal link và báo cáo Search Console.</p></article>
        <article data-aos="fade-up" data-aos-delay="210"><i class="fa-brands fa-facebook-f"></i><h3>Nội dung Facebook</h3><p>Đồng bộ chủ đề giữa website và fanpage để duy trì hiện diện đều đặn.</p></article>
      </div>
    </div>
  </section>

  <section class="detail-section detail-section--light">
    <div class="container">
      <div class="row align-items-end gy-3 mb-5"><div class="col-lg-8"><span class="section-kicker">GIAO DIỆN LIÊN QUAN</span><h2 class="section-title mb-0">Tham khảo thêm các mẫu gần nhu cầu này</h2></div><div class="col-lg-4 text-lg-end"><a class="text-link" href="<?= e(url('kho-giao-dien.php')) ?>">Xem toàn bộ kho giao diện <i class="fa-solid fa-arrow-right"></i></a></div></div>
      <div class="theme-related-grid"><?php foreach ($relatedThemes as $related): ?><?php view('components.theme-card', ['theme' => $related, 'showQuick' => false]); ?><?php endforeach; ?></div>
    </div>
  </section>

  <section class="theme-detail-contact" id="themeConsult">
    <div class="container">
      <div class="theme-detail-contact__shell">
        <div><span class="section-kicker section-kicker--gold">CHỌN MẪU <?= e($theme['code']) ?></span><h2>Gửi thông tin để nhận cấu trúc và báo giá phù hợp.</h2><p>WebApp Bắc Ninh sẽ trao đổi nhu cầu, xác định phần cần giữ, phần cần chỉnh và phạm vi chức năng trước khi báo giá.</p><div class="theme-contact__points"><span><i class="fa-solid fa-check"></i> Tư vấn theo ngành nghề</span><span><i class="fa-solid fa-check"></i> Chốt phạm vi trước khi triển khai</span><span><i class="fa-solid fa-check"></i> Có gói hỗ trợ sau bàn giao</span></div></div>
        <form class="theme-contact__form needs-validation" id="themeDetailForm" novalidate>
          <input type="hidden" name="theme_code" id="selectedThemeCode" value="<?= e($theme['code']) ?>">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label" for="detailName">Họ và tên *</label><input class="form-control" id="detailName" name="name" required></div>
            <div class="col-md-6"><label class="form-label" for="detailPhone">Số điện thoại *</label><input class="form-control" id="detailPhone" name="phone" type="tel" required></div>
            <div class="col-md-6"><label class="form-label" for="detailBusiness">Doanh nghiệp / lĩnh vực</label><input class="form-control" id="detailBusiness" name="business"></div>
            <div class="col-md-6"><label class="form-label">Mã giao diện</label><input class="form-control" value="<?= e($theme['code']) ?>" readonly></div>
            <div class="col-12"><label class="form-label" for="detailMessage">Nhu cầu bổ sung</label><textarea class="form-control" id="detailMessage" name="message" rows="4" placeholder="Ví dụ: cần thêm tiếng Anh, danh mục sản phẩm, form báo giá..."></textarea></div>
            <div class="col-12"><button class="btn btn-light btn-lg" type="submit">Gửi yêu cầu tư vấn</button></div>
          </div>
          <div class="alert alert-success mt-3 d-none" id="themeDetailSuccess">Dữ liệu mẫu đã hợp lệ. Sau này thay phần demo bằng route xử lý form trong Laravel.</div>
        </form>
      </div>
    </div>
  </section>
</main>
