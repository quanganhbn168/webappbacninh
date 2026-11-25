// Project detail gallery. Swiper comes from public/vendor/swiper, loaded before this module.
const speed = matchMedia('(prefers-reduced-motion: reduce)').matches ? 0 : 350;

document.querySelectorAll('[data-project-gallery]').forEach(gallery => {
    const main = gallery.querySelector('.project-gallery__main');
    const strip = gallery.querySelector('.project-gallery__thumbs');
    if (!window.Swiper || !main || !strip) return;

    const thumbs = new window.Swiper(strip, {
        slidesPerView: 'auto',
        spaceBetween: 12,
        watchSlidesProgress: true,
        speed,
    });
    const slider = new window.Swiper(main, {
        speed,
        rewind: true,
        grabCursor: true,
        keyboard: { enabled: true, onlyInViewport: true, pageUpDown: false },
        navigation: {
            prevEl: main.querySelector('.project-gallery__nav--prev'),
            nextEl: main.querySelector('.project-gallery__nav--next'),
            addIcons: false,
        },
        pagination: { el: main.querySelector('.project-gallery__counter'), type: 'fraction' },
        thumbs: { swiper: thumbs },
        a11y: {
            prevSlideMessage: 'Ảnh trước',
            nextSlideMessage: 'Ảnh tiếp theo',
            slideLabelMessage: 'Ảnh {{index}} / {{slidesLength}}',
        },
    });

    // Thumbnail buttons also work from the keyboard.
    strip.querySelectorAll('button').forEach((button, index) => {
        button.addEventListener('click', () => slider.slideTo(index));
    });
});
