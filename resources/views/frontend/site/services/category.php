<main>
  <section class="inner-hero">
    <div class="container">
      <nav class="inner-breadcrumb" aria-label="Breadcrumb"><a href="<?= e(route('home')) ?>">Trang chủ</a><span>/</span><span><?= e($category->name) ?></span></nav>
      <div class="row align-items-center gy-4">
        <div class="col-lg-8">
          <span class="section-kicker">NHÓM DỊCH VỤ</span>
          <h1><?= e($category->name) ?></h1>
          <p><?= e($category->description ?: 'Các dịch vụ được tổng hợp theo cùng một nhu cầu triển khai.') ?></p>
          <div class="inner-hero__actions"><a class="btn btn-primary btn-lg" href="#categoryContact">Nhận tư vấn</a></div>
        </div>
        <div class="col-lg-4">
          <div class="hero-summary">
            <div><i class="fa-solid fa-layer-group"></i><strong><?= $services->count() ?> dịch vụ</strong><span>Đang hiển thị trong nhóm</span></div>
            <div><i class="fa-solid fa-headset"></i><strong>Tư vấn theo nhu cầu</strong><span>Chọn hạng mục cần thiết, không bắt buộc trọn gói</span></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <?php if ($category->content): ?><div class="service-rich-content mb-5"><?= $category->content ?></div><?php endif; ?>
      <div class="section-heading text-center"><span class="section-kicker">DANH SÁCH DỊCH VỤ</span><h2>Chọn hạng mục phù hợp với nhu cầu hiện tại</h2></div>
      <div class="service-detail-grid">
        <?php foreach ($services as $index => $service): ?>
          <article>
            <i class="<?= e($service->icon ?: 'fa-solid fa-screwdriver-wrench') ?>"></i>
            <span><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
            <h3><?= e($service->title) ?></h3>
            <p><?= e($service->highlight ?: $service->description) ?></p>
            <a class="btn btn-outline-primary btn-sm mt-2" href="<?= e(route('slug.handle', ['slug' => $service->slug])) ?>">Xem chi tiết</a>
          </article>
        <?php endforeach; ?>
      </div>
      <?php if ($services->isEmpty()): ?><div class="alert alert-light border text-center">Nhóm này đang được cập nhật dịch vụ. Hãy gửi nhu cầu để được tư vấn trực tiếp.</div><?php endif; ?>
    </div>
  </section>

  <?php echo view('frontend.site.components.lead-form', [
      'sectionId' => 'categoryContact',
      'title' => 'Tư vấn '.$category->name,
      'description' => 'Gửi nhu cầu, đội ngũ sẽ gợi ý các hạng mục phù hợp với doanh nghiệp.',
      'needValue' => $category->name,
  ]); ?>
</main>
