<?php
  $image = $service->image_url;
  $categoryUrl = $service->category ? route('slug.handle', ['slug' => $service->category->slug]) : route('services.index');
?>

<main>
  <section class="inner-hero">
    <div class="container">
      <nav class="inner-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= e(route('home')) ?>">Trang chủ</a><span>/</span>
        <a href="<?= e($categoryUrl) ?>"><?= e($service->category?->name ?? 'Dịch vụ') ?></a><span>/</span>
        <span><?= e($service->title) ?></span>
      </nav>
      <div class="row align-items-center gy-4">
        <div class="col-lg-7">
          <?php if ($service->eyebrow): ?><span class="section-kicker"><?= e($service->eyebrow) ?></span><?php endif; ?>
          <h1><?= e($service->title) ?></h1>
          <p><?= e($service->highlight ?: $service->description) ?></p>
          <div class="inner-hero__actions">
            <a class="btn btn-primary btn-lg" href="#serviceContact">Nhận tư vấn</a>
            <?php if ($service->category): ?><a class="btn btn-outline-dark btn-lg" href="<?= e($categoryUrl) ?>">Xem nhóm dịch vụ</a><?php endif; ?>
          </div>
        </div>
        <div class="col-lg-5"><img class="inner-hero__image" src="<?= e($image) ?>" alt="<?= e($service->title) ?>"></div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="row justify-content-center">
        <article class="col-lg-9">
          <div class="section-heading text-center">
            <span class="section-kicker">GIỚI THIỆU DỊCH VỤ</span>
            <h2><?= e($service->title) ?></h2>
            <?php if ($service->description): ?><p><?= e($service->description) ?></p><?php endif; ?>
          </div>
          <?php if ($service->content): ?>
            <div class="service-rich-content"><?= $service->content ?></div>
          <?php else: ?>
            <div class="alert alert-light border text-center mb-0">Nội dung chi tiết đang được cập nhật. Liên hệ để nhận tư vấn theo nhu cầu của anh/chị.</div>
          <?php endif; ?>
        </article>
      </div>
    </div>
  </section>

  <?php echo view('frontend.site.components.lead-form', [
      'sectionId' => 'serviceContact',
      'title' => 'Trao đổi về '.$service->title,
      'description' => 'Gửi nhu cầu, mục tiêu và thông tin hiện có. WebApp Bắc Ninh sẽ tư vấn phạm vi phù hợp.',
      'needValue' => $service->title,
  ]); ?>
</main>
