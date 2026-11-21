// Conversion events for GA4. Works with the gtag.js snippet pasted in Settings → Tracking,
// or with Google Tag Manager (pushes { event } to the dataLayer). Without either it does nothing.
export function track(name, params = {}) {
    if (typeof window.gtag === 'function') {
        window.gtag('event', name, params);
    } else if (Array.isArray(window.dataLayer)) {
        window.dataLayer.push({ event: name, ...params });
    }
}

const CONTACT_LINKS = [
    ['click_phone', /^tel:/i],
    ['click_zalo', /(^|\/\/)(www\.)?(zalo\.me|chat\.zalo\.me|zaloapp\.com)/i],
    ['click_messenger', /(^|\/\/)(www\.)?(m\.me|messenger\.com)\//i],
];

function linkLocation(link) {
    if (link.closest('.floating-actions')) return 'floating';
    if (link.closest('header')) return 'header';
    if (link.closest('footer')) return 'footer';
    return 'content';
}

// Calls and Zalo chats are how most customers get in touch: count each tap on those links.
export function initContactTracking() {
    document.addEventListener('click', (event) => {
        const link = event.target.closest?.('a[href]');
        if (!link) return;
        const href = link.getAttribute('href');
        const match = CONTACT_LINKS.find(([, pattern]) => pattern.test(href));
        if (!match) return;
        track(match[0], { link_url: href, link_location: linkLocation(link), page_path: window.location.pathname });
    });
}
