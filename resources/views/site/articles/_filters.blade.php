{{-- Category links and search, shared by the article list and category pages. --}}
<div class="catalog-toolbar">
    <nav class="chip-list" aria-label="Danh mục bài viết">
        <a @class(['chip', 'active' => ! isset($category)]) href="{{ route('articles.index') }}" @if (! isset($category)) aria-current="page" @endif>Tất cả</a>
        @foreach ($categories as $item)
            <a @class(['chip', 'active' => isset($category) && $category->is($item)]) href="{{ route('articles.category', $item->slug) }}" @if (isset($category) && $category->is($item)) aria-current="page" @endif>{{ $item->name }}</a>
        @endforeach
    </nav>
    <form class="search-field" action="{{ route('articles.index') }}" method="get" role="search">
        <label class="visually-hidden" for="article-search">Tìm bài viết</label>
        <x-icon name="search" />
        <input class="form-control" id="article-search" type="search" name="q" value="{{ $search ?? '' }}" placeholder="Tìm bài viết…">
    </form>
</div>
