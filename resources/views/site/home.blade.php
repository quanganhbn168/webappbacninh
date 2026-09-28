@extends('layouts.site')

@section('content')
    <x-hero variant="home" eyebrow="Giải pháp số toàn diện cho doanh nghiệp" title="Website – Phần mềm –" highlight="Vận hành số cho doanh nghiệp"
        lead="Chúng tôi thiết kế website, landing page, phát triển phần mềm, CRM, booking và đồng hành vận hành số cùng doanh nghiệp với giải pháp tối ưu, hiện đại và hỗ trợ lâu dài."
        :image="asset('frontend/images/hero-home.webp')" image-alt="Giải pháp website và phần mềm quản trị doanh nghiệp trên máy tính">
        <a class="btn btn-primary" href="{{ route('products') }}">Xem Demo <x-icon name="arrow-right" /></a>
        <button class="btn btn-outline-dark" type="button" data-consult="Tư vấn dự án">Nhận tư vấn miễn phí</button>
        <x-slot:after>
            <x-trust-row :items="[
                ['icon' => 'shield-check', 'title' => 'Uy tín - Chuyên nghiệp'],
                ['icon' => 'users-round', 'title' => 'Đồng hành lâu dài'],
                ['icon' => 'trending-up', 'title' => 'Giải pháp tối ưu chi phí'],
                ['icon' => 'headset', 'title' => 'Hỗ trợ nhanh chóng'],
            ]" />
        </x-slot:after>
    </x-hero>
    <x-ad-banners position="homepage_hero" class="container pt-4" />

    <section class="section" id="dich-vu">
        <div class="container">
            <x-section-head title="Dịch vụ nổi bật" lead="Giải pháp toàn diện từ website, phần mềm đến vận hành số, đáp ứng mọi nhu cầu của doanh nghiệp." :href="route('services.overview')" link="Xem tất cả dịch vụ" />
            @include('site.partials.service-grid')
        </div>
    </section>

    @if ($products->isNotEmpty())
        <section class="section section--soft" id="san-pham">
            <div class="container">
                <x-section-head title="Sản phẩm & giải pháp đóng gói" lead="Các giải pháp được đóng gói sẵn, triển khai nhanh, phù hợp nhiều ngành nghề." :href="route('products')" link="Xem tất cả sản phẩm" />
                <div class="product-strip">
                    @foreach ($products as $product)
                        <x-card.product :product="$product" variant="tile" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-ad-banners position="homepage_promo" class="container pt-5" />

    @if ($projects->isNotEmpty())
        <section class="section" id="du-an">
            <div class="container">
                <x-section-head title="Dự án tiêu biểu" lead="Những dự án chúng tôi đã triển khai, đồng hành cùng sự phát triển của khách hàng." :href="route('projects.index')" link="Xem tất cả dự án" />
                <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                    @foreach ($projects as $project)
                        <div class="col"><x-card.project :project="$project" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($plans->isNotEmpty())
        <section class="section section--soft" id="bang-gia">
            <div class="container">
                <x-section-head title="Bảng giá tham khảo" lead="Chi phí minh bạch, phù hợp với nhu cầu. Cam kết không phát sinh chi phí ẩn." :href="route('pricing')" link="Xem bảng giá chi tiết" />
                <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-xl-4">
                    @foreach ($plans as $plan)
                        <div class="col"><x-card.price :plan="$plan" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('site.partials.agency-band')

    <section class="section" id="quy-trinh">
        <div class="container">
            <x-section-head title="Quy trình triển khai" lead="Quy trình làm việc rõ ràng, minh bạch, đảm bảo tiến độ và chất lượng." :href="route('services.overview').'#quy-trinh'" link="Tìm hiểu chi tiết" />
            @include('site.partials.process')
        </div>
    </section>

    <section class="section" id="module">
        <div class="container">
            <x-section-head title="Mở rộng theo module" lead="Dễ dàng nâng cấp và mở rộng tính năng theo nhu cầu thực tế." />
            @include('site.partials.modules')
        </div>
    </section>

    @if ($testimonials->isNotEmpty())
        <section class="section" id="danh-gia">
            <div class="container">
                <x-section-head title="Khách hàng nói về chúng tôi" />
                <div class="row g-4 row-cols-1 row-cols-md-3">
                    @foreach ($testimonials as $testimonial)
                        <div class="col"><x-card.quote :testimonial="$testimonial" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-ad-banners position="before_blog" class="container" />

    @if ($posts->isNotEmpty())
        <section class="section" id="kien-thuc">
            <div class="container">
                <x-section-head title="Kiến thức & chia sẻ" lead="Cập nhật những bài viết hữu ích về website, marketing và chuyển đổi số." :href="route('articles.index')" link="Xem tất cả bài viết" />
                <div class="row g-4">
                    <div class="col-lg-6"><x-card.post :post="$posts->first()" variant="featured" /></div>
                    <div class="col-lg-6 d-grid gap-3">
                        @foreach ($posts->skip(1) as $post)
                            <x-card.post :post="$post" variant="row" />
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <x-cta-banner title="Sẵn sàng bắt đầu dự án của bạn ngay hôm nay?" text="Chúng tôi luôn sẵn sàng tư vấn và đồng hành cùng doanh nghiệp của bạn." />
@endsection
