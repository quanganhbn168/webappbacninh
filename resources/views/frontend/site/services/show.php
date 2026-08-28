<main>
  <section class="service-detail-hero">
    <div class="service-detail-hero__shape"></div>
    <div class="container position-relative">
      <nav class="service-breadcrumb" data-aos="fade-up">
        <a href="<?= e(frontend_url('index.php')) ?>">Trang chủ</a>
        <i class="fa-solid fa-angle-right"></i>
        <a href="<?= e(frontend_url('thiet-ke-website.php')) ?>">Thiết kế website</a>
        <i class="fa-solid fa-angle-right"></i>
        <span><?= e($service['eyebrow']) ?></span>
      </nav>
      <div class="row align-items-center gy-5">
        <div class="col-lg-6">
          <span class="section-kicker" data-aos="fade-up"><?= e($service['eyebrow']) ?></span>
          <h1 data-aos="fade-up" data-aos-delay="70"><?= e($service['title']) ?></h1>
          <p class="service-detail-hero__highlight" data-aos="fade-up" data-aos-delay="120"><?= e($service['highlight']) ?></p>
          <p class="service-detail-hero__description" data-aos="fade-up" data-aos-delay="160"><?= e($service['description']) ?></p>
          <div class="service-detail-hero__actions" data-aos="fade-up" data-aos-delay="210">
            <a class="btn btn-primary btn-lg" href="#serviceContact"><?= e($service['cta']) ?></a>
            <a class="btn btn-outline-dark btn-lg" href="<?= e(frontend_url('kho-giao-dien.php')) ?>">Xem kho giao diện</a>
          </div>
          <div class="service-detail-hero__facts" data-aos="fade-up" data-aos-delay="250">
            <div><small>Chi phí tham khảo</small><strong><?= e($service['price_from']) ?></strong></div>
            <div><small>Thời gian dự kiến</small><strong><?= e($service['timeline']) ?></strong></div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="service-detail-hero__visual" data-aos="fade-left">
            <img src="<?= e($service['image_url']) ?>" alt="<?= e($service['title']) ?>" width="1200" height="750">
            <div class="service-detail-hero__badge"><i class="<?= e($service['icon']) ?>"></i><span><small>Giải pháp</small><strong><?= e($service['eyebrow']) ?></strong></span></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="service-audience">
    <div class="container">
      <div class="service-audience__grid">
        <?php foreach ($service['audiences'] as $index => $audience): ?>
          <div data-aos="fade-up" data-aos-delay="<?= (int) $index * 45 ?>"><i class="fa-solid fa-circle-check"></i><span><?= e($audience) ?></span></div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="row align-items-center gy-5">
        <div class="col-lg-5" data-aos="fade-right">
          <div class="service-intro-media">
            <img src="<?= e($service['secondary_image_url']) ?>" alt="Minh họa <?= e($service['eyebrow']) ?>" width="1200" height="800">
            <div><i class="fa-solid fa-lightbulb"></i><span><strong>Không làm theo cảm tính</strong><small>Cấu trúc được xác định từ mục tiêu, dữ liệu và hành vi khách hàng.</small></span></div>
          </div>
        </div>
        <div class="col-lg-7" data-aos="fade-left">
          <span class="section-kicker">BÀI TOÁN THƯỜNG GẶP</span>
          <h2 class="section-title">Website cần xử lý đúng vấn đề trước khi thêm hiệu ứng.</h2>
          <div class="service-problem-list">
            <?php foreach ($service['problems'] as $problem): ?>
              <article><i class="<?= e($problem['icon']) ?>"></i><div><h3><?= e($problem['title']) ?></h3><p><?= e($problem['text']) ?></p></div></article>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--light">
    <div class="container">
      <div class="row align-items-end gy-3 mb-5">
        <div class="col-lg-7" data-aos="fade-up"><span class="section-kicker">PHẠM VI TRIỂN KHAI</span><h2 class="section-title mb-0">Các trang và nội dung cốt lõi</h2></div>
        <div class="col-lg-5" data-aos="fade-up" data-aos-delay="80"><p class="section-lead mb-0">Danh sách được điều chỉnh theo dữ liệu thực tế. Không bắt doanh nghiệp mua những trang không dùng đến.</p></div>
      </div>
      <div class="service-page-map">
        <?php foreach ($service['pages'] as $index => $page): ?>
          <div data-aos="fade-up" data-aos-delay="<?= (int) ($index % 4) * 45 ?>"><span><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span><strong><?= e($page) ?></strong></div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section section--navy">
    <div class="container">
      <div class="section-heading text-center" data-aos="fade-up"><span class="section-kicker section-kicker--light">GIÁ TRỊ BÀN GIAO</span><h2 class="section-title section-title--light">Không chỉ đẹp khi xem bản demo</h2><p>Website phải dễ sử dụng, dễ cập nhật và có nền tảng để doanh nghiệp tiếp tục phát triển.</p></div>
      <div class="service-feature-grid">
        <?php foreach ($service['features'] as $index => $feature): ?>
          <article data-aos="fade-up" data-aos-delay="<?= (int) ($index % 3) * 55 ?>"><i class="<?= e($feature['icon']) ?>"></i><h3><?= e($feature['title']) ?></h3><p><?= e($feature['text']) ?></p></article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-heading text-center" data-aos="fade-up"><span class="section-kicker">QUY TRÌNH</span><h2>Từng bước rõ ràng trước khi bàn giao</h2></div>
      <div class="service-process">
        <article data-aos="fade-up"><span>01</span><i class="fa-regular fa-comments"></i><h3>Khảo sát nhu cầu</h3><p>Mục tiêu, khách hàng, dữ liệu hiện có và vấn đề cần xử lý.</p></article>
        <article data-aos="fade-up" data-aos-delay="50"><span>02</span><i class="fa-solid fa-sitemap"></i><h3>Chốt cấu trúc</h3><p>Sơ đồ trang, chức năng, nội dung và hướng thiết kế.</p></article>
        <article data-aos="fade-up" data-aos-delay="100"><span>03</span><i class="fa-solid fa-pen-ruler"></i><h3>Thiết kế & phát triển</h3><p>Triển khai giao diện, chức năng và dữ liệu mẫu.</p></article>
        <article data-aos="fade-up" data-aos-delay="150"><span>04</span><i class="fa-solid fa-vial-circle-check"></i><h3>Kiểm thử</h3><p>Responsive, form, tốc độ, SEO và các tình huống sử dụng.</p></article>
        <article data-aos="fade-up" data-aos-delay="200"><span>05</span><i class="fa-solid fa-graduation-cap"></i><h3>Bàn giao</h3><p>Đưa lên hosting, hướng dẫn quản trị và thống nhất bảo hành.</p></article>
      </div>
    </div>
  </section>

  <section class="section section--cream">
    <div class="container">
      <div class="section-heading text-center" data-aos="fade-up"><span class="section-kicker">MỨC ĐẦU TƯ THAM KHẢO</span><h2>Chọn theo phạm vi thực tế</h2><p>Chi phí cuối cùng được xác định sau khi chốt dữ liệu, chức năng và tiến độ.</p></div>
      <div class="service-package-grid">
        <?php foreach ($service['packages'] as $index => $package): ?>
          <article class="<?= !empty($package['featured']) ? 'is-featured' : '' ?>" data-aos="fade-up" data-aos-delay="<?= (int) $index * 65 ?>">
            <?php if (!empty($package['featured'])): ?><span class="service-package__badge">ĐƯỢC QUAN TÂM</span><?php endif; ?>
            <small>GÓI <?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></small>
            <h3><?= e($package['name']) ?></h3>
            <strong class="service-package__price"><?= e($package['price']) ?></strong>
            <p><?= e($package['desc']) ?></p>
            <ul><?php foreach ($package['items'] as $item): ?><li><?= e($item) ?></li><?php endforeach; ?></ul>
            <a class="btn <?= !empty($package['featured']) ? 'btn-primary' : 'btn-outline-primary' ?> w-100" href="#serviceContact">Nhận báo giá</a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="row gy-5 align-items-start">
        <div class="col-lg-5" data-aos="fade-right"><span class="section-kicker">CÂU HỎI THƯỜNG GẶP</span><h2 class="section-title">Làm rõ trước khi bắt đầu</h2><p class="section-lead">Phần còn phụ thuộc dữ liệu hoặc hệ thống hiện tại sẽ được khảo sát trước khi báo giá chính thức.</p><a class="btn btn-outline-primary" href="<?= e(frontend_url('lien-he.php')) ?>">Gửi câu hỏi khác</a></div>
        <div class="col-lg-7" data-aos="fade-left">
          <div class="accordion service-faq" id="serviceFaq">
            <?php foreach ($service['faqs'] as $index => $faq): $faqId = 'serviceFaq' . $index; ?>
              <div class="accordion-item"><h3 class="accordion-header"><button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" data-ui-toggle="collapse" data-ui-target="#<?= e($faqId) ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>"><?= e($faq['q']) ?></button></h3><div id="<?= e($faqId) ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" data-ui-parent="#serviceFaq"><div class="accordion-body"><?= e($faq['a']) ?></div></div></div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php echo view('frontend.site.components.lead-form', [
      'sectionId' => 'serviceContact',
      'title' => $service['cta'],
      'description' => 'Gửi ngành nghề, mục tiêu và dữ liệu đang có. WebApp Bắc Ninh sẽ đề xuất cấu trúc, tiến độ và mức đầu tư phù hợp.',
      'needValue' => $service['need_value'],
  ]); ?>
</main>


