export function initScrollTop() {
    const button = document.querySelector('[data-scroll-top]');
    if (!button) return;
    const update = () => { button.hidden = window.scrollY < 600; };
    button.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    window.addEventListener('scroll', update, { passive: true });
    update();
}
