{{-- The eight service groups, shared by the home and services pages. --}}
<div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-4">
    @foreach ([
        ['monitor', 'Thiết kế Website', 'Website doanh nghiệp, bán hàng chuẩn SEO', route('services.index')],
        ['app-window', 'Landing Page', 'Trang đích tối ưu chuyển đổi', route('services.show', 'landing-page')],
        ['brain', 'Phần mềm doanh nghiệp', 'Phát triển phần mềm theo nghiệp vụ riêng', route('services.overview').'#phan-mem'],
        ['calendar-check', 'CRM & Booking', 'Quản lý khách hàng, đặt lịch, tự động hóa', route('services.overview').'#crm-booking'],
        ['rocket', 'SEO & Nội dung', 'Tối ưu SEO, sản xuất nội dung chuẩn tìm kiếm', route('operations.index')],
        ['megaphone', 'Quảng cáo & Tracking', 'Triển khai quảng cáo, đo lường hiệu quả', route('services.overview').'#seo-quang-cao'],
        ['server', 'Hosting / Domain / Email', 'Hạ tầng ổn định, bảo mật cao', route('hosting')],
        ['settings', 'Chăm sóc & Vận hành Website', 'Bảo trì, cập nhật, hỗ trợ dài hạn', route('operations.index')],
    ] as [$icon, $title, $text, $href])
        <div class="col"><x-feature-card :icon="$icon" :title="$title" :text="$text" :href="$href" class="feature-card--row" /></div>
    @endforeach
</div>
