import { initLandingLeads } from './lead';

initLandingLeads();

const menuButton = document.querySelector('.ph-menu');
const navigation = document.querySelector('.ph-nav');

function closeMenu() {
    navigation.classList.remove('is-open');
    menuButton.setAttribute('aria-expanded', 'false');
    menuButton.setAttribute('aria-label', 'Mở menu');
}

menuButton.addEventListener('click', () => {
    const isOpen = navigation.classList.toggle('is-open');
    menuButton.setAttribute('aria-expanded', String(isOpen));
    menuButton.setAttribute('aria-label', isOpen ? 'Đóng menu' : 'Mở menu');
});

navigation.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', closeMenu);
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && navigation.classList.contains('is-open')) {
        closeMenu();
        menuButton.focus();
    }
});

document.addEventListener('click', (event) => {
    if (!event.target.closest('.ph-header')) closeMenu();
});

const navLinks = [...navigation.querySelectorAll('a')];
const sections = navLinks.map((link) => document.querySelector(link.hash));
function updateActiveNavigation() {
    let current = sections[0];
    sections.forEach((section) => {
        if (section.getBoundingClientRect().top <= 120 && section.offsetTop >= current.offsetTop) current = section;
    });
    if (window.scrollY > 0 && window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 2) current = sections.at(-1);
    navLinks.forEach((link) => {
        const active = link.hash === `#${current.id}`;
        link.classList.toggle('is-active', active);
        if (active) link.setAttribute('aria-current', 'location');
        else link.removeAttribute('aria-current');
    });
}

let navigationUpdatePending = false;
window.addEventListener('scroll', () => {
    if (navigationUpdatePending) return;
    navigationUpdatePending = true;
    requestAnimationFrame(() => {
        updateActiveNavigation();
        navigationUpdatePending = false;
    });
}, { passive: true });
window.addEventListener('resize', updateActiveNavigation);
updateActiveNavigation();

document.querySelectorAll('[data-dialog-open]').forEach((button) => {
    button.addEventListener('click', () => {
        document.getElementById(button.dataset.dialogOpen).showModal();
    });
});

document.querySelectorAll('.ph-dialog').forEach((dialog) => {
    dialog.querySelector('[data-dialog-close]').addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', (event) => {
        if (event.target !== dialog) return;
        const bounds = dialog.getBoundingClientRect();
        if (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom) dialog.close();
    });
});

const allReviews = document.querySelector('.ph-dialog-reviews');
document.querySelectorAll('#ph-review-cards > article').forEach((review) => {
    allReviews.append(review.cloneNode(true));
});

// Policy texts come from the landing page record (see #landing-config in the view).
const policies = JSON.parse(document.getElementById('landing-config').textContent).policies;

document.querySelectorAll('[data-policy]').forEach((button) => {
    button.addEventListener('click', () => {
        const [title, content] = policies[button.dataset.policy];
        document.getElementById('ph-policy-title').textContent = title;
        document.getElementById('ph-policy-content').textContent = content;
        document.getElementById('ph-policy').showModal();
    });
});
