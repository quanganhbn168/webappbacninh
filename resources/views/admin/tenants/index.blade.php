@extends('layouts.admin')

@section('title', 'Quản lý Tenants')

@section('header_title', 'Quản lý khách hàng (Tenants)')

@section('breadcrumb')
    <li class="breadcrumb-item active">Tenants</li>
@stop

@section('admin_content')
    <div class="card card-outline card-primary shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover table-striped mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 50px">#</th>
                        <th>Mã Định Danh (ID)</th>
                        <th>Tên Miền (Domain)</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th style="width: 150px" class="text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenants as $tenant)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge badge-info px-2 py-1">{{ $tenant->id }}</span>
                            </td>
                            <td>
                                @foreach($tenant->domains as $domain)
                                    <a href="http://{{ $domain->domain }}" target="_blank" class="text-primary fw-bold">
                                        {{ $domain->domain }} <i class="fas fa-external-link-alt small ml-1"></i>
                                    </a>
                                    @if(!$loop->last) <br> @endif
                                @endforeach
                            </td>
                            <td>
                                <span class="badge badge-success">Đang hoạt động</span>
                            </td>
                            <td class="text-muted small">
                                {{ $tenant->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="text-right">
                                <div class="btn-group">
                                    <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="btn btn-sm btn-outline-info mr-1 rounded" title="Chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted italic">
                                <i class="fas fa-folder-open fa-3x mb-3 d-block"></i>
                                Chưa có khách hàng nào được tạo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @if($tenants->hasPages())
            <div class="card-footer bg-white border-top-0">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>
@stop
