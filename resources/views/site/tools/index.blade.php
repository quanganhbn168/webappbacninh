@extends('layouts.site')

@section('content')
    <x-hero eyebrow="Miễn phí cho doanh nghiệp" title="Công cụ miễn phí" highlight="dùng ngay trên trình duyệt"
        lead="Tính thuế TNCN, thuế hộ kinh doanh, thuế doanh nghiệp, tạo mã QR, xem lịch vạn niên, lấy ảnh cover video… Không cần đăng ký, không cài đặt." />

    <section class="section">
        <div class="container">
            @if ($tools->isEmpty())
                <p class="empty-state">Các công cụ đang được cập nhật.</p>
            @else
                <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-3">
                    @foreach ($tools as $tool)
                        <div class="col">
                            <x-feature-card :icon="$tool->icon ?: 'wrench'" :title="$tool->name" :text="$tool->description" :href="url($tool->link)" class="tool-card">
                                @if ($tool->badge)<span class="tag tag--gold tool-card__badge">{{ $tool->badge }}</span>@endif
                                <span class="link-arrow mt-3">Dùng ngay <x-icon name="arrow-right" /></span>
                            </x-feature-card>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <x-cta-banner title="Cần một công cụ riêng cho doanh nghiệp?" text="Chúng tôi phát triển công cụ tính toán, báo giá, đặt lịch hoặc quản lý theo nghiệp vụ của bạn." need="Công cụ theo yêu cầu" :secondary-href="route('services.overview').'#phan-mem'" secondary-label="Phần mềm theo yêu cầu" />
@endsection
