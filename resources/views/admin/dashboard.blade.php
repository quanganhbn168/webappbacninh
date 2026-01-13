@extends('layouts.admin')

@section('plugins.Apexcharts', true)

@section('title', 'Bảng điều khiển - WebApp Bắc Ninh')

@section('header_title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@stop

@section('admin_content')
    <div class="container-fluid">
        {{-- Stat Cards --}}
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>150</h3>
                        <p>Khách hàng (Tenants)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="#" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>53<sup style="font-size: 20px">%</sup></h3>
                        <p>Tỷ lệ chuyển đổi</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <a href="#" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>44</h3>
                        <p>User mới</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <a href="#" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>65</h3>
                        <p>Lượt truy cập</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <a href="#" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- ApexCharts Section --}}
            <div class="col-md-8">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="far fa-chart-bar"></i>
                            Thống kê doanh thu & Tăng trưởng
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="revenue-chart" style="min-height: 250px;"></div>
                    </div>
                </div>
            </div>

            {{-- Activity/Quick actions --}}
            <div class="col-md-4">
                <div class="card card-dark card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Thao tác nhanh</h3>
                    </div>
                    <div class="card-body">
                        <button id="test-swal" class="btn btn-primary btn-block mb-3">
                            <i class="fas fa-magic"></i> Thử SweetAlert2
                        </button>
                        <a href="{{ url('admin/settings') }}" class="btn btn-info btn-block mb-3">
                            <i class="fas fa-cogs"></i> Cấu hình Website
                        </a>
                        <div class="alert alert-info alert-dismissible small">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-info"></i> Thông báo</h5>
                            Hệ thống Landlord đang chạy ổn định. Kiểm tra backup hàng ngày.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('admin_css')
    <style>
        .small-box { border-radius: 12px; }
    </style>
@endpush

@push('admin_js')
    <script>
        // SweetAlert2 Test
        $('#test-swal').click(function() {
            Swal.fire({
                title: 'Chào anh Quang Anh!',
                text: 'Hệ thống AdminLTE 3 đã sẵn sàng hoạt động.',
                icon: 'success',
                confirmButtonText: 'Tuyệt vời'
            });
        });

        // ApexCharts Sample
        var options = {
            series: [{
                name: 'Doanh thu',
                data: [31, 40, 28, 51, 42, 109, 100]
            }, {
                name: 'Chi phí',
                data: [11, 32, 45, 32, 34, 52, 41]
            }],
            chart: {
                height: 350,
                type: 'area'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'datetime',
                categories: ["2026-01-01T00:00:00.000Z", "2026-01-02T00:00:00.000Z", "2026-01-03T00:00:00.000Z", "2026-01-04T00:00:00.000Z", "2026-01-05T00:00:00.000Z", "2026-01-06T00:00:00.000Z", "2026-01-07T00:00:00.000Z"]
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
            colors: ['#007bff', '#28a745']
        };

        var chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
        chart.render();
    </script>
@endpush
