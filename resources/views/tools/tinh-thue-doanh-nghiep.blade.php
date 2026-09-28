@extends('layouts.tool')

@section('tool-content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <span class="badge text-bg-primary bg-opacity-25 text-dark px-3 py-2 mb-3 fw-bold tool-hero__badge">
                    <x-icon name="building-2" class="me-1" /> DNNVV
                </span>
                <h1 class="display-5 fw-bold mb-3"><x-icon name="building-2" class="text-primary me-2" />Tính thuế Doanh nghiệp</h1>
                <p class="text-secondary lead">Công cụ tính thuế TNDN cho doanh nghiệp nhỏ và vừa theo Luật mới 2025.</p>
            </div>

            <div class="card shadow-lg border-0 rounded-4" id="tax-tool" data-url="{{ route('tools.tax.sme.calculate') }}">
                <div class="card-body p-4 p-md-5">
                    <div class="row g-3 mb-4">
                        @foreach ([['15%', 'Siêu nhỏ', 'Doanh thu ≤ 3 tỷ'], ['17%', 'Nhỏ', 'Doanh thu 3 - 50 tỷ'], ['20%', 'Vừa', 'Doanh thu > 50 tỷ']] as [$rate, $tier, $range])
                            <div class="col-md-4">
                                <div class="tool-info h-100 text-center py-3">
                                    <span class="badge rounded-pill text-bg-primary fs-6 mb-2">{{ $rate }}</span>
                                    <p class="small mb-0 fw-bold">{{ $tier }}</p>
                                    <p class="text-secondary small mb-0">{{ $range }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-5">
                            <div class="mb-4">
                                <label class="form-label fw-bold" for="revenue"><x-icon name="chart-line" class="text-success me-1" /> Doanh thu năm (VNĐ)</label>
                                <div class="tool-input-suffix">
                                    <input type="text" inputmode="numeric" class="form-control form-control-lg" id="revenue" value="2000000000" placeholder="2.000.000.000">
                                    <span>VNĐ</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold" for="expenses"><x-icon name="circle-minus" class="text-danger me-1" /> Chi phí (VNĐ) <small class="text-secondary fw-normal">(Mặc định 70% doanh thu)</small></label>
                                <div class="tool-input-suffix">
                                    <input type="text" inputmode="numeric" class="form-control form-control-lg" id="expenses" value="1400000000" placeholder="1.400.000.000">
                                    <span>VNĐ</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="new-business">
                                    <label class="form-check-label fw-bold" for="new-business"><x-icon name="sprout" class="text-success me-1" /> Doanh nghiệp mới thành lập</label>
                                </div>
                                <div class="mt-2" id="years-wrapper" hidden>
                                    <label class="form-label small" for="years">Đã hoạt động bao nhiêu năm?</label>
                                    <select class="form-select" id="years">
                                        <option value="0">Chưa đầy 1 năm</option>
                                        <option value="1">1 năm</option>
                                        <option value="2">2 năm</option>
                                        <option value="3">3 năm trở lên</option>
                                    </select>
                                    <small class="text-success" id="exempt-hint"><x-icon name="circle-check" /> Được miễn thuế TNDN!</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="tool-result h-100" data-result hidden aria-live="polite">
                                <div class="text-center mb-4">
                                    <span class="badge text-bg-success mb-2" data-exempt-badge hidden><x-icon name="gift" class="me-1" /> Miễn thuế TNDN</span>
                                    <p class="mb-1 opacity-75">Tổng thuế ước tính</p>
                                    <p class="display-4 fw-bold mb-0" data-field="total_tax" data-format="money"></p>
                                    <p class="small mt-2 opacity-75" data-field="meta.revenue_tier" data-format="text"></p>
                                </div>
                                <hr class="opacity-25">
                                <div class="row text-center g-3">
                                    <div class="col-4">
                                        <p class="mb-1 opacity-75 small">Lợi nhuận</p>
                                        <p class="fw-bold h6 mb-0" data-field="deductions.profit" data-format="money"></p>
                                    </div>
                                    <div class="col-4">
                                        <p class="mb-1 opacity-75 small">Thuế TNDN</p>
                                        <p class="fw-bold h6 mb-0" data-field="meta.cit_rate" data-format="percent"></p>
                                    </div>
                                    <div class="col-4">
                                        <p class="mb-1 opacity-75 small">Lợi nhuận sau thuế</p>
                                        <p class="fw-bold h6 mb-0 text-warning" data-field="net_amount" data-format="money"></p>
                                    </div>
                                </div>
                                <hr class="opacity-25">
                                <div class="small" data-rows="breakdown">
                                    <template>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="opacity-75">
                                                <span data-item="label" data-format="text"></span>
                                                (<span data-item="rate" data-format="percent"></span>)
                                                <span class="badge text-bg-success ms-1" data-item="note" data-format="text" data-item-show="note"></span>
                                            </span>
                                            <span data-item="tax" data-format="money"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="alert alert-danger" data-tool-error hidden role="alert"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-5">
                @foreach ([['sprout', 'text-success', 'Miễn 3 năm đầu', 'DN mới thành lập được miễn thuế TNDN trong 3 năm đầu.'], ['percent', 'text-warning', 'Giảm 2% GTGT', 'Thuế GTGT giảm từ 10% xuống 8% đến hết năm 2026.'], ['shield-check', 'text-gold-dark', 'Thuế suất ưu đãi', 'DNNVV được hưởng thuế suất 15-17% thay vì 20% thông thường.']] as [$icon, $color, $title, $text])
                    <div class="col-md-4">
                        <div class="tool-info h-100 text-center">
                            <x-icon :name="$icon" class="{{ $color }} icon-lg mb-3" />
                            <h2 class="h6 fw-bold">{{ $title }}</h2>
                            <p class="small mb-0 text-secondary">{{ $text }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            @include('tools.partials.tax-links', ['current' => 'sme'])
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
    const { ToolKit } = window;
    const root = document.getElementById('tax-tool');
    const error = root.querySelector('[data-tool-error]');
    const expensesInput = document.getElementById('expenses');
    const newBusiness = document.getElementById('new-business');
    const years = document.getElementById('years');
    let timer;

    const schedule = () => { clearTimeout(timer); timer = setTimeout(calculate, 300); };
    const expenses = ToolKit.moneyInput(expensesInput, schedule);
    const revenue = ToolKit.moneyInput(document.getElementById('revenue'), () => {
        // Expenses follow revenue at 70% until the user types their own figure.
        expensesInput.value = ToolKit.number(revenue() * 0.7);
        expensesInput.dataset.value = String(Math.round(revenue() * 0.7));
        schedule();
    });

    async function calculate() {
        document.getElementById('years-wrapper').hidden = !newBusiness.checked;
        document.getElementById('exempt-hint').hidden = Number(years.value) >= 3;
        try {
            const { data } = await ToolKit.postJson(root.dataset.url, {
                revenue: revenue(),
                expenses: expenses(),
                is_new_business: newBusiness.checked,
                years_in_business: Number(years.value),
                version: '2026',
            });
            error.hidden = true;
            root.querySelector('[data-result]').hidden = false;
            root.querySelector('[data-exempt-badge]').hidden = !(data.meta?.is_new_business && data.meta?.exempt_years_remaining > 0);
            ToolKit.render(root, data);
        } catch (exception) {
            error.textContent = exception.message;
            error.hidden = false;
        }
    }

    newBusiness.addEventListener('change', calculate);
    years.addEventListener('change', calculate);
    calculate();
</script>
@endpush
