<?php
$showQuick = $showQuick ?? true;
$badgeClass = $theme['badge'] === 'Bán chạy' ? 'theme-card__badge--hot' : ($theme['badge'] === 'Mới' ? 'theme-card__badge--new' : '');
$searchText = implode(' ', [$theme['name'], $theme['code'], $theme['industryLabel'], $theme['description'], ...$theme['tags']]);
?>
<article class="theme-card"
  data-theme-card
  data-theme-id="<?= e($theme['id']) ?>"
  data-search="<?= e($searchText) ?>"
  data-type="<?= e($theme['type']) ?>"
  data-industry="<?= e($theme['industry']) ?>"
  data-price="<?= e($theme['price']) ?>"
  data-features="<?= e(implode(',', $theme['featureKeys'])) ?>"
  data-year="<?= e($theme['year']) ?>"
  data-featured="<?= e($theme['featured']) ?>"
  data-name="<?= e($theme['name']) ?>">
  <div class="theme-card__media">
    <a href="<?= e(theme_url($theme)) ?>"><img src="<?= e(asset('assets/images/' . $theme['image'])) ?>" alt="<?= e($theme['name']) ?>" width="1200" height="750" loading="lazy"></a>
    <div class="theme-card__badges">
      <?php if ($theme['badge'] !== ''): ?><span class="theme-card__badge <?= e($badgeClass) ?>"><?= e($theme['badge']) ?></span><?php endif; ?>
      <span class="theme-card__badge"><?= e($theme['typeLabel']) ?></span>
    </div>
    <?php if ($showQuick): ?><button class="theme-card__quick" type="button" data-theme-quick="<?= e($theme['id']) ?>" aria-label="Xem nhanh <?= e($theme['name']) ?>"><i class="fa-regular fa-eye"></i></button><?php endif; ?>
  </div>
  <div class="theme-card__body">
    <div class="theme-card__meta"><span><?= e($theme['industryLabel']) ?></span><span class="theme-card__code"><?= e($theme['code']) ?></span></div>
    <h3><a href="<?= e(theme_url($theme)) ?>"><?= e($theme['name']) ?></a></h3>
    <p><?= e($theme['description']) ?></p>
    <div class="theme-card__tags"><?php foreach (array_slice($theme['tags'], 0, 3) as $tag): ?><span><?= e($tag) ?></span><?php endforeach; ?></div>
    <div class="theme-card__footer">
      <div class="theme-card__price"><small>Chi phí tham khảo từ</small><strong><?= e(money($theme['price'])) ?></strong></div>
      <div class="theme-card__actions"><?php if ($showQuick): ?><button class="btn btn-outline-primary" type="button" data-theme-quick="<?= e($theme['id']) ?>">Xem nhanh</button><?php endif; ?><a class="btn btn-primary" href="<?= e(theme_url($theme)) ?>">Chi tiết</a></div>
    </div>
  </div>
</article>
