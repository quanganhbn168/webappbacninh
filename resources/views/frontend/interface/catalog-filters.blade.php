<div class="filter-tabs" role="group" aria-label="Lọc danh mục">
    <button class="filter-tab" data-filter="all" @click="category='all'" :class="{active:category==='all'}" :aria-pressed="category==='all'">Tất cả</button>
    @foreach ($catalogCategories as $category)
        <button class="filter-tab" data-filter="{{ $category['slug'] }}" @click="category = $el.dataset.filter" :class="{active:category === $el.dataset.filter}" :aria-pressed="category === $el.dataset.filter">{{ $category['label'] }}</button>
    @endforeach
</div>
