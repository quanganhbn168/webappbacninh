@extends('layouts.master')

@section('title', 'Tính thuế Thu nhập Cá nhân (TNCN) 2026 - WebApp Bắc Ninh')
@section('meta_description', 'Công cụ tính thuế thu nhập cá nhân (TNCN) miễn phí theo Luật mới 109/2025/QH15. Hỗ trợ biểu thuế 2025 và 2026, tính tự động giảm trừ gia cảnh.')
@section('meta_keywords', 'tính thuế tncn, thuế thu nhập cá nhân 2026, biểu thuế lũy tiến, giảm trừ gia cảnh, công cụ tính thuế')

@push('head')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .version-toggle { 
            display: flex; 
            background: #e9ecef; 
            border-radius: 50px; 
            padding: 4px;
        }
        .version-toggle button {
            flex: 1;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }
        .version-toggle button.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .result-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 30px;
        }
        .breakdown-table th, .breakdown-table td {
            padding: 12px;
            text-align: right;
        }
        .breakdown-table th:first-child, .breakdown-table td:first-child {
            text-align: left;
        }
        .info-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 20px;
        }
        .input-icon-wrapper {
            position: relative;
        }
        .input-icon-wrapper .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 0.9rem;
        }
        .tax-input {
            padding-right: 50px !important;
        }
    </style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            {{-- Header --}}
            <div class="text-center mb-5">
                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 mb-3 fw-bold">
                    <i class="fas fa-fire me-1"></i> Luật mới 2026
                </span>
                <h1 class="display-5 fw-bold text-dark mb-3">
                    <i class="fas fa-calculator text-primary me-2"></i>
                    Tính thuế Thu nhập Cá nhân
                </h1>
                <p class="text-muted lead">
                    Công cụ tính thuế TNCN theo Luật 109/2025/QH15 (5 bậc mới) và biểu thuế cũ (7 bậc).
                </p>
            </div>

            {{-- Calculator --}}
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden" x-data="taxCalculator()" x-init="calculate()">
                <div class="card-body p-4 p-md-5">
                    
                    {{-- Version Toggle --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Chọn phiên bản thuế</label>
                        <div class="version-toggle">
                            <button type="button" 
                                    :class="{ 'active': version === '2025' }" 
                                    @click="version = '2025'; calculate()">
                                2025 (7 bậc cũ)
                            </button>
                            <button type="button" 
                                    :class="{ 'active': version === '2026' }" 
                                    @click="version = '2026'; calculate()">
                                2026 (5 bậc mới) ✨
                            </button>
                        </div>
                    </div>

                    <div class="row g-4">
                        {{-- Left: Input Form --}}
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-money-bill-wave text-success me-1"></i>
                                    Tổng thu nhập (Gross)
                                </label>
                                <div class="input-icon-wrapper">
                                    <input type="text" 
                                           class="form-control form-control-lg tax-input" 
                                           x-model="grossIncomeFormatted"
                                           @input="formatGrossIncome($event)"
                                           @change="calculate()"
                                           placeholder="30,000,000">
                                    <span class="input-icon">VNĐ</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-users text-info me-1"></i>
                                    Số người phụ thuộc
                                </label>
                                <select class="form-select form-select-lg" x-model="dependents" @change="calculate()">
                                    <template x-for="i in 11" :key="i">
                                        <option :value="i - 1" x-text="i - 1 + ' người'"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-shield-alt text-warning me-1"></i>
                                        BHXH, BHYT, BHTN (10.5%)
                                    </span>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" 
                                               x-model="autoInsurance" @change="calculate()">
                                        <label class="form-check-label small">Tự động</label>
                                    </div>
                                </label>
                                <div class="input-icon-wrapper">
                                    <input type="text" 
                                           class="form-control form-control-lg tax-input" 
                                           x-model="insuranceFormatted"
                                           @input="formatInsurance($event)"
                                           @change="calculate()"
                                           :disabled="autoInsurance"
                                           :class="{ 'bg-light': autoInsurance }">
                                    <span class="input-icon">VNĐ</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-minus-circle text-secondary me-1"></i>
                                    Các khoản giảm trừ khác
                                </label>
                                <div class="input-icon-wrapper">
                                    <input type="text" 
                                           class="form-control form-control-lg tax-input" 
                                           x-model="otherDeductionsFormatted"
                                           @input="formatOtherDeductions($event)"
                                           @change="calculate()"
                                           placeholder="0">
                                    <span class="input-icon">VNĐ</span>
                                </div>
                                <small class="text-muted">Từ thiện, quỹ hưu trí tự nguyện...</small>
                            </div>
                        </div>

                        {{-- Right: Results --}}
                        <div class="col-lg-6">
                            <div class="result-card h-100" x-show="result" x-transition>
                                <div class="text-center mb-4">
                                    <p class="mb-1 opacity-75">Thuế TNCN phải nộp</p>
                                    <h2 class="display-4 fw-bold mb-0" x-text="formatNumber(result?.total_tax || 0) + ' đ'"></h2>
                                    <p class="small mt-2 opacity-75">
                                        Thuế suất hiệu dụng: <strong x-text="(result?.effective_rate || 0) + '%'"></strong>
                                    </p>
                                </div>

                                <hr class="opacity-25">

                                <div class="row text-center g-3">
                                    <div class="col-6">
                                        <p class="mb-1 opacity-75 small">Thu nhập tính thuế</p>
                                        <p class="fw-bold h5 mb-0" x-text="formatNumber(result?.taxable_income || 0) + ' đ'"></p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1 opacity-75 small">Thực nhận (NET)</p>
                                        <p class="fw-bold h5 mb-0 text-warning" x-text="formatNumber(result?.net_income || 0) + ' đ'"></p>
                                    </div>
                                </div>

                                <hr class="opacity-25">

                                <div class="small">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="opacity-75">Giảm trừ bản thân:</span>
                                        <span x-text="formatNumber(result?.personal_deduction || 0) + ' đ'"></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="opacity-75">Giảm trừ người phụ thuộc:</span>
                                        <span x-text="formatNumber(result?.dependent_deduction || 0) + ' đ'"></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="opacity-75">Bảo hiểm:</span>
                                        <span x-text="formatNumber(result?.insurance || 0) + ' đ'"></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="opacity-75">Tổng giảm trừ:</span>
                                        <strong x-text="formatNumber(result?.total_deductions || 0) + ' đ'"></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tax Breakdown Table --}}
                    <div class="mt-5" x-show="result && result.tax_breakdown && result.tax_breakdown.length > 0" x-transition>
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-layer-group text-primary me-2"></i>
                            Chi tiết thuế theo bậc
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover breakdown-table bg-white rounded-3 overflow-hidden shadow-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Bậc</th>
                                        <th>Thu nhập chịu thuế</th>
                                        <th>Thuế suất</th>
                                        <th>Số thuế</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="item in (result?.tax_breakdown || [])" :key="item.bracket">
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary bg-opacity-10 text-primary" x-text="'Bậc ' + item.bracket"></span>
                                            </td>
                                            <td x-text="formatNumber(item.amount) + ' đ'"></td>
                                            <td>
                                                <span class="badge bg-success" x-text="item.rate + '%'"></span>
                                            </td>
                                            <td class="fw-bold text-danger" x-text="formatNumber(item.tax) + ' đ'"></td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="fw-bold">Tổng thuế TNCN</td>
                                        <td class="fw-bold text-danger" x-text="formatNumber(result?.total_tax || 0) + ' đ'"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Cards --}}
            <div class="row g-4 mt-5">
                <div class="col-md-6">
                    <div class="info-card h-100">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Mức giảm trừ gia cảnh 2026
                        </h5>
                        <ul class="mb-0">
                            <li class="mb-2">Bản thân người nộp thuế: <strong class="text-primary">15.5 triệu/tháng</strong></li>
                            <li>Mỗi người phụ thuộc: <strong class="text-primary">6.2 triệu/tháng</strong></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card h-100">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-gavel text-warning me-2"></i>
                            Căn cứ pháp lý
                        </h5>
                        <ul class="mb-0 small">
                            <li class="mb-2">Luật Thuế TNCN số 109/2025/QH15 (có hiệu lực từ 01/07/2026)</li>
                            <li>Nghị quyết 110/2025/UBTVQH15 về mức giảm trừ gia cảnh (áp dụng từ kỳ tính thuế 2026)</li>
                        </ul>
                    </div>
            </div>

            {{-- Related Tax Tools --}}
            <div class="row g-4 mt-4">
                <div class="col-12">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-calculator text-primary me-2"></i>
                        Công cụ tính thuế khác
                    </h5>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('tools.tax.household') }}" class="card bg-success bg-gradient text-white text-decoration-none h-100 hover-lift">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-store fa-2x me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Tính thuế Hộ kinh doanh</h6>
                                <small class="opacity-75">GTGT + TNCN theo ngành nghề, ngưỡng 500tr</small>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('tools.tax.sme') }}" class="card bg-primary bg-gradient text-white text-decoration-none h-100 hover-lift">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-building fa-2x me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Tính thuế Doanh nghiệp</h6>
                                <small class="opacity-75">TNDN 15%/17%/20%, miễn 3 năm đầu</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function taxCalculator() {
    return {
        version: '2026',
        grossIncome: 30000000,
        grossIncomeFormatted: '30,000,000',
        dependents: 0,
        insurance: 0,
        insuranceFormatted: '0',
        autoInsurance: true,
        otherDeductions: 0,
        otherDeductionsFormatted: '0',
        result: null,
        isLoading: false,

        formatNumber(num) {
            return new Intl.NumberFormat('vi-VN').format(Math.round(num));
        },

        parseNumber(str) {
            return parseFloat(str.replace(/[,.]/g, '')) || 0;
        },

        formatGrossIncome(event) {
            let value = this.parseNumber(event.target.value);
            this.grossIncome = value;
            this.grossIncomeFormatted = this.formatNumber(value);
            if (this.autoInsurance) {
                this.insurance = value * 0.105;
                this.insuranceFormatted = this.formatNumber(this.insurance);
            }
        },

        formatInsurance(event) {
            let value = this.parseNumber(event.target.value);
            this.insurance = value;
            this.insuranceFormatted = this.formatNumber(value);
        },

        formatOtherDeductions(event) {
            let value = this.parseNumber(event.target.value);
            this.otherDeductions = value;
            this.otherDeductionsFormatted = this.formatNumber(value);
        },

        async calculate() {
            if (this.autoInsurance) {
                this.insurance = this.grossIncome * 0.105;
                this.insuranceFormatted = this.formatNumber(this.insurance);
            }

            this.isLoading = true;
            
            try {
                const response = await fetch('{{ route("tools.tax.calculate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        gross_income: this.grossIncome,
                        dependents: parseInt(this.dependents),
                        insurance: this.autoInsurance ? null : this.insurance,
                        other_deductions: this.otherDeductions,
                        version: this.version,
                    }),
                });

                const data = await response.json();
                
                if (data.success) {
                    this.result = data.data;
                }
            } catch (error) {
                console.error('Error calculating tax:', error);
            } finally {
                this.isLoading = false;
            }
        }
    }
}
</script>
@endpush
