<footer class="site-footer" id="lien-he">
   <div class="container footer-grid">
    <div class="footer-brand">
     @include('partials.frontend.brand')
     <p>
      Đồng hành cùng doanh nghiệp trong hành trình chuyển đổi số với những giải pháp website, phần mềm và vận hành hiệu quả.
     </p>
     <div class="d-flex flex-wrap gap-3 mt-4">
@foreach ($socialChannels['footer'] as $channel)
<a href="{{ $channel['url'] }}" target="_blank" rel="noopener noreferrer" class="small fw-semibold">{{ $channel['label'] }}</a>
@endforeach
</div>
    </div>
    @foreach ([\App\Models\Menu::FOOTER_SERVICES, \App\Models\Menu::FOOTER_PRODUCTS, \App\Models\Menu::FOOTER_ABOUT] as $location)
     @php($footerMenu = app(\App\Domain\Navigation\Actions\BuildMenuTree::class)->name($location))
     @php($footerItems = app(\App\Domain\Navigation\Actions\BuildMenuTree::class)->execute($location))
     @if ($footerMenu && $footerItems !== [])
      <nav class="footer-column" aria-label="{{ $footerMenu }}">
       <h3>{{ $footerMenu }}</h3>
       @foreach ($footerItems as $item)
        <a href="{{ $item['url'] }}" @if ($item['new_tab']) target="_blank" rel="noopener" @endif>{{ $item['title'] }}</a>
       @endforeach
      </nav>
     @endif
    @endforeach
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
