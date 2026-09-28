@extends('layouts.site')

@section('content')
    @php($gallery = $theme->gallery_urls ?: [$theme->image_url])
    <section class="page-hero page-hero--compact">
        <div class="container">
            <x-breadcrumbs :items="$breadcrumbs" />
            <div class="row g-4 g-lg-5 align-items-start">
                <div class="col-lg-8">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @if (filled($theme->badge))<span class="tag tag--gold">{{ $theme->badge }}</span>@endif
                        <span class="tag">{{ $theme->type_label }}</span>
                        <span class="tag">{{ $theme->industry_label }}</span>
                    </div>
                    <h1>{{ $theme->name }}</h1>
                    <p class="page-hero__lead">{{ $theme->description }}</p>
                    <div class="gallery" data-gallery>
                        <img class="gallery__main" src="{{ $gallery[0] }}" alt="{{ $theme->name }}" width="960" height="600" data-gallery-main fetchpriority="high">
                        @if (count($gallery) > 1)
                            <div class="gallery__thumbs">
                                @foreach ($gallery as $index => $url)
                                    <button type="button" @class(['gallery__thumb', 'active' => $index === 0]) data-gallery-image="{{ $url }}" aria-label="Xem ảnh {{ $index + 1 }}" aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"><img src="{{ $url }}" alt="" width="120" height="80" loading="lazy"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4">
                    <aside class="order-card">
                        <span class="order-card__code">{{ $theme->code }}</span>
                        <span class="text-muted small">Chi phí triển khai tham khảo từ</span>
                        <strong class="order-card__price">{{ money($theme->price ?? 0) }}</strong>
                        <p class="small text-muted">Giá chính thức phụ thuộc nội dung, chức năng và mức độ tùy chỉnh.</p>
                        <dl class="order-card__specs">
                            <div><dt>Thời gian dự kiến</dt><dd>{{ $theme->duration ?: 'Theo phạm vi' }}</dd></div>
                            <div><dt>Loại website</dt><dd>{{ $theme->type_label }}</dd></div>
                            <div><dt>Hiển thị</dt><dd>Responsive đa thiết bị</dd></div>
                            <div><dt>Tùy chỉnh</dt><dd>Màu, nội dung, bố cục</dd></div>
                        </dl>
                        <div class="d-grid gap-2">
                            <a class="btn btn-primary" href="#chon-mau">Chọn mẫu này</a>
                            @if ($theme->demo_url)
                                <a class="btn btn-outline-dark" href="{{ $theme->demo_url }}" target="_blank" rel="noopener">Xem demo <x-icon name="external-link" /></a>
                            @endif
                            <a class="btn btn-outline-primary" href="tel:{{ site_config('phone_href') }}"><x-icon name="phone" /> {{ site_config('phone') }}</a>
                        </div>
                        <p class="small text-muted mb-0 mt-3">Không bắt buộc giữ nguyên mẫu. Có thể kết hợp bố cục từ nhiều giao diện.</p>
                    </aside>
                </div>
            </div>
        </div>
    </section>

    @if ($theme->audiences)
        <section class="section">
            <div class="container">
                <div class="row g-4 g-lg-5">
                    <div class="col-lg-5">
                        <p class="eyebrow">Mẫu này phù hợp với ai?</p>
                        <h2>Một nền tảng website rõ ràng để tiếp tục bán hàng và phát triển nội dung.</h2>
                        <p class="lead-text mt-3 mb-0">Mẫu được sử dụng như điểm xuất phát. Khi triển khai, WebApp Bắc Ninh sẽ thay nhận diện, nội dung và điều chỉnh cấu trúc theo doanh nghiệp.</p>
                    </div>
                    <div class="col-lg-7">
                        <ul class="check-list check-list--gold two-columns">
                            @foreach ($theme->audiences as $item)<li>{{ $item }}</li>@endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if ($theme->pages || $theme->included_features)
        <section class="section section--soft">
            <div class="container">
                <x-section-head eyebrow="Phạm vi bàn giao" title="Các trang và chức năng cơ bản đã có trong mẫu" lead="Phạm vi có thể điều chỉnh sau khi chốt cấu trúc và dữ liệu thực tế." center />
                <div class="row g-4">
                    @if ($theme->pages)
                        <div class="col-lg-5">
                            <div class="feature-card"><h3>Các trang dự kiến</h3><ul class="check-list">@foreach ($theme->pages as $page)<li>{{ $page }}</li>@endforeach</ul></div>
                        </div>
                    @endif
                    @if ($theme->included_features)
                        <div class="col-lg-7">
                            <div class="feature-card"><h3>Chức năng và tiêu chuẩn bàn giao</h3><ul class="check-list two-columns">@foreach ($theme->included_features as $feature)<li>{{ $feature }}</li>@endforeach</ul></div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    @if ($theme->customizations)
        <section class="section">
            <div class="container">
                <div class="media-split">
                    <div class="media-split__image"><img src="{{ $gallery[1] ?? $gallery[0] }}" alt="Tùy chỉnh {{ $theme->name }}" width="720" height="540" loading="lazy"></div>
                    <div>
                        <p class="eyebrow">Tùy chỉnh theo doanh nghiệp</p>
                        <h2>Không phải mua một mẫu đóng khung.</h2>
                        <p class="lead-text my-3">Màu sắc, hình ảnh, nội dung và chức năng sẽ được thay đổi để website phù hợp với thương hiệu và cách doanh nghiệp đang vận hành.</p>
                        <ul class="check-list check-list--gold">@foreach ($theme->customizations as $item)<li>{{ $item }}</li>@endforeach</ul>
                    </div>
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

    @if ($relatedThemes->isNotEmpty())
        <section class="section section--soft">
            <div class="container">
                <x-section-head eyebrow="Giao diện liên quan" title="Tham khảo thêm các mẫu gần nhu cầu này" :href="route('themes.index')" link="Xem toàn bộ kho giao diện" />
                <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                    @foreach ($relatedThemes as $related)
                        <div class="col"><x-card.theme :theme="$related" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-lead-section id="chon-mau" :eyebrow="'Chọn mẫu '.$theme->code" title="Gửi thông tin để nhận cấu trúc và báo giá phù hợp."
        text="WebApp Bắc Ninh sẽ trao đổi nhu cầu, xác định phần cần giữ, phần cần chỉnh và phạm vi chức năng trước khi báo giá."
        :need="'Mẫu giao diện '.$theme->code" />
@endsection
