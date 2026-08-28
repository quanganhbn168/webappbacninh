<main>
<section class="hero" id="home">
<div class="hero__shape hero__shape--one"></div>
<div class="hero__shape hero__shape--two"></div>
<div class="container position-relative">
<div class="row align-items-center gy-5">
<div class="col-lg-6">
<div class="eyebrow" data-aos="fade-up"><i class="fa-solid fa-location-dot"></i> Thiết kế website tại Bắc Ninh</div>
<h1 data-aos="fade-up" data-aos-delay="80">Website chuyên nghiệp<br/><span>để khách hàng tin và liên hệ.</span></h1>
<p class="hero__lead" data-aos="fade-up" data-aos-delay="140">WebApp Bắc Ninh thiết kế website doanh nghiệp, website bán hàng và landing page theo đúng mục tiêu kinh doanh; dễ quản trị, tối ưu hiển thị và có người đồng hành sau bàn giao.</p>
<div class="hero__actions" data-aos="fade-up" data-aos-delay="200">
<a class="btn btn-primary btn-lg" href="#contact">Nhận tư vấn &amp; báo giá</a>
<a class="btn btn-outline-dark btn-lg" href="#projects">Xem dự án đã làm</a>
</div>
<div class="hero__benefits" data-aos="fade-up" data-aos-delay="260">
<span><i class="fa-solid fa-circle-check"></i> Giao diện phù hợp ngành</span>
<span><i class="fa-solid fa-circle-check"></i> Quản trị dễ sử dụng</span>
<span><i class="fa-solid fa-circle-check"></i> Hỗ trợ lâu dài</span>
</div>
</div>
<div class="col-lg-6">
<div class="hero__visual" data-aos="fade-left" data-aos-delay="120">
<img alt="Thiết kế website và vận hành số cho doanh nghiệp" class="hero__photo" height="900" src="/frontend/assets/images/hero-industrial.webp" width="1600"/>
<div class="hero__screen hero__screen--desktop">
<img alt="Mẫu website doanh nghiệp" height="750" src="/frontend/assets/images/project-corporate.webp" width="1200"/>
</div>
<div class="hero__screen hero__screen--mobile">
<img alt="Mẫu website bán hàng trên điện thoại" height="750" src="/frontend/assets/images/project-ecommerce.webp" width="1200"/>
</div>
<div class="hero__support">
<i class="fa-solid fa-headset"></i>
<span><strong>Không bỏ khách sau bàn giao</strong><small>Hosting, bảo trì, nội dung và SEO</small></span>
</div>
</div>
</div>
</div>
</div>
</section>
<section class="trust-strip">
<div class="container">
<div class="trust-strip__grid">
<div><i class="fa-solid fa-pen-ruler"></i><span><strong>Thiết kế theo nhu cầu</strong><small>Không ép doanh nghiệp vào một mẫu cứng</small></span></div>
<div><i class="fa-solid fa-mobile-screen-button"></i><span><strong>Hiển thị tốt trên mobile</strong><small>Tối ưu trải nghiệm khách hàng Việt Nam</small></span></div>
<div><i class="fa-solid fa-magnifying-glass-chart"></i><span><strong>SEO nền tảng ngay từ đầu</strong><small>Cấu trúc rõ, tốc độ và nội dung chuẩn</small></span></div>
<div><i class="fa-solid fa-screwdriver-wrench"></i><span><strong>Hỗ trợ sau bàn giao</strong><small>Có gói duy trì theo tháng và theo năm</small></span></div>
</div>
</div>
</section>
<section class="section section--light" id="website-types">
<div class="container">
<div class="section-heading text-center" data-aos="fade-up">
<span class="section-kicker">DỊCH VỤ CHÍNH</span>
<h2>Doanh nghiệp đang cần loại website nào?</h2>
<p>Tập trung vào những loại website có nhu cầu rõ, triển khai được nhanh và tạo giá trị trực tiếp cho hoạt động bán hàng.</p>
</div>
<div class="website-grid">
<?php foreach ($featuredServices as $index => $service): ?>
<article class="website-card <?= $index === 0 ? 'website-card--featured' : '' ?>" data-aos="fade-up" data-aos-delay="<?= e((string) ($index * 60)) ?>">
<div class="website-card__image"><img alt="<?= e($service['title']) ?>" height="750" src="<?= e($service['image_url']) ?>" width="1200"/></div>
<div class="website-card__content">
<span class="website-card__number"><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
<h3><?= e($service['eyebrow']) ?></h3><p><?= e($service['description']) ?></p>
<?php if ($index === 0 && !empty($service['pages'])): ?><ul><?php foreach (array_slice($service['pages'], 0, 3) as $page): ?><li><?= e($page) ?></li><?php endforeach; ?></ul><?php endif; ?>
<a href="<?= e(route('services.show', $service['slug'])) ?>"><?= e($service['cta']) ?> <i class="fa-solid fa-arrow-right"></i></a>
</div></article>
<?php endforeach; ?>
</div>
</div>
</section>
<section class="section" id="about">
<div class="container">
<div class="row align-items-center gy-5">
<div class="col-lg-6" data-aos="fade-right">
<div class="about-media">
<img alt="Đội ngũ tư vấn và thiết kế website" height="800" src="/frontend/assets/images/about-bacninh.webp" width="1200"/>
<div class="about-media__note">
<i class="fa-solid fa-location-crosshairs"></i>
<span><strong>Hiểu doanh nghiệp địa phương</strong><small>Trao đổi trực tiếp, làm rõ nhu cầu trước khi báo giá</small></span>
</div>
</div>
</div>
<div class="col-lg-6" data-aos="fade-left">
<span class="section-kicker">KHÔNG CHỈ GIAO MỘT BỘ SOURCE</span>
<h2 class="section-title">Website phải phục vụ công việc kinh doanh sau khi đưa vào sử dụng.</h2>
<p class="section-lead">Một website không hiệu quả thường không phải vì thiếu hiệu ứng. Vấn đề nằm ở nội dung không rõ, khách không biết phải làm gì tiếp theo hoặc doanh nghiệp không có người duy trì sau bàn giao.</p>
<div class="about-points">
<div><i class="fa-solid fa-bullseye"></i><span><strong>Xác định mục tiêu trước</strong><small>Website để giới thiệu năng lực, nhận khách, bán hàng hay hỗ trợ đội kinh doanh?</small></span></div>
<div><i class="fa-solid fa-layer-group"></i><span><strong>Cấu trúc nội dung rõ</strong><small>Sắp xếp dịch vụ, dự án, bảng giá và CTA theo cách khách hàng dễ hiểu.</small></span></div>
<div><i class="fa-solid fa-person-chalkboard"></i><span><strong>Bàn giao và hướng dẫn</strong><small>Doanh nghiệp có thể tự cập nhật; phần khó đã có gói hỗ trợ riêng.</small></span></div>
</div>
<a class="text-link" href="#process">Xem quy trình triển khai <i class="fa-solid fa-arrow-right"></i></a>
</div>
</div>
</div>
</section>
<section class="section section--navy" id="projects">
<div class="container">
<div class="row align-items-end gy-3 mb-5">
<div class="col-lg-8" data-aos="fade-up">
<span class="section-kicker section-kicker--light">DỰ ÁN WEBSITE</span>
<h2 class="section-title section-title--light mb-0">Không chỉ xem ảnh đẹp.<br/>Hãy xem website giải quyết bài toán gì.</h2>
</div>
<div class="col-lg-4 text-lg-end" data-aos="fade-up" data-aos-delay="80">
<a class="btn btn-outline-light" href="#contact">Yêu cầu dự án tương tự</a>
</div>
</div>
<div class="project-grid">
<?php foreach ($featuredProjects as $index => $project): ?>
<article class="project-card <?= $index === 0 ? 'project-card--wide' : '' ?>" data-aos="fade-up" data-aos-delay="<?= e((string) ($index * 60)) ?>">
<a href="<?= e(project_url($project)) ?>"><img alt="<?= e($project['title']) ?>" height="750" src="<?= e($project['image_url']) ?>" width="1200"/></a>
<div class="project-card__body"><span><?= e(strtoupper($project['category_label'])) ?></span><h3><a href="<?= e(project_url($project)) ?>"><?= e($project['title']) ?></a></h3><p><?= e($project['excerpt']) ?></p>
<?php if ($index === 0 && !empty($project['deliverables'])): ?><ul><?php foreach (array_slice($project['deliverables'], 0, 3) as $item): ?><li><?= e($item) ?></li><?php endforeach; ?></ul><?php endif; ?></div>
</article>
<?php endforeach; ?>
</div>
</div>
</section>
<section class="section section--cream" id="pricing">
<div class="container">
<div class="section-heading text-center" data-aos="fade-up">
<span class="section-kicker">GÓI THIẾT KẾ WEBSITE</span>
<h2>Chọn theo mục tiêu, không chọn theo số lượng hiệu ứng.</h2>
<p>Mức giá trong bản mẫu có thể chỉnh lại trước khi đưa lên website chính thức.</p>
</div>
<div class="pricing-grid">
<article class="price-card" data-aos="fade-up">
<div class="price-card__head">
<span>GÓI 01</span>
<h3>Website Khởi đầu</h3>
<p>Dành cho doanh nghiệp cần website giới thiệu cơ bản, rõ ràng và dễ quản trị.</p>
</div>
<div class="price-card__price"><small>Từ</small><strong>8 triệu</strong></div>
<ul>
<li>Giao diện tùy chỉnh theo bộ nhận diện</li>
<li>Trang chủ và các trang nội dung cơ bản</li>
<li>Quản trị bài viết, dịch vụ và thông tin</li>
<li>Responsive, form liên hệ, bản đồ</li>
<li>SEO kỹ thuật nền tảng</li>
<li>Hướng dẫn quản trị và bảo hành</li>
</ul>
<a class="btn btn-outline-primary w-100" href="#contact">Nhận báo giá chi tiết</a>
</article>
<article class="price-card price-card--featured" data-aos="fade-up" data-aos-delay="80">
<div class="price-card__badge">ĐƯỢC QUAN TÂM</div>
<div class="price-card__head">
<span>GÓI 02</span>
<h3>Website Tăng trưởng</h3>
<p>Dành cho doanh nghiệp cần website làm nền tảng bán hàng và phát triển nội dung.</p>
</div>
<div class="price-card__price"><small>Từ</small><strong>15 triệu</strong></div>
<ul>
<li>Thiết kế giao diện riêng theo ngành</li>
<li>Cấu trúc dịch vụ, dự án và bài viết chuyên sâu</li>
<li>Landing page cho dịch vụ trọng điểm</li>
<li>Tracking, Search Console và tối ưu tốc độ</li>
<li>Kế hoạch nội dung ban đầu</li>
<li>Hỗ trợ vận hành sau bàn giao</li>
</ul>
<a class="btn btn-primary w-100" href="#contact">Tư vấn gói phù hợp</a>
</article>
<article class="price-card" data-aos="fade-up" data-aos-delay="140">
<div class="price-card__head">
<span>GÓI 03</span>
<h3>Website Bán hàng / Theo yêu cầu</h3>
<p>Dành cho website có nghiệp vụ riêng, nhiều nhóm sản phẩm hoặc cần kết nối hệ thống.</p>
</div>
<div class="price-card__price"><small>Khảo sát</small><strong>Báo giá riêng</strong></div>
<ul>
<li>Sản phẩm, đơn hàng và phân loại nâng cao</li>
<li>Tích hợp thanh toán hoặc vận chuyển</li>
<li>Phân quyền quản trị</li>
<li>Đa ngôn ngữ hoặc nhiều chi nhánh</li>
<li>API và chức năng theo nghiệp vụ</li>
<li>Chia giai đoạn triển khai rõ ràng</li>
</ul>
<a class="btn btn-outline-primary w-100" href="#contact">Đặt lịch khảo sát</a>
</article>
</div>
<div class="pricing-note" data-aos="fade-up">
<i class="fa-solid fa-circle-info"></i>
<p><strong>Giá cuối cùng phụ thuộc vào nội dung, số trang, chức năng, dữ liệu cần nhập và mức độ thiết kế riêng.</strong> WebApp Bắc Ninh sẽ gửi phạm vi công việc trước khi chốt chi phí.</p>
</div>
</div>
</section>
<section class="section" id="monthly-services">
<div class="container">
<div class="row align-items-end gy-3 mb-5">
<div class="col-lg-7" data-aos="fade-up">
<span class="section-kicker">SAU KHI WEBSITE HOẠT ĐỘNG</span>
<h2 class="section-title mb-0">Có website rồi, ai sẽ duy trì để nó tiếp tục tạo giá trị?</h2>
</div>
<div class="col-lg-5" data-aos="fade-up" data-aos-delay="80">
<p class="section-lead mb-0">Đây là nhóm dịch vụ giúp doanh nghiệp không phải tuyển ngay một đội kỹ thuật và nội dung riêng.</p>
</div>
</div>
<div class="operation-layout">
<div class="operation-list">
<?php foreach ($featuredOperations as $index => $service): ?>
<article data-aos="fade-up" data-aos-delay="<?= e((string) ($index * 50)) ?>">
<span class="operation-list__icon"><i class="<?= e($service['icon']) ?>"></i></span>
<div><h3><a href="<?= e(route('operations.show', $service['slug'])) ?>"><?= e($service['title']) ?></a></h3><p><?= e($service['description']) ?></p></div>
</article>
<?php endforeach; ?>
</div>
<div class="operation-plans" data-aos="fade-left">
<div class="operation-plans__head">
<span>GÓI DUY TRÌ THEO THÁNG</span>
<h3>Không phải mua tất cả.<br/>Chọn đúng phần doanh nghiệp đang thiếu.</h3>
</div>
<div class="operation-plan">
<div><strong>Website Care</strong><small>Bảo trì kỹ thuật, backup và hỗ trợ</small></div>
<span>Theo tháng / năm</span>
</div>
<div class="operation-plan">
<div><strong>Content Website</strong><small>Đăng bài, sản phẩm và cập nhật nội dung</small></div>
<span>Theo khối lượng</span>
</div>
<div class="operation-plan operation-plan--highlight">
<div><strong>Website Growth</strong><small>Website + nội dung + SEO + báo cáo</small></div>
<span>Gói đồng hành</span>
</div>
<a class="btn btn-light w-100" href="#contact">Nhận đề xuất gói duy trì</a>
</div>
</div>
</div>
</section>
<section class="section section--light" id="process">
<div class="container">
<div class="section-heading text-center" data-aos="fade-up">
<span class="section-kicker">QUY TRÌNH TRIỂN KHAI</span>
<h2>Rõ việc trước, rõ chi phí và tiến độ sau.</h2>
</div>
<div class="process-grid">
<article data-aos="fade-up"><span>01</span><i class="fa-regular fa-comments"></i><h3>Tiếp nhận nhu cầu</h3><p>Ngành nghề, khách hàng, mục tiêu, nội dung hiện có và thời gian mong muốn.</p></article>
<article data-aos="fade-up" data-aos-delay="60"><span>02</span><i class="fa-solid fa-sitemap"></i><h3>Đề xuất cấu trúc</h3><p>Sơ đồ trang, chức năng, hướng giao diện và nội dung cần chuẩn bị.</p></article>
<article data-aos="fade-up" data-aos-delay="120"><span>03</span><i class="fa-solid fa-file-signature"></i><h3>Chốt phạm vi</h3><p>Báo giá, tiến độ, đầu việc hai bên và tiêu chí nghiệm thu.</p></article>
<article data-aos="fade-up" data-aos-delay="180"><span>04</span><i class="fa-solid fa-pen-ruler"></i><h3>Thiết kế &amp; phát triển</h3><p>Triển khai giao diện, lập trình chức năng và cập nhật nội dung.</p></article>
<article data-aos="fade-up" data-aos-delay="240"><span>05</span><i class="fa-solid fa-circle-check"></i><h3>Kiểm thử &amp; bàn giao</h3><p>Kiểm tra responsive, form, SEO cơ bản và hướng dẫn quản trị.</p></article>
<article data-aos="fade-up" data-aos-delay="300"><span>06</span><i class="fa-solid fa-headset"></i><h3>Đồng hành vận hành</h3><p>Bảo hành, bảo trì, đăng bài, SEO hoặc nâng cấp theo nhu cầu.</p></article>
</div>
</div>
</section>
<section class="agency" id="agency">
<div class="container position-relative">
<div class="row align-items-center gy-5">
<div class="col-lg-6" data-aos="fade-right">
<span class="section-kicker section-kicker--gold">HỢP TÁC KỸ THUẬT VỚI AGENCY</span>
<h2>Agency có khách hàng.<br/>WebApp Bắc Ninh phụ trách phần kỹ thuật.</h2>
<p>Nhận triển khai website, landing page, hosting, bảo trì hoặc từng module theo hình thức giới thiệu khách, đồng triển khai và white-label.</p>
<div class="agency__actions">
<a class="btn btn-warning btn-lg" href="#contact">Trao đổi chính sách hợp tác</a>
<a class="btn btn-outline-light btn-lg" href="tel:0986123168">Gọi trao đổi trực tiếp</a>
</div>
</div>
<div class="col-lg-6" data-aos="fade-left">
<div class="agency__visual">
<img alt="Hợp tác kỹ thuật với agency" height="900" src="/frontend/assets/images/agency-partnership.webp" width="1600"/>
<div class="agency__promise">
<div><i class="fa-solid fa-user-shield"></i><span><strong>Không giành khách</strong><small>Tôn trọng đầu mối và thương hiệu đối tác</small></span></div>
<div><i class="fa-solid fa-eye-slash"></i><span><strong>Có thể white-label</strong><small>Ẩn thương hiệu WebApp Bắc Ninh khi cần</small></span></div>
<div><i class="fa-solid fa-list-check"></i><span><strong>Rõ phạm vi</strong><small>Tiến độ, đầu việc và chi phí thống nhất trước</small></span></div>
</div>
</div>
</div>
</div>
</div>
</section>
<section class="section software-extension">
<div class="container">
<div class="software-extension__box" data-aos="fade-up">
<div class="software-extension__icon"><i class="fa-solid fa-laptop-code"></i></div>
<div>
<span class="section-kicker">NĂNG LỰC MỞ RỘNG</span>
<h2>Khi website phát sinh nhu cầu quản lý, chúng tôi có thể phát triển tiếp.</h2>
<p>CRM, quản lý khách hàng, báo giá, công việc, booking, đồng bộ dữ liệu và phần mềm theo quy trình riêng. Đây là bước mở rộng sau khi bài toán đã đủ rõ.</p>
</div>
<a class="btn btn-outline-primary" href="#contact">Trao đổi nhu cầu phần mềm</a>
</div>
</div>
</section>
<section class="section section--light" id="knowledge">
<div class="container">
<div class="row align-items-end gy-3 mb-5">
<div class="col-lg-8" data-aos="fade-up">
<span class="section-kicker">KIẾN THỨC WEBSITE</span>
<h2 class="section-title mb-0">Nội dung giúp doanh nghiệp chọn đúng và vận hành website tốt hơn.</h2>
</div>
<div class="col-lg-4 text-lg-end" data-aos="fade-up" data-aos-delay="80">
<a class="text-link" href="#">Xem toàn bộ bài viết <i class="fa-solid fa-arrow-right"></i></a>
</div>
</div>
<div class="article-grid">
<?php foreach ($featuredArticles as $index => $article): ?>
<article class="article-card" data-aos="fade-up" data-aos-delay="<?= e((string) ($index * 70)) ?>">
<a class="article-card__thumb article-card__thumb--<?= e(['one', 'two', 'three'][$index] ?? 'one') ?>" href="<?= e(article_url($article)) ?>" style="background-image:url('<?= e($article['image_url']) ?>')"><i class="fa-solid fa-file-lines"></i></a>
<div class="article-card__body"><span><?= e(strtoupper($article['category_label'])) ?></span><h3><a href="<?= e(article_url($article)) ?>"><?= e($article['title']) ?></a></h3><p><?= e($article['excerpt']) ?></p><a href="<?= e(article_url($article)) ?>">Đọc bài viết <i class="fa-solid fa-arrow-right"></i></a></div>
</article>
<?php endforeach; ?>
</div>
</div>
</section>
<section class="contact" id="contact">
<div class="container">
<div class="contact__shell">
<div class="row g-0">
<div class="col-lg-5">
<div class="contact__info">
<span class="section-kicker section-kicker--gold">NHẬN TƯ VẤN</span>
<h2>Cho chúng tôi biết doanh nghiệp đang cần website như thế nào.</h2>
<p>WebApp Bắc Ninh sẽ làm rõ nhu cầu, đề xuất cấu trúc và gửi mức đầu tư phù hợp thay vì báo một con số chung cho mọi dự án.</p>
<div class="contact__item"><i class="fa-solid fa-phone"></i><span><small>Hotline / Zalo</small><a href="tel:<?= e(site_config('phone_href')) ?>"><?= e(site_config('phone')) ?></a></span></div>
<div class="contact__item"><i class="fa-regular fa-envelope"></i><span><small>Email</small><a href="mailto:<?= e(site_config('email')) ?>"><?= e(site_config('email')) ?></a></span></div>
<div class="contact__item"><i class="fa-solid fa-location-dot"></i><span><small>Khu vực phục vụ</small><strong><?= e(site_config('address')) ?></strong></span></div>
</div>
</div>
<div class="col-lg-7">
<form action="<?= e(route('leads.store')) ?>" method="POST" data-lead-form class="contact__form needs-validation" id="consultForm" novalidate="">
<div class="row g-3">
<div class="col-md-6">
<label class="form-label" for="name">Họ và tên *</label>
<input class="form-control" id="name" name="name" required="" type="text"/>
<div class="invalid-feedback">Anh/chị vui lòng nhập họ tên.</div>
</div>
<div class="col-md-6">
<label class="form-label" for="phone">Số điện thoại *</label>
<input class="form-control" id="phone" name="phone" pattern="^(0|\+84)[0-9]{9,10}$" required="" type="tel"/>
<div class="invalid-feedback">Anh/chị vui lòng nhập số điện thoại hợp lệ.</div>
</div>
<div class="col-md-6">
<label class="form-label" for="business">Doanh nghiệp / lĩnh vực</label>
<input class="form-control" id="business" name="business" type="text"/>
</div>
<div class="col-md-6">
<label class="form-label" for="need">Nhu cầu chính *</label>
<select class="form-select" id="need" name="need" required="">
<option value="">Chọn nhu cầu</option>
<option>Website doanh nghiệp</option>
<option>Website bán hàng</option>
<option>Landing page</option>
<option>Thiết kế lại website cũ</option>
<option>Hosting và bảo trì</option>
<option>SEO và quản trị nội dung</option>
<option>Hợp tác Agency</option>
<option>Phần mềm theo yêu cầu</option>
</select>
<div class="invalid-feedback">Anh/chị vui lòng chọn nhu cầu.</div>
</div>
<div class="col-md-6">
<label class="form-label" for="budget">Ngân sách dự kiến</label>
<select class="form-select" id="budget" name="budget">
<option value="">Chưa xác định</option>
<option>Dưới 10 triệu</option>
<option>10 - 20 triệu</option>
<option>20 - 40 triệu</option>
<option>Trên 40 triệu</option>
</select>
</div>
<div class="col-md-6">
<label class="form-label" for="timeline">Thời gian mong muốn</label>
<select class="form-select" id="timeline" name="timeline">
<option value="">Chưa xác định</option>
<option>Trong 2 tuần</option>
<option>Trong 1 tháng</option>
<option>Trong 2 - 3 tháng</option>
<option>Cần trao đổi thêm</option>
</select>
</div>
<div class="col-12">
<label class="form-label" for="message">Mô tả ngắn nhu cầu</label>
<textarea class="form-control" id="message" name="message" placeholder="Ví dụ: Công ty sản xuất cần website giới thiệu khoảng 6 nhóm sản phẩm, có tiếng Việt và tiếng Anh..." rows="4"></textarea>
</div>
<div class="col-12 d-flex flex-column flex-sm-row align-items-sm-center gap-3">
<button class="btn btn-primary btn-lg" type="submit">Gửi yêu cầu tư vấn</button>
<small class="form-note"><i class="fa-solid fa-lock"></i> Thông tin chỉ dùng để tư vấn dự án.</small>
</div>
</div>
<div class="alert alert-success mt-4 d-none" id="formSuccess" role="alert">
                </div>
</form>
</div>
</div>
</div>
</div>
</section>
</main>




