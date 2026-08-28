<main>
  <section class="inner-hero inner-hero--operations">
    <div class="container"><div class="row align-items-center gy-5">
      <div class="col-lg-6" data-aos="fade-up">
        <nav class="inner-breadcrumb"><a href="<?= e(route('home')) ?>">Trang chủ</a><i class="fa-solid fa-angle-right"></i><span>Dịch vụ vận hành</span></nav>
        <span class="section-kicker">SAU KHI WEBSITE ĐI VÀO HOẠT ĐỘNG</span><h1>Website không tự tạo giá trị nếu không được duy trì</h1>
        <p>WebApp Bắc Ninh hỗ trợ phần kỹ thuật, nội dung, SEO và cập nhật định kỳ để doanh nghiệp không phải tuyển ngay một đội riêng.</p>
        <div class="inner-hero__actions"><a class="btn btn-primary btn-lg" href="#operationServices">Xem dịch vụ</a><a class="btn btn-outline-dark btn-lg" href="#operationContact">Nhận đề xuất gói</a></div>
      </div>
      <div class="col-lg-6" data-aos="fade-left"><img class="inner-hero__image" src="<?= e(frontend_asset('assets/images/seo-operation.webp')) ?>" alt="Dịch vụ vận hành website và SEO"></div>
    </div></div>
  </section>

  <section class="section" id="operationServices">
    <div class="container"><div class="section-heading text-center"><span class="section-kicker"><?= e((string) count($operationServices)) ?> NHÓM CÔNG VIỆC</span><h2>Chọn đúng phần doanh nghiệp đang thiếu</h2><p>Không bắt buộc mua trọn gói. Mỗi nhóm có thể triển khai riêng hoặc kết hợp.</p></div>
      <div class="service-detail-grid">
        <?php foreach ($operationServices as $index => $service): ?>
          <article id="<?= e($service['slug']) ?>">
            <a class="service-detail-card__link" href="<?= e(route('operations.show', $service['slug'])) ?>" aria-label="Xem dịch vụ <?= e($service['title']) ?>"></a>
            <i class="<?= e($service['icon']) ?>"></i><span><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span><h3><?= e($service['title']) ?></h3><p><?= e($service['description']) ?></p>
            <?php if (!empty($service['scope'])): ?><ul><?php foreach (array_slice($service['scope'], 0, 4) as $scope): ?><li><?= e($scope['title']) ?></li><?php endforeach; ?></ul><?php endif; ?>
            <strong class="service-detail-card__more">Xem chi tiết <i class="fa-solid fa-arrow-right"></i></strong>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section section--light"><div class="container"><div class="row align-items-end gy-3 mb-5"><div class="col-lg-7"><span class="section-kicker">GÓI ĐỒNG HÀNH</span><h2 class="section-title mb-0">Ba cách duy trì phổ biến</h2></div><div class="col-lg-5"><p class="section-lead mb-0">Phạm vi và số lượng công việc được thống nhất theo tháng để tránh hiểu nhầm.</p></div></div><div class="operation-package-grid"><article><span>CARE</span><h3>Chăm sóc kỹ thuật</h3><p>Phù hợp website ít cập nhật nhưng cần hoạt động ổn định.</p><ul><li>Hosting và backup</li><li>Kiểm tra định kỳ</li><li>Hỗ trợ lỗi kỹ thuật</li><li>Cập nhật nhỏ</li></ul><strong>Từ 500.000đ/tháng</strong></article><article><span>CONTENT</span><h3>Quản trị nội dung</h3><p>Phù hợp doanh nghiệp có bài viết, sản phẩm và dự án mới.</p><ul><li>Đăng nội dung định kỳ</li><li>Chuẩn hóa ảnh và bài</li><li>Cập nhật banner, dịch vụ</li><li>Báo cáo khối lượng</li></ul><strong>Từ 1.500.000đ/tháng</strong></article><article class="is-highlight"><span>GROWTH</span><h3>Website tăng trưởng</h3><p>Phù hợp doanh nghiệp muốn duy trì cả kỹ thuật, nội dung và SEO.</p><ul><li>Website Care</li><li>Nội dung định kỳ</li><li>SEO và Search Console</li><li>Báo cáo và đề xuất tháng sau</li></ul><strong>Từ 3.500.000đ/tháng</strong></article></div></div></section>
  <section class="section section--navy"><div class="container"><div class="row align-items-center gy-5"><div class="col-lg-5"><span class="section-kicker section-kicker--gold">CÁCH LÀM VIỆC</span><h2 class="section-title section-title--light">Mỗi tháng đều biết đã làm gì</h2><p class="text-light-emphasis">Không dùng các cụm từ chung chung như “chăm sóc website”. Công việc được ghi theo đầu mục và khối lượng.</p></div><div class="col-lg-7"><div class="report-steps"><div><span>01</span><strong>Chốt danh sách việc</strong><small>Ưu tiên theo mục tiêu tháng</small></div><div><span>02</span><strong>Tiếp nhận dữ liệu</strong><small>Nội dung, hình ảnh và thay đổi</small></div><div><span>03</span><strong>Thực hiện & kiểm tra</strong><small>Rà soát trên desktop và mobile</small></div><div><span>04</span><strong>Báo cáo & đề xuất</strong><small>Khối lượng và việc tiếp theo</small></div></div></div></div></div></section>
  <section class="section"><div class="container"><div class="section-heading text-center"><span class="section-kicker">DOANH NGHIỆP CẦN CUNG CẤP</span><h2>Phối hợp gọn để nội dung không bị tắc</h2></div><div class="input-grid"><article><i class="fa-solid fa-user-check"></i><h3>Một đầu mối duyệt</h3><p>Tránh nhiều người sửa chéo và kéo dài thời gian phản hồi.</p></article><article><i class="fa-solid fa-folder-open"></i><h3>Dữ liệu có tổ chức</h3><p>Ảnh, thông tin, giá và tài liệu được gom theo từng nhóm.</p></article><article><i class="fa-solid fa-calendar-check"></i><h3>Lịch cập nhật</h3><p>Chốt trước các sự kiện, chương trình và nội dung ưu tiên.</p></article><article><i class="fa-solid fa-bullseye"></i><h3>Mục tiêu rõ</h3><p>Muốn tăng uy tín, tìm khách, tuyển đại lý hay bán sản phẩm.</p></article></div></div></section>
  <?php echo view('frontend.site.components.lead-form', ['sectionId' => 'operationContact', 'title' => 'Nhận đề xuất gói vận hành phù hợp', 'description' => 'Cho biết website hiện tại, tần suất cập nhật và phần doanh nghiệp đang thiếu. Chúng tôi sẽ tách rõ từng đầu việc và chi phí.', 'needValue' => 'Dịch vụ vận hành website']); ?>
</main>
