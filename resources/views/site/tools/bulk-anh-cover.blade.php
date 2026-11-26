@extends('layouts.tool')

@section('tool-content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4" id="bulk-tool" data-url="{{ route('cover.getInfo') }}">
        <div class="card-body p-4 p-md-5">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif

            <div class="text-center mb-5">
                <h1 class="h2 fw-bold mb-2">Lấy Ảnh Cover Video — Hàng loạt</h1>
                <p class="text-secondary">Dán nhiều link YouTube/TikTok, lấy thumbnail, sửa tiêu đề và tải tất cả trong một nốt nhạc.</p>
                <a href="{{ route('cover.page') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4 mt-2">
                    <x-icon name="arrow-left" class="me-1" /> Quay lại chế độ tải đơn lẻ
                </a>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold small text-uppercase text-secondary" for="bulk-urls">Dán danh sách link (Mỗi link 1 dòng)</label>
                <textarea id="bulk-urls" class="form-control form-control-lg bg-light" rows="8" placeholder="https://www.tiktok.com/@user/video/123...&#10;https://www.youtube.com/watch?v=abc..."></textarea>

                <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
                    <button type="button" class="btn btn-primary" data-parse>
                        <span data-idle><x-icon name="search" class="me-1" /> Phân tích danh sách</span>
                        <span data-busy hidden><span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span> Đang xử lý...</span>
                    </button>
                    <button type="button" class="btn btn-light border" data-clear>Xoá tất cả</button>
                    <div class="ms-auto d-flex align-items-center gap-2">
                        <label class="small text-secondary mb-0" for="bulk-sort">Sắp xếp:</label>
                        <select id="bulk-sort" class="form-select form-select-sm w-auto">
                            <option value="none">Mặc định</option>
                            <option value="title_asc">Tiêu đề A→Z</option>
                            <option value="title_desc">Tiêu đề Z→A</option>
                            <option value="provider">Theo nền tảng</option>
                        </select>
                    </div>
                </div>

                <div class="alert alert-danger mt-3" data-error hidden role="alert"></div>
                <div class="alert alert-warning mt-3" data-skipped hidden role="status"></div>
            </div>

            <div class="mt-5" data-results hidden>
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="select-all" checked>
                        <label class="form-check-label user-select-none" for="select-all">Chọn tất cả (<span data-selected-count>0</span>/<span data-total>0</span>)</label>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" data-download="selected"><x-icon name="file-archive" class="me-1" /> Tải ZIP (Đã chọn)</button>
                        <button type="button" class="btn btn-outline-success" data-download="all"><x-icon name="download" class="me-1" /> Tải ZIP (Tất cả)</button>
                    </div>
                </div>
                <div class="row g-3" data-list></div>
            </div>

            <template data-card>
                <div class="col-lg-6">
                    <div class="card h-100 border shadow-sm">
                        <div class="card-body p-2">
                            <div class="d-flex gap-3">
                                <div class="flex-shrink-0" style="width: 120px"><img class="img-fluid rounded border" loading="lazy" alt="" width="120" height="68" data-thumb></div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge" data-provider></span>
                                        <input class="form-check-input" type="checkbox" aria-label="Chọn video" data-select>
                                    </div>
                                    <input type="text" class="form-control form-control-sm mb-2" placeholder="Nhập tên file..." aria-label="Tên file" data-title>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-secondary py-0" title="Lên" data-move="-1">↑</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary py-0" title="Xuống" data-move="1">↓</button>
                                        <a target="_blank" rel="noopener" class="ms-auto small text-decoration-none" data-original>Xem gốc <x-icon name="external-link" class="small" /></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <form id="bulkForm" class="d-none" method="POST" action="{{ route('cover.download.bulk') }}">
                @csrf
                <div id="bulkPayload"></div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/tools/bulk-anh-cover.js')
@endpush
