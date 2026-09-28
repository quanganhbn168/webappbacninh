@extends('layouts.site')

@section('content')
    <x-hero eyebrow="WebApp Bắc Ninh" title="Thiết kế website và đồng hành vận hành cho doanh nghiệp"
        lead="Chúng tôi tập trung vào website dễ hiểu, dễ sử dụng và có thể tiếp tục phát triển bằng nội dung, SEO, tích hợp và phần mềm khi doanh nghiệp thực sự cần."
        :image="asset('frontend/assets/images/about-bacninh.webp')" image-alt="Đội ngũ WebApp Bắc Ninh">
        <a class="btn btn-primary" href="{{ route('projects.index') }}">Xem dự án <x-icon name="arrow-right" /></a>
        <button class="btn btn-outline-dark" type="button" data-consult="Trao đổi nhu cầu">Trao đổi nhu cầu</button>
    </x-hero>

    <section class="section">
        <div class="container">
            <div class="row g-4 g-lg-5 align-items-center">
                <div class="col-lg-5">
                    <p class="eyebrow">Định vị</p>
                    <h2>Bắt đầu bằng website. Tiếp tục bằng vận hành lâu dài.</h2>
                    <p class="lead-text mt-3 mb-0">Website là bước đầu dễ hiểu nhất để doanh nghiệp hiện diện trên môi trường số. Sau bàn giao, WebApp Bắc Ninh tiếp tục hỗ trợ hosting, bảo trì, đăng bài, SEO, nội dung Facebook và nâng cấp chức năng.</p>
                </div>
                <div class="col-lg-7">
                    <div class="row g-3 row-cols-1 row-cols-sm-2">
                        @foreach ([
                            ['Website là nền tảng', 'Website doanh nghiệp, bán hàng, landing page, website theo ngành và làm lại website cũ.'],
                            ['Vận hành để website luôn hiệu quả', 'Hosting, bảo trì, quản trị, nội dung và SEO theo tháng hoặc theo năm.'],
                            ['Đồng hành cùng Agency', 'Gia công website, white-label, nhận module và bảo trì hệ thống cho đối tác.'],
                            ['Phần mềm khi bài toán đủ rõ', 'CRM, quy trình, booking, tích hợp và hệ thống theo nghiệp vụ riêng.'],
                        ] as [$title, $text])
                            <div class="col"><x-feature-card :number="str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT)" :title="$title" :text="$text" /></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--soft">
        <div class="container">
            <x-section-head eyebrow="Nguyên tắc làm việc" title="Không làm phức tạp hơn nhu cầu thực tế" center />
            <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                @foreach ([
                    ['target', 'Đúng mục tiêu', 'Chốt website dùng để làm gì trước khi bàn đến hiệu ứng và chức năng.'],
                    ['file-check', 'Rõ phạm vi', 'Đầu việc, dữ liệu, tiến độ và nghiệm thu được thống nhất trước.'],
                    ['scale', 'Vừa đủ', 'Không ép doanh nghiệp mua hệ thống lớn khi chỉ cần một giải pháp gọn.'],
                    ['headset', 'Hỗ trợ lâu dài', 'Có phương án duy trì và nâng cấp sau khi website đi vào hoạt động.'],
                ] as [$icon, $title, $text])
                    <div class="col"><x-feature-card :icon="$icon" :title="$title" :text="$text" /></div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--dark">
        <div class="container">
            <div class="media-split">
                <div class="media-split__image"><img src="{{ asset('frontend/assets/images/project-software.webp') }}" alt="Năng lực phát triển website và phần mềm" width="720" height="540" loading="lazy"></div>
                <div>
                    <p class="eyebrow">Năng lực triển khai</p>
                    <h2 class="mb-4">Từ giao diện đến hệ thống phía sau</h2>
                    <div class="d-grid gap-3">
                        @foreach ([
                            ['pencil-ruler', 'Thiết kế giao diện', 'Responsive, rõ ràng và phù hợp thói quen người dùng Việt.'],
                            ['code', 'Phát triển website', 'PHP, Laravel, trang quản trị, API và chức năng theo nhu cầu.'],
                            ['search-check', 'SEO và nội dung', 'Cấu trúc kỹ thuật, trang dịch vụ, bài viết và theo dõi dữ liệu.'],
                            ['cog', 'Tích hợp và phần mềm', 'CRM, quy trình, Zalo, thanh toán và hệ thống quản lý.'],
                        ] as [$icon, $title, $text])
                            <x-feature-card :icon="$icon" :title="$title" :text="$text" class="feature-card--row" />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <x-section-head eyebrow="Đối tượng phục vụ" title="Ưu tiên doanh nghiệp nhỏ và vừa" lead="Những đơn vị cần một đội triển khai thực dụng, trao đổi trực tiếp và không dùng thuật ngữ để làm phức tạp vấn đề." center />
            <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                @foreach ([
                    ['factory', 'Doanh nghiệp sản xuất', 'Website năng lực, sản phẩm, nhà máy, chứng nhận và đa ngôn ngữ.'],
                    ['store', 'Thương mại và dịch vụ', 'Website giới thiệu, bán hàng, nhận lead và quảng bá dịch vụ.'],
                    ['graduation-cap', 'Giáo dục và tổ chức', 'Tuyển sinh, khóa học, thông báo, sự kiện và nội dung hoạt động.'],
                    ['users-round', 'Agency và freelancer', 'Đối tác triển khai website, landing page và phần kỹ thuật phía sau.'],
                ] as [$icon, $title, $text])
                    <div class="col"><x-feature-card :icon="$icon" :title="$title" :text="$text" /></div>
                @endforeach
            </div>
        </div>
    </section>

    @if ($projects->isNotEmpty())
        <section class="section section--soft">
            <div class="container">
                <x-section-head title="Dự án tiêu biểu" :href="route('projects.index')" link="Xem tất cả dự án" />
                <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                    @foreach ($projects as $project)
                        <div class="col"><x-card.project :project="$project" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta-banner title="Trao đổi dự án với WebApp Bắc Ninh" text="Kể cho chúng tôi nghe mục tiêu của bạn, chúng tôi sẽ đề xuất phương án vừa đủ và rõ chi phí." :secondary-href="route('projects.index')" secondary-label="Xem dự án" />
@endsection
