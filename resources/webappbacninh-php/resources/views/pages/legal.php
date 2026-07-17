<main>
  <section class="legal-hero">
    <div class="container">
      <nav class="inner-breadcrumb" data-aos="fade-up">
        <a href="<?= e(url('index.php')) ?>">Trang chủ</a>
        <i class="fa-solid fa-angle-right"></i>
        <span><?= e($page['short_title']) ?></span>
      </nav>

      <div class="row align-items-center gy-4">
        <div class="col-lg-8" data-aos="fade-up">
          <span class="section-kicker">THÔNG TIN VÀ CHÍNH SÁCH</span>
          <h1><?= e($page['title']) ?></h1>
          <p><?= e($page['intro']) ?></p>
          <div class="legal-hero__meta">
            <span><i class="fa-regular fa-calendar-check"></i> Cập nhật lần cuối: <?= e($page['updated_at']) ?></span>
            <span><i class="fa-solid fa-building-shield"></i> WebApp Bắc Ninh</span>
          </div>
        </div>
        <div class="col-lg-4" data-aos="fade-left">
          <div class="legal-hero__icon"><i class="<?= e($page['icon']) ?>"></i></div>
        </div>
      </div>
    </div>
  </section>

  <section class="legal-content section--light">
    <div class="container">
      <div class="row g-4 align-items-start">
        <aside class="col-lg-3">
          <div class="legal-toc">
            <strong>Nội dung chính</strong>
            <nav>
              <?php foreach ($page['sections'] as $index => $section): ?>
                <a href="#section-<?= e((string) ($index + 1)) ?>"><?= e($section['title']) ?></a>
              <?php endforeach; ?>
            </nav>
            <a class="btn btn-outline-primary w-100" href="<?= e(url('lien-he.php')) ?>">Cần giải thích thêm</a>
          </div>
        </aside>

        <div class="col-lg-9">
          <div class="legal-notice" data-aos="fade-up">
            <i class="fa-solid fa-circle-info"></i>
            <p><?= e($page['notice']) ?></p>
          </div>

          <div class="legal-document">
            <?php foreach ($page['sections'] as $index => $section): ?>
              <article id="section-<?= e((string) ($index + 1)) ?>" data-aos="fade-up">
                <h2><?= e($section['title']) ?></h2>
                <?php if (!empty($section['content'])): ?>
                  <p><?= e($section['content']) ?></p>
                <?php endif; ?>
                <?php if (!empty($section['items'])): ?>
                  <ul>
                    <?php foreach ($section['items'] as $item): ?>
                      <li><?= e($item) ?></li>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>
              </article>
            <?php endforeach; ?>
          </div>

          <div class="legal-contact" data-aos="fade-up">
            <div>
              <span class="section-kicker">THÔNG TIN LIÊN HỆ</span>
              <h2>Cần trao đổi về chính sách này?</h2>
              <p>Gửi nội dung cần làm rõ hoặc liên hệ trực tiếp qua hotline và email được công bố trên website.</p>
            </div>
            <div class="legal-contact__actions">
              <a class="btn btn-primary" href="<?= e(url('lien-he.php')) ?>">Gửi yêu cầu</a>
              <a class="btn btn-outline-primary" href="tel:<?= e(config('phone_href')) ?>"><i class="fa-solid fa-phone"></i> <?= e(config('phone')) ?></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
