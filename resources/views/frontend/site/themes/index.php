<main>
<section class="theme-hero">
<div class="container">
<nav aria-label="breadcrumb" class="theme-breadcrumb" data-aos="fade-up">
<ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="<?= e(route('home')) ?>">Trang chủ</a></li><li aria-current="page" class="breadcrumb-item active">Kho giao diện</li></ol>
</nav>
<div class="row align-items-center gy-4">
<div class="col-lg-7">
<span class="section-kicker" data-aos="fade-up">KHO GIAO DIỆN WEBSITE</span>
<h1 data-aos="fade-up" data-aos-delay="70">Chọn mẫu phù hợp.<br/><span>Chúng tôi tùy chỉnh theo doanh nghiệp.</span></h1>
<p data-aos="fade-up" data-aos-delay="130">Kho giao diện giúp anh/chị hình dung nhanh phong cách website trước khi triển khai. Mỗi mẫu có thể thay màu sắc, hình ảnh, nội dung, bố cục và bổ sung chức năng theo nhu cầu thực tế.</p>
<div class="theme-hero__actions" data-aos="fade-up" data-aos-delay="190">
<a class="btn btn-primary btn-lg" href="#themeGrid">Xem các mẫu giao diện</a>
<a class="btn btn-outline-dark btn-lg" href="#themeContact">Chưa biết chọn mẫu nào?</a>
</div>
</div>
<div class="col-lg-5">
<div class="theme-hero__stats" data-aos="fade-left">
<div><strong><?= e((string) $themes->count()) ?></strong><span>Mẫu minh họa</span></div>
<div><strong><?= e((string) $industries->count()) ?></strong><span>Nhóm ngành nghề</span></div>
<div><strong>100%</strong><span>Tùy chỉnh nội dung</span></div>
<div><strong>Mobile</strong><span>Tối ưu responsive</span></div>
</div>
</div>
</div>
</div>
</section>
<section class="theme-categories">
<div class="container">
<div class="theme-categories__track" id="quickCategories">
<button class="quick-category is-active" data-quick-category="all"><i class="fa-solid fa-border-all"></i><span>Tất cả</span></button>
<button class="quick-category" data-quick-category="doanh-nghiep"><i class="fa-solid fa-building"></i><span>Doanh nghiệp</span></button>
<button class="quick-category" data-quick-category="ban-hang"><i class="fa-solid fa-cart-shopping"></i><span>Bán hàng</span></button>
<button class="quick-category" data-quick-category="landing-page"><i class="fa-solid fa-bullseye"></i><span>Landing page</span></button>
<button class="quick-category" data-quick-category="du-lich"><i class="fa-solid fa-plane"></i><span>Du lịch</span></button>
<button class="quick-category" data-quick-category="giao-duc"><i class="fa-solid fa-graduation-cap"></i><span>Giáo dục</span></button>
<button class="quick-category" data-quick-category="noi-that"><i class="fa-solid fa-couch"></i><span>Nội thất</span></button>
<button class="quick-category" data-quick-category="dich-vu"><i class="fa-solid fa-briefcase"></i><span>Dịch vụ</span></button>
</div>
</div>
</section>
<section class="theme-library section--light" id="themeGrid">
<div class="container">
<div class="theme-toolbar">
<div>
<span class="section-kicker">TÌM GIAO DIỆN PHÙ HỢP</span>
<h2>Kho giao diện theo ngành và mục tiêu sử dụng</h2>
</div>
<button class="btn btn-outline-primary d-lg-none" data-bs-target="#filterCanvas" data-bs-toggle="offcanvas" aria-controls="filterCanvas" type="button"><i class="fa-solid fa-sliders"></i> Bộ lọc</button>
</div>
<div class="row g-4 align-items-start">
<aside class="col-lg-3 d-none d-lg-block">
<?php echo view('frontend.site.themes.filters', ['suffix' => 'Desktop', 'industries' => $industries, 'features' => $features]); ?>
</aside>
<div class="col-lg-9">
<div class="theme-resultbar">
<div><strong id="resultCount">0</strong> giao diện phù hợp <span id="activeFilterText"></span></div>
<div class="theme-sort"><label for="themeSort">Sắp xếp</label><select class="form-select" id="themeSort"><option value="featured">Nổi bật</option><option value="newest">Mới nhất</option><option value="price-asc">Giá thấp đến cao</option><option value="price-desc">Giá cao đến thấp</option><option value="name-asc">Tên A - Z</option></select></div>
</div>
<div class="active-filters d-none" id="activeFilters"></div>
<div aria-live="polite" class="theme-grid" id="themeList"><?php foreach ($themes as $theme): ?><?php echo view('frontend.site.components.theme-card', compact('theme')); ?><?php endforeach; ?></div>
<div class="theme-empty d-none" id="themeEmpty"><i class="fa-regular fa-folder-open"></i><h3>Chưa có mẫu phù hợp</h3><p>Hãy bỏ bớt bộ lọc hoặc gửi nhu cầu để WebApp Bắc Ninh tư vấn mẫu gần nhất.</p><button class="btn btn-primary" data-reset-filters="" type="button">Đặt lại bộ lọc</button></div>
<nav aria-label="Phân trang kho giao diện" class="theme-pagination"><ul class="pagination justify-content-center" id="themePagination"></ul></nav>
</div>
</div>
</div>
</section>
<section class="theme-note">
<div class="container">
<div class="theme-note__box" data-aos="fade-up">
<div class="theme-note__icon"><i class="fa-solid fa-pen-ruler"></i></div>
<div><span class="section-kicker">KHÔNG PHẢI MẪU ĐÓNG KHUNG</span><h2>Mỗi giao diện đều được tùy chỉnh theo nhận diện và nội dung của doanh nghiệp.</h2><p>Kho giao diện chỉ giúp rút ngắn thời gian chọn phong cách. Website bàn giao vẫn được thay logo, màu sắc, hình ảnh, bố cục nội dung, danh mục và chức năng phù hợp với dự án thực tế.</p></div>
<a class="btn btn-primary" href="#themeContact">Trao đổi nhu cầu</a>
</div>
</div>
</section>
<section class="theme-contact" id="themeContact">
<div class="container">
<div class="theme-contact__shell">
<div>
<span class="section-kicker section-kicker--gold">NHẬN TƯ VẤN MẪU</span>
<h2>Gửi mã giao diện hoặc mô tả phong cách anh/chị thích.</h2>
<p>Chúng tôi sẽ kiểm tra nhu cầu, đề xuất mẫu phù hợp và báo chi phí tùy chỉnh theo nội dung, tính năng và tiến độ.</p>
<div class="theme-contact__points"><span><i class="fa-solid fa-check"></i> Không bắt buộc dùng nguyên mẫu</span><span><i class="fa-solid fa-check"></i> Có thể kết hợp nhiều bố cục</span><span><i class="fa-solid fa-check"></i> Tư vấn theo ngân sách thực tế</span></div>
</div>
<form action="<?= e(route('leads.store')) ?>" method="POST" data-lead-form class="theme-contact__form needs-validation" id="themeForm" novalidate>
<div class="row g-3">
<div class="col-md-6"><label class="form-label" for="themeName">Họ và tên *</label><input class="form-control" id="themeName" name="name" required=""/></div>
<div class="col-md-6"><label class="form-label" for="themePhone">Số điện thoại *</label><input class="form-control" id="themePhone" name="phone" required="" type="tel"/></div>
<div class="col-md-6"><label class="form-label" for="themeCode">Mã giao diện</label><input class="form-control" id="themeCode" name="need" placeholder="Ví dụ: WABN-001"/></div>
<div class="col-md-6"><label class="form-label" for="themeIndustry">Lĩnh vực</label><input class="form-control" id="themeIndustry" name="business" placeholder="Sản xuất, nội thất, giáo dục..."/></div>
<div class="col-12"><label class="form-label" for="themeMessage">Nhu cầu bổ sung</label><textarea class="form-control" id="themeMessage" name="message" placeholder="Mô tả màu sắc, trang cần có hoặc website tham khảo..." rows="3"></textarea></div>
<div class="col-12"><button class="btn btn-light btn-lg" type="submit">Gửi yêu cầu tư vấn</button></div>
</div>
<div class="alert alert-success mt-3 d-none" id="themeFormSuccess"></div>
</form>
</div>
</div>
</section>

<div class="offcanvas offcanvas-start" tabindex="-1" id="filterCanvas" aria-labelledby="filterCanvasTitle">
<div class="offcanvas-header"><h2 class="offcanvas-title h5" id="filterCanvasTitle">Bộ lọc giao diện</h2><button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Đóng"></button></div>
<div class="offcanvas-body"><?php echo view('frontend.site.themes.filters', ['suffix' => 'Mobile', 'industries' => $industries, 'features' => $features]); ?></div>
</div>
<div class="modal fade" id="themeQuickView" tabindex="-1" aria-label="Xem nhanh giao diện" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content"><div class="modal-header border-0 pb-0"><button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Đóng"></button></div><div class="modal-body" id="themeModalBody"></div></div></div>
</div>
<script type="application/json" id="themesJson"><?= json_encode($themes->map(static function (\App\Models\Template $theme): array {
  return [
    'id' => $theme->id,
    'code' => $theme->code,
    'name' => $theme->name,
    'typeLabel' => $theme->type_label,
    'industryLabel' => $theme->industry_label,
    'price' => $theme->price,
    'duration' => $theme->duration,
    'description' => $theme->description,
    'tags' => $theme->tags ?? [],
    'imageUrl' => $theme->image_url,
    'detailUrl' => $theme->url,
  ];
})->all(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>

</main>




