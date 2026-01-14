@extends('adminlte::page')

@section('title', 'Quản lý Banner')

@section('content_header')
    <h1>Banner / Quảng cáo</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách banner</h3>
        <div class="card-tools">
            <a href="{{ route('admin.banners.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
                    <th style="width: 100px">Ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Vị trí</th>
                    <th>Thời gian</th>
                    <th>Trạng thái</th>
                    <th style="width: 150px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                <tr>
                    <td>{{ $banner->id }}</td>
                    <td>
                        @if($banner->image)
                            <img src="{{ asset('storage/' . $banner->image) }}" alt="" style="width: 80px; height: 45px; object-fit: cover;" class="rounded">
                        @else
                            <span class="badge bg-secondary">No Image</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $banner->title }}</strong>
                        @if($banner->subtitle)
                            <br><small class="text-muted">{{ $banner->subtitle }}</small>
                        @endif
                    </td>
                    <td><span class="badge bg-info">{{ $banner->position }}</span></td>
                    <td>
                        <small>
                            {{ $banner->start_date?->format('d/m/Y') ?? 'N/A' }} - {{ $banner->end_date?->format('d/m/Y') ?? 'Không giới hạn' }}
                        </small>
                    </td>
                    <td>
                        @if($banner->is_active)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
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
                    <td colspan="7" class="text-center text-muted py-4">Chưa có banner nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{ $banners->links() }}
    </div>
</div>
@stop
