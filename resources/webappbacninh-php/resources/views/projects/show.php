<main>
  <section class="project-detail-hero">
    <div class="container">
      <nav aria-label="breadcrumb" class="project-detail-breadcrumb" data-aos="fade-up">
        <ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="<?= e(url('index.php')) ?>">Trang chủ</a></li><li class="breadcrumb-item"><a href="<?= e(url('du-an.php')) ?>">Dự án</a></li><li class="breadcrumb-item active" aria-current="page"><?= e($project['code']) ?></li></ol>
      </nav>
      <div class="row align-items-center gy-5">
        <div class="col-lg-6">
          <div class="project-detail-meta" data-aos="fade-up"><span><?= e($project['category_label']) ?></span><span><?= e($project['industry_label']) ?></span><span><?= e($project['year']) ?></span></div>
          <h1 data-aos="fade-up" data-aos-delay="70"><?= e($project['title']) ?></h1>
          <p data-aos="fade-up" data-aos-delay="130"><?= e($project['excerpt']) ?></p>
          <div class="project-detail-summary" data-aos="fade-up" data-aos-delay="190">
            <div><small>Khách hàng</small><strong><?= e($project['client']) ?></strong></div>
            <div><small>Loại dự án</small><strong><?= e($project['website_type']) ?></strong></div>
            <div><small>Thời gian</small><strong><?= e($project['duration']) ?></strong></div>
          </div>
          <div class="project-detail-actions" data-aos="fade-up" data-aos-delay="240"><a class="btn btn-primary btn-lg" href="#projectConsult">Làm dự án tương tự</a><a class="btn btn-outline-dark btn-lg" href="<?= e(url('du-an.php')) ?>">Xem dự án khác</a></div>
        </div>
        <div class="col-lg-6">
          <div class="project-detail-cover" data-aos="fade-left"><img id="projectMainImage" src="<?= e(asset($project['gallery'][0])) ?>" alt="<?= e($project['title']) ?>" width="1200" height="750"></div>
          <div class="project-detail-thumbs" data-project-gallery data-aos="fade-up">
            <?php foreach ($project['gallery'] as $index => $image): ?><button type="button" class="<?= $index === 0 ? 'is-active' : '' ?>" data-gallery-image="<?= e(asset($image)) ?>"><img src="<?= e(asset($image)) ?>" alt="Ảnh dự án <?= $index + 1 ?>" width="1200" height="750"></button><?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="project-story section--light">
    <div class="container">
      <div class="project-story__grid">
        <article data-aos="fade-up"><span>01</span><h2>Bài toán ban đầu</h2><p><?= e($project['challenge']) ?></p></article>
        <article data-aos="fade-up" data-aos-delay="80"><span>02</span><h2>Giải pháp triển khai</h2><p><?= e($project['solution']) ?></p></article>
      </div>
    </div>
  </section>

  <section class="project-scope">
    <div class="container">
      <div class="row gy-5">
        <div class="col-lg-7">
          <span class="section-kicker" data-aos="fade-up">PHẠM VI BÀN GIAO</span>
          <h2 class="section-title" data-aos="fade-up">Những hạng mục chính trong dự án</h2>
          <div class="project-deliverables">
            <?php foreach ($project['deliverables'] as $index => $item): ?><div data-aos="fade-up" data-aos-delay="<?= min($index * 35, 210) ?>"><i class="fa-solid fa-check"></i><span><?= e($item) ?></span></div><?php endforeach; ?>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="project-tech" data-aos="fade-left"><span>CÔNG NGHỆ VÀ NỀN TẢNG</span><h3>Ưu tiên dễ vận hành và có khả năng mở rộng.</h3><div><?php foreach ($project['technologies'] as $technology): ?><span><?= e($technology) ?></span><?php endforeach; ?></div><p>Công nghệ thực tế sẽ được lựa chọn theo nghiệp vụ, hạ tầng và khả năng vận hành của từng doanh nghiệp.</p></div>
        </div>
      </div>
    </div>
  </section>

  <section class="project-results section--navy">
    <div class="container">
      <div class="section-heading text-center" data-aos="fade-up"><span class="section-kicker section-kicker--light">GIÁ TRỊ SAU BÀN GIAO</span><h2 class="section-title section-title--light">Dự án không dừng ở việc website hiển thị đẹp.</h2></div>
      <div class="project-results__grid">
        <?php foreach ($project['results'] as $index => $result): ?><article data-aos="fade-up" data-aos-delay="<?= $index * 70 ?>"><span>0<?= $index + 1 ?></span><p><?= e($result) ?></p></article><?php endforeach; ?>
      </div>
    </div>
  </section>

  <?php if ($relatedItems): ?>
  <section class="related-projects">
    <div class="container">
      <div class="row align-items-end gy-3 mb-5"><div class="col-lg-8"><span class="section-kicker">DỰ ÁN LIÊN QUAN</span><h2 class="section-title mb-0">Tham khảo thêm các hướng triển khai khác</h2></div><div class="col-lg-4 text-lg-end"><a class="text-link" href="<?= e(url('du-an.php')) ?>">Xem toàn bộ dự án <i class="fa-solid fa-arrow-right"></i></a></div></div>
      <div class="related-projects__grid">
        <?php foreach ($relatedItems as $item): ?><article><a href="<?= e(project_url($item)) ?>"><img src="<?= e(asset($item['image'])) ?>" alt="<?= e($item['title']) ?>" width="1200" height="750" loading="lazy"></a><div><span><?= e($item['category_label']) ?></span><h3><a href="<?= e(project_url($item)) ?>"><?= e($item['title']) ?></a></h3><a class="text-link" href="<?= e(project_url($item)) ?>">Xem dự án <i class="fa-solid fa-arrow-right"></i></a></div></article><?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <section class="project-consult" id="projectConsult">
    <div class="container">
      <div class="project-consult__shell" data-aos="fade-up">
        <div><span class="section-kicker section-kicker--gold">LÀM DỰ ÁN TƯƠNG TỰ</span><h2>Không cần giống hoàn toàn. Hãy bắt đầu từ bài toán của doanh nghiệp.</h2><p>Gửi mã dự án <?= e($project['code']) ?> và mô tả nội dung, chức năng hoặc website tham khảo. WebApp Bắc Ninh sẽ đề xuất phương án phù hợp.</p></div>
        <form class="project-consult__form needs-validation" novalidate id="detailProjectForm">
          <input type="hidden" value="<?= e($project['code']) ?>" id="detailProjectCode">
          <div class="row g-3"><div class="col-md-6"><label class="form-label" for="detailName">Họ và tên *</label><input class="form-control" id="detailName" required></div><div class="col-md-6"><label class="form-label" for="detailPhone">Số điện thoại *</label><input class="form-control" id="detailPhone" type="tel" required></div><div class="col-12"><label class="form-label" for="detailMessage">Nhu cầu</label><textarea class="form-control" id="detailMessage" rows="4" placeholder="Tôi quan tâm dự án <?= e($project['code']) ?> và cần..."></textarea></div><div class="col-12"><button class="btn btn-warning btn-lg" type="submit">Gửi yêu cầu tư vấn</button></div></div>
          <div class="alert alert-success mt-3 d-none" id="detailProjectSuccess">Form mẫu đã hợp lệ. Sau này thay bằng route xử lý thật.</div>
        </form>
      </div>
    </div>
  </section>
</main>
