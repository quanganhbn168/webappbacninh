<main>
  <section class="operation-detail-hero">
    <div class="container">
      <nav class="inner-breadcrumb" data-aos="fade-up">
        <a href="<?= e(frontend_url('index.php')) ?>">Trang chủ</a>
        <i class="fa-solid fa-angle-right"></i>
        <a href="<?= e(frontend_url('dich-vu-van-hanh.php')) ?>">Dịch vụ vận hành</a>
        <i class="fa-solid fa-angle-right"></i>
        <span><?= e($service['eyebrow']) ?></span>
      </nav>

      <div class="row align-items-center gy-5">
        <div class="col-lg-6" data-aos="fade-up">
          <span class="section-kicker"><?= e($service['eyebrow']) ?></span>
          <h1><?= e($service['title']) ?></h1>
          <p class="operation-detail-hero__highlight"><?= e($service['highlight']) ?></p>
          <p class="operation-detail-hero__description"><?= e($service['description']) ?></p>
          <div class="operation-detail-hero__actions">
            <a class="btn btn-primary btn-lg" href="#operationServiceContact">Nhận tư vấn</a>
            <a class="btn btn-outline-dark btn-lg" href="<?= e(frontend_url('dich-vu-van-hanh.php')) ?>">Xem toàn bộ dịch vụ</a>
          </div>
          <div class="operation-detail-meta">
            <div><small>Chi phí tham khảo</small><strong><?= e($service['price_from']) ?></strong></div>
            <div><small>Hình thức triển khai</small><strong><?= e($service['cadence']) ?></strong></div>
          </div>
        </div>
        <div class="col-lg-6" data-aos="fade-left">
          <div class="operation-detail-hero__media">
            <img src="<?= e(frontend_asset($service['image'])) ?>" alt="<?= e($service['title']) ?>" width="1200" height="800">
            <div class="operation-detail-hero__badge"><i class="<?= e($service['icon']) ?>"></i><span><small>Dịch vụ vận hành</small><strong>WebApp Bắc Ninh</strong></span></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="operation-detail-trust">
    <div class="container">
      <div class="operation-detail-trust__grid">
        <div><i class="fa-solid fa-list-check"></i><span><strong>Rõ phạm vi</strong><small>Chốt trước đầu việc và khối lượng</small></span></div>
        <div><i class="fa-solid fa-user-shield"></i><span><strong>Có đầu mối phụ trách</strong><small>Không để yêu cầu bị bỏ quên</small></span></div>
        <div><i class="fa-solid fa-file-circle-check"></i><span><strong>Có báo cáo</strong><small>Biết tháng này đã làm gì</small></span></div>
        <div><i class="fa-solid fa-arrow-trend-up"></i><span><strong>Có đề xuất tiếp theo</strong><small>Ưu tiên theo mục tiêu thực tế</small></span></div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="row align-items-end gy-3 mb-5">
        <div class="col-lg-7" data-aos="fade-up">
          <span class="section-kicker">DỊCH VỤ NÀY PHÙ HỢP KHI</span>
          <h2 class="section-title mb-0">Doanh nghiệp đang gặp một trong các tình huống sau</h2>
        </div>
        <div class="col-lg-5" data-aos="fade-up" data-aos-delay="80">
          <p class="section-lead mb-0">Phạm vi cuối cùng được điều chỉnh theo hệ thống, dữ liệu và nhân sự hiện có của doanh nghiệp.</p>
        </div>
      </div>
      <div class="operation-audience-grid">
        <?php foreach ($service['audiences'] as $index => $item): ?>
          <article data-aos="fade-up" data-aos-delay="<?= e((string) ($index * 50)) ?>">
            <span><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
            <p><?= e($item) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section section--light">
    <div class="container">
      <div class="section-heading text-center" data-aos="fade-up">
        <span class="section-kicker">PHẠM VI CÔNG VIỆC</span>
        <h2>Những hạng mục có thể triển khai</h2>
        <p>Chọn riêng từng phần hoặc kết hợp thành gói vận hành theo tháng.</p>
      </div>
      <div class="operation-scope-grid">
        <?php foreach ($service['scope'] as $index => $item): ?>
          <article data-aos="fade-up" data-aos-delay="<?= e((string) (($index % 3) * 60)) ?>">
            <i class="<?= e($item['icon']) ?>"></i>
            <h3><?= e($item['title']) ?></h3>
            <p><?= e($item['text']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section operation-deliverables">
    <div class="container">
      <div class="row align-items-center gy-5">
        <div class="col-lg-6" data-aos="fade-right">
          <div class="operation-deliverables__media">
            <img src="<?= e(frontend_asset($service['secondary_image'])) ?>" alt="Phạm vi bàn giao <?= e($service['eyebrow']) ?>" width="1200" height="800">
          </div>
        </div>
        <div class="col-lg-6" data-aos="fade-left">
          <span class="section-kicker">KẾT QUẢ BÀN GIAO</span>
          <h2 class="section-title">Không chỉ nói chung chung là “đã làm”</h2>
          <p class="section-lead">Mỗi gói đều có đầu việc, đường dẫn hoặc báo cáo tương ứng để doanh nghiệp kiểm tra.</p>
          <ul class="operation-deliverables__list">
            <?php foreach ($service['deliverables'] as $item): ?>
              <li><i class="fa-solid fa-circle-check"></i><span><?= e($item) ?></span></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--navy">
    <div class="container">
      <div class="section-heading text-center" data-aos="fade-up">
        <span class="section-kicker section-kicker--gold">QUY TRÌNH THỰC HIỆN</span>
        <h2 class="section-title section-title--light">Triển khai theo bốn bước rõ ràng</h2>
      </div>
      <div class="operation-process-grid">
        <?php foreach ($service['process'] as $index => $item): ?>
          <article data-aos="fade-up" data-aos-delay="<?= e((string) ($index * 70)) ?>">
            <span><?= e($item['step']) ?></span>
            <h3><?= e($item['title']) ?></h3>
            <p><?= e($item['text']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section section--cream">
    <div class="container">
      <div class="section-heading text-center" data-aos="fade-up">
        <span class="section-kicker">MỨC ĐẦU TƯ THAM KHẢO</span>
        <h2>Chọn theo khối lượng và mức độ hỗ trợ</h2>
        <p>Giá cuối cùng phụ thuộc hiện trạng website, dữ liệu, tần suất và yêu cầu phản hồi.</p>
      </div>
      <div class="operation-detail-packages">
        <?php foreach ($service['packages'] as $index => $package): ?>
          <article class="<?= !empty($package['featured']) ? 'is-featured' : '' ?>" data-aos="fade-up" data-aos-delay="<?= e((string) ($index * 70)) ?>">
            <?php if (!empty($package['featured'])): ?><span class="operation-package-badge">ĐƯỢC QUAN TÂM</span><?php endif; ?>
            <h3><?= e($package['name']) ?></h3>
            <strong><?= e($package['price']) ?></strong>
            <ul>
              <?php foreach ($package['items'] as $item): ?><li><?= e($item) ?></li><?php endforeach; ?>
            </ul>
            <a class="btn <?= !empty($package['featured']) ? 'btn-primary' : 'btn-outline-primary' ?> w-100" href="#operationServiceContact">Nhận phạm vi chi tiết</a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="row gy-5 justify-content-between">
        <div class="col-lg-4" data-aos="fade-right">
          <span class="section-kicker">CÂU HỎI THƯỜNG GẶP</span>
          <h2 class="section-title">Làm rõ trước khi triển khai</h2>
          <p class="section-lead">Phạm vi cụ thể vẫn được ghi lại trong báo giá hoặc thỏa thuận dịch vụ.</p>
        </div>
        <div class="col-lg-7" data-aos="fade-left">
          <div class="accordion faq-accordion" id="operationFaq">
            <?php foreach ($service['faqs'] as $index => $faq): $faqId = 'operationFaq' . $index; ?>
              <div class="accordion-item">
                <h3 class="accordion-header">
                  <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?= e($faqId) ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" aria-controls="<?= e($faqId) ?>"><?= e($faq['q']) ?></button>
                </h3>
                <div id="<?= e($faqId) ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" data-bs-parent="#operationFaq">
                  <div class="accordion-body"><?= e($faq['a']) ?></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php echo view('frontend.site.components.lead-form', [
      'sectionId' => 'operationServiceContact',
      'title' => 'Nhận đề xuất phạm vi ' . mb_strtolower($service['eyebrow'], 'UTF-8'),
      'description' => 'Cho biết website hiện tại, khối lượng mong muốn và phần doanh nghiệp đang thiếu. WebApp Bắc Ninh sẽ tách rõ đầu việc, thời gian và chi phí.',
      'needValue' => $service['need_value'],
  ]); ?>
</main>


