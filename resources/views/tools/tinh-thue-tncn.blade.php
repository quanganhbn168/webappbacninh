@extends('layouts.basic')

@section('tool', '1')
@section('robots', 'index, follow')
@section('title', 'Tính thuế Thu nhập Cá nhân (TNCN) 2026 - WebApp Bắc Ninh')
@section('meta_description', 'Công cụ tính thuế thu nhập cá nhân (TNCN) miễn phí theo Luật mới 109/2025/QH15. Hỗ trợ biểu thuế 2025 và 2026, tính tự động giảm trừ gia cảnh.')
@section('meta_keywords', 'tính thuế tncn, thuế thu nhập cá nhân 2026, biểu thuế lũy tiến, giảm trừ gia cảnh, công cụ tính thuế')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <span class="badge text-bg-danger bg-opacity-10 text-danger px-3 py-2 mb-3 fw-bold tool-hero__badge">
                    <i class="fas fa-fire me-1"></i> Luật mới 2026
                </span>
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-calculator text-primary me-2"></i>Tính thuế Thu nhập Cá nhân
                </h1>
                <p class="text-secondary lead">Công cụ tính thuế TNCN theo Luật 109/2025/QH15 (5 bậc mới) và biểu thuế cũ (7 bậc).</p>
            </div>

            <div class="card shadow-lg border-0 rounded-4" id="tax-tool" data-url="{{ route('tools.tax.calculate') }}">
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4">
                        <span class="form-label fw-bold text-secondary small text-uppercase d-block">Chọn phiên bản thuế</span>
                        <div class="tool-toggle" data-toggle-group="version" role="group" aria-label="Phiên bản thuế">
                            <button type="button" class="btn" data-value="2025" aria-pressed="false">2025 (7 bậc cũ)</button>
                            <button type="button" class="btn btn-primary active" data-value="2026" aria-pressed="true">2026 (5 bậc mới) ✨</button>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold" for="gross-income"><i class="fas fa-money-bill-wave text-success me-1"></i> Tổng thu nhập (Gross)</label>
                                <div class="tool-input-suffix">
                                    <input type="text" inputmode="numeric" class="form-control form-control-lg" id="gross-income" value="30000000" placeholder="30.000.000">
                                    <span>VNĐ</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold" for="dependents"><i class="fas fa-users text-info me-1"></i> Số người phụ thuộc</label>
                                <select class="form-select form-select-lg" id="dependents">
                                    @for ($i = 0; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }} người</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label fw-bold mb-0" for="insurance"><i class="fas fa-shield-alt text-warning me-1"></i> BHXH, BHYT, BHTN (10.5%)</label>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="auto-insurance" checked>
                                        <label class="form-check-label small" for="auto-insurance">Tự động</label>
                                    </div>
                                </div>
                                <div class="tool-input-suffix">
                                    <input type="text" inputmode="numeric" class="form-control form-control-lg" id="insurance" value="0" disabled>
                                    <span>VNĐ</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold" for="other-deductions"><i class="fas fa-minus-circle text-secondary me-1"></i> Các khoản giảm trừ khác</label>
                                <div class="tool-input-suffix">
                                    <input type="text" inputmode="numeric" class="form-control form-control-lg" id="other-deductions" value="0" placeholder="0">
                                    <span>VNĐ</span>
                                </div>
                                <small class="text-secondary">Từ thiện, quỹ hưu trí tự nguyện...</small>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="tool-result h-100" data-show-if="total_tax" hidden aria-live="polite">
                                <div class="text-center mb-4">
                                    <p class="mb-1 opacity-75">Thuế TNCN phải nộp</p>
                                    <p class="display-4 fw-bold mb-0" data-field="total_tax" data-format="money"></p>
                                    <p class="small mt-2 opacity-75">Thuế suất hiệu dụng: <strong data-field="effective_rate" data-format="percent"></strong></p>
                                </div>
                                <hr class="opacity-25">
                                <div class="row text-center g-3">
                                    <div class="col-6">
                                        <p class="mb-1 opacity-75 small">Thu nhập tính thuế</p>
                                        <p class="fw-bold h5 mb-0" data-field="taxable_income" data-format="money"></p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1 opacity-75 small">Thực nhận (NET)</p>
                                        <p class="fw-bold h5 mb-0 text-warning" data-field="net_income" data-format="money"></p>
                                    </div>
                                </div>
                                <hr class="opacity-25">
                                <dl class="small mb-0">
                                    <div class="d-flex justify-content-between mb-2"><dt class="fw-normal opacity-75">Giảm trừ bản thân:</dt><dd class="mb-0" data-field="personal_deduction" data-format="money"></dd></div>
                                    <div class="d-flex justify-content-between mb-2"><dt class="fw-normal opacity-75">Giảm trừ người phụ thuộc:</dt><dd class="mb-0" data-field="dependent_deduction" data-format="money"></dd></div>
                                    <div class="d-flex justify-content-between mb-2"><dt class="fw-normal opacity-75">Bảo hiểm:</dt><dd class="mb-0" data-field="insurance" data-format="money"></dd></div>
                                    <div class="d-flex justify-content-between"><dt class="fw-normal opacity-75">Tổng giảm trừ:</dt><dd class="mb-0 fw-bold" data-field="total_deductions" data-format="money"></dd></div>
                                </dl>
                            </div>
                            <div class="alert alert-danger" data-tool-error hidden role="alert"></div>
                        </div>
                    </div>

                    <div class="mt-5" data-show-if="tax_breakdown" hidden>
                        <h2 class="h5 fw-bold mb-3"><i class="fas fa-layer-group text-primary me-2"></i>Chi tiết thuế theo bậc</h2>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr><th>Bậc</th><th class="text-end">Thu nhập chịu thuế</th><th class="text-end">Thuế suất</th><th class="text-end">Số thuế</th></tr>
                                </thead>
                                <tbody data-rows="tax_breakdown">
                                    <template>
                                        <tr>
                                            <td><span class="badge text-bg-light">Bậc <span data-item="bracket" data-format="text"></span></span></td>
                                            <td class="text-end" data-item="amount" data-format="money"></td>
                                            <td class="text-end"><span class="badge text-bg-success" data-item="rate" data-format="percent"></span></td>
                                            <td class="text-end fw-bold text-danger" data-item="tax" data-format="money"></td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr><td colspan="3" class="fw-bold">Tổng thuế TNCN</td><td class="text-end fw-bold text-danger" data-field="total_tax" data-format="money"></td></tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-5">
                <div class="col-md-6">
                    <div class="tool-info h-100">
                        <h2 class="h5 fw-bold mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Mức giảm trừ gia cảnh 2026</h2>
                        <ul class="mb-0">
                            <li class="mb-2">Bản thân người nộp thuế: <strong>15.5 triệu/tháng</strong></li>
                            <li>Mỗi người phụ thuộc: <strong>6.2 triệu/tháng</strong></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="tool-info h-100">
                        <h2 class="h5 fw-bold mb-3"><i class="fas fa-gavel text-warning me-2"></i>Căn cứ pháp lý</h2>
                        <ul class="mb-0 small">
                            <li class="mb-2">Luật Thuế TNCN số 109/2025/QH15 (có hiệu lực từ 01/07/2026)</li>
                            <li>Nghị quyết 110/2025/UBTVQH15 về mức giảm trừ gia cảnh (áp dụng từ kỳ tính thuế 2026)</li>
                        </ul>
                    </div>
                </div>
            </div>

            @include('tools.partials.tax-links', ['current' => 'tncn'])
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
    const { ToolKit } = window;
    const root = document.getElementById('tax-tool');
    const error = root.querySelector('[data-tool-error]');
    const insuranceInput = document.getElementById('insurance');
    const autoInsurance = document.getElementById('auto-insurance');
    let timer;

    const version = ToolKit.toggleGroup(root.querySelector('[data-toggle-group]'), () => calculate());
    const gross = ToolKit.moneyInput(document.getElementById('gross-income'), () => schedule());
    const insurance = ToolKit.moneyInput(insuranceInput, () => schedule());
    const other = ToolKit.moneyInput(document.getElementById('other-deductions'), () => schedule());

    function syncInsurance() {
        insuranceInput.disabled = autoInsurance.checked;
        if (autoInsurance.checked) {
            insuranceInput.value = ToolKit.number(gross() * 0.105);
            insuranceInput.dataset.value = String(Math.round(gross() * 0.105));
        }
    }

    function schedule() {
        clearTimeout(timer);
        timer = setTimeout(calculate, 300);
    }

    async function calculate() {
        syncInsurance();
        try {
            const { data } = await ToolKit.postJson(root.dataset.url, {
                gross_income: gross(),
                dependents: Number(document.getElementById('dependents').value),
                insurance: autoInsurance.checked ? null : insurance(),
                other_deductions: other(),
                version: version(),
            });
            error.hidden = true;
            ToolKit.render(root, data);
        } catch (exception) {
            error.textContent = exception.message;
            error.hidden = false;
        }
    }

    document.getElementById('dependents').addEventListener('change', calculate);
    autoInsurance.addEventListener('change', calculate);
    calculate();
</script>
@endpush
