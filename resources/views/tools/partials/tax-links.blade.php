@php($taxTools = [
    'tncn' => ['route' => 'tools.tax', 'icon' => 'fa-user', 'title' => 'Tính thuế TNCN', 'text' => 'Thu nhập cá nhân, lũy tiến 5/7 bậc'],
    'household' => ['route' => 'tools.tax.household', 'icon' => 'fa-store', 'title' => 'Tính thuế Hộ kinh doanh', 'text' => 'GTGT + TNCN theo ngành nghề, ngưỡng 500 triệu'],
    'sme' => ['route' => 'tools.tax.sme', 'icon' => 'fa-building', 'title' => 'Tính thuế Doanh nghiệp', 'text' => 'TNDN 15%/17%/20%, miễn 3 năm đầu'],
])
<section class="mt-5" aria-labelledby="tax-links-title">
    <h2 class="h5 fw-bold mb-3" id="tax-links-title"><i class="fas fa-calculator text-primary me-2"></i>Công cụ tính thuế khác</h2>
    <div class="row g-4">
        @foreach ($taxTools as $key => $tool)
            @continue($key === $current)
            <div class="col-md-6">
                <a href="{{ route($tool['route']) }}" class="card tool-link-card h-100 text-decoration-none border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-25 text-dark me-3" style="width: 3rem; height: 3rem"><i class="fas {{ $tool['icon'] }} fa-lg"></i></span>
                        <span>
                            <span class="d-block fw-bold text-dark">{{ $tool['title'] }}</span>
                            <small class="text-secondary">{{ $tool['text'] }}</small>
                        </span>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</section>
