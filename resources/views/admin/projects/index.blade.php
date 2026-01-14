@extends('adminlte::page')

@section('title', 'Quản lý Dự án')

@section('content_header')
    <h1>Dự án tiêu biểu</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách dự án</h3>
        <div class="card-tools">
            <a href="{{ route('admin.projects.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
                    <th style="width: 80px">Ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Danh mục</th>
                    <th>Nổi bật</th>
                    <th style="width: 150px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                <tr>
                    <td>{{ $project->id }}</td>
                    <td>
                        @if($project->image)
                            <img src="{{ asset('storage/' . $project->image) }}" alt="" style="width: 60px; height: 40px; object-fit: cover;" class="rounded">
                        @else
                            <span class="badge bg-secondary">No Image</span>
                        @endif
                    </td>
                    <td>{{ $project->title }}</td>
                    <td><span class="badge bg-info">{{ $project->category ?? 'N/A' }}</span></td>
                    <td>
                        @if($project->is_featured)
                            <span class="badge bg-success"><i class="fas fa-star"></i> Nổi bật</span>
                        @else
                            <span class="badge bg-secondary">Thường</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Chưa có dự án nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{ $projects->links() }}
    </div>
</div>
@stop
