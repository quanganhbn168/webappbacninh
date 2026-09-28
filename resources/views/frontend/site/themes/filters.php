<?php /** @var string $suffix Desktop or Mobile, keeps field ids unique. */ ?>
<div class="theme-filter<?= $suffix === 'Desktop' ? ' theme-filter--sticky' : '' ?>" id="<?= e($suffix === 'Desktop' ? 'desktopFilters' : 'mobileFilters') ?>">
<div class="theme-filter__head"><strong>Bộ lọc giao diện</strong><button class="filter-reset" data-reset-filters="" type="button">Đặt lại</button></div>
<div class="theme-filter__group">
<label for="themeSearch<?= e($suffix) ?>">Tìm kiếm</label>
<div class="theme-search"><i class="fa-solid fa-magnifying-glass"></i><input data-filter-search="" id="themeSearch<?= e($suffix) ?>" placeholder="Tên mẫu, ngành nghề..." type="search"/></div>
</div>
<div class="theme-filter__group">
<label>Loại website</label>
<div class="filter-checks">
<?php foreach (\App\Enums\TemplateType::cases() as $type): ?><label><input data-filter-type="" type="checkbox" value="<?= e($type->value) ?>"/> <?= e($type->label()) ?></label><?php endforeach; ?>
</div>
</div>
<div class="theme-filter__group">
<label for="industry<?= e($suffix) ?>">Ngành nghề</label>
<select class="form-select" data-filter-industry="" id="industry<?= e($suffix) ?>">
<option value="all">Tất cả ngành nghề</option>
<?php foreach ($industries as $industry): ?><option value="<?= e($industry->slug) ?>"><?= e($industry->name) ?></option><?php endforeach; ?>
</select>
</div>
<div class="theme-filter__group">
<label>Mức đầu tư</label>
<div class="filter-checks">
<label><input checked="" data-filter-price="" name="price<?= e($suffix) ?>" type="radio" value="all"/> Tất cả</label>
<label><input data-filter-price="" name="price<?= e($suffix) ?>" type="radio" value="under10"/> Dưới 10 triệu</label>
<label><input data-filter-price="" name="price<?= e($suffix) ?>" type="radio" value="10to20"/> 10 - 20 triệu</label>
<label><input data-filter-price="" name="price<?= e($suffix) ?>" type="radio" value="over20"/> Trên 20 triệu</label>
</div>
</div>
<div class="theme-filter__group">
<label>Tính năng</label>
<div class="filter-checks">
<?php foreach ($features as $feature): ?><label><input data-filter-feature="" type="checkbox" value="<?= e($feature->slug) ?>"/> <?= e($feature->name) ?></label><?php endforeach; ?>
</div>
</div>
</div>
