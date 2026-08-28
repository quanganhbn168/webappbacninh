@extends('layouts.utility')

@section('title', 'Tính thuế Doanh nghiệp nhỏ và vừa 2026 - WebApp Bắc Ninh')
@section('meta_description', 'Công cụ tính thuế TNDN cho doanh nghiệp nhỏ và vừa. Thuế suất ưu đãi 15%, 17%, 20% theo doanh thu, miễn thuế 3 năm đầu cho DN mới.')
@section('meta_keywords', 'tính thuế doanh nghiệp, thuế TNDN, DNNVV, thuế doanh nghiệp nhỏ, miễn thuế 3 năm')

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
            background: linear-gradient(135deg, #4776E6 0%, #8E54E9 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(71, 118, 230, 0.4);
        }
        .result-card {
            background: linear-gradient(135deg, #4776E6 0%, #8E54E9 100%);
            color: white;
            border-radius: 20px;
            padding: 30px;
        }
        .tier-badge {
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .tier-badge.tier-1 { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; }
        .tier-badge.tier-2 { background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); color: #333; }
        .tier-badge.tier-3 { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); color: white; }
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
        .exempt-badge {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            {{-- Header --}}
            <div class="text-center mb-5">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3 fw-bold">
                    <i class="fas fa-building me-1"></i> DNNVV
                </span>
                <h1 class="display-5 fw-bold text-dark mb-3">
                    <i class="fas fa-building text-primary me-2"></i>
                    Tính thuế Doanh nghiệp
                </h1>
                <p class="text-muted lead">
                    Công cụ tính thuế TNDN cho doanh nghiệp nhỏ và vừa theo Luật mới 2025.
                </p>
            </div>

            {{-- Calculator --}}
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden" x-data="smeTaxCalculator()" x-init="calculate()">
                <div class="card-body p-4 p-md-5">
                    
                    {{-- Tax Rates Info --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card bg-light border-0 h-100 text-center p-3">
                                <span class="tier-badge tier-1 mx-auto mb-2">15%</span>
                                <p class="small mb-0 fw-bold">Siêu nhỏ</p>
                                <p class="text-muted small mb-0">Doanh thu ≤ 3 tỷ</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light border-0 h-100 text-center p-3">
                                <span class="tier-badge tier-2 mx-auto mb-2">17%</span>
                                <p class="small mb-0 fw-bold">Nhỏ</p>
                                <p class="text-muted small mb-0">Doanh thu 3 - 50 tỷ</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light border-0 h-100 text-center p-3">
                                <span class="tier-badge tier-3 mx-auto mb-2">20%</span>
                                <p class="small mb-0 fw-bold">Vừa</p>
                                <p class="text-muted small mb-0">Doanh thu > 50 tỷ</p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        {{-- Left: Input Form --}}
                        <div class="col-lg-5">
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-chart-line text-success me-1"></i>
                                    Doanh thu năm (VNĐ)
                                </label>
                                <div class="input-icon-wrapper">
                                    <input type="text" 
                                           class="form-control form-control-lg tax-input" 
                                           x-model="revenueFormatted"
                                           @input="formatRevenue($event)"
                                           @change="calculate()"
                                           placeholder="2,000,000,000">
                                    <span class="input-icon">VNĐ</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-minus-circle text-danger me-1"></i>
                                    Chi phí (VNĐ)
                                    <small class="text-muted fw-normal">(Mặc định 70% doanh thu)</small>
                                </label>
                                <div class="input-icon-wrapper">
                                    <input type="text" 
                                           class="form-control form-control-lg tax-input" 
                                           x-model="expensesFormatted"
                                           @input="formatExpenses($event)"
                                           @change="calculate()"
                                           placeholder="1,400,000,000">
                                    <span class="input-icon">VNĐ</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           id="newBusiness" x-model="isNewBusiness" @change="calculate()">
                                    <label class="form-check-label fw-bold" for="newBusiness">
                                        <i class="fas fa-seedling text-success me-1"></i>
                                        Doanh nghiệp mới thành lập
                                    </label>
                                </div>
                                <template x-if="isNewBusiness">
                                    <div class="mt-2">
                                        <label class="form-label small">Đã hoạt động bao nhiêu năm?</label>
                                        <select class="form-select" x-model="yearsInBusiness" @change="calculate()">
                                            <option value="0">Chưa đầy 1 năm</option>
                                            <option value="1">1 năm</option>
                                            <option value="2">2 năm</option>
                                            <option value="3">3 năm trở lên</option>
                                        </select>
                                        <small class="text-success" x-show="yearsInBusiness < 3">
                                            <i class="fas fa-check-circle"></i> Được miễn thuế TNDN!
                                        </small>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Right: Results --}}
                        <div class="col-lg-7">
                            <div class="result-card h-100" x-show="result" x-transition>
                                <div class="text-center mb-4">
                                    <template x-if="result?.meta?.is_new_business && result?.meta?.exempt_years_remaining > 0">
                                        <span class="exempt-badge mb-2">
                                            <i class="fas fa-gift me-1"></i> Miễn thuế TNDN
                                        </span>
                                    </template>
                                    <p class="mb-1 opacity-75">Tổng thuế ước tính</p>
                                    <h2 class="display-4 fw-bold mb-0" x-text="formatNumber(result?.total_tax || 0) + ' đ'"></h2>
                                    <p class="small mt-2 opacity-75">
                                        <span x-text="result?.meta?.revenue_tier"></span>
                                    </p>
                                </div>

                                <hr class="opacity-25">

                                <div class="row text-center g-3">
                                    <div class="col-4">
                                        <p class="mb-1 opacity-75 small">Lợi nhuận</p>
                                        <p class="fw-bold h6 mb-0" x-text="formatNumber(result?.deductions?.profit || 0) + ' đ'"></p>
                                    </div>
                                    <div class="col-4">
                                        <p class="mb-1 opacity-75 small">Thuế TNDN</p>
                                        <p class="fw-bold h6 mb-0" x-text="(result?.meta?.cit_rate || 0) + '%'"></p>
                                    </div>
                                    <div class="col-4">
                                        <p class="mb-1 opacity-75 small">Lợi nhuận sau thuế</p>
                                        <p class="fw-bold h6 mb-0 text-warning" x-text="formatNumber(result?.net_amount || 0) + ' đ'"></p>
                                    </div>
                                </div>

                                <hr class="opacity-25">

                                <template x-for="item in (result?.breakdown || [])" :key="item.type">
                                    <div class="d-flex justify-content-between mb-2 small">
                                        <span class="opacity-75">
                                            <span x-text="item.label"></span>
                                            <span x-text="'(' + item.rate + '%)'"></span>
                                            <template x-if="item.note">
                                                <span class="badge bg-success ms-1" x-text="item.note"></span>
                                            </template>
                                        </span>
                                        <span x-text="formatNumber(item.tax) + ' đ'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Cards --}}
            <div class="row g-4 mt-5">
                <div class="col-md-4">
                    <div class="card bg-light border-0 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-seedling fa-2x text-success mb-3"></i>
                            <h6 class="fw-bold">Miễn 3 năm đầu</h6>
                            <p class="small mb-0 text-muted">DN mới thành lập được miễn thuế TNDN trong 3 năm đầu.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light border-0 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-percent fa-2x text-warning mb-3"></i>
                            <h6 class="fw-bold">Giảm 2% GTGT</h6>
                            <p class="small mb-0 text-muted">Thuế GTGT giảm từ 10% xuống 8% đến hết năm 2026.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light border-0 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-shield-alt fa-2x text-primary mb-3"></i>
                            <h6 class="fw-bold">Thuế suất ưu đãi</h6>
                            <p class="small mb-0 text-muted">DNNVV được hưởng thuế suất 15-17% thay vì 20% thông thường.</p>
                        </div>
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
                    <a href="{{ route('tools.tax') }}" class="card bg-danger bg-gradient text-white text-decoration-none h-100 hover-lift">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-user-tie fa-2x me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Tính thuế TNCN</h6>
                                <small class="opacity-75">Thu nhập cá nhân, lũy tiến 5/7 bậc</small>
                            </div>
                        </div>
                    </a>
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
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function smeTaxCalculator() {
    return {
        version: '2026',
        revenue: 2000000000,
        revenueFormatted: '2,000,000,000',
        expenses: 1400000000,
        expensesFormatted: '1,400,000,000',
        isNewBusiness: false,
        yearsInBusiness: 0,
        result: null,
        isLoading: false,

        formatNumber(num) {
            return new Intl.NumberFormat('vi-VN').format(Math.round(num));
        },

        parseNumber(str) {
            return parseFloat(str.replace(/[,.]/g, '')) || 0;
        },

        formatRevenue(event) {
            let value = this.parseNumber(event.target.value);
            this.revenue = value;
            this.revenueFormatted = this.formatNumber(value);
            // Auto update expenses to 70%
            this.expenses = value * 0.7;
            this.expensesFormatted = this.formatNumber(this.expenses);
        },

        formatExpenses(event) {
            let value = this.parseNumber(event.target.value);
            this.expenses = value;
            this.expensesFormatted = this.formatNumber(value);
        },

        async calculate() {
            this.isLoading = true;
            
            try {
                const response = await fetch('{{ route("tools.tax.sme.calculate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        revenue: this.revenue,
                        expenses: this.expenses,
                        is_new_business: this.isNewBusiness,
                        years_in_business: parseInt(this.yearsInBusiness),
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
