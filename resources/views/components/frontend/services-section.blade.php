<section id="services" class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase ls-2">Dịch vụ cốt lõi</h6>
            <h2 class="fw-bold display-6">Giải pháp toàn diện</h2>
        </div>
        <div class="row g-4 text-center">
            @forelse($services as $service)
            <div class="col-md-3">
                <div class="p-4 border rounded-3 h-100 hover-shadow transition">
                    @if($service->icon)
                        <i class="{{ $service->icon }} fa-3x text-primary mb-3"></i>
                    @else
                        <i class="fas fa-cube fa-3x text-muted mb-3"></i>
                    @endif
                    <h5 class="fw-bold">{{ $service->title }}</h5>
                    <p class="text-muted small">{{ $service->description }}</p>
                    <a href="{{ route('services.show', $service->slug) }}" class="text-decoration-none small fw-bold mt-2 d-inline-block">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted">
                Dịch vụ đang được cập nhật...
            </div>
            @endforelse
        </div>
    </div>
</section>