<main>
  <section class="projects-hero">
    <div class="container">
      <nav aria-label="breadcrumb" class="projects-breadcrumb" data-aos="fade-up">
        <ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="<?= e(frontend_url('index.php')) ?>">Trang chủ</a></li><li class="breadcrumb-item active" aria-current="page">Dự án</li></ol>
      </nav>
      <div class="row align-items-end gy-4">
        <div class="col-lg-8">
          <span class="section-kicker" data-aos="fade-up">DỰ ÁN WEBSITE VÀ PHẦN MỀM</span>
          <h1 data-aos="fade-up" data-aos-delay="70">Xem cách từng dự án<br><span>giải quyết một bài toán cụ thể.</span></h1>
          <p data-aos="fade-up" data-aos-delay="130">Không chỉ trình bày ảnh giao diện. Mỗi dự án cho thấy mục tiêu, phạm vi triển khai, cách xử lý nội dung và kết quả doanh nghiệp nhận được sau bàn giao.</p>
        </div>
        <div class="col-lg-4">
          <div class="projects-hero__note" data-aos="fade-left">
            <i class="fa-solid fa-circle-info"></i>
            <p>Dữ liệu hiện đang fix cứng để anh thay dần bằng dự án thực tế, tên khách hàng, số liệu và đường dẫn demo.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="projects-library section--light" id="projectListSection">
    <div class="container">
      <div class="projects-toolbar" data-aos="fade-up">
        <div class="project-search"><i class="fa-solid fa-magnifying-glass"></i><input type="search" id="projectSearch" placeholder="Tìm theo tên dự án, ngành nghề..."></div>
        <div class="project-filters" id="projectFilters">
          <button class="is-active" type="button" data-project-filter="all">Tất cả</button>
          <button type="button" data-project-filter="doanh-nghiep">Doanh nghiệp</button>
          <button type="button" data-project-filter="ban-hang">Bán hàng</button>
          <button type="button" data-project-filter="landing-page">Landing page</button>
          <button type="button" data-project-filter="dich-vu">Dịch vụ</button>
          <button type="button" data-project-filter="phan-mem">Phần mềm</button>
        </div>
      </div>

      <div class="projects-resultbar">
        <span><strong id="projectCount"><?= count($projectItems) ?></strong> dự án phù hợp</span>
        <select class="form-select" id="projectSort" aria-label="Sắp xếp dự án">
          <option value="featured">Nổi bật</option>
          <option value="newest">Mới nhất</option>
          <option value="name">Tên A - Z</option>
        </select>
      </div>

      <div class="projects-grid" id="projectsGrid">
        <?php foreach ($projectItems as $index => $project): ?>
        <article class="project-showcase-card <?= $index === 0 ? 'project-showcase-card--featured' : '' ?>"
          data-project-card
          data-category="<?= e($project['category']) ?>"
          data-search="<?= e(strtolower($project['title'] . ' ' . $project['industry_label'] . ' ' . $project['excerpt'])) ?>"
          data-year="<?= e($project['year']) ?>"
          data-featured="<?= e($project['featured']) ?>"
          data-name="<?= e($project['title']) ?>"
          data-aos="fade-up">
          <a class="project-showcase-card__media" href="<?= e(project_url($project)) ?>">
            <img src="<?= e(frontend_asset($project['image'])) ?>" alt="<?= e($project['title']) ?>" width="1200" height="750" loading="lazy">
            <span><?= e($project['code']) ?></span>
          </a>
          <div class="project-showcase-card__body">
            <div class="project-showcase-card__meta"><span><?= e($project['category_label']) ?></span><span><?= e($project['industry_label']) ?> · <?= e($project['year']) ?></span></div>
            <h2><a href="<?= e(project_url($project)) ?>"><?= e($project['title']) ?></a></h2>
            <p><?= e($project['excerpt']) ?></p>
            <div class="project-showcase-card__stats">
              <div><small>Thời gian</small><strong><?= e($project['duration']) ?></strong></div>
              <div><small>Phạm vi</small><strong><?= count($project['deliverables']) ?> hạng mục</strong></div>
            </div>
            <a class="project-showcase-card__link" href="<?= e(project_url($project)) ?>">Xem chi tiết dự án <i class="fa-solid fa-arrow-right"></i></a>
          </div>
        </article>
        <?php endforeach; ?>
      </div>

      <div class="projects-empty d-none" id="projectsEmpty">
        <i class="fa-regular fa-folder-open"></i><h2>Chưa có dự án phù hợp</h2><p>Hãy thử từ khóa khác hoặc chọn lại tất cả danh mục.</p><button class="btn btn-primary" id="projectReset" type="button">Đặt lại bộ lọc</button>
      </div>
    </div>
  </section>

  <section class="projects-method">
    <div class="container">
      <div class="section-heading text-center" data-aos="fade-up">
        <span class="section-kicker">CÁCH CHÚNG TÔI TRÌNH BÀY DỰ ÁN</span>
        <h2>Khách hàng cần thấy năng lực giải quyết công việc, không chỉ ảnh mockup.</h2>
      </div>
      <div class="projects-method__grid">
        <article data-aos="fade-up"><i class="fa-solid fa-triangle-exclamation"></i><h3>Bài toán ban đầu</h3><p>Doanh nghiệp đang vướng gì về nội dung, bán hàng, quản trị hay trải nghiệm khách hàng?</p></article>
        <article data-aos="fade-up" data-aos-delay="60"><i class="fa-solid fa-compass-drafting"></i><h3>Phương án triển khai</h3><p>Cấu trúc trang, chức năng, dữ liệu và cách chia giai đoạn được đề xuất ra sao?</p></article>
        <article data-aos="fade-up" data-aos-delay="120"><i class="fa-solid fa-box-open"></i><h3>Phạm vi bàn giao</h3><p>Danh sách trang, chức năng, quản trị, SEO nền tảng và tài liệu hướng dẫn.</p></article>
        <article data-aos="fade-up" data-aos-delay="180"><i class="fa-solid fa-chart-line"></i><h3>Giá trị sau bàn giao</h3><p>Website giúp đội kinh doanh, nội dung và vận hành làm việc thuận lợi hơn như thế nào?</p></article>
      </div>
    </div>
  </section>

  <section class="projects-contact" id="projectContact">
    <div class="container">
      <div class="projects-contact__shell" data-aos="fade-up">
        <div>
          <span class="section-kicker section-kicker--gold">CẦN MỘT DỰ ÁN TƯƠNG TỰ?</span>
          <h2>Gửi website tham khảo hoặc mô tả bài toán của doanh nghiệp.</h2>
          <p>WebApp Bắc Ninh sẽ đề xuất cấu trúc, phạm vi và mức đầu tư phù hợp thay vì sao chép nguyên một giao diện có sẵn.</p>
        </div>
        <form action="<?= e(route('leads.store')) ?>" method="POST" data-lead-form class="projects-contact__form needs-validation" novalidate id="projectForm">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label" for="projectName">Họ và tên *</label><input class="form-control" id="projectName" name="name" required></div>
            <div class="col-md-6"><label class="form-label" for="projectPhone">Số điện thoại *</label><input class="form-control" id="projectPhone" name="phone" type="tel" required></div>
            <div class="col-md-6"><label class="form-label" for="projectIndustry">Lĩnh vực</label><input class="form-control" id="projectIndustry" name="business" placeholder="Sản xuất, giáo dục, thương mại..."></div>
            <div class="col-md-6"><label class="form-label" for="projectReference">Dự án tham khảo</label><input class="form-control" id="projectReference" name="need" placeholder="Ví dụ: DA-001"></div>
            <div class="col-12"><label class="form-label" for="projectMessage">Mô tả nhu cầu</label><textarea class="form-control" id="projectMessage" name="message" rows="4"></textarea></div>
            <div class="col-12"><button class="btn btn-warning btn-lg" type="submit">Gửi yêu cầu tư vấn</button></div>
          </div>
          <div class="alert alert-success mt-3 d-none" id="projectFormSuccess">Form mẫu đã hợp lệ. Sau này thay bằng route xử lý thật.</div>
        </form>
      </div>
    </div>
  </section>
</main>




