@extends('layouts.site')

@section('content')
    <x-hero variant="catalog" />

    <div data-theme-library>
        <section class="section theme-library" id="kho">
            <div class="container">
                <h2 class="visually-hidden">Danh sách mẫu website</h2>
                <form class="theme-toolbar" data-theme-filters aria-label="Lọc giao diện">
                    <div class="search-field theme-toolbar__search">
                        <x-icon name="search" />
                        <input class="form-control" name="search" type="search" placeholder="Tên mẫu, ngành nghề…" aria-label="Tìm giao diện">
                    </div>
                    <div class="dropdown">
                        <button class="btn theme-toolbar__button dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">Loại website</button>
                        <div class="dropdown-menu theme-toolbar__menu">
                            @foreach (\App\Enums\TemplateType::cases() as $type)
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="type" value="{{ $type->value }}" id="type-{{ $type->value }}"><label class="form-check-label" for="type-{{ $type->value }}">{{ $type->label() }}</label></div>
                            @endforeach
                        </div>
                    </div>
                    <select class="form-select theme-toolbar__select" name="industry" aria-label="Ngành nghề">
                        <option value="all">Tất cả ngành nghề</option>
                        @foreach ($industries as $industry)
                            <option value="{{ $industry->slug }}">{{ $industry->name }}</option>
                        @endforeach
                    </select>
                    <div class="dropdown">
                        <button class="btn theme-toolbar__button dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">Mức giá</button>
                        <div class="dropdown-menu theme-toolbar__menu">
                            @foreach (['all' => 'Tất cả', 'under10' => 'Dưới 10 triệu', '10to20' => '10 - 20 triệu', 'over20' => 'Trên 20 triệu'] as $value => $label)
                                <div class="form-check"><input class="form-check-input" type="radio" name="price" value="{{ $value }}" id="price-{{ $value }}" @checked($value === 'all')><label class="form-check-label" for="price-{{ $value }}">{{ $label }}</label></div>
                            @endforeach
                        </div>
                    </div>
                    @if ($features->isNotEmpty())
                        <div class="dropdown">
                            <button class="btn theme-toolbar__button dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">Tính năng</button>
                            <div class="dropdown-menu theme-toolbar__menu">
                                @foreach ($features as $feature)
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="feature" value="{{ $feature->slug }}" id="feature-{{ $feature->slug }}"><label class="form-check-label" for="feature-{{ $feature->slug }}">{{ $feature->name }}</label></div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </form>
                <div class="theme-resultbar">
                    <p class="mb-0"><strong data-theme-count>{{ $themes->count() }}</strong> giao diện</p>
                    <div class="d-flex flex-wrap align-items-center gap-2" data-theme-chips hidden></div>
                    <label class="d-flex align-items-center gap-2 ms-auto"><span class="small text-muted text-nowrap">Sắp xếp</span>
                        <select class="form-select form-select-sm" data-theme-sort>
                            <option value="featured">Nổi bật</option>
                            <option value="newest">Mới nhất</option>
                            <option value="price-asc">Giá thấp đến cao</option>
                            <option value="price-desc">Giá cao đến thấp</option>
                            <option value="name-asc">Tên A - Z</option>
                        </select>
                    </label>
                </div>
                <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3" data-theme-list aria-live="polite">
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
        </section>
    </div>

    @if ($industries->isNotEmpty())
        <section class="section section--soft">
            <div class="container">
                <x-section-head eyebrow="Theo ngành" title="Mẫu website theo lĩnh vực kinh doanh" />
                <x-industry-links :industries="$industries" />
            </div>
        </section>
    @endif

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
