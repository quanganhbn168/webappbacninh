@extends('layouts.site')

@section('content')
    <x-hero eyebrow="Dịch vụ thiết kế website" title="Website rõ nội dung," highlight="dễ quản trị và có khả năng bán hàng."
        lead="WebApp Bắc Ninh thiết kế website theo mục tiêu thực tế của doanh nghiệp: giới thiệu năng lực, nhận khách hàng, bán sản phẩm, chạy chiến dịch hoặc xây nền tảng nội dung lâu dài."
        :image="asset('frontend/assets/images/hero-industrial.webp')" image-alt="Dịch vụ thiết kế website tại Bắc Ninh">
        <a class="btn btn-primary" href="#tu-van-website">Nhận tư vấn & báo giá <x-icon name="arrow-right" /></a>
        <a class="btn btn-outline-dark" href="{{ route('themes.index') }}">Xem kho giao diện</a>
        <x-slot:meta>
            <ul class="check-list mt-4 small">
                <li>Có cấu trúc nội dung trước khi làm</li>
                <li>Responsive và SEO nền tảng</li>
                <li>Hướng dẫn quản trị sau bàn giao</li>
            </ul>
        </x-slot:meta>
    </x-hero>
    <x-trust-row :items="[
        ['icon' => 'pencil-ruler', 'title' => 'Thiết kế theo mục tiêu', 'text' => 'Không chỉ thay logo trên mẫu có sẵn'],
        ['icon' => 'smartphone', 'title' => 'Tối ưu đa thiết bị', 'text' => 'Desktop, tablet và điện thoại'],
        ['icon' => 'gauge', 'title' => 'Nền tảng nhẹ, rõ', 'text' => 'Hạn chế hiệu ứng dư thừa'],
        ['icon' => 'wrench', 'title' => 'Có người đồng hành', 'text' => 'Bảo trì, nội dung và SEO'],
    ]" />

    <section class="section" id="giai-phap">
        <div class="container">
            <x-section-head eyebrow="Giải pháp theo nhu cầu" title="Không phải doanh nghiệp nào cũng cần cùng một loại website." lead="Chọn đúng cấu trúc ngay từ đầu giúp tiết kiệm chi phí, thời gian nhập nội dung và công sức vận hành sau này." center />
            @if ($landingServices->isNotEmpty())
                <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-{{ min(4, $landingServices->count()) }}">
                    @foreach ($landingServices as $service)
                        <div class="col">
                            <article class="media-card">
                                <a class="media-card__image ratio-card" href="{{ $service->url }}" tabindex="-1" aria-hidden="true"><img class="img-cover" src="{{ $service->image_url }}" alt="" width="640" height="400" loading="lazy"></a>
                                <div class="media-card__body">
                                    <p class="eyebrow mb-0">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                                    <h3 class="media-card__title"><a href="{{ $service->url }}">{{ $service->title }}</a></h3>
                                    <p>{{ \Illuminate\Support\Str::limit((string) $service->description, 140) }}</p>
                                    <p class="media-card__meta"><x-icon name="tag" class="icon-sm" /> {{ $service->price_label }} · {{ $service->timeline_label }}</p>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="section section--soft">
        <div class="container">
            <x-section-head eyebrow="Phạm vi cơ bản" title="Một website hoàn chỉnh cần nhiều hơn một giao diện đẹp." lead="Các hạng mục dưới đây được xem xét ngay từ đầu để tránh phát sinh những phần lẽ ra phải có." />
            <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                @foreach ([
                    ['network', 'Cấu trúc trang', 'Sơ đồ trang và luồng nội dung theo mục tiêu của website.'],
                    ['palette', 'Giao diện thương hiệu', 'Màu sắc, font chữ, hình ảnh và cách trình bày thống nhất.'],
                    ['smartphone', 'Responsive', 'Tối ưu hiển thị trên máy tính, tablet và điện thoại.'],
                    ['settings', 'Trang quản trị', 'Cập nhật bài viết, dịch vụ, sản phẩm và thông tin cơ bản.'],
                    ['search-check', 'SEO nền tảng', 'Title, description, heading, URL, sitemap và schema cơ bản.'],
                    ['shield-check', 'Bảo mật cơ bản', 'SSL, phân quyền, chống spam form và các thiết lập cần thiết.'],
                    ['chart-line', 'Đo lường', 'Kết nối Search Console, Analytics hoặc công cụ tracking khi cần.'],
                    ['graduation-cap', 'Hướng dẫn bàn giao', 'Hướng dẫn sử dụng, kiểm tra và bảo hành theo phạm vi.'],
                ] as [$icon, $title, $text])
                    <div class="col"><x-feature-card :icon="$icon" :title="$title" :text="$text" /></div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <x-section-head eyebrow="Cách triển khai" title="Chọn mức độ tùy chỉnh phù hợp ngân sách và thời gian." lead="Không cần thiết kế riêng mọi thứ nếu doanh nghiệp chưa cần; cũng không nên dùng mẫu cứng khi nghiệp vụ đã khác biệt." center />
            <div class="row g-4 row-cols-1 row-cols-lg-3">
                @foreach ([
                    ['Mẫu giao diện tùy chỉnh', 'Chọn mẫu gần nhu cầu, sau đó thay nhận diện, nội dung, hình ảnh và điều chỉnh bố cục cần thiết.', 'Doanh nghiệp cần triển khai nhanh và ngân sách rõ.', 'Xem kho giao diện', route('themes.index'), false],
                    ['Thiết kế riêng theo ngành', 'Xây bố cục dựa trên khách hàng, dịch vụ, dự án và cách doanh nghiệp muốn tạo chuyển đổi.', 'Doanh nghiệp cần khác biệt và phát triển nội dung lâu dài.', 'Nhận đề xuất cấu trúc', '#tu-van-website', true],
                    ['Website có chức năng riêng', 'Phát triển thêm booking, đơn hàng, phân quyền, đa chi nhánh, API hoặc nghiệp vụ đặc thù.', 'Dự án cần khảo sát, tài liệu và chia giai đoạn triển khai.', 'Đặt lịch khảo sát', '#tu-van-website', false],
                ] as [$title, $text, $fit, $cta, $href, $featured])
                    <div class="col">
                        <article @class(['price-card', 'is-featured' => $featured])>
                            @if ($featured)<span class="price-card__badge">Được chọn nhiều</span>@endif
                            <span class="feature-card__number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3>{{ $title }}</h3>
                            <p class="text-muted mb-0">{{ $text }}</p>
                            <p class="small mb-0"><strong>Phù hợp:</strong> {{ $fit }}</p>
                            <a @class(['btn', 'mt-auto', 'btn-primary' => $featured, 'btn-outline-primary' => ! $featured]) href="{{ $href }}">{{ $cta }}</a>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--dark">
        <div class="container">
            <x-section-head eyebrow="Quy trình làm website" title="Mỗi giai đoạn đều có đầu việc và kết quả rõ ràng." />
            <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-3">
                @foreach ([
                    ['messages-square', 'Tiếp nhận nhu cầu', 'Mục tiêu, ngành nghề, khách hàng, dữ liệu và thời gian mong muốn.'],
                    ['network', 'Đề xuất cấu trúc', 'Sơ đồ trang, chức năng, hướng giao diện và nội dung cần chuẩn bị.'],
                    ['file-pen-line', 'Chốt phạm vi', 'Báo giá, tiến độ, đầu việc hai bên và tiêu chí nghiệm thu.'],
                    ['pencil-ruler', 'Thiết kế & phát triển', 'Dựng giao diện, lập trình chức năng và cập nhật nội dung.'],
                    ['test-tube-diagonal', 'Kiểm thử', 'Responsive, form, nội dung, SEO cơ bản và các luồng chính.'],
                    ['rocket', 'Bàn giao & vận hành', 'Đưa website hoạt động, hướng dẫn quản trị và hỗ trợ sau bàn giao.'],
                ] as [$icon, $title, $text])
                    <div class="col"><x-feature-card :icon="$icon" :title="str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT).' · '.$title" :text="$text" class="feature-card--row" /></div>
                @endforeach
            </div>
        </div>
    </section>

    @if ($plans->isNotEmpty())
        <section class="section section--cream" id="chi-phi">
            <div class="container">
                <x-section-head eyebrow="Chi phí tham khảo" title="Các mức đầu tư phổ biến cho website doanh nghiệp." lead="Chi phí cuối cùng được xác định sau khi thống nhất phạm vi, dữ liệu, chức năng và mức độ thiết kế riêng." :href="route('pricing')" link="Xem bảng giá chi tiết" />
                <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-xl-4">
                    @foreach ($plans as $plan)
                        <div class="col"><x-card.price :plan="$plan" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($operationServices->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="media-split">
                    <div>
                        <p class="eyebrow">Sau khi website chạy</p>
                        <h2>Website cần được duy trì để tiếp tục tạo giá trị.</h2>
                        <p class="lead-text my-3">Doanh nghiệp có thể tự vận hành hoặc chọn từng phần đang thiếu. Không bắt buộc mua trọn gói.</p>
                        <div class="d-grid gap-3">
                            @foreach ($operationServices as $service)
                                <x-feature-card :icon="$service->icon ?: 'settings'" :title="$service->title" :text="$service->highlight ?: $service->description" :href="$service->url" class="feature-card--row" />
                            @endforeach
                        </div>
                    </div>
                    <div class="media-split__image"><img src="{{ asset('frontend/assets/images/seo-operation.webp') }}" alt="Dịch vụ SEO và vận hành website" width="720" height="540" loading="lazy"></div>
                </div>
            </div>
        </section>
    @endif

    <section class="section section--soft">
        <div class="container">
            <div class="row g-4 g-lg-5">
                <div class="col-lg-5">
                    <p class="eyebrow">Câu hỏi thường gặp</p>
                    <h2>Những điểm nên làm rõ trước khi bắt đầu.</h2>
                    <p class="lead-text my-3">Báo giá chính xác chỉ có sau khi biết website cần làm gì, nội dung hiện có và mức độ tùy chỉnh mong muốn.</p>
                    <button class="btn btn-outline-primary" type="button" data-consult="Câu hỏi về thiết kế website">Gửi câu hỏi khác</button>
                </div>
                <div class="col-lg-7"><x-faq :items="\App\Http\Controllers\Frontend\ServiceController::WEBSITE_FAQS" :columns="1" /></div>
            </div>
        </div>
    </section>

    <x-lead-section id="tu-van-website" eyebrow="Nhận tư vấn website" title="Cho chúng tôi biết doanh nghiệp đang cần website như thế nào."
        text="WebApp Bắc Ninh sẽ đề xuất cấu trúc, hướng giao diện, phạm vi và mức đầu tư phù hợp." need="Thiết kế website" />
@endsection
