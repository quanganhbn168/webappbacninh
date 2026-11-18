// Hides the sticky header while the page scrolls down and shows it again on any scroll up.
// It stays visible near the top, while a dropdown or the mobile menu is open, and on keyboard focus.
const THRESHOLD = 8; // px of travel before the header reacts, so small jitters are ignored

export function initHeader() {
    const header = document.querySelector('.site-header');
    if (!header) return;

    let lastY = window.scrollY;
    let pending = false;

    const update = () => {
        pending = false;
        const y = window.scrollY;
        if (Math.abs(y - lastY) < THRESHOLD) return;

        const menuOpen = header.querySelector('.dropdown-menu.show, .offcanvas.show, .offcanvas.showing');
        header.classList.toggle('is-hidden', y > lastY && y > header.offsetHeight && !menuOpen);
        lastY = y;
    };

    window.addEventListener('scroll', () => {
        if (pending) return;
        pending = true;
        requestAnimationFrame(update);
    }, { passive: true });

    header.addEventListener('focusin', () => header.classList.remove('is-hidden'));
}
