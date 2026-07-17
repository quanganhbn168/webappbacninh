@extends('layouts.admin')

@section('title', 'Quản lý bài viết')

@section('header_title', 'Blog & Tin tức')

@section('breadcrumb')
    <li class="breadcrumb-item active">Blog</li>
@stop

@section('admin_content')
    <div class="card card-outline card-primary shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h3 class="card-title font-weight-bold">Danh sách bài viết</h3>
            <div class="card-tools">
                <a href="{{ route('admin.blog.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Tạo bài viết mới
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover table-striped mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 50px">#</th>
                        <th>Ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Trạng thái</th>
                        <th>Ngày xuất bản</th>
                        <th style="width: 150px" class="text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="rounded overflow-hidden shadow-sm" style="width: 60px; height: 40px; background: #eee;">
                                @if($post->hasMedia('featured') || $post->featured_image)
                                    <img src="{{ $post->featured_image_url }}" class="img-fluid object-fit-cover" style="width: 100%; height: 100%;" alt="thumb">
                                    @else
                                        <i class="fas fa-image text-muted d-block text-center mt-2"></i>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="font-weight-bold">{{ $post->title }}</div>
                                <small class="text-muted text-truncate d-block" style="max-width: 300px;">{{ $post->slug }}</small>
                            </td>
                            <td>
                                @if($post->is_published)
                                    <span class="badge badge-success px-2 py-1">Đã xuất bản</span>
                                @else
                                    <span class="badge badge-warning px-2 py-1 text-white">Bản nháp</span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                {{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : '---' }}
                            </td>
                            <td class="text-right">
                                <div class="btn-group">
                                    <a href="{{ route('admin.blog.edit', $post->id) }}" class="btn btn-sm btn-outline-primary mr-1 rounded" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.blog.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Anh có chắc muốn xóa bài viết này không?')">
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
                                <i class="fas fa-newspaper fa-3x mb-3 d-block"></i>
                                Chưa có bài viết nào được đăng. Hãy bắt đầu ngay!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($posts->hasPages())
            <div class="card-footer bg-white border-top-0">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@stop
