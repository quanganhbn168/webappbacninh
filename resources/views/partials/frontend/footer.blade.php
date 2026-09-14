<footer class="site-footer" id="lien-he">
   <div class="container footer-grid">
    <div class="footer-brand">
     <a aria-label="WebApp Bắc Ninh - Trang chủ" class="brand" href="/">
      @if(site_config('site_logo_wide'))
<img class="brand__logo" src="{{ site_asset_url(site_config('site_logo_wide')) }}" alt="{{ site_config('name') }}" width="230" height="64">
@else
<span class="brand__name">
       WEBAPP
       <b>
        BẮC NINH
       </b>
      </span>
      <span class="brand__tagline">
       <i>
       </i>
       WEBSITE - PHẦN MỀM - VẬN HÀNH SỐ
       <i>
       </i>
      </span>
     @endif
</a>
     <p>
      Đồng hành cùng doanh nghiệp trong hành trình chuyển đổi số với những giải pháp website, phần mềm và vận hành hiệu quả.
     </p>
     <div class="flex flex-wrap gap-3 mt-4">
@foreach ($socialChannels['footer'] as $channel)
<a href="{{ $channel['url'] }}" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold">{{ $channel['label'] }}</a>
@endforeach
</div>
    </div>
    <div class="footer-column">
     <h3>
      Dịch vụ
     </h3>
     <a href="/dich-vu#thiet-ke-website">
      Thiết kế Website
     </a>
     <a href="/dich-vu#phan-mem">
      Phần mềm doanh nghiệp
     </a>
     <a href="/dich-vu#crm-booking">
      CRM &amp; Booking
     </a>
     <a href="/dich-vu#seo-quang-cao">
      SEO &amp; Nội dung
     </a>
     <a href="/dich-vu#seo-quang-cao">
      Quảng cáo &amp; Tracking
     </a>
     <a href="/dich-vu#quy-trinh">
      Chăm sóc &amp; Vận hành
     </a>
    </div>
    <div class="footer-column">
     <h3>
      Sản phẩm
     </h3>
     <button data-description="Website dành cho spa, thẩm mỹ và chăm sóc sắc đẹp." data-image="/frontend/images/product-spa.svg" data-preview="" data-title="Website Spa" type="button">
      Website Spa
     </button>
     <button data-description="Giới thiệu tour, điểm đến, lưu trú và nhận yêu cầu đặt chỗ." data-image="/frontend/images/project-resort.webp" data-preview="" data-title="Website Du lịch" type="button">
      Website Du lịch
     </button>
     <button data-description="Trưng bày bộ sưu tập nội thất, dự án và hồ sơ năng lực." data-image="/frontend/images/service-website.webp" data-preview="" data-title="Website Nội thất" type="button">
      Website Nội thất
     </button>
     <button data-description="Giới thiệu thiết bị, dịch vụ và dự án phòng cháy chữa cháy." data-image="/frontend/images/product-pccc.svg" data-preview="" data-title="Website PCCC" type="button">
      Website PCCC
     </button>
     <button data-description="Quản lý khách hàng, cơ hội và quy trình chăm sóc." data-image="/frontend/images/service-crm.webp" data-preview="" data-title="CRM" type="button">
      CRM
     </button>
     <button data-description="Nhận và quản lý lịch đặt hẹn cho doanh nghiệp dịch vụ." data-image="/frontend/images/hero-services.webp" data-preview="" data-title="Booking System" type="button">
      Booking System
     </button>
     <button data-description="Kết nối các nghiệp vụ vận hành trên một hệ thống quản trị." data-image="/frontend/images/service-software.webp" data-preview="" data-title="Mini ERP" type="button">
      Mini ERP
     </button>
    </div>
    <div class="footer-column">
     <h3>
      Về chúng tôi
     </h3>
     <a href="/gioi-thieu">
      Giới thiệu
     </a>
     <a href="/du-an">
      Dự án tiêu biểu
     </a>
     <a href="/bang-gia">
      Bảng giá
     </a>
     <a href="/kien-thuc">
      Blog - Kiến thức
     </a>
     <button data-consult="Hợp tác / Tuyển dụng" type="button">
      Tuyển dụng
     </button>
     <button data-consult="Liên hệ" type="button">
      Liên hệ
     </button>
    </div>
    <div class="footer-column footer-contact">
     @if (site_config('phone_secondary') && site_config('phone_secondary_href'))
         <a href="tel:{{ site_config('phone_secondary_href') }}">{{ site_config('phone_secondary') }}</a>
     @endif
     <h3>
      Liên hệ
     </h3>
     <a href="tel:{{ site_config('phone_href') }}">
      <svg aria-hidden="true" class="icon" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" viewBox="0 0 24 24" width="24">
       <path d="m7 2-4 2c-3 8 9 20 17 17l2-4-6-4-3 3-5-5 3-3-4-6Z">
       </path>
      </svg>
      <span data-contact-phone="">
       {{ site_config('phone') }}
      </span>
     </a>
     <a href="mailto:{{ site_config('email') }}">
      <svg aria-hidden="true" class="icon" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" viewBox="0 0 24 24" width="24">
       <rect height="16" rx="1.5" width="20" x="2" y="4">
       </rect>
       <path d="m2 5 10 8L22 5M2 19l7-7m13 7-7-7">
       </path>
      </svg>
      <span data-contact-email="">
       {{ site_config('email') }}
      </span>
     </a>
     <span class="contact-line">
      <svg aria-hidden="true" class="icon" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" viewBox="0 0 24 24" width="24">
       <path d="M20 9c0 6-8 13-8 13S4 15 4 9a8 8 0 1 1 16 0Z">
       </path>
       <circle cx="12" cy="9" r="3">
       </circle>
      </svg>
      {{ site_config('address') }}
     </span>
    </div>
   </div>
   <div class="container footer-bottom">
    <p>
     © {{ date('Y') }} {{ site_config('name') }}. All rights reserved.
    </p>
    <span>
     WEBSITE – PHẦN MỀM – VẬN HÀNH SỐ
    </span>
   </div>
  </footer>
