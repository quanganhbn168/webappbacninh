@extends('layouts.admin')

@section('title', 'Dịch vụ vận hành')
@section('header_title', 'Dịch vụ vận hành')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dịch vụ vận hành</li>
@stop

@section('admin_content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách dịch vụ vận hành</h3>
        <div class="card-tools"><a href="{{ route('admin.operation-services.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Thêm mới</a></div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead><tr><th>Thứ tự</th><th>Dịch vụ</th><th>Mô tả</th><th>Trạng thái</th><th></th></tr></thead>
            <tbody>
            @forelse ($services as $service)
                <tr>
                    <td>{{ $service->order }}</td>
                    <td><i class="{{ $service->icon ?: 'fas fa-concierge-bell' }} text-primary mr-2"></i><strong>{{ $service->title }}</strong><br><small class="text-muted">/{{ $service->slug }}</small></td>
                    <td>{{ \Illuminate\Support\Str::limit($service->description, 80) }}</td>
                    <td><span class="badge {{ $service->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $service->is_active ? 'Hiển thị' : 'Ẩn' }}</span></td>
                    <td class="text-right"><a href="{{ route('admin.operation-services.edit', $service) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a><form action="{{ route('admin.operation-services.destroy', $service) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa dịch vụ này?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></form></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Chưa có dịch vụ vận hành.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $services->links() }}</div>
</div>
@stop
