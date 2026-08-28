<?php
$sectionId = $sectionId ?? 'leadContact';
$title = $title ?? 'Nhận tư vấn';
$description = $description ?? 'Gửi thông tin để được tư vấn.';
$needValue = $needValue ?? '';
?>
<section class="lead-section" id="<?= e($sectionId) ?>"><div class="container"><div class="lead-shell"><div class="lead-copy"><span class="section-kicker section-kicker--gold">NHẬN TƯ VẤN</span><h2><?= e($title) ?></h2><p><?= e($description) ?></p><div class="lead-contact"><a href="tel:<?= e(site_config('phone_href')) ?>"><i class="fa-solid fa-phone"></i><span><small>Hotline / Zalo</small><strong><?= e(site_config('phone')) ?></strong></span></a><a href="mailto:<?= e(site_config('email')) ?>"><i class="fa-regular fa-envelope"></i><span><small>Email</small><strong><?= e(site_config('email')) ?></strong></span></a></div></div><form action="<?= e(route('leads.store')) ?>" method="POST" data-lead-form class="lead-form"><div class="row g-3"><div class="col-md-6"><label class="form-label">Họ và tên *</label><input class="form-control" name="name" required></div><div class="col-md-6"><label class="form-label">Số điện thoại *</label><input class="form-control" name="phone" type="tel" required></div><div class="col-md-6"><label class="form-label">Doanh nghiệp / lĩnh vực</label><input class="form-control" name="business"></div><div class="col-md-6"><label class="form-label">Nhu cầu</label><input class="form-control" name="need" value="<?= e($needValue) ?>"></div><div class="col-12"><label class="form-label">Mô tả ngắn</label><textarea class="form-control" name="message" rows="3"></textarea></div><div class="col-12"><button class="btn btn-warning btn-lg" type="submit">Gửi yêu cầu</button></div></div><div class="alert alert-success mt-3 d-none" data-form-success></div></form></div></div></section>


