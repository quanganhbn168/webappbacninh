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
            <form action="{{ route('admin.ad-banners.bulkDestroy') }}" method="POST" id="bulkDeleteForm" class="d-inline">
                @csrf
                <input type="hidden" name="ids" id="bulkDeleteIds">
                <button type="submit" class="btn btn-danger btn-sm d-none" id="bulkDeleteBtn" onclick="return confirm('Xóa các mục đã chọn?')">
                    <i class="fas fa-trash"></i> Xóa đã chọn (<span id="selectedCount">0</span>)
                </button>
            </form>
            <a href="{{ route('admin.ad-banners.create') }}" class="btn btn-success btn-sm">
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
                    <th style="width: 120px">Ảnh</th>
                    <th>Tên</th>
                    <th>Vị trí</th>
                    <th>Thời gian</th>
                    <th>Trạng thái</th>
                    <th style="width: 120px">Thao tác</th>
                </tr>
            </thead>
            <tbody id="sortable-banners">
                @forelse($banners as $banner)
                <tr data-id="{{ $banner->id }}">
                    <td><input type="checkbox" class="row-checkbox" value="{{ $banner->id }}"></td>
                    <td class="handle" style="cursor: grab;"><i class="fas fa-grip-vertical text-muted"></i></td>
                    <td>
                        @if($banner->getFirstMediaUrl('banner_image', 'thumb'))
                            <img src="{{ $banner->getFirstMediaUrl('banner_image', 'thumb') }}" alt="" style="width: 100px; height: 50px; object-fit: cover;" class="rounded">
                        @else
                            <span class="badge bg-secondary">No Image</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $banner->name }}</strong>
                        @if($banner->link)
                            <br><small class="text-muted"><i class="fas fa-link"></i> {{ Str::limit($banner->link, 30) }}</small>
                        @endif
                    </td>
                    <td><span class="badge bg-info">{{ $banner->slot->label() }}</span></td>
                    <td>
                        <small>
                            {{ $banner->starts_at?->format('d/m/Y') ?? 'N/A' }} - {{ $banner->ends_at?->format('d/m/Y') ?? '∞' }}
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
                        <a href="{{ route('admin.ad-banners.edit', $banner) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.ad-banners.destroy', $banner) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
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
                    <td colspan="8" class="text-center text-muted py-4">Chưa có banner nào.</td>
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

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
$(function() {
    var el = document.getElementById('sortable-banners');
    if (el) {
        Sortable.create(el, {
            handle: '.handle',
            animation: 150,
            onEnd: function() {
                var order = [];
                $('#sortable-banners tr[data-id]').each(function() {
                    order.push($(this).data('id'));
                });
                
                $.ajax({
                    url: '{{ route("admin.ad-banners.updateOrder") }}',
                    method: 'POST',
                    data: { order: order, _token: '{{ csrf_token() }}' },
                    success: function() {
                        toastr.success('Đã cập nhật thứ tự!');
                    }
                });
            }
        });
    }

    $('#selectAll').on('change', function() {
        $('.row-checkbox').prop('checked', $(this).is(':checked'));
        updateBulkDeleteBtn();
    });

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
