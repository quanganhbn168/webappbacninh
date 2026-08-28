@extends('layouts.utility')

@section('title', 'Tính thuế Hộ kinh doanh 2026 - WebApp Bắc Ninh')
@section('meta_description', 'Công cụ tính thuế hộ kinh doanh cá nhân theo Luật mới 2026. Ngưỡng miễn thuế 500 triệu, tính thuế GTGT và TNCN theo ngành nghề.')
@section('meta_keywords', 'tính thuế hộ kinh doanh, thuế GTGT, thuế TNCN hộ kinh doanh, ngưỡng 500 triệu')

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
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(17, 153, 142, 0.4);
        }
        .result-card {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            border-radius: 20px;
            padding: 30px;
        }
        .sector-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .sector-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .sector-card.active {
            border-color: #11998e;
            background: linear-gradient(135deg, #11998e10 0%, #38ef7d10 100%);
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
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 mb-3 fw-bold">
                    <i class="fas fa-store me-1"></i> Hộ kinh doanh
                </span>
                <h1 class="display-5 fw-bold text-dark mb-3">
                    <i class="fas fa-store text-success me-2"></i>
                    Tính thuế Hộ kinh doanh
                </h1>
                <p class="text-muted lead">
                    Công cụ tính thuế GTGT và TNCN cho hộ kinh doanh cá nhân theo Luật mới 2026.
                </p>
            </div>

            {{-- Calculator --}}
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden" x-data="householdTaxCalculator()" x-init="calculate()">
                <div class="card-body p-4 p-md-5">
                    
                    {{-- Version Toggle --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Chọn phiên bản thuế</label>
                        <div class="version-toggle">
                            <button type="button" 
                                    :class="{ 'active': version === '2025' }" 
                                    @click="version = '2025'; calculate()">
                                2025 (Ngưỡng 100tr)
                            </button>
                            <button type="button" 
                                    :class="{ 'active': version === '2026' }" 
                                    @click="version = '2026'; calculate()">
                                2026 (Ngưỡng 500tr) ✨
                            </button>
                        </div>
                    </div>

                    {{-- Sector Selection --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-industry text-info me-1"></i>
                            Chọn ngành nghề
                        </label>
                        <div class="row g-3">
                            @foreach($sectors as $sector)
                            <div class="col-md-4">
                                <div class="sector-card card h-100 p-3" 
                                     :class="{ 'active': selectedSector === '{{ $sector['value'] }}' }"
                                     @click="selectedSector = '{{ $sector['value'] }}'; calculate()">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="fw-bold mb-1 small">{{ $sector['label'] }}</h6>
                                            <div class="text-muted small">
                                                GTGT: <span class="text-success fw-bold">{{ $sector['vat_rate'] }}%</span> |
                                                TNCN: <span class="text-danger fw-bold">{{ $sector['pit_rate'] }}%</span>
                                            </div>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" 
                                                   :checked="selectedSector === '{{ $sector['value'] }}'">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row g-4">
                        {{-- Left: Input Form --}}
                        <div class="col-lg-5">
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-money-bill-wave text-success me-1"></i>
                                    Doanh thu năm (VNĐ)
                                </label>
                                <div class="input-icon-wrapper">
                                    <input type="text" 
                                           class="form-control form-control-lg tax-input" 
                                           x-model="revenueFormatted"
                                           @input="formatRevenue($event)"
                                           @change="calculate()"
                                           placeholder="600,000,000">
                                    <span class="input-icon">VNĐ</span>
                                </div>
                                <small class="text-muted">
                                    Ngưỡng miễn thuế: <strong x-text="version === '2026' ? '500 triệu' : '100 triệu'"></strong>/năm
                                </small>
                            </div>
                        </div>

                        {{-- Right: Results --}}
                        <div class="col-lg-7">
                            <div class="result-card h-100" x-show="result" x-transition>
                                {{-- Below threshold message --}}
                                <template x-if="result?.taxable_amount === 0">
                                    <div class="text-center py-4">
                                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                                        <h4 class="fw-bold">Không phải nộp thuế!</h4>
                                        <p class="opacity-75 mb-0">Doanh thu dưới ngưỡng chịu thuế <span x-text="version === '2026' ? '500 triệu' : '100 triệu'"></span>/năm</p>
                                    </div>
                                </template>

                                {{-- Has tax --}}
                                <template x-if="result?.taxable_amount > 0">
                                    <div>
                                        <div class="text-center mb-4">
                                            <p class="mb-1 opacity-75">Tổng thuế phải nộp</p>
                                            <h2 class="display-4 fw-bold mb-0" x-text="formatNumber(result?.total_tax || 0) + ' đ'"></h2>
                                            <p class="small mt-2 opacity-75">
                                                Thuế suất hiệu dụng: <strong x-text="(result?.effective_rate || 0) + '%'"></strong>
                                            </p>
                                        </div>

                                        <hr class="opacity-25">

                                        <div class="row text-center g-3">
                                            <div class="col-6">
                                                <p class="mb-1 opacity-75 small">Doanh thu chịu thuế</p>
                                                <p class="fw-bold h5 mb-0" x-text="formatNumber(result?.taxable_amount || 0) + ' đ'"></p>
                                            </div>
                                            <div class="col-6">
                                                <p class="mb-1 opacity-75 small">Thực nhận</p>
                                                <p class="fw-bold h5 mb-0 text-warning" x-text="formatNumber(result?.net_amount || 0) + ' đ'"></p>
                                            </div>
                                        </div>

                                        <hr class="opacity-25">

                                        <template x-for="item in (result?.breakdown || [])" :key="item.type">
                                            <div class="d-flex justify-content-between mb-2 small">
                                                <span class="opacity-75" x-text="item.label + ' (' + item.rate + '%):'"></span>
                                                <span x-text="formatNumber(item.tax) + ' đ'"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Cards --}}
            <div class="row g-4 mt-5">
                <div class="col-md-6">
                    <div class="card bg-light border-0 h-100">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">
                                <i class="fas fa-info-circle text-success me-2"></i>
                                Ngưỡng miễn thuế 2026
                            </h5>
                            <p class="mb-0">Từ 01/01/2026, hộ kinh doanh có doanh thu ≤ <strong class="text-success">500 triệu/năm</strong> không phải nộp thuế GTGT và TNCN.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light border-0 h-100">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">
                                <i class="fas fa-gavel text-warning me-2"></i>
                                Bãi bỏ thuế khoán
                            </h5>
                            <p class="mb-0">Từ 2026, hộ kinh doanh chuyển sang <strong>tự kê khai</strong> thuế dựa trên doanh thu thực tế, bỏ hình thức thuế khoán.</p>
                        </div>
                    </div>
            </div>

            {{-- Related Tax Tools --}}
            <div class="row g-4 mt-4">
                <div class="col-12">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-calculator text-success me-2"></i>
                        Công cụ tính thuế khác
                    </h5>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('tools.tax') }}" class="card bg-primary bg-gradient text-white text-decoration-none h-100 hover-lift">
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
                    <a href="{{ route('tools.tax.sme') }}" class="card bg-info bg-gradient text-white text-decoration-none h-100 hover-lift">
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
function householdTaxCalculator() {
    return {
        version: '2026',
        selectedSector: 'service',
        revenue: 600000000,
        revenueFormatted: '600,000,000',
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
        },

        async calculate() {
            this.isLoading = true;
            
            try {
                const response = await fetch('{{ route("tools.tax.household.calculate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        revenue: this.revenue,
                        sector: this.selectedSector,
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
