<div class="secondary-content">
    <main id="main" x-data="catalog()" data-catalog>
        <section class="hero">
            <div class="container hero-inner">
                <div class="hero-copy">
                    <div class="breadcrumb"><a href="{{ route('home') }}">Trang chủ</a> / <a href="{{ route('articles.index') }}">Kiến thức</a></div>
                    <h1>{{ $category->name }}</h1>
                    <p>{{ $category->description }}</p>
                </div>
                @if($category->image)
                    <div class="hero-art"><img src="{{ $category->image->url }}" alt="{{ $category->image->alt ?: $category->name }}"></div>
                @endif
            </div>
        </section>
        <section class="section">
            <div class="container cards-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($catalogItems as $item)
                    @include('partials.frontend.catalog-card', ['catalogType' => 'articles'])
                @empty
                    <p>Danh mục này chưa có bài viết. <a href="{{ route('articles.index') }}">Xem tất cả bài viết →</a></p>
                @endforelse
            </div>
        </section>
    </main>
</div>
