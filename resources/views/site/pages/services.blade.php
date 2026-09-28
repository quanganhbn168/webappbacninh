@extends('layouts.site')

@section('content')
    <x-hero eyebrow="Dịch vụ WebApp Bắc Ninh" title="Giải pháp dịch vụ toàn diện" highlight="cho doanh nghiệp"
        lead="Chúng tôi cung cấp dịch vụ thiết kế website, phát triển phần mềm, CRM, booking, SEO, quảng cáo và vận hành số, giúp doanh nghiệp tối ưu quy trình, tăng trưởng bền vững với sự đồng hành lâu dài."
        :image="asset('frontend/images/hero-services.webp')" image-alt="Giải pháp website và phần mềm quản trị doanh nghiệp trên máy tính">
        <button class="btn btn-primary" type="button" data-consult="Tư vấn dự án">Nhận tư vấn miễn phí <x-icon name="arrow-right" /></button>
        <a class="btn btn-outline-dark" href="{{ route('about') }}">Xem hồ sơ năng lực</a>
        <x-slot:after>
            <x-trust-row :items="[
                ['icon' => 'rocket', 'title' => 'Triển khai linh hoạt', 'text' => 'Phù hợp nhu cầu doanh nghiệp'],
                ['icon' => 'badge-dollar-sign', 'title' => 'Tối ưu chi phí', 'text' => 'Hiệu quả và bền vững'],
                ['icon' => 'headset', 'title' => 'Hỗ trợ lâu dài', 'text' => 'Đồng hành sau triển khai'],
                ['icon' => 'building-2', 'title' => 'Phù hợp nhiều ngành', 'text' => 'Từ SME đến doanh nghiệp lớn'],
            ]" />
        </x-slot:after>
    </x-hero>
    <x-ad-banners position="after_hero" class="container pt-4" />

    <section class="section" id="dich-vu">
        <div class="container">
            <x-section-head title="Dịch vụ của chúng tôi" lead="Đa dạng giải pháp, đáp ứng mọi nhu cầu chuyển đổi số của doanh nghiệp." />
            @include('site.partials.service-grid')
        </div>
    </section>

    <section class="section section--soft">
        <div class="container">
            <x-section-head title="Dịch vụ nổi bật" lead="Những dịch vụ được nhiều doanh nghiệp lựa chọn nhất tại WebApp Bắc Ninh." />
            <div class="feature-rows">
                <x-feature-row id="thiet-ke-website" :image="asset('frontend/images/service-website.webp')" title="Thiết kế Website theo ngành"
                    text="Thiết kế website chuyên nghiệp, phù hợp đặc thù từng ngành nghề, chuẩn SEO và tối ưu trải nghiệm người dùng." note="Website chuyên nghiệp – Tạo lợi thế cạnh tranh!">
                    <ul class="check-list check-list--gold">
                        <li>Giao diện hiện đại, chuẩn thương hiệu</li>
                        <li>Tối ưu hiển thị trên mọi thiết bị</li>
                        <li>Tích hợp tính năng theo ngành nghề</li>
                        <li>Chuẩn SEO, tốc độ tải nhanh</li>
                        <li>Hỗ trợ nội dung và hình ảnh</li>
                    </ul>
                    <div class="btn-row"><a class="btn btn-outline-primary" href="{{ route('services.index') }}">Xem dịch vụ thiết kế website</a></div>
                </x-feature-row>
                <x-feature-row id="phan-mem" reversed :image="asset('frontend/images/service-software.webp')" title="Phần mềm doanh nghiệp theo yêu cầu"
                    text="Phát triển phần mềm quản lý, hệ thống nội bộ theo quy trình riêng của doanh nghiệp, giúp tối ưu vận hành và tăng hiệu suất.">
                    <ul class="check-list check-list--gold">
                        <li>Phân tích và thiết kế theo nhu cầu thực tế</li>
                        <li>Ứng dụng công nghệ hiện đại, bảo mật cao</li>
                        <li>Tích hợp dễ dàng với hệ thống hiện có</li>
                        <li>Mở rộng linh hoạt theo quy mô doanh nghiệp</li>
                        <li>Hỗ trợ và bảo trì lâu dài</li>
                    </ul>
                    <div class="btn-row"><button class="btn btn-outline-primary" type="button" data-consult="Phần mềm doanh nghiệp theo yêu cầu">Nhận tư vấn</button></div>
                </x-feature-row>
                <x-feature-row id="crm-booking" :image="asset('frontend/images/service-crm.webp')" title="CRM / Booking cho doanh nghiệp dịch vụ"
                    text="Giải pháp quản lý khách hàng và đặt lịch thông minh, giúp doanh nghiệp tự động hóa quy trình, tăng tỷ lệ chuyển đổi." note="Quản lý khách hàng – Dễ dàng hơn mỗi ngày!">
                    <ul class="check-list check-list--gold">
                        <li>Quản lý khách hàng tập trung</li>
                        <li>Đặt lịch online, nhắc lịch tự động</li>
                        <li>Theo dõi doanh thu, hiệu suất nhân viên</li>
                        <li>Tích hợp Zalo, Email, SMS</li>
                        <li>Hỗ trợ spa, phòng khám, giáo dục, dịch vụ…</li>
                    </ul>
                    <div class="btn-row"><a class="btn btn-outline-primary" href="{{ route('products') }}?loai=crm">Xem sản phẩm CRM</a></div>
                </x-feature-row>
                <x-feature-row id="seo-quang-cao" reversed :image="asset('frontend/images/service-marketing.webp')" title="SEO, quảng cáo & vận hành số"
                    text="Tăng trưởng bền vững với các giải pháp SEO, quảng cáo đa kênh và vận hành số toàn diện.">
                    <ul class="check-list check-list--gold">
                        <li>Tối ưu SEO tổng thể, lên top bền vững</li>
                        <li>Triển khai quảng cáo Google, Facebook, Zalo</li>
                        <li>Thiết lập hệ thống đo lường, tracking</li>
                        <li>Tư vấn chiến lược nội dung và marketing</li>
                        <li>Hỗ trợ vận hành, tối ưu liên tục</li>
                    </ul>
                    <div class="btn-row"><a class="btn btn-outline-primary" href="{{ route('operations.index') }}">Xem dịch vụ vận hành</a></div>
                </x-feature-row>
            </div>
        </div>
    </section>

    @if ($landingServices->isNotEmpty() || $operationServices->isNotEmpty())
        <section class="section">
            <div class="container">
                <x-section-head title="Gói dịch vụ chi tiết" lead="Xem phạm vi, quy trình và chi phí của từng gói trước khi trao đổi." />
                <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                    @foreach ($landingServices->concat($operationServices) as $service)
                        <div class="col"><x-feature-card :icon="$service->icon ?: 'layers'" :title="$service->title" :text="$service->price_label" :href="$service->url" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('site.partials.agency-band', [
        'title' => 'Dành cho Agency / White Label',
        'text' => 'WebApp Bắc Ninh sẵn sàng hợp tác cùng các Agency, Studio, Media… với mô hình White Label, Outsourcing hoặc đối tác kỹ thuật, giúp bạn mở rộng năng lực và nâng cao chất lượng dịch vụ.',
        'benefits' => ['Bàn giao minh bạch, rõ ràng', 'Hỗ trợ backend Laravel, API', 'Mở rộng năng lực kỹ thuật', 'Đảm bảo tiến độ & chất lượng'],
    ])

    <section class="section" id="quy-trinh">
        <div class="container">
            <x-section-head title="Quy trình triển khai dịch vụ" lead="Quy trình làm việc rõ ràng, minh bạch, đảm bảo tiến độ và chất lượng." />
            <x-steps :items="[
                ['clipboard-list', 'Tư vấn & khảo sát', 'Tiếp nhận nhu cầu, phân tích mục tiêu và khảo sát chi tiết.'],
                ['lightbulb', 'Đề xuất giải pháp', 'Tư vấn giải pháp phù hợp và báo giá chi tiết.'],
                ['code', 'Thiết kế & phát triển', 'Triển khai thiết kế, lập trình theo kế hoạch.'],
                ['shield-check', 'Kiểm thử & bàn giao', 'Kiểm tra, nghiệm thu và hướng dẫn sử dụng.'],
                ['headset', 'Hỗ trợ & tối ưu', 'Đồng hành, bảo trì và nâng cấp liên tục.'],
            ]" />
        </div>
    </section>

    @if ($plans->isNotEmpty())
        <section class="section section--soft" id="bang-gia">
            <div class="container">
                <x-section-head title="Chi phí tham khảo" lead="Bảng giá mang tính tham khảo, có thể điều chỉnh theo yêu cầu cụ thể của từng dự án." :href="route('pricing')" link="Xem bảng giá chi tiết" />
                <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-xl-4">
                    @foreach ($plans as $plan)
                        <div class="col"><x-card.price :plan="$plan" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section" id="cau-hoi">
        <div class="container">
            <x-section-head title="Câu hỏi thường gặp" lead="Một số câu hỏi phổ biến về dịch vụ của WebApp Bắc Ninh." />
            <x-faq :items="[
                ['Thời gian triển khai bao lâu?', 'Thời gian cụ thể phụ thuộc vào phạm vi, số lượng giao diện và tính năng. Sau khi thống nhất yêu cầu, hai bên sẽ chốt tiến độ theo từng hạng mục trong báo giá và hợp đồng.'],
                ['Bàn giao source code như thế nào?', 'Phạm vi bàn giao source code, cơ sở dữ liệu, tài liệu và thông tin triển khai được thống nhất theo hợp đồng.'],
                ['Có hỗ trợ nội dung không?', 'Có thể hỗ trợ biên tập và nhập nội dung theo phạm vi đã thống nhất. Các hạng mục nội dung bổ sung sẽ được báo giá riêng trước khi triển khai.'],
                ['Có hỗ trợ vận hành sau bàn giao không?', 'Gói hỗ trợ, thời hạn và mức độ hỗ trợ phụ thuộc vào dịch vụ đã lựa chọn. Các nhu cầu bảo trì, cập nhật và vận hành định kỳ có thể được thống nhất riêng.'],
                ['Có thể nâng cấp thêm module không?', 'Có thể khảo sát và bổ sung các module như booking, CRM, bán hàng hoặc tích hợp API theo kiến trúc hệ thống và yêu cầu thực tế.'],
            ]" />
        </div>
    </section>

    <x-cta-banner title="Sẵn sàng bắt đầu dự án của bạn?" text="Hãy để WebApp Bắc Ninh đồng hành cùng bạn trên hành trình chuyển đổi số." />
@endsection
