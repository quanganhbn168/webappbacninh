<section id="ecosystem" class="py-5 bg-light">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase ls-2">Hệ sinh thái công cụ</h6>
            <h2 class="fw-bold display-6">Mini-Apps tiện ích</h2>
            <p class="text-muted mx-auto" style="max-width: 500px;">
                Kho công cụ miễn phí giúp bạn vận hành công việc kinh doanh dễ dàng hơn.
            </p>
        </div>

        {{-- Domain Check Tool --}}
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <div class="glass-card p-4 text-center">
                    <h4 class="fw-bold mb-3"><i class="fas fa-search me-2 text-primary"></i>Kiểm tra tên miền</h4>
                    <p class="text-muted small mb-4">Nhập tên miền bạn muốn đăng ký để kiểm tra tình trạng (VD: mybrand.vn, hanoi.com)</p>
                    <form action="{{ route('domain.check') }}" method="GET" class="d-flex gap-2">
                        <input type="text" name="domain" class="form-control form-control-lg" placeholder="Nhập tên miền..." required>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">Kiểm tra</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @foreach($apps as $app)
                <x-app-card 
                    :title="$app->name"
                    :icon="$app->icon"
                    :description="$app->description"
                    :link="$app->link ?? '#'"
                    :badge="$app->badge"
                />
            @endforeach
        </div>
    </div>
</section>