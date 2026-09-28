@extends('layouts.site')

@section('content')
    <x-hero eyebrow="Sau khi website đi vào hoạt động" title="Website không tự tạo giá trị nếu không được duy trì"
        lead="WebApp Bắc Ninh hỗ trợ phần kỹ thuật, nội dung, SEO và cập nhật định kỳ để doanh nghiệp không phải tuyển ngay một đội riêng."
        :image="asset('frontend/assets/images/seo-operation.webp')" image-alt="Dịch vụ vận hành website và SEO">
        <a class="btn btn-primary" href="#nhom-cong-viec">Xem dịch vụ <x-icon name="arrow-right" /></a>
        <a class="btn btn-outline-dark" href="#de-xuat-goi">Nhận đề xuất gói</a>
    </x-hero>

    <section class="section" id="nhom-cong-viec">
        <div class="container">
            <x-section-head :eyebrow="$operationServices->count().' nhóm công việc'" title="Chọn đúng phần doanh nghiệp đang thiếu" lead="Không bắt buộc mua trọn gói. Mỗi nhóm có thể triển khai riêng hoặc kết hợp." center />
            <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-3">
                @foreach ($operationServices as $service)
                    <div class="col" id="{{ $service->slug }}">
                        <x-feature-card :icon="$service->icon ?: 'settings'" :title="$service->title" :text="$service->description" :href="$service->url">
                            @if ($service->scope)
                                <ul class="check-list small mt-3">@foreach (array_slice($service->scope, 0, 4) as $scope)<li>{{ $scope['title'] }}</li>@endforeach</ul>
                            @endif
                            <span class="link-arrow mt-3">Xem chi tiết <x-icon name="arrow-right" /></span>
                        </x-feature-card>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @if ($plans->isNotEmpty())
        <section class="section section--soft">
            <div class="container">
                <x-section-head eyebrow="Gói đồng hành" title="Ba cách duy trì phổ biến" lead="Phạm vi và số lượng công việc được thống nhất theo tháng để tránh hiểu nhầm." :href="route('pricing')" link="Xem bảng giá" />
                <div class="row g-4 row-cols-1 row-cols-md-3">
                    @foreach ($plans as $plan)
                        <div class="col"><x-card.price :plan="$plan" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section section--dark">
        <div class="container">
            <div class="row g-4 g-lg-5 align-items-center">
                <div class="col-lg-5">
                    <p class="eyebrow">Cách làm việc</p>
                    <h2>Mỗi tháng đều biết đã làm gì</h2>
                    <p class="lead-text mt-3 mb-0">Không dùng các cụm từ chung chung như “chăm sóc website”. Công việc được ghi theo đầu mục và khối lượng.</p>
                </div>
                <div class="col-lg-7">
                    <div class="row g-3 row-cols-1 row-cols-sm-2">
                        @foreach ([['Chốt danh sách việc', 'Ưu tiên theo mục tiêu tháng'], ['Tiếp nhận dữ liệu', 'Nội dung, hình ảnh và thay đổi'], ['Thực hiện & kiểm tra', 'Rà soát trên desktop và mobile'], ['Báo cáo & đề xuất', 'Khối lượng và việc tiếp theo']] as [$title, $text])
                            <div class="col"><x-feature-card :number="str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT)" :title="$title" :text="$text" /></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <x-section-head eyebrow="Doanh nghiệp cần cung cấp" title="Phối hợp gọn để nội dung không bị tắc" center />
            <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                @foreach ([
                    ['user-check', 'Một đầu mối duyệt', 'Tránh nhiều người sửa chéo và kéo dài thời gian phản hồi.'],
                    ['folder-open', 'Dữ liệu có tổ chức', 'Ảnh, thông tin, giá và tài liệu được gom theo từng nhóm.'],
                    ['calendar-check', 'Lịch cập nhật', 'Chốt trước các sự kiện, chương trình và nội dung ưu tiên.'],
                    ['target', 'Mục tiêu rõ', 'Muốn tăng uy tín, tìm khách, tuyển đại lý hay bán sản phẩm.'],
                ] as [$icon, $title, $text])
                    <div class="col"><x-feature-card :icon="$icon" :title="$title" :text="$text" /></div>
                @endforeach
            </div>
        </div>
    </section>

    <x-lead-section id="de-xuat-goi" title="Nhận đề xuất gói vận hành phù hợp" text="Cho biết website hiện tại, tần suất cập nhật và phần doanh nghiệp đang thiếu. Chúng tôi sẽ tách rõ từng đầu việc và chi phí." need="Dịch vụ vận hành website" />
@endsection
