{{--
    Service cards, shared by the home and services pages: the services switched on
    in the admin, between the fixed pages that are not services themselves.
--}}
@php
    $cards = collect([['monitor', 'Thiết kế Website', 'Website doanh nghiệp, bán hàng chuẩn SEO', route('services.index')]])
        ->concat($gridServices->map(fn (\App\Models\Service $service): array => [$service->icon_class, $service->grid_title, $service->grid_text, $service->url]))
        ->push(
            ['server', 'Hosting / Domain / Email', 'Hạ tầng ổn định, bảo mật cao', route('hosting')],
            ['settings', 'Chăm sóc & Vận hành Website', 'Bảo trì, cập nhật, hỗ trợ dài hạn', route('operations.index')],
        );
@endphp
<div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-4 justify-content-center">
    @foreach ($cards as [$icon, $title, $text, $href])
        <div class="col"><x-feature-card :icon="$icon" :title="$title" :text="$text" :href="$href" class="feature-card--row" /></div>
    @endforeach
</div>
