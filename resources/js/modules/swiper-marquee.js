// resources/js/modules/swiper-marquee.js

import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

export function initMarqueeSwiper() {
  new Swiper('.marquee-swiper', {
    loop: true,
    freeMode: true,
    grabCursor: true,
    allowTouchMove: false,
    slidesPerView: 'auto',
    spaceBetween: 60,
    speed: 8000,
    autoplay: {
      delay: 0,
      disableOnInteraction: false,
    },
  });
}