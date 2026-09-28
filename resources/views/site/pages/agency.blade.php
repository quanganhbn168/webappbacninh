@extends('layouts.site')

@section('content')
    <x-hero variant="dark" eyebrow="Đối tác kỹ thuật phía sau" title="Agency tập trung khách hàng. Chúng tôi phụ trách phần website."
        lead="Nhận triển khai website, landing page, bảo trì hoặc từng module theo hình thức giới thiệu khách, đồng triển khai và white-label."
        :image="asset('frontend/assets/images/agency-partnership.webp')" image-alt="Hợp tác kỹ thuật với agency">
        <a class="btn btn-primary" href="#mo-hinh">Xem mô hình hợp tác <x-icon name="arrow-right" /></a>
        <a class="btn btn-outline-light" href="#agency-contact">Trao đổi dự án</a>
    </x-hero>
    <x-trust-row :items="[
        ['icon' => 'handshake', 'title' => 'Không giành khách', 'text' => 'Tôn trọng đầu mối của đối tác'],
        ['icon' => 'eye-off', 'title' => 'White-label', 'text' => 'Có thể ẩn thương hiệu triển khai'],
        ['icon' => 'list-checks', 'title' => 'Rõ phạm vi', 'text' => 'Đầu việc và nghiệm thu cụ thể'],
        ['icon' => 'messages-square', 'title' => 'Phối hợp trực tiếp', 'text' => 'Cập nhật tiến độ trong quá trình làm'],
    ]" />

    <section class="section" id="mo-hinh">
        <div class="container">
            <x-section-head eyebrow="Mô hình hợp tác" title="Chọn cách phù hợp với quy trình của Agency" center />
            <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                @foreach ([
                    ['Giới thiệu khách hàng', 'Agency kết nối nhu cầu. WebApp Bắc Ninh tư vấn, triển khai và cập nhật tiến độ theo cách đã thống nhất.'],
                    ['Gia công white-label', 'Agency đứng tên dự án và làm việc với khách hàng; chúng tôi thực hiện phần kỹ thuật phía sau.'],
                    ['Đồng triển khai', 'Hai bên chia đầu việc: agency phụ trách chiến lược, nội dung hoặc thiết kế; WebApp phụ trách lập trình.'],
                    ['Nhận riêng module', 'Nhận frontend, backend, landing page, tích hợp, sửa lỗi hoặc bảo trì hệ thống hiện có.'],
                ] as [$title, $text])
                    <div class="col"><x-feature-card :number="str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT)" :title="$title" :text="$text" /></div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--soft">
        <div class="container">
            <x-section-head eyebrow="Phạm vi kỹ thuật" title="Những phần có thể nhận triển khai" lead="Có thể nhận trọn dự án hoặc tách theo module và giai đoạn." />
            <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-3">
                @foreach ([
                    ['building-2', 'Website doanh nghiệp', 'Giao diện, trang quản trị, dịch vụ, dự án, bài viết, đa ngôn ngữ.'],
                    ['shopping-cart', 'Website bán hàng', 'Sản phẩm, giỏ hàng, đặt hàng, thanh toán và vận chuyển.'],
                    ['app-window', 'Landing page', 'Chiến dịch quảng cáo, form lead, tracking và tối ưu chuyển đổi.'],
                    ['plug', 'Tích hợp và API', 'Zalo OA, CRM, thanh toán, dữ liệu hoặc dịch vụ bên thứ ba.'],
                    ['wrench', 'Sửa lỗi và bảo trì', 'Tiếp nhận hệ thống hiện có, xử lý lỗi và duy trì kỹ thuật.'],
                    ['puzzle', 'Module theo yêu cầu', 'Quản trị, form, dashboard, quy trình hoặc chức năng riêng.'],
                ] as [$icon, $title, $text])
                    <div class="col"><x-feature-card :icon="$icon" :title="$title" :text="$text" class="feature-card--row" /></div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--dark">
        <div class="container">
            <div class="row g-4 g-lg-5 align-items-center">
                <div class="col-lg-5">
                    <p class="eyebrow">Quy trình phối hợp</p>
                    <h2>Không nhận dự án bằng một câu “cứ làm đi”</h2>
                    <p class="lead-text mt-3 mb-0">Mỗi dự án cần tài liệu phạm vi, đầu mối và cách nghiệm thu đủ rõ trước khi bắt đầu.</p>
                </div>
                <div class="col-lg-7">
                    <div class="row g-3 row-cols-1 row-cols-sm-2">
                        @foreach ([
                            ['Nhận brief', 'Mục tiêu, sitemap, thiết kế, chức năng và thời hạn.'],
                            ['Đánh giá kỹ thuật', 'Chốt phần làm được, rủi ro và dữ liệu cần bổ sung.'],
                            ['Báo giá & tiến độ', 'Tách milestone, đầu việc và điều kiện nghiệm thu.'],
                            ['Triển khai & bàn giao', 'Cập nhật tiến độ, test và hỗ trợ sau bàn giao.'],
                        ] as [$title, $text])
                            <div class="col"><x-feature-card :number="str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT)" :title="$title" :text="$text" /></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row g-4 g-lg-5">
                <div class="col-lg-6">
                    <p class="eyebrow">Phù hợp với</p>
                    <h2 class="mb-4">Agency nào nên hợp tác?</h2>
                    <ul class="info-list">
                        @foreach ([['Agency marketing hoặc truyền thông', 'Có khách cần website nhưng không duy trì đội code cố định.'], ['Đơn vị thiết kế thương hiệu', 'Cần đối tác chuyển thiết kế thành website hoạt động thực tế.'], ['Freelancer bán hàng và SEO', 'Cần đội kỹ thuật thực hiện phần frontend, backend và bảo trì.']] as [$title, $text])
                            <li><span class="icon-tile"><x-icon name="circle-check" /></span><div><h3>{{ $title }}</h3><p>{{ $text }}</p></div></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-6">
                    <p class="eyebrow">Không phù hợp khi</p>
                    <h2 class="mb-4">Dự án thiếu phạm vi và đầu mối</h2>
                    <ul class="info-list">
                        @foreach ([['Không có brief tối thiểu', 'Không xác định được trang, chức năng và dữ liệu cần bàn giao.'], ['Tiến độ không thực tế', 'Yêu cầu hoàn thiện ngay nhưng chưa có nội dung và thiết kế.'], ['Nhiều người cùng duyệt', 'Không có một đầu mối tổng hợp thay đổi và nghiệm thu.']] as [$title, $text])
                            <li><span class="icon-tile text-danger bg-danger-subtle"><x-icon name="triangle-alert" /></span><div><h3>{{ $title }}</h3><p>{{ $text }}</p></div></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <x-lead-section id="agency-contact" title="Trao đổi chính sách hợp tác Agency" text="Gửi loại dự án, cách phối hợp mong muốn và khối lượng dự kiến. Hai bên sẽ thống nhất rõ vai trò trước khi triển khai." need="Hợp tác Agency" />
@endsection
