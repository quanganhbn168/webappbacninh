@extends('layouts.site')

@push('head')
    <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
    @vite('resources/js/projects/gallery.js')
@endpush

@section('content')
    @php
        $slides = $theme->slides;
        $tabs = array_filter([
            'giao-dien' => 'Giao diện',
            'tinh-nang' => $theme->included_features || $theme->customizations || $theme->audiences ? 'Tính năng' : null,
            'cac-trang' => $theme->pages ? 'Các trang' : null,
        ]);
    @endphp
    <section class="page-hero page-hero--detail theme-detail">
        <div class="container">
            <x-breadcrumbs :items="$breadcrumbs" />
            <div class="row g-4 g-lg-5">
                <div class="col-lg-5 col-xl-4">
                    <aside class="theme-summary">
                        <p class="theme-summary__meta">
                            <span>{{ $theme->code }} · @if ($theme->category)<a href="{{ $theme->category->url }}">{{ $theme->industry_label }}</a>@else{{ $theme->industry_label }}@endif</span>
                            @if (filled($theme->badge))<span class="tag tag--gold">{{ $theme->badge }}</span>@endif
                        </p>
                        <h1>{{ $theme->name }}</h1>
                        <p class="theme-summary__lead">{{ $theme->description }}</p>
                        <dl class="theme-summary__specs">
                            <div><dt>Loại website</dt><dd>{{ $theme->type_label }}</dd></div>
                            @if ($theme->pages)<div><dt>Số trang</dt><dd>{{ count($theme->pages) }}</dd></div>@endif
                            <div><dt>Hiển thị</dt><dd>Responsive đa thiết bị</dd></div>
                            <div><dt>Thời gian dự kiến</dt><dd>{{ $theme->duration ?: 'Theo phạm vi' }}</dd></div>
                        </dl>
                        <x-theme-price :theme="$theme" label="Chi phí triển khai tham khảo từ" class="theme-summary__price" />
                        <p class="small text-muted">Giá chính thức phụ thuộc nội dung, chức năng và mức độ tùy chỉnh.</p>
                        <div class="theme-summary__actions">
                            <a class="btn btn-primary" href="#chon-mau">Chọn mẫu này <x-icon name="arrow-right" /></a>
                            @if ($theme->preview_url)
                                <a class="btn btn-outline-dark" href="{{ $theme->preview_url }}" target="_blank" rel="noopener">Xem demo <x-icon name="external-link" /></a>
                            @endif
                        </div>
                        <a class="link-arrow" href="tel:{{ site_config('phone_href') }}"><x-icon name="phone" /> {{ site_config('phone') }}</a>
                        @if ($previousTheme || $nextTheme)
                            <nav class="theme-summary__pager" aria-label="Mẫu trước và mẫu tiếp theo">
                                @if ($previousTheme)<a href="{{ $previousTheme->url }}" rel="prev"><small><x-icon name="arrow-left" /> Mẫu trước</small>{{ $previousTheme->name }}</a>@endif
                                @if ($nextTheme)<a class="theme-summary__next" href="{{ $nextTheme->url }}" rel="next"><small>Mẫu tiếp theo <x-icon name="arrow-right" /></small>{{ $nextTheme->name }}</a>@endif
                            </nav>
                        @endif
                    </aside>
                </div>
                <div class="col-lg-7 col-xl-8">
                    <ul class="nav theme-tabs" role="tablist">
                        @foreach ($tabs as $key => $label)
                            <li role="presentation">
                                <button @class(['theme-tabs__button', 'active' => $loop->first]) id="tab-{{ $key }}" type="button" role="tab" data-bs-toggle="tab" data-bs-target="#{{ $key }}" aria-controls="{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    {{ $label }}
                                    @if ($key === 'giao-dien')<span>{{ count($slides) }}</span>@endif
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="giao-dien" role="tabpanel" aria-labelledby="tab-giao-dien" tabindex="0">
                            <div class="project-gallery theme-gallery" data-project-gallery>
                                <div class="swiper project-gallery__main">
                                    <div class="swiper-wrapper">
                                        @foreach ($slides as $index => $slide)
                                            {{-- Hovering scrolls through a tall screenshot. --}}
                                            <div class="swiper-slide" tabindex="0">
                                                <x-img :src="$slide" sizes="(min-width: 992px) 60vw, 100vw" alt="{{ $theme->name }}{{ $index ? ' - ảnh '.($index + 1) : '' }}" width="960" height="600" @if ($index) loading="lazy" @else fetchpriority="high" @endif />
                                            </div>
                                        @endforeach
                                    </div>
                                    @if (count($slides) > 1)
                                        <button class="project-gallery__nav project-gallery__nav--prev" type="button" aria-label="Ảnh trước"><x-icon name="chevron-left" /></button>
                                        <button class="project-gallery__nav project-gallery__nav--next" type="button" aria-label="Ảnh tiếp theo"><x-icon name="chevron-right" /></button>
                                        <span class="project-gallery__counter"></span>
                                    @endif
                                </div>
                                @if (count($slides) > 1)
                                    <div class="swiper project-gallery__thumbs">
                                        <div class="swiper-wrapper">
                                            @foreach ($slides as $index => $slide)
                                                <div class="swiper-slide"><button type="button" aria-label="Xem ảnh {{ $index + 1 }}"><img src="{{ $slide['thumb'] }}" alt="" width="120" height="80" loading="lazy"></button></div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @isset($tabs['tinh-nang'])
                            <div class="tab-pane fade" id="tinh-nang" role="tabpanel" aria-labelledby="tab-tinh-nang" tabindex="0">
                                @foreach ([
                                    'Chức năng và tiêu chuẩn bàn giao' => $theme->included_features,
                                    'Tùy chỉnh theo doanh nghiệp' => $theme->customizations,
                                    'Mẫu này phù hợp với ai?' => $theme->audiences,
                                ] as $heading => $items)
                                    @if ($items)
                                        <div class="theme-list">
                                            <h2>{{ $heading }}</h2>
                                            <ul class="check-list check-list--gold check-list--2col">@foreach ($items as $item)<li>{{ $item }}</li>@endforeach</ul>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endisset
                        @isset($tabs['cac-trang'])
                            <div class="tab-pane fade" id="cac-trang" role="tabpanel" aria-labelledby="tab-cac-trang" tabindex="0">
                                <div class="theme-list">
                                    <h2>Các trang dự kiến</h2>
                                    <ol class="scope-grid">@foreach ($theme->pages as $page)<li>{{ $page }}</li>@endforeach</ol>
                                </div>
                            </div>
                        @endisset
                    </div>
                    <div class="theme-cta">
                        <div>
                            <h2>Cần thêm tính năng riêng?</h2>
                            <p>Mẫu chỉ là điểm bắt đầu. Chúng tôi phát triển thêm theo cách doanh nghiệp đang vận hành.</p>
                        </div>
                        <button class="btn btn-dark" type="button" data-bs-toggle="modal" data-bs-target="#consult-modal">Cùng trao đổi <x-icon name="arrow-right" /></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($relatedThemes->isNotEmpty())
        <section class="section section--soft">
            <div class="container">
                <x-section-head eyebrow="Giao diện liên quan" title="Mẫu tương tự" :href="route('themes.index')" link="Xem tất cả mẫu" />
                <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                    @foreach ($relatedThemes as $related)
                        <div class="col"><x-card.theme :theme="$related" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($operationServices->isNotEmpty())
        <section class="section section--dark">
            <div class="container">
                <x-section-head eyebrow="Sau khi bàn giao" title="Có thể tiếp tục duy trì và phát triển website theo tháng" lead="Không bắt buộc mua cùng lúc. Doanh nghiệp chọn đúng phần đang thiếu." center />
                <div class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                    @foreach ($operationServices as $service)
                        <div class="col"><x-feature-card :icon="$service->icon ?: 'settings'" :title="$service->title" :text="$service->description" :href="$service->url" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-lead-section id="chon-mau" :eyebrow="'Chọn mẫu '.$theme->code" title="Gửi thông tin để nhận cấu trúc và báo giá phù hợp."
        text="WebApp Bắc Ninh sẽ trao đổi nhu cầu, xác định phần cần giữ, phần cần chỉnh và phạm vi chức năng trước khi báo giá."
        :need="'Mẫu giao diện '.$theme->code" />
@endsection
