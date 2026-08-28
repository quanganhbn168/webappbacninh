@extends('layouts.utility')

@section('title', 'Lấy Ảnh Cover Hàng Loạt (Bulk Thumbnail) - WebApp Bắc Ninh')

@push('head')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak]{display:none!important}</style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="container py-5">
    
    <div class="card shadow-lg border-0 rounded-lg" x-data="BulkThumb()" x-init="init()">
        <div class="card-body p-4 p-md-5">

            {{-- Alerts --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="text-center mb-5">
                <h1 class="h2 fw-bold text-dark mb-2">Lấy Ảnh Cover Video — Hàng loạt</h1>
                <p class="text-muted">Dán nhiều link YouTube/TikTok, lấy thumbnail, sửa tiêu đề và tải tất cả trong một nốt nhạc.</p>
                <div class="mt-3">
                    <a href="{{ route('cover.page') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại chế độ tải đơn lẻ
                    </a>
                </div>
            </div>

            {{-- Khu dán link --}}
            <div class="mb-4">
                <label class="form-label fw-bold small text-uppercase text-secondary">Dán danh sách link (Mỗi link 1 dòng)</label>
                <textarea x-model="urlsText" class="form-control form-control-lg bg-light" rows="8" 
                          placeholder="https://www.tiktok.com/@user/video/123...&#10;https://www.youtube.com/watch?v=abc..."></textarea>
                
                <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
                    <button @click="parseUrls()" class="btn btn-primary" :disabled="isLoading">
                        <span x-show="!isLoading"><i class="fas fa-search me-1"></i> Phân tích danh sách</span>
                        <span x-show="isLoading"><span class="spinner-border spinner-border-sm me-1"></span> Đang xử lý...</span>
                    </button>
                    
                    <button @click="clearAll()" class="btn btn-light border">Xoá tất cả</button>

                    <div class="ms-auto d-flex align-items-center gap-2">
                        <label class="small text-muted mb-0">Sắp xếp:</label>
                        <select x-model="sortBy" @change="applySort()" class="form-select form-select-sm" style="width: auto;">
                            <option value="none">Mặc định</option>
                            <option value="title_asc">Tiêu đề A→Z</option>
                            <option value="title_desc">Tiêu đề Z→A</option>
                            <option value="provider">Theo nền tảng</option>
                        </select>
                    </div>
                </div>

                <template x-if="error">
                    <div class="alert alert-danger mt-3" x-text="error"></div>
                </template>
            </div>

            {{-- Danh sách kết quả --}}
            <div class="mt-5" x-show="items.length" x-cloak>
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAllMeta" @change="toggleSelectAll($event)">
                        <label class="form-check-label user-select-none" for="selectAllMeta">
                            Chọn tất cả (<span x-text="selectedCount()"></span>/<span x-text="items.length"></span>)
                        </label>
                    </div>

                    <div class="d-flex gap-2">
                        <button @click="downloadSelected()" class="btn btn-success">
                            <i class="fas fa-file-zipper me-1"></i> Tải ZIP (Đã chọn)
                        </button>
                        <button @click="selectAll(); downloadSelected()" class="btn btn-outline-success">
                            <i class="fas fa-download me-1"></i> Tải ZIP (Tất cả)
                        </button>
                    </div>
                </div>

                <div class="row g-3">
                    <template x-for="(it, idx) in items" :key="it.uid">
                        <div class="col-lg-6">
                            <div class="card h-100 border shadow-sm">
                                <div class="card-body p-2">
                                    <div class="d-flex gap-3">
                                        <div class="flex-shrink-0" style="width: 120px;">
                                            <img :src="it.thumbnail_url" class="img-fluid rounded border" loading="lazy" alt="">
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <span class="badge" 
                                                      :class="it.provider==='tiktok' ? 'bg-dark text-white' : 'bg-danger text-white'" 
                                                      x-text="it.provider.toUpperCase()"></span>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" x-model="it.selected">
                                                </div>
                                            </div>

                                            <div class="mb-2">
                                                <input type="text" x-model="it.title" class="form-control form-control-sm" placeholder="Nhập tên file...">
                                            </div>

                                            <div class="d-flex gap-1">
                                                <button @click="moveUp(idx)" class="btn btn-xs btn-outline-secondary py-0" title="Lên">↑</button>
                                                <button @click="moveDown(idx)" class="btn btn-xs btn-outline-secondary py-0" title="Xuống">↓</button>
                                                <a :href="it.original_url" target="_blank" class="ms-auto small text-decoration-none">Xem gốc <i class="fas fa-external-link-alt small"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Form submit bulk (ẩn) --}}
            <form id="bulkForm" class="hidden d-none" method="POST" action="{{ route('cover.download.bulk') }}">
                @csrf
                <div id="bulkPayload"></div>
            </form>

        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/tools/bulk-anh-cover.js') }}"></script>
@endpush
