@extends('layouts.site')

@section('content')
    <x-hero title="Tin tức & Kiến thức" highlight="Đồng hành cùng doanh nghiệp"
        lead="Chia sẻ kiến thức hữu ích về website, phần mềm, marketing và vận hành số, giúp doanh nghiệp bắt kịp xu hướng và phát triển bền vững."
        :image="asset('frontend/images/news-hero.webp')" image-alt="Bài viết kiến thức trên laptop và máy tính bảng" />

    @if ($featured->isNotEmpty())
        <section class="section">
            <div class="container">
                <x-section-head title="Bài viết nổi bật" lead="Những bài viết mang đến góc nhìn thực tế và giá trị ứng dụng cho doanh nghiệp." />
                <div class="row g-4">
                    <div class="col-lg-6"><x-card.post :post="$featured->first()" variant="featured" /></div>
                    <div class="col-lg-6 d-grid gap-3 align-content-start">
                        @foreach ($featured->skip(1) as $post)
                            <x-card.post :post="$post" variant="row" />
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section @class(['section', 'section--soft' => $featured->isNotEmpty()]) id="bai-viet">
        <div class="container">
            <x-section-head :title="$search !== '' ? 'Kết quả cho “'.$search.'”' : 'Bài viết mới nhất'" lead="Cập nhật những kiến thức, xu hướng và kinh nghiệm mới nhất về website, phần mềm và chuyển đổi số." />
            @include('site.articles._filters')
            @if ($posts->isEmpty())
                <p class="empty-state">Không có bài viết phù hợp. Hãy đổi từ khóa hoặc danh mục.</p>
            @else
                <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                    @foreach ($posts as $post)
                        <div class="col"><x-card.post :post="$post" /></div>
                    @endforeach
                </div>
                <div class="mt-5">{{ $posts->links('partials.site.pagination') }}</div>
            @endif
        </div>
    </section>

    <x-cta-banner title="Cần áp dụng kiến thức này cho website của bạn?" text="WebApp Bắc Ninh có thể kiểm tra hiện trạng website và đề xuất phạm vi cải thiện thực tế." need="Tư vấn cải thiện website" />
@endsection
