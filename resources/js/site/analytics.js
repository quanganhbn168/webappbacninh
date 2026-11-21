// Conversion events for GA4. Works with the gtag.js snippet pasted in Settings → Tracking,
// or with Google Tag Manager (pushes { event } to the dataLayer). Without either it does nothing.
export function track(name, params = {}) {
    if (typeof window.gtag === 'function') {
        window.gtag('event', name, params);
    } else if (Array.isArray(window.dataLayer)) {
        window.dataLayer.push({ event: name, ...params });
    }
}

// Event name sent to GA4 for each kind of contact link, recognised by its address.
// To count a new channel, add a line here: ['click_<name>', /pattern matching its links/].
const CONTACT_LINKS = [
    ['click_phone', /^tel:/i],
    ['click_zalo', /(^|\/\/)(www\.)?(zalo\.me|chat\.zalo\.me|zaloapp\.com)/i],
    ['click_messenger', /(^|\/\/)(www\.)?(m\.me|messenger\.com)\//i],
    ['click_whatsapp', /(^|\/\/)(www\.)?(wa\.me|api\.whatsapp\.com|web\.whatsapp\.com|whatsapp\.com\/send)/i],
    ['click_telegram', /(^|\/\/)(www\.)?(t\.me|telegram\.me)\//i],
    ['click_email', /^mailto:/i],
];

function linkLocation(element) {
    if (element.closest('.floating-actions')) return 'floating';
    if (element.closest('header')) return 'header';
    if (element.closest('footer')) return 'footer';
    return 'content';
}

// Counts taps on contact links (call, Zalo, WhatsApp…), and on any element marked
// data-ga-event="event_name" — the way to track a button without touching this file.
export function initContactTracking() {
    document.addEventListener('click', (event) => {
        const marked = event.target.closest?.('[data-ga-event]');
        if (marked) {
            track(marked.dataset.gaEvent, { link_location: linkLocation(marked), page_path: window.location.pathname });
            return;
        }

        const link = event.target.closest?.('a[href]');
        if (!link) return;
        const href = link.getAttribute('href');
        const match = CONTACT_LINKS.find(([, pattern]) => pattern.test(href));
        if (!match) return;
        track(match[0], { link_url: href, link_location: linkLocation(link), page_path: window.location.pathname });
    });
}
