import './bootstrap';
import 'bootstrap';
import '@fortawesome/fontawesome-free/js/all.min.js';
import 'animate.css';

import Toastify from 'toastify-js';
window.Toastify = Toastify;

// Animate on scroll (nếu dùng animate.css + AOS)
import AOS from 'aos';
import 'aos/dist/aos.css';
AOS.init();

// Swiper
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import { initMarqueeSwiper } from './modules/swiper-marquee';

document.addEventListener('DOMContentLoaded', () => {
  // Swiper thường (slider ảnh)
  document.querySelectorAll('.swiper:not(.marquee-swiper)').forEach((el) => {
    new Swiper(el, {
      loop: true,
      autoplay: {
        delay: 4000,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
    });
  });

  // Swiper chữ chạy
  initMarqueeSwiper();
});