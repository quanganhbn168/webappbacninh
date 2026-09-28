@extends('layouts.site')

@section('content')
    <x-hero eyebrow="Kho giao diện website" title="Chọn mẫu phù hợp." highlight="Chúng tôi tùy chỉnh theo doanh nghiệp."
        lead="Kho giao diện giúp anh/chị hình dung nhanh phong cách website trước khi triển khai. Mỗi mẫu có thể thay màu sắc, hình ảnh, nội dung, bố cục và bổ sung chức năng theo nhu cầu thực tế.">
        <a class="btn btn-primary" href="#kho">Xem các mẫu giao diện <x-icon name="arrow-right" /></a>
        <a class="btn btn-outline-dark" href="#tu-van-mau">Chưa biết chọn mẫu nào?</a>
        <x-slot:aside>
            <div class="stat-panel">
                <div><strong>{{ $themes->count() }}</strong><span>Mẫu minh họa</span></div>
                <div><strong>{{ $industries->count() }}</strong><span>Nhóm ngành nghề</span></div>
                <div><strong>100%</strong><span>Tùy chỉnh nội dung</span></div>
                <div><strong>Mobile</strong><span>Tối ưu responsive</span></div>
            </div>
        </x-slot:aside>
    </x-hero>

    <div data-theme-library>
        <div class="theme-quick">
            <div class="container">
                <div class="theme-quick__track" role="group" aria-label="Danh mục nhanh">
                    <button class="theme-quick__item active" type="button" data-quick="all"><x-icon name="layout-grid" /><span>Tất cả</span></button>
                    @foreach (\App\Enums\TemplateType::cases() as $type)
                        @if ($themes->contains('type', $type))
                            <button class="theme-quick__item" type="button" data-quick="{{ $type->value }}"><x-icon :name="['doanh-nghiep' => 'building-2', 'ban-hang' => 'shopping-cart', 'landing-page' => 'target', 'dich-vu' => 'briefcase'][$type->value]" /><span>{{ $type->label() }}</span></button>
                        @endif
                    @endforeach
                    @foreach ($industries->take(4) as $industry)
                        <button class="theme-quick__item" type="button" data-quick="{{ $industry->slug }}"><x-icon name="layout-template" /><span>{{ $industry->name }}</span></button>
                    @endforeach
                </div>
            </div>
        </div>

        <section class="section section--soft" id="kho">
            <div class="container">
                <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
                    <div>
                        <p class="eyebrow">Tìm giao diện phù hợp</p>
                        <h2 class="mb-0">Kho giao diện theo ngành và mục tiêu sử dụng</h2>
                    </div>
                    <button class="btn btn-outline-primary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#theme-filters" aria-controls="theme-filters"><x-icon name="settings-2" /> Bộ lọc</button>
                </div>
                <div class="row g-4 align-items-start">
                    <div class="col-lg-3">
                        <div class="offcanvas-lg offcanvas-start" tabindex="-1" id="theme-filters" aria-labelledby="theme-filters-title">
                            <div class="offcanvas-header">
                                <h2 class="offcanvas-title h5" id="theme-filters-title">Bộ lọc giao diện</h2>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#theme-filters" aria-label="Đóng"></button>
                            </div>
                            <div class="offcanvas-body">
                                <form class="theme-filter" data-theme-filters>
                                    <div class="theme-filter__head"><strong>Bộ lọc giao diện</strong><button class="btn btn-link btn-sm p-0" type="button" data-theme-reset>Đặt lại</button></div>
                                    <div class="theme-filter__group">
                                        <label class="form-label" for="theme-search">Tìm kiếm</label>
                                        <div class="search-field"><x-icon name="search" /><input class="form-control" id="theme-search" name="search" type="search" placeholder="Tên mẫu, ngành nghề…"></div>
                                    </div>
                                    <fieldset class="theme-filter__group">
                                        <legend class="form-label">Loại website</legend>
                                        @foreach (\App\Enums\TemplateType::cases() as $type)
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="type" value="{{ $type->value }}" id="type-{{ $type->value }}"><label class="form-check-label" for="type-{{ $type->value }}">{{ $type->label() }}</label></div>
                                        @endforeach
                                    </fieldset>
                                    <div class="theme-filter__group">
                                        <label class="form-label" for="theme-industry">Ngành nghề</label>
                                        <select class="form-select" id="theme-industry" name="industry">
                                            <option value="all">Tất cả ngành nghề</option>
                                            @foreach ($industries as $industry)
                                                <option value="{{ $industry->slug }}">{{ $industry->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <fieldset class="theme-filter__group">
                                        <legend class="form-label">Mức đầu tư</legend>
                                        @foreach (['all' => 'Tất cả', 'under10' => 'Dưới 10 triệu', '10to20' => '10 - 20 triệu', 'over20' => 'Trên 20 triệu'] as $value => $label)
                                            <div class="form-check"><input class="form-check-input" type="radio" name="price" value="{{ $value }}" id="price-{{ $value }}" @checked($value === 'all')><label class="form-check-label" for="price-{{ $value }}">{{ $label }}</label></div>
                                        @endforeach
                                    </fieldset>
                                    @if ($features->isNotEmpty())
                                        <fieldset class="theme-filter__group">
                                            <legend class="form-label">Tính năng</legend>
                                            @foreach ($features as $feature)
                                                <div class="form-check"><input class="form-check-input" type="checkbox" name="feature" value="{{ $feature->slug }}" id="feature-{{ $feature->slug }}"><label class="form-check-label" for="feature-{{ $feature->slug }}">{{ $feature->name }}</label></div>
                                            @endforeach
                                        </fieldset>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="theme-resultbar">
                            <p class="mb-0"><strong data-theme-count>{{ $themes->count() }}</strong> giao diện phù hợp</p>
                            <label class="d-flex align-items-center gap-2"><span class="small text-muted text-nowrap">Sắp xếp</span>
                                <select class="form-select form-select-sm" data-theme-sort>
                                    <option value="featured">Nổi bật</option>
                                    <option value="newest">Mới nhất</option>
                                    <option value="price-asc">Giá thấp đến cao</option>
                                    <option value="price-desc">Giá cao đến thấp</option>
                                    <option value="name-asc">Tên A - Z</option>
                                </select>
                            </label>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3" data-theme-chips hidden></div>
                        <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-xl-3" data-theme-list aria-live="polite">
                            @foreach ($themes as $theme)
                                <div class="col" data-theme-card-wrapper><x-card.theme :theme="$theme" /></div>
                            @endforeach
                        </div>
                        <div class="empty-state" data-theme-empty hidden>
                            <h3>Chưa có mẫu phù hợp</h3>
                            <p>Hãy bỏ bớt bộ lọc hoặc gửi nhu cầu để WebApp Bắc Ninh tư vấn mẫu gần nhất.</p>
                            <button class="btn btn-primary" type="button" data-theme-reset>Đặt lại bộ lọc</button>
                        </div>
                        <nav class="mt-4" aria-label="Phân trang kho giao diện"><ul class="pagination justify-content-center flex-wrap gap-1" data-theme-pager></ul></nav>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <section class="section">
        <div class="container">
            <div class="note-box">
                <span class="icon-tile icon-tile--lg"><x-icon name="pencil-ruler" /></span>
                <div>
                    <p class="eyebrow mb-1">Không phải mẫu đóng khung</p>
                    <h2 class="h3">Mỗi giao diện đều được tùy chỉnh theo nhận diện và nội dung của doanh nghiệp.</h2>
                    <p class="mb-0 text-muted">Kho giao diện chỉ giúp rút ngắn thời gian chọn phong cách. Website bàn giao vẫn được thay logo, màu sắc, hình ảnh, bố cục nội dung, danh mục và chức năng phù hợp với dự án thực tế.</p>
                </div>
                <a class="btn btn-primary" href="#tu-van-mau">Trao đổi nhu cầu</a>
            </div>
        </div>
    </section>

    <x-lead-section id="tu-van-mau" eyebrow="Nhận tư vấn mẫu" title="Gửi mã giao diện hoặc mô tả phong cách anh/chị thích."
        text="Chúng tôi sẽ kiểm tra nhu cầu, đề xuất mẫu phù hợp và báo chi phí tùy chỉnh theo nội dung, tính năng và tiến độ. Không bắt buộc dùng nguyên mẫu, có thể kết hợp nhiều bố cục."
        need="Tư vấn mẫu giao diện" />
@endsection
