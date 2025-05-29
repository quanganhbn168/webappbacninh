{{-- resources/views/partials/frontend/marquee.blade.php --}}
<section class="bg-dark py-2 overflow-hidden">
  <div class="swiper marquee-swiper">
    <div class="swiper-wrapper">
      @foreach (['UY TÍN', 'GIÁ RẺ', 'HỖ TRỢ TẬN TÂM', 'CHUYÊN NGHIỆP', 'CHUẨN SEO','UY TÍN', 'GIÁ RẺ', 'HỖ TRỢ TẬN TÂM', 'CHUYÊN NGHIỆP', 'CHUẨN SEO','UY TÍN', 'GIÁ RẺ', 'HỖ TRỢ TẬN TÂM', 'CHUYÊN NGHIỆP', 'CHUẨN SEO',] as $item)
        <div class="swiper-slide text-uppercase text-white px-4 fw-bold">
          {{ $item }}
        </div>
      @endforeach
    </div>
  </div>
</section>
