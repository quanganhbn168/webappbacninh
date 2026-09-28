// [data-gallery] with an <img data-gallery-main> and buttons [data-gallery-image="url"].
export function initGalleries() {
    document.querySelectorAll('[data-gallery]').forEach(gallery => {
        const main = gallery.querySelector('[data-gallery-main]');
        gallery.addEventListener('click', event => {
            const thumb = event.target.closest('[data-gallery-image]');
            if (!thumb || !main) return;
            main.src = thumb.dataset.galleryImage;
            gallery.querySelectorAll('[data-gallery-image]').forEach(item => {
                item.classList.toggle('active', item === thumb);
                item.setAttribute('aria-pressed', String(item === thumb));
            });
        });
    });
}
