@props(['action', 'size' => 'btn-sm', 'confirm' => 'Bạn có chắc chắn muốn xóa?'])

<form action="{{ $action }}" method="POST" {{ $attributes->merge(['class' => 'd-inline-block confirm-delete']) }}>
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger {{ $size }}" title="Xóa">
        <i class="fas fa-trash"></i>
    </button>
</form>
