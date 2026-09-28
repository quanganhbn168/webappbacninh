@extends('layouts.site')

@section('content')
    <x-hero title="Sản phẩm & Giải pháp đóng gói" highlight="Sẵn sàng cho doanh nghiệp bạn"
        lead="Các mẫu website theo ngành và phần mềm quản lý được dựng sẵn, dễ dàng tùy biến theo nhu cầu thực tế của doanh nghiệp, giúp bạn triển khai nhanh chóng, tiết kiệm chi phí và vận hành hiệu quả."
        :image="asset('frontend/images/products-hero.webp')" image-alt="Phần mềm quản lý khách hàng trên laptop và điện thoại" note="Quản lý khách hàng – Dễ dàng hơn mỗi ngày!">
        <a class="btn btn-primary" href="#san-pham">Khám phá sản phẩm <x-icon name="arrow-right" /></a>
        <button class="btn btn-outline-dark" type="button" data-consult="Tư vấn lựa chọn sản phẩm">Nhận tư vấn lựa chọn</button>
        <x-slot:after>
            <x-trust-row :items="[
                ['icon' => 'rocket', 'title' => 'Triển khai linh hoạt', 'text' => 'Phù hợp mọi quy mô doanh nghiệp'],
                ['icon' => 'settings', 'title' => 'Dễ tùy biến', 'text' => 'Điều chỉnh theo nhu cầu thực tế'],
                ['icon' => 'chart-column', 'title' => 'Dễ quản trị', 'text' => 'Giao diện thân thiện, dễ sử dụng'],
                ['icon' => 'headset', 'title' => 'Hỗ trợ lâu dài', 'text' => 'Đồng hành cùng sự phát triển'],
            ]" />
        </x-slot:after>
    </x-hero>

    <section class="section" id="san-pham">
        <div class="container" data-catalog>
            <div class="catalog-toolbar">
                <x-section-head title="Danh mục sản phẩm" lead="Khám phá các giải pháp website và phần mềm phù hợp với ngành nghề của bạn." class="mb-0" />
                <div class="d-flex flex-wrap gap-2">
                    <label class="search-field">
                        <span class="visually-hidden">Tìm sản phẩm</span>
                        <x-icon name="search" />
                        <input class="form-control" type="search" placeholder="Tìm kiếm sản phẩm…" data-catalog-search>
                    </label>
                    <label class="d-flex align-items-center gap-2">
                        <span class="small text-muted text-nowrap">Sắp xếp</span>
                        <select class="form-select" data-catalog-sort>
                            <option value="featured">Nổi bật</option>
                            <option value="az">Tên A – Z</option>
                            <option value="za">Tên Z – A</option>
                        </select>
                    </label>
                </div>
            </div>
            <div class="chip-list mb-4" role="group" aria-label="Lọc sản phẩm">
                <button class="chip active" type="button" data-catalog-filter="all" aria-pressed="true">Tất cả sản phẩm</button>
                @foreach ($groups as $group)
                    <button class="chip" type="button" data-catalog-filter="{{ $group->value }}" aria-pressed="false">{{ $group->label() }}</button>
                @endforeach
            </div>
            <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                @foreach ($products as $product)
                    <div class="col" data-catalog-item data-categories="{{ $product->group?->value }}" data-title="{{ $product->name }}">
                        <x-card.product :product="$product" />
                    </div>
                @endforeach
            </div>
            <p class="empty-state mt-4" data-catalog-empty hidden>Không tìm thấy sản phẩm. Hãy thử từ khóa khác.</p>
        </div>
    </section>

    <section class="section section--cream">
        <div class="container">
            <div class="row g-4 g-lg-5 align-items-center">
                <div class="col-lg-5">
                    <h2>Chọn mẫu sẵn.<br>Phát triển theo cách của bạn.</h2>
                    <p class="lead-text my-3">Tất cả sản phẩm đều có thể tùy biến giao diện, tính năng và tích hợp hệ thống theo nhu cầu riêng của doanh nghiệp.</p>
                    <button class="btn btn-primary" type="button" data-consult="Tùy chỉnh sản phẩm">Trao đổi nhu cầu tùy chỉnh <x-icon name="arrow-right" /></button>
                </div>
                <div class="col-lg-4 col-md-6">
                    <img class="img-fluid rounded-3" src="{{ asset('frontend/images/contact-hero.webp') }}" alt="Đồng hành cùng doanh nghiệp" width="560" height="400" loading="lazy">
                </div>
                <div class="col-lg-3 col-md-6">
                    <ul class="check-list check-list--dot">
                        <li>Giao diện theo bộ nhận diện</li>
                        <li>Module và tính năng mở rộng</li>
                        <li>Kết nối hệ thống/API</li>
                        <li>Hướng dẫn sử dụng và bàn giao</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <x-cta-banner title="Bạn đang phân vân giữa các sản phẩm?" text="Đội ngũ chuyên gia tư vấn giải pháp phù hợp với nhu cầu và ngân sách của doanh nghiệp." need="Tư vấn lựa chọn sản phẩm" :secondary-href="route('themes.index')" secondary-label="Xem kho giao diện" />
@endsection
