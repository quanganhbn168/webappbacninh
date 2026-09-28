@extends('layouts.site')

@section('content')
    <x-hero title="Giải pháp số phù hợp" highlight="với từng doanh nghiệp"
        lead="Chúng tôi kết nối website, phần mềm và vận hành thành giải pháp tổng thể, phù hợp với đặc thù từng ngành nghề, giúp doanh nghiệp tăng trưởng bền vững trong kỷ nguyên số."
        :image="asset('frontend/images/solutions-hero.webp')" image-alt="Giải pháp số cho doanh nghiệp">
        <a class="btn btn-primary" href="#giai-phap">Tìm giải pháp phù hợp <x-icon name="arrow-right" /></a>
        <a class="btn btn-outline-dark" href="{{ route('products') }}">Xem sản phẩm</a>
        <x-slot:after>
            <x-trust-row :items="[
                ['icon' => 'target', 'title' => 'Đúng bài toán', 'text' => 'Hiểu nhu cầu, tư vấn chính xác'],
                ['icon' => 'rocket', 'title' => 'Triển khai linh hoạt', 'text' => 'Phù hợp quy mô và ngân sách'],
                ['icon' => 'network', 'title' => 'Kết nối đồng bộ', 'text' => 'Website – Phần mềm – Vận hành'],
                ['icon' => 'handshake', 'title' => 'Đồng hành lâu dài', 'text' => 'Hỗ trợ, tư vấn và phát triển'],
            ]" />
        </x-slot:after>
    </x-hero>

    <section class="section" id="giai-phap">
        <div class="container" data-catalog>
            <x-section-head title="Giải pháp theo lĩnh vực" lead="Chúng tôi cung cấp các giải pháp chuyên biệt, phù hợp với đặc thù từng ngành nghề và mô hình doanh nghiệp." />
            @php($industries = [
                ['spa', 'Spa & Thẩm mỹ', 'spa-service.webp', 'Giải pháp website, đặt lịch spa, quản lý khách hàng và chăm sóc tự động cho spa, thẩm mỹ viện.', ['Website', 'Đặt lịch', 'CRM']],
                ['travel', 'Du lịch & Khách sạn', 'resort.webp', 'Website đặt phòng, quản lý dịch vụ, kết nối kênh OTA và nâng cao trải nghiệm khách hàng.', ['Website', 'Booking', 'Quản lý dịch vụ']],
                ['retail', 'Bán lẻ & Thương mại', 'retail.webp', 'Giải pháp bán hàng online, quản lý kho, khách hàng và đa kênh hiệu quả.', ['Website', 'Bán hàng', 'Quản lý kho']],
                ['company', 'Doanh nghiệp & Sản xuất', 'factory.webp', 'Số hóa quy trình, quản lý nội bộ, khách hàng và vận hành hiệu quả cho doanh nghiệp.', ['Website', 'Phần mềm', 'ERP']],
                ['education', 'Giáo dục & Đào tạo', 'education.webp', 'Website tuyển sinh, quản lý học viên, lớp học và học phí, hỗ trợ dạy học trực tuyến.', ['Website', 'Quản lý học viên', 'E-Learning']],
                ['agency', 'Agency & Đơn vị dịch vụ', 'agency.webp', 'Giải pháp quản lý dự án, khách hàng, nhân sự và tăng hiệu suất cho agency, studio, media.', ['Website', 'CRM', 'Quản lý dự án']],
            ])
            <div class="chip-list mb-4" role="group" aria-label="Lọc theo lĩnh vực">
                <button class="chip active" type="button" data-catalog-filter="all" aria-pressed="true">Tất cả</button>
                @foreach ($industries as [$key, $name])
                    <button class="chip" type="button" data-catalog-filter="{{ $key }}" aria-pressed="false">{{ $name }}</button>
                @endforeach
            </div>
            <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                @foreach ($industries as [$key, $name, $image, $text, $tags])
                    <div class="col" data-catalog-item data-categories="{{ $key }}" data-title="{{ $name }}">
                        <article class="media-card">
                            <div class="media-card__image ratio-card"><img class="img-cover" src="{{ asset('frontend/images/'.$image) }}" alt="{{ $name }}" width="640" height="400" loading="lazy"></div>
                            <div class="media-card__body">
                                <h3 class="media-card__title">{{ $name }}</h3>
                                <p>{{ $text }}</p>
                                <div class="media-card__tags">@foreach ($tags as $tag)<span class="tag">{{ $tag }}</span>@endforeach</div>
                                <button class="btn btn-link p-0 mt-auto align-self-start" type="button" data-consult="Giải pháp {{ $name }}">Khám phá giải pháp <x-icon name="arrow-right" /></button>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--cream">
        <div class="container">
            <x-section-head title="Bắt đầu từ bài toán doanh nghiệp của bạn" lead="Chúng tôi đồng hành cùng bạn giải quyết những bài toán cốt lõi, tạo nền tảng phát triển bền vững." />
            <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                @foreach ([
                    ['monitor', 'Xây dựng hiện diện số', 'Thiết kế website chuyên nghiệp, chuẩn SEO, thể hiện đúng thương hiệu.'],
                    ['users', 'Quản lý khách hàng', 'Lưu trữ, phân loại và chăm sóc khách hàng tự động, tăng tỷ lệ chuyển đổi.'],
                    ['calendar-days', 'Tối ưu lịch hẹn', 'Đặt lịch online, quản lý lịch hẹn thông minh, giảm tải vận hành.'],
                    ['settings', 'Vận hành hiệu quả', 'Kết nối dữ liệu, tự động hóa quy trình, tối ưu chi phí và nguồn lực.'],
                ] as [$icon, $title, $text])
                    <div class="col"><x-feature-card :icon="$icon" :title="$title" :text="$text" /></div>
                @endforeach
            </div>
        </div>
    </section>

    @if ($projects->isNotEmpty())
        <section class="section">
            <div class="container">
                <x-section-head title="Dự án đã triển khai" lead="Một số giải pháp đang được khách hàng sử dụng." :href="route('projects.index')" link="Xem tất cả dự án" />
                <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                    @foreach ($projects as $project)
                        <div class="col"><x-card.project :project="$project" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section">
        <div class="container">
            <x-section-head title="Mở rộng linh hoạt theo module" lead="Dễ dàng nâng cấp và mở rộng tính năng theo nhu cầu thực tế của doanh nghiệp." />
            @include('site.partials.modules')
        </div>
    </section>

    <section class="section section--soft">
        <div class="container">
            <x-section-head title="Từ nhu cầu đến giải pháp hoàn chỉnh" lead="Quy trình làm việc rõ ràng, minh bạch, đảm bảo tiến độ và chất lượng." />
            @include('site.partials.process')
        </div>
    </section>

    <x-cta-banner title="Chưa tìm thấy giải pháp đúng với nhu cầu?" text="Chúng tôi luôn sẵn sàng tư vấn và đồng hành cùng doanh nghiệp của bạn." />
@endsection
