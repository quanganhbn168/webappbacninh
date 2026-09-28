@extends('layouts.site')

@section('content')
    <x-hero title="Hạ tầng Website ổn định," highlight="bảo mật và dễ vận hành"
        lead="WebApp Bắc Ninh cung cấp giải pháp Hosting, Domain, Email doanh nghiệp và cấu hình kỹ thuật đồng bộ, giúp website của bạn hoạt động ổn định, nhanh chóng và an toàn."
        :image="asset('frontend/images/hero-hosting.webp')" image-alt="Hệ thống máy chủ hosting, website trên laptop và điện thoại" note="Hạ tầng vững chắc cho doanh nghiệp phát triển"
        :breadcrumbs="[['name' => 'Trang chủ', 'url' => route('home')], ['name' => 'Dịch vụ', 'url' => route('services.overview')], ['name' => 'Hosting - Domain - Email', 'url' => route('hosting')]]">
        <button class="btn btn-primary" type="button" data-consult="Hosting / Domain / Email">Nhận tư vấn hạ tầng <x-icon name="arrow-right" /></button>
        <button class="btn btn-outline-dark" type="button" data-domain>Kiểm tra tên miền</button>
        <x-slot:after>
            <x-trust-row :items="[
                ['icon' => 'gauge', 'title' => 'Tốc độ ổn định'],
                ['icon' => 'shield-check', 'title' => 'Bảo mật cao'],
                ['icon' => 'hard-drive', 'title' => 'Backup định kỳ'],
                ['icon' => 'headset', 'title' => 'Hỗ trợ kỹ thuật chuyên sâu'],
            ]" />
        </x-slot:after>
    </x-hero>

    <section class="section">
        <div class="container">
            <div class="row g-4 g-lg-5 align-items-center">
                <div class="col-lg-5">
                    <p class="eyebrow">Bạn đang gặp những vấn đề gì?</p>
                    <h2>Hạ tầng không ổn định làm gián đoạn công việc</h2>
                    <p class="lead-text my-3">Website chậm, thường xuyên lỗi, email không hoạt động hay domain hết hạn… đều ảnh hưởng đến hình ảnh và hiệu suất kinh doanh của doanh nghiệp.</p>
                    <button class="btn btn-primary" type="button" data-consult="Tư vấn khắc phục sự cố hạ tầng">Liên hệ tư vấn</button>
                </div>
                <div class="col-lg-7">
                    <div class="row g-3 row-cols-1 row-cols-sm-2">
                        @foreach ([
                            ['gauge', 'Website chậm, hay lỗi', 'Ảnh hưởng trải nghiệm khách hàng và hiệu quả kinh doanh.'],
                            ['shield', 'Thiếu bảo mật', 'Dễ bị tấn công, mất dữ liệu, ảnh hưởng uy tín thương hiệu.'],
                            ['mail', 'Email không ổn định', 'Gửi/nhận chậm, bị spam, ảnh hưởng giao dịch.'],
                            ['globe', 'Domain sắp hết hạn', 'Nguy cơ mất tên miền, ảnh hưởng thương hiệu.'],
                        ] as [$icon, $title, $text])
                            <div class="col"><x-feature-card :icon="$icon" :title="$title" :text="$text" /></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--soft" id="ha-tang">
        <div class="container">
            <x-section-head eyebrow="Dịch vụ của chúng tôi" title="Giải pháp hạ tầng toàn diện" lead="Từ tên miền đến hosting, email và quản trị hệ thống – tất cả trong một, được triển khai và hỗ trợ bởi đội ngũ WebApp Bắc Ninh." />
            <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-3">
                @foreach ([
                    ['globe', 'Tên miền', 'Đăng ký & gia hạn tên miền, quản lý DNS, subdomain, redirect…'],
                    ['server', 'Hosting Website', 'Hosting chất lượng cao, ổn định, bảo mật, phù hợp mọi loại website.'],
                    ['cloud', 'VPS / Cloud Server', 'Cấu hình linh hoạt, phù hợp CRM, WebApp, hệ thống lớn.'],
                    ['lock', 'SSL & Bảo mật', 'Chứng chỉ SSL, tường lửa, backup, chống tấn công và giám sát.'],
                    ['mail', 'Email doanh nghiệp', 'Email theo tên miền riêng, Google Workspace, Microsoft 365.'],
                    ['settings-2', 'Quản trị hạ tầng', 'Cấu hình, theo dõi, sao lưu và xử lý sự cố nhanh chóng.'],
                ] as [$icon, $title, $text])
                    <div class="col"><x-feature-card :icon="$icon" :title="$title" :text="$text" class="feature-card--row" /></div>
                @endforeach
            </div>
        </div>
    </section>

    @if ($plans->isNotEmpty())
        <section class="section" id="bang-gia-hosting">
            <div class="container">
                <x-section-head eyebrow="Bảng giá dịch vụ" title="Gói Hosting phù hợp với nhu cầu của bạn" lead="Các gói hosting được tối ưu cho website doanh nghiệp, landing page và website bán hàng. Cam kết tốc độ, bảo mật và hỗ trợ kỹ thuật tận tâm." />
                <div class="row g-4 row-cols-1 row-cols-md-3 justify-content-center">
                    @foreach ($plans as $plan)
                        <div class="col"><x-card.price :plan="$plan" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section section--soft">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-4">
                    <h2>Dịch vụ bổ sung (Add-on)</h2>
                    <p class="lead-text mb-0">Nâng cấp linh hoạt theo nhu cầu, giúp hệ thống của bạn vận hành hiệu quả và an toàn hơn.</p>
                </div>
                <div class="col-lg-8">
                    <div class="row g-3 row-cols-1 row-cols-sm-2">
                        @foreach ([['globe', 'Tên miền', 'Theo giá thị trường'], ['mail', 'Email doanh nghiệp', 'Từ 50.000đ/tháng'], ['lock', 'SSL cao cấp', 'Từ 300.000đ/năm'], ['hard-drive', 'Sao lưu dữ liệu', 'Từ 300.000đ/tháng']] as [$icon, $title, $price])
                            <div class="col">
                                <button class="feature-card feature-card--link feature-card--row w-100 text-start" type="button" data-consult="{{ $title }}">
                                    <span class="icon-tile"><x-icon :name="$icon" /></span>
                                    <span><strong class="d-block text-dark">{{ $title }}</strong><span class="text-gold-dark fw-semibold">{{ $price }}</span></span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <x-section-head title="Câu hỏi thường gặp" />
            <x-faq :items="[
                ['Tôi đã có tên miền ở nơi khác, có dùng được không?', 'Được. Chúng tôi hướng dẫn trỏ DNS hoặc chuyển tên miền về quản lý tập trung, không làm gián đoạn website và email đang chạy.'],
                ['Email doanh nghiệp khác gì email miễn phí?', 'Email theo tên miền (ten@congty.vn) tạo sự chuyên nghiệp, quản lý được tài khoản nhân viên và ít bị đánh dấu spam hơn khi cấu hình đầy đủ SPF, DKIM, DMARC.'],
                ['Website có được sao lưu không?', 'Các gói hosting đều có sao lưu định kỳ. Gói Business và Pro sao lưu hằng ngày hoặc nhiều phiên bản để khôi phục nhanh khi có sự cố.'],
                ['Chuyển website từ hosting cũ có mất dữ liệu không?', 'Chúng tôi sao lưu và chuyển toàn bộ mã nguồn, cơ sở dữ liệu, email trước khi đổi DNS để website hoạt động liên tục.'],
            ]" />
        </div>
    </section>

    <x-cta-banner title="Bạn cần một giải pháp hạ tầng cho website?" text="Hãy để WebApp Bắc Ninh tư vấn và triển khai phù hợp với nhu cầu của bạn." need="Tư vấn hạ tầng" :secondary-href="route('services.overview')" secondary-label="Xem thêm dịch vụ" />
@endsection
