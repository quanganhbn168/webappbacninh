@props([
    'action' => null, 
    'model', 
    'buttonText' => 'Xóa đã chọn',
    'confirmMessage' => 'Bạn có chắc chắn muốn xóa các mục đã chọn?'
])

@php
    $action = $action ?? route('admin.bulk-destroy');
@endphp

<form action="{{ $action }}" method="POST" id="bulkDeleteForm" class="d-inline">
    @csrf
    <input type="hidden" name="model" value="{{ $model }}">
    <input type="hidden" name="ids" id="bulkDeleteIds">
    <button type="submit" class="btn btn-danger btn-sm d-none" id="bulkDeleteBtn">
        <i class="fas fa-trash"></i> {{ $buttonText }} (<span id="selectedCount">0</span>)
    </button>
</form>
