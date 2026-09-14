import Alpine from 'alpinejs';

const settings = JSON.parse(document.getElementById('interface-settings')?.textContent || '{}');
async function submitConsultation(payload) {
    const response = await fetch('/lien-he', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ ...payload, need: payload.service, source: location.pathname }),
    });
    const result = await response.json();
    if (!response.ok) {
        throw new Error(Object.values(result.errors || {}).flat()[0] || result.message || 'Không thể gửi yêu cầu. Vui lòng thử lại.');
    }
    return { ok: true, message: result.message };
}
window.WEBAPP_CONFIG = { ...settings, submitConsultation };
window.WEBAPP_SITE_CONFIG = { ...settings, submitConsultation };
async function initialize() {
await import('./interface/primary.js');
await import('./interface/secondary.js');
window.Alpine = Alpine;
Alpine.start();

// Both source packages use this event to initialize their page interactions.
// Vite modules can complete after the browser has already dispatched it.
document.dispatchEvent(new Event('interface:ready'));
document.querySelectorAll('.main-nav a.nav-link').forEach(link => {
    const active = new URL(link.href).pathname === location.pathname;
    link.classList.toggle('is-active', active);
    if (active) link.setAttribute('aria-current', 'page');
    else link.removeAttribute('aria-current');
});
}
initialize();
