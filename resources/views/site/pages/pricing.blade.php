@extends('layouts.site')

@section('content')
    <x-hero title="Bảng giá dịch vụ" highlight="Minh bạch – Linh hoạt – Phù hợp"
        lead="Lựa chọn giải pháp phù hợp với mục tiêu và ngân sách của doanh nghiệp. Chúng tôi luôn tư vấn chi tiết, tối ưu chi phí và đồng hành lâu dài."
        :image="asset('frontend/images/prices-hero.webp')" image-alt="Bảng điều khiển doanh thu trên laptop">
        <button class="btn btn-primary" type="button" data-consult="Nhận báo giá theo yêu cầu">Nhận báo giá theo yêu cầu <x-icon name="arrow-right" /></button>
        <x-slot:after>
            <x-trust-row :items="[
                ['icon' => 'database', 'title' => 'Chi phí rõ ràng', 'text' => 'Công khai, minh bạch'],
                ['icon' => 'file-text', 'title' => 'Báo giá chi tiết', 'text' => 'Tư vấn cụ thể theo nhu cầu'],
                ['icon' => 'settings', 'title' => 'Tùy chỉnh linh hoạt', 'text' => 'Phù hợp quy mô và ngân sách'],
                ['icon' => 'headset', 'title' => 'Hỗ trợ lâu dài', 'text' => 'Đồng hành trong quá trình sử dụng'],
            ]" />
        </x-slot:after>
    </x-hero>

    @if ($tabs->isNotEmpty())
        <section class="section" id="bang-gia">
            <div class="container">
                <x-section-head title="Chi phí dịch vụ" lead="Lựa chọn gói phù hợp với nhu cầu và định hướng phát triển của bạn." />
                <div class="chip-list mb-4 nav" role="tablist">
                    @foreach ($tabs as $group)
                        <button @class(['chip', 'active' => $loop->first]) type="button" role="tab" id="tab-{{ $group->value }}" data-bs-toggle="tab" data-bs-target="#plans-{{ $group->value }}" aria-controls="plans-{{ $group->value }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $group->label() }}</button>
                    @endforeach
                </div>
                <div class="tab-content">
                    @foreach ($tabs as $group)
                        <div @class(['tab-pane', 'fade', 'show active' => $loop->first]) id="plans-{{ $group->value }}" role="tabpanel" aria-labelledby="tab-{{ $group->value }}" tabindex="0">
                            <div class="row g-4 pt-2 row-cols-1 row-cols-sm-2 row-cols-xl-4 justify-content-center">
                                @foreach ($plans[$group->value] as $plan)
                                    <div class="col"><x-card.price :plan="$plan" /></div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="small text-muted text-center mt-4 mb-0">Lưu ý: Giá trên là giá tham khảo. Giá thực tế, phạm vi và thuế được xác nhận trong báo giá chính thức.</p>
            </div>
        </section>
    @endif

    @php($websitePlans = $plans->get('website', collect())->take(4)->values())
    @if ($websitePlans->count() === 4)
        <section class="section section--soft">
            <div class="container">
                <x-section-head title="So sánh nhanh các gói Website" lead="Bảng so sánh giúp bạn dễ dàng lựa chọn gói dịch vụ phù hợp nhất." />
                @php($yes = true)
                @php($rows = [
                    ['Thiết kế theo yêu cầu', [$yes, $yes, $yes, $yes]],
                    ['Chuẩn Responsive (mọi thiết bị)', [$yes, $yes, $yes, $yes]],
                    ['Tích hợp form liên hệ', [$yes, $yes, $yes, $yes]],
                    ['Chuẩn SEO cơ bản', [$yes, $yes, $yes, $yes]],
                    ['Tính năng nâng cao', ['—', 'Theo gói', $yes, $yes]],
                    ['Tích hợp API / Phần mềm', ['—', '—', 'Theo phạm vi', $yes]],
                    ['Bảo mật nâng cao', ['—', '—', $yes, $yes]],
                    ['Hỗ trợ kỹ thuật', ['Theo hợp đồng', '12 tháng', '12 tháng', 'Theo hợp đồng']],
                    ['Phù hợp với', ['Chiến dịch nhỏ', 'Doanh nghiệp vừa', 'Doanh nghiệp phát triển', 'Nghiệp vụ riêng / WebApp']],
                ])
                <div class="table-responsive rounded-3 border">
                    <table class="table compare-table">
                        <thead>
                            <tr>
                                <th scope="col" class="text-start">Tính năng / Gói dịch vụ</th>
                                @foreach ($websitePlans as $plan)<th scope="col">{{ $plan->name }}</th>@endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Giá tham khảo</th>
                                @foreach ($websitePlans as $plan)<td class="is-price">{{ $plan->priceText() }}</td>@endforeach
                            </tr>
                            @foreach ($rows as [$label, $values])
                                <tr>
                                    <th scope="row">{{ $label }}</th>
                                    @foreach ($values as $value)
                                        <td>@if ($value === true)<x-icon name="circle-check" label="Có" />@else{{ $value }}@endif</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endif

    @if ($plans->has('addon'))
        <section class="section">
            <div class="container">
                <x-section-head title="Dịch vụ bổ sung" lead="Các dịch vụ đi kèm giúp website vận hành ổn định, hiệu quả và an toàn." />
                <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                    @foreach ($plans['addon'] as $addon)
                        <div class="col">
                            <article class="addon-card">
                                <span class="icon-tile"><x-icon :name="$addon->icon ?: 'layers'" /></span>
                                <h3>{{ $addon->name }}</h3>
                                <strong>{{ $addon->priceText() }}</strong>
                                @if ($addon->summary)<p>{{ $addon->summary }}</p>@endif
                                <button class="btn btn-link" type="button" data-consult="{{ $addon->name }}">{{ $addon->cta_label ?: 'Nhận tư vấn' }} <x-icon name="arrow-right" /></button>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section section--soft">
        <div class="container">
            <x-section-head title="Quy trình báo giá & triển khai" lead="Quy trình minh bạch, rõ ràng, giúp bạn dễ dàng nắm bắt tiến độ." />
            @include('site.partials.process')
        </div>
    </section>

    <section class="section">
        <div class="container">
            <x-section-head title="Câu hỏi thường gặp về chi phí" lead="Giải đáp những thắc mắc phổ biến của khách hàng." />
            <x-faq :items="\App\Http\Controllers\Frontend\PageController::PRICING_FAQS" />
        </div>
    </section>

    <x-cta-banner title="Bạn cần một báo giá đúng với nhu cầu?" text="Chúng tôi luôn sẵn sàng tư vấn và đồng hành cùng doanh nghiệp của bạn." need="Nhận báo giá" />
@endsection
