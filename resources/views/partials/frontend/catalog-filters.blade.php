<div class="filter-tabs" role="group" aria-label="Lọc danh mục">
    <button class="filter-tab" data-filter="all">Tất cả</button>
    @foreach ($catalogCategories as $category)
        <button class="filter-tab" data-filter="{{ $category['slug'] }}">{{ $category['label'] }}</button>
    @endforeach
</div>
