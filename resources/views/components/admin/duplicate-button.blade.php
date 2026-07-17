@props(['model', 'id', 'createRoute'])

<form action="{{ route('admin.global.duplicate') }}" method="POST" {{ $attributes->merge(['class' => 'd-inline-block']) }}>
    @csrf
    <input type="hidden" name="model" value="{{ $model }}">
    <input type="hidden" name="id" value="{{ $id }}">
    <input type="hidden" name="create_route" value="{{ $createRoute }}">
    <button type="button" class="btn btn-sm btn-secondary btn-duplicate" title="Nhân bản">
        <i class="fas fa-copy"></i>
    </button>
</form>
