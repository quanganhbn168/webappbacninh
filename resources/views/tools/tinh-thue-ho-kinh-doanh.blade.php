@extends('layouts.basic')

@section('tool', '1')
@section('robots', 'index, follow')
@section('title', 'Tính thuế Hộ kinh doanh 2026 - WebApp Bắc Ninh')
@section('meta_description', 'Công cụ tính thuế hộ kinh doanh miễn phí theo Luật mới 2026. Ngưỡng miễn thuế 500 triệu/năm, tính thuế GTGT và TNCN theo ngành nghề.')
@section('meta_keywords', 'thuế hộ kinh doanh, thuế khoán, ngưỡng 500 triệu, thuế GTGT, thuế TNCN, kinh doanh cá nhân')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <span class="badge text-bg-success bg-opacity-10 text-success px-3 py-2 mb-3 fw-bold tool-hero__badge">
                    <i class="fas fa-store me-1"></i> Hộ kinh doanh
                </span>
                <h1 class="display-5 fw-bold mb-3"><i class="fas fa-store text-primary me-2"></i>Tính thuế Hộ kinh doanh</h1>
                <p class="text-secondary lead">Công cụ tính thuế GTGT và TNCN cho hộ kinh doanh cá nhân theo Luật mới 2026.</p>
            </div>

            <div class="card shadow-lg border-0 rounded-4" id="tax-tool" data-url="{{ route('tools.tax.household.calculate') }}">
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4">
                        <span class="form-label fw-bold text-secondary small text-uppercase d-block">Chọn phiên bản thuế</span>
                        <div class="tool-toggle" data-toggle-group="version" role="group" aria-label="Phiên bản thuế">
                            <button type="button" class="btn" data-value="2025" aria-pressed="false">2025 (Ngưỡng 100tr)</button>
                            <button type="button" class="btn btn-primary active" data-value="2026" aria-pressed="true">2026 (Ngưỡng 500tr) ✨</button>
                        </div>
                    </div>

                    <fieldset class="mb-4">
                        <legend class="form-label fw-bold fs-6"><i class="fas fa-industry text-info me-1"></i> Chọn ngành nghề</legend>
                        <div class="row g-3">
                            @foreach ($sectors as $sector)
                                <div class="col-md-4">
                                    <input type="radio" class="btn-check" name="sector" id="sector-{{ $sector['value'] }}" value="{{ $sector['value'] }}" autocomplete="off" @checked($sector['value'] === 'service')>
                                    <label class="btn btn-outline-primary w-100 h-100 text-start p-3" for="sector-{{ $sector['value'] }}">
                                        <span class="d-block fw-bold small mb-1">{{ $sector['label'] }}</span>
                                        <span class="d-block small">GTGT: <strong>{{ $sector['vat_rate'] }}%</strong> | TNCN: <strong>{{ $sector['pit_rate'] }}%</strong></span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </fieldset>

                    <div class="row g-4">
                        <div class="col-lg-5">
                            <label class="form-label fw-bold" for="revenue"><i class="fas fa-money-bill-wave text-success me-1"></i> Doanh thu năm (VNĐ)</label>
                            <div class="tool-input-suffix">
                                <input type="text" inputmode="numeric" class="form-control form-control-lg" id="revenue" value="600000000" placeholder="600.000.000">
                                <span>VNĐ</span>
                            </div>
                            <small class="text-secondary">Ngưỡng miễn thuế: <strong data-threshold>500 triệu</strong>/năm</small>
                        </div>

                        <div class="col-lg-7">
                            <div class="tool-result h-100" data-result hidden aria-live="polite">
                                <div class="text-center py-4" data-exempt hidden>
                                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                                    <p class="h4 fw-bold">Không phải nộp thuế!</p>
                                    <p class="opacity-75 mb-0">Doanh thu dưới ngưỡng chịu thuế <span data-threshold>500 triệu</span>/năm</p>
                                </div>
                                <div data-taxed hidden>
                                    <div class="text-center mb-4">
                                        <p class="mb-1 opacity-75">Tổng thuế phải nộp</p>
                                        <p class="display-4 fw-bold mb-0" data-field="total_tax" data-format="money"></p>
                                        <p class="small mt-2 opacity-75">Thuế suất hiệu dụng: <strong data-field="effective_rate" data-format="percent"></strong></p>
                                    </div>
                                    <hr class="opacity-25">
                                    <div class="row text-center g-3">
                                        <div class="col-6">
                                            <p class="mb-1 opacity-75 small">Doanh thu chịu thuế</p>
                                            <p class="fw-bold h5 mb-0" data-field="taxable_amount" data-format="money"></p>
                                        </div>
                                        <div class="col-6">
                                            <p class="mb-1 opacity-75 small">Thực nhận</p>
                                            <p class="fw-bold h5 mb-0 text-warning" data-field="net_amount" data-format="money"></p>
                                        </div>
                                    </div>
                                    <hr class="opacity-25">
                                    <div class="small" data-rows="breakdown">
                                        <template>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="opacity-75"><span data-item="label" data-format="text"></span> (<span data-item="rate" data-format="percent"></span>):</span>
                                                <span data-item="tax" data-format="money"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-danger" data-tool-error hidden role="alert"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-5">
                <div class="col-md-6">
                    <div class="tool-info h-100">
                        <h2 class="h5 fw-bold mb-3"><i class="fas fa-info-circle text-success me-2"></i>Ngưỡng miễn thuế 2026</h2>
                        <p class="mb-0">Từ 01/01/2026, hộ kinh doanh có doanh thu ≤ <strong>500 triệu/năm</strong> không phải nộp thuế GTGT và TNCN.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="tool-info h-100">
                        <h2 class="h5 fw-bold mb-3"><i class="fas fa-gavel text-warning me-2"></i>Bãi bỏ thuế khoán</h2>
                        <p class="mb-0">Từ 2026, hộ kinh doanh chuyển sang <strong>tự kê khai</strong> thuế dựa trên doanh thu thực tế, bỏ hình thức thuế khoán.</p>
                    </div>
                </div>
            </div>

            @include('tools.partials.tax-links', ['current' => 'household'])
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
    const { ToolKit } = window;
    const root = document.getElementById('tax-tool');
    const error = root.querySelector('[data-tool-error]');
    let timer;

    const version = ToolKit.toggleGroup(root.querySelector('[data-toggle-group]'), () => calculate());
    const revenue = ToolKit.moneyInput(document.getElementById('revenue'), () => {
        clearTimeout(timer);
        timer = setTimeout(calculate, 300);
    });

    async function calculate() {
        document.querySelectorAll('[data-threshold]').forEach(el => { el.textContent = version() === '2026' ? '500 triệu' : '100 triệu'; });
        try {
            const { data } = await ToolKit.postJson(root.dataset.url, {
                revenue: revenue(),
                sector: root.querySelector('input[name="sector"]:checked').value,
                version: version(),
            });
            error.hidden = true;
            root.querySelector('[data-result]').hidden = false;
            root.querySelector('[data-exempt]').hidden = data.taxable_amount > 0;
            root.querySelector('[data-taxed]').hidden = !(data.taxable_amount > 0);
            ToolKit.render(root, data);
        } catch (exception) {
            error.textContent = exception.message;
            error.hidden = false;
        }
    }

    root.querySelectorAll('input[name="sector"]').forEach(input => input.addEventListener('change', calculate));
    calculate();
</script>
@endpush
