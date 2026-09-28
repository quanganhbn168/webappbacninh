@extends('layouts.site')

@section('content')
    <x-hero title="Liên hệ với chúng tôi" highlight="Cùng bắt đầu dự án của bạn"
        lead="Chúng tôi luôn sẵn sàng lắng nghe và đồng hành cùng bạn trong việc xây dựng website, phát triển phần mềm và triển khai các giải pháp vận hành số hiệu quả cho doanh nghiệp."
        :image="asset('frontend/images/contact-hero.webp')" image-alt="Tư vấn viên trao đổi với khách hàng">
        <a class="btn btn-primary" href="#gui-yeu-cau">Trao đổi về dự án <x-icon name="arrow-right" /></a>
        @if (site_config('phone_href'))
            <a class="btn btn-outline-dark" href="tel:{{ site_config('phone_href') }}"><x-icon name="phone" /> {{ site_config('phone') }}</a>
        @endif
    </x-hero>

    <section class="section pb-0">
        <div class="container">
            <h2 class="visually-hidden">Thông tin liên hệ</h2>
            <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                <div class="col"><x-feature-card icon="phone" title="Hotline tư vấn" :href="'tel:'.site_config('phone_href')" class="feature-card--row"><p class="text-dark fw-semibold">{{ site_config('phone') }}</p>@if (site_config('phone_secondary'))<p>{{ site_config('phone_secondary') }}</p>@endif</x-feature-card></div>
                <div class="col"><x-feature-card icon="mail" title="Email liên hệ" :href="'mailto:'.site_config('email')" class="feature-card--row"><p class="text-dark fw-semibold text-break">{{ site_config('email') }}</p><p>Phản hồi trong 24h</p></x-feature-card></div>
                <div class="col"><x-feature-card icon="map-pin" title="Khu vực làm việc" :href="'https://www.google.com/maps/search/?api=1&query='.rawurlencode((string) site_config('address'))" class="feature-card--row" target="_blank" rel="noopener"><p class="text-dark fw-semibold">{{ site_config('address') }}</p><p>Hỗ trợ toàn quốc</p></x-feature-card></div>
                <div class="col"><x-feature-card icon="clock" title="Thời gian làm việc" class="feature-card--row"><p class="text-dark fw-semibold">{{ site_config('working_time') ?: 'Thứ 2 – Thứ 7' }}</p></x-feature-card></div>
            </div>
        </div>
    </section>

    <section class="section" id="gui-yeu-cau">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 h-100">
                        <h2>Gửi yêu cầu tư vấn</h2>
                        <p class="text-muted">Hãy để lại thông tin, chúng tôi sẽ liên hệ và tư vấn giải pháp phù hợp nhất cho doanh nghiệp của bạn.</p>
                        <x-lead-form id="contact" :need-options="['Thiết kế Website', 'Landing Page', 'CRM & Phần mềm', 'Booking System', 'Hosting / Domain / Email', 'SEO & Marketing', 'Chăm sóc & Vận hành', 'Hợp tác Agency / White Label', 'Tư vấn giải pháp']" />
                    </div>
                </div>
                <div class="col-lg-5 d-grid gap-4 align-content-start">
                    <div class="lead-panel__copy rounded-4">
                        <p class="eyebrow">Trao đổi nhanh</p>
                        <h2 class="h3">Bạn cần trao đổi nhanh?</h2>
                        <p class="mb-0">Liên hệ ngay qua Zalo, điện thoại hoặc email để được hỗ trợ nhanh chóng.</p>
                        <div class="lead-panel__contacts">
                            @if (site_config('zalo'))
                                <a href="{{ site_config('zalo') }}" target="_blank" rel="noopener"><x-icon name="brand-zalo" /><span><small>Zalo</small><strong>Nhắn Zalo</strong></span></a>
                            @endif
                            <a href="tel:{{ site_config('phone_href') }}"><x-icon name="phone" /><span><small>Hotline</small><strong>{{ site_config('phone') }}</strong></span></a>
                            <a href="mailto:{{ site_config('email') }}"><x-icon name="mail" /><span><small>Email</small><strong class="text-break">{{ site_config('email') }}</strong></span></a>
                        </div>
                    </div>
                    <div class="feature-card">
                        <h2 class="h3 mb-0">Kết nối với WebApp Bắc Ninh</h2>
                        <p>Chúng tôi luôn sẵn sàng gặp gỡ, trao đổi trực tiếp hoặc kết nối online để tư vấn giải pháp phù hợp nhất.</p>
                        <a class="link-arrow" href="https://www.google.com/maps/search/?api=1&query={{ rawurlencode((string) site_config('address')) }}" target="_blank" rel="noopener">Xem vị trí trên bản đồ <x-icon name="arrow-right" /></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--soft">
        <div class="container">
            <x-section-head title="Bạn muốn trao đổi về nội dung nào?" lead="Chọn chủ đề phù hợp để chúng tôi hỗ trợ bạn nhanh chóng và đúng nhu cầu hơn." />
            <div class="row g-4 row-cols-1 row-cols-md-3">
                @foreach ([
                    ['rocket', 'Bắt đầu dự án mới', 'Tư vấn website, phần mềm hoặc giải pháp chuyển đổi số cho doanh nghiệp.', 'Trao đổi ngay', 'Dự án mới'],
                    ['wrench', 'Hỗ trợ kỹ thuật', 'Cần hỗ trợ trong quá trình sử dụng, bảo trì, nâng cấp hệ thống?', 'Liên hệ hỗ trợ', 'Hỗ trợ kỹ thuật'],
                    ['handshake', 'Hợp tác Agency / White Label', 'Dành cho các agency, studio, đối tác muốn hợp tác phát triển dự án.', 'Trao đổi hợp tác', 'Hợp tác Agency / White Label'],
                ] as [$icon, $title, $text, $cta, $need])
                    <div class="col">
                        <x-feature-card :icon="$icon" :title="$title" :text="$text">
                            <button class="btn btn-link p-0 mt-3" type="button" data-consult="{{ $need }}">{{ $cta }} <x-icon name="arrow-right" /></button>
                        </x-feature-card>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <x-section-head title="Thông tin thường gặp" lead="Một số câu hỏi phổ biến giúp bạn hiểu hơn về quy trình làm việc và dịch vụ." />
            <x-faq :items="[
                ['Thời gian triển khai một website hoặc phần mềm là bao lâu?', 'Thời gian được xác nhận trong báo giá sau khi thống nhất phạm vi, nội dung và yêu cầu tích hợp.'],
                ['Chi phí được tính như thế nào?', 'Chi phí phụ thuộc vào phạm vi công việc, thiết kế, tính năng và dịch vụ đi kèm. Tham khảo bảng giá hoặc gửi yêu cầu để nhận báo giá chi tiết.'],
                ['Sau khi hoàn thành, tôi có được hỗ trợ kỹ thuật không?', 'Có. Thời gian và phạm vi hỗ trợ được xác nhận trong hợp đồng dịch vụ.'],
                ['Cách thức thanh toán như thế nào?', 'Mốc thanh toán được thống nhất trong hợp đồng trước khi triển khai.'],
            ]" />
        </div>
    </section>

    <x-cta-banner title="Một cuộc trao đổi, một hướng đi rõ ràng" text="Chúng tôi luôn sẵn sàng tư vấn và đồng hành cùng doanh nghiệp của bạn." />
@endsection
