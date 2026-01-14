<section class="py-5 bg-white border-bottom">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h6 class="text-primary fw-bold text-uppercase ls-2">Giao diện mẫu</h6>
                <h2 class="fw-bold display-6">Kho giao diện phong phú</h2>
            </div>
            <a href="{{ route('templates.index') }}" class="btn btn-outline-primary px-4 d-none d-md-block">Xem tất cả</a>
        </div>

        <div class="row g-4">
            @forelse($templates as $template)
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 hover-top">
                    <div class="card-header bg-light border-bottom-0 pt-3 px-3 pb-0">
                        <div class="d-flex gap-1 mb-2">
                             <div class="rounded-circle bg-danger" style="width: 8px; height: 8px;"></div>
                             <div class="rounded-circle bg-warning" style="width: 8px; height: 8px;"></div>
                             <div class="rounded-circle bg-success" style="width: 8px; height: 8px;"></div>
                        </div>
                    </div>
                    <div class="ratio ratio-4x3 overflow-hidden bg-secondary bg-opacity-10">
                        @if($template->image)
                            <img src="{{ $template->image_url }}" alt="{{ $template->name }}" class="img-fluid object-fit-cover w-100 h-100">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                <i class="fas fa-laptop-code fa-2x"></i>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($template->category)
                            <div class="badge bg-info bg-opacity-10 text-info mb-2">{{ $template->category }}</div>
                        @endif
                        <h6 class="fw-bold mb-1">{{ $template->name }}</h6>
                        <p class="text-muted small">
                            @if($template->is_premium)
                                <span class="badge bg-warning text-dark"><i class="fas fa-crown"></i> Premium</span>
                            @else
                                <span class="badge bg-success">Free</span>
                            @endif
                        </p>
                        @if($template->demo_url)
                            <a href="{{ $template->demo_url }}" target="_blank" class="btn btn-sm btn-primary w-100">Xem Demo</a>
                        @else
                            <button disabled class="btn btn-sm btn-secondary w-100">Đang cập nhật</button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-5">
                Kho giao diện đang được cập nhật...
            </div>
            @endforelse
        </div>
    </div>
</section>