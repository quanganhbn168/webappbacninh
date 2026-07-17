@extends('layouts.admin')

@section('title', 'Nhóm dịch vụ')
@section('header_title', 'Nhóm dịch vụ')

@section('breadcrumb')
    <li class="breadcrumb-item active">Nhóm dịch vụ</li>
@stop

@section('admin_content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Các nhóm dùng cho trang dịch vụ theo category</h3>
        <div class="card-tools">
            <a href="{{ route('admin.service-categories.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Thêm nhóm</a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead><tr><th>Tên nhóm</th><th>Slug</th><th>Dịch vụ</th><th>Trạng thái</th><th class="text-right">Thao tác</th></tr></thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="font-weight-bold">{{ $category->name }}</td>
                        <td>/{{ $category->slug }}</td>
                        <td>{{ $category->services_count }}</td>
                        <td><span class="badge {{ $category->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $category->is_active ? 'Hiển thị' : 'Ẩn' }}</span></td>
                        <td class="text-right">
                            <a href="{{ route('slug.handle', ['slug' => $category->slug]) }}" class="btn btn-sm btn-outline-secondary" target="_blank" title="Xem trang"><i class="fas fa-external-link-alt"></i></a>
                            <a href="{{ route('admin.service-categories.edit', $category) }}" class="btn btn-sm btn-info" title="Sửa"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.service-categories.destroy', $category) }}" method="POST" class="d-inline confirm-delete">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Chưa có nhóm dịch vụ nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">{{ $categories->links() }}</div>
</div>
@stop
