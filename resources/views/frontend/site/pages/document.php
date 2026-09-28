<?php
/** @var \App\Models\Page $page */
$toc = $page->tableOfContents();
?>
<main>
  <section class="legal-hero">
    <div class="container">
      <nav class="inner-breadcrumb">
        <a href="<?= e(route('home')) ?>">Trang chủ</a>
        <i class="fa-solid fa-angle-right"></i>
        <span><?= e($page->breadcrumbTitle()) ?></span>
      </nav>

      <div class="row align-items-center gy-4">
        <div class="col-lg-8">
          <?php if (filled($page->eyebrow)): ?>
            <span class="section-kicker"><?= e($page->eyebrow) ?></span>
          <?php endif; ?>
          <h1><?= e($page->title) ?></h1>
          <?php if (filled($page->summary)): ?>
            <p><?= e($page->summary) ?></p>
          <?php endif; ?>
          <div class="legal-hero__meta">
            <?php if ($page->content_updated_at): ?>
              <span><i class="fa-regular fa-calendar-check"></i> Cập nhật lần cuối: <?= e($page->content_updated_at->format('d/m/Y')) ?></span>
            <?php endif; ?>
            <span><i class="fa-solid fa-building-shield"></i> <?= e(site_config('name')) ?></span>
          </div>
        </div>
        <?php if (filled($page->icon)): ?>
          <div class="col-lg-4">
            <div class="legal-hero__icon"><i class="<?= e($page->icon) ?>"></i></div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="legal-content section--light">
    <div class="container">
      <div class="row g-4 align-items-start">
        <?php if ($toc !== []): ?>
          <aside class="col-lg-3">
            <div class="legal-toc">
              <strong>Nội dung chính</strong>
              <nav>
                <?php foreach ($toc as $anchor => $heading): ?>
                  <a href="#section-<?= e((string) $anchor) ?>"><?= e($heading) ?></a>
                <?php endforeach; ?>
              </nav>
              <a class="btn btn-outline-primary w-100" href="<?= e(route('contact')) ?>">Cần giải thích thêm</a>
            </div>
          </aside>
        <?php endif; ?>

        <div class="<?= $toc !== [] ? 'col-lg-9' : 'col-lg-10 mx-auto' ?>">
          <?php if (filled($page->notice)): ?>
            <div class="legal-notice">
              <i class="fa-solid fa-circle-info"></i>
              <p><?= e($page->notice) ?></p>
            </div>
          <?php endif; ?>

          <div class="legal-document">
            <?php foreach ($page->blocks() as $index => $block): ?>
              <?php $data = $block['data'] ?? []; ?>
              <?php if ($block['type'] === 'section'): ?>
                <article id="section-<?= e((string) ($index + 1)) ?>">
                  <?php if (filled($data['heading'] ?? null)): ?>
                    <h2><?= e($data['heading']) ?></h2>
                  <?php endif; ?>
                  <?php if (filled($data['content'] ?? null)): ?>
                    <p><?= e($data['content']) ?></p>
                  <?php endif; ?>
                  <?php $items = array_filter(array_map('trim', preg_split('/\R/', (string) ($data['items'] ?? '')))); ?>
                  <?php if ($items !== []): ?>
                    <ul>
                      <?php foreach ($items as $item): ?>
                        <li><?= e($item) ?></li>
                      <?php endforeach; ?>
                    </ul>
                  <?php endif; ?>
                </article>
              <?php elseif ($block['type'] === 'rich_text'): ?>
                <article><?= str((string) ($data['body'] ?? ''))->sanitizeHtml() ?></article>
              <?php elseif ($block['type'] === 'callout' && filled($data['text'] ?? null)): ?>
                <div class="legal-notice">
                  <i class="fa-solid fa-circle-info"></i>
                  <p><?= e($data['text']) ?></p>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>

          <div class="legal-contact">
            <div>
              <span class="section-kicker">THÔNG TIN LIÊN HỆ</span>
              <h2>Cần trao đổi thêm về nội dung này?</h2>
              <p>Gửi nội dung cần làm rõ hoặc liên hệ trực tiếp qua hotline và email được công bố trên website.</p>
            </div>
            <div class="legal-contact__actions">
              <a class="btn btn-primary" href="<?= e(route('contact')) ?>">Gửi yêu cầu</a>
              <a class="btn btn-outline-primary" href="tel:<?= e(site_config('phone_href')) ?>"><i class="fa-solid fa-phone"></i> <?= e(site_config('phone')) ?></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
