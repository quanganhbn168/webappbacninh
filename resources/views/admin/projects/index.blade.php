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
            <form action="{{ route('admin.projects.bulkDestroy') }}" method="POST" id="bulkDeleteForm" class="d-inline">
                @csrf
                <input type="hidden" name="ids" id="bulkDeleteIds">
                <button type="submit" class="btn btn-danger btn-sm d-none" id="bulkDeleteBtn" onclick="return confirm('Xóa các mục đã chọn?')">
                    <i class="fas fa-trash"></i> Xóa đã chọn (<span id="selectedCount">0</span>)
                </button>
            </form>
            <a href="{{ route('admin.projects.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th style="width: 30px"><input type="checkbox" id="selectAll"></th>
                    <th style="width: 30px"><i class="fas fa-grip-vertical text-muted"></i></th>
                    <th style="width: 80px">Ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Danh mục</th>
                    <th>Nổi bật</th>
                    <th style="width: 120px">Thao tác</th>
                </tr>
            </thead>
            <tbody id="sortable-projects">
                @forelse($projects as $project)
                <tr data-id="{{ $project->id }}">
                    <td><input type="checkbox" class="row-checkbox" value="{{ $project->id }}"></td>
                    <td class="handle" style="cursor: grab;"><i class="fas fa-grip-vertical text-muted"></i></td>
                    <td>
                        @if($project->image)
                            <img src="{{ $project->image_url }}" alt="" style="width: 60px; height: 40px; object-fit: cover;" class="rounded">
                        @else
                            <span class="badge bg-secondary">No Image</span>
                        @endif
                    </td>
                    <td>{{ $project->title }}</td>
                    <td><span class="badge bg-info">{{ $project->category?->label() ?? 'N/A' }}</span></td>
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
                    <td colspan="7" class="text-center text-muted py-4">Chưa có dự án nào.</td>
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

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
$(function() {
    // SortableJS for drag-to-sort
    var el = document.getElementById('sortable-projects');
    if (el) {
        Sortable.create(el, {
            handle: '.handle',
            animation: 150,
            onEnd: function() {
                var order = [];
                $('#sortable-projects tr[data-id]').each(function() {
                    order.push($(this).data('id'));
                });
                
                $.ajax({
                    url: '{{ route("admin.projects.updateOrder") }}',
                    method: 'POST',
                    data: { order: order, _token: '{{ csrf_token() }}' },
                    success: function() {
                        toastr.success('Đã cập nhật thứ tự!');
                    }
                });
            }
        });
    }

    // Select all checkbox
    $('#selectAll').on('change', function() {
        $('.row-checkbox').prop('checked', $(this).is(':checked'));
        updateBulkDeleteBtn();
    });

    // Individual checkbox
    $('.row-checkbox').on('change', updateBulkDeleteBtn);

    function updateBulkDeleteBtn() {
        var checked = $('.row-checkbox:checked');
        var count = checked.length;
        $('#selectedCount').text(count);
        
        if (count > 0) {
            $('#bulkDeleteBtn').removeClass('d-none');
            var ids = checked.map(function() { return $(this).val(); }).get();
            $('#bulkDeleteIds').val(JSON.stringify(ids));
        } else {
            $('#bulkDeleteBtn').addClass('d-none');
        }
    }
});
</script>
@endpush
