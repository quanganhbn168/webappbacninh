@extends('layouts.admin')

@section('title', 'Tạo Tenant mới')

@section('header_title', 'Tạo Tenant mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.tenants.index') }}">Tenants</a></li>
    <li class="breadcrumb-item active">Tạo mới</li>
@stop

@section('admin_content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card card-primary card-outline shadow-lg border-0">
                <form action="{{ route('admin.tenants.store') }}" method="POST">
                    @csrf
                    <div class="card-body p-4">
                        <div class="form-group mb-4">
                            <label for="tenant_id" class="font-weight-bold">Mã định danh (ID)</label>
                            <input type="text" name="id" id="tenant_id" class="form-control form-control-lg @error('id') is-invalid @enderror" 
                                   placeholder="Ví dụ: khach-hang-a" value="{{ old('id') }}" required>
                            <small class="text-muted">Mã này dùng để phân biệt các khách hàng trong hệ thống (viết liền, không dấu).</small>
                            @error('id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="domain" class="font-weight-bold">Tên miền phụ (Subdomain)</label>
                            <div class="input-group input-group-lg">
                                <input type="text" name="domain" id="domain" class="form-control @error('domain') is-invalid @enderror" 
                                       placeholder="ví dụ: khachhang" value="{{ old('domain') }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">.{{ config('tenancy.central_domains')[0] }}</span>
                                </div>
                            </div>
                            <small class="text-muted">Khách hàng sẽ truy cập website qua địa chỉ này.</small>
                            @error('domain')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="callout callout-info small">
                            <h5><i class="fas fa-info"></i> Lưu ý:</h5>
                            <p>Sau khi nhấn tạo, hệ thống sẽ tự động khởi tạo cơ sở dữ liệu riêng cho khách hàng này. Quá trình này có thể mất vài giây.</p>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 p-4 d-flex justify-content-between">
                        <a href="{{ route('admin.tenants.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-arrow-left mr-1"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-primary px-5 shadow fw-bold">
                            <i class="fas fa-check mr-2"></i> Khởi tạo ngay
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
