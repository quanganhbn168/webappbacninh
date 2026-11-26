// Landing forms marked data-landing-lead post to the shared contact endpoint, so every
// request becomes a Lead in the admin and a Telegram alert, with no page reload.
// On the demo of a catalog design (<body data-demo>) nothing is sent, called or counted.
import { initContactTracking, track } from '../site/analytics';

const isDemo = () => document.body.hasAttribute('data-demo');
const DEMO_NOTICE = 'Đây là trang mẫu, thông tin không được gửi đi.';

// Ad parameters of the address the visitor arrived on, kept for the visit so they
// are still there when the form is sent after a reload. Same list as Lead::CAMPAIGN_KEYS.
const CAMPAIGN_KEYS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'gbraid', 'wbraid', 'fbclid', 'ttclid'];
const campaignStore = `landing-campaign:${window.location.pathname}`;

function readCampaign() {
    const params = new URLSearchParams(window.location.search);
    const current = Object.fromEntries(CAMPAIGN_KEYS.filter((key) => params.get(key)).map((key) => [key, params.get(key)]));
    try {
        if (Object.keys(current).length) sessionStorage.setItem(campaignStore, JSON.stringify(current));
        else return JSON.parse(sessionStorage.getItem(campaignStore)) ?? {};
    } catch { /* Without storage the parameters of this address still count. */ }
    return current;
}

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';

function status(form, message, ok) {
    const element = form.querySelector('[data-lead-status]');
    if (!element) return;
    element.textContent = message;
    element.classList.toggle('is-error', !ok);
    element.hidden = false;
}

// The sample hotline and email of a demo lead nowhere.
function holdDemoContacts() {
    document.addEventListener('click', (event) => {
        const link = event.target.closest?.('a[href^="tel:"], a[href^="mailto:"]');
        if (!link) return;
        event.preventDefault();
        window.alert('Đây là trang mẫu, số điện thoại và email chỉ để minh họa.');
    });
}

export function initLandingLeads(onSent = () => {}) {
    const campaign = readCampaign();
    if (isDemo()) holdDemoContacts();
    else initContactTracking();

    document.addEventListener('submit', async (event) => {
        const form = event.target.closest('form[data-landing-lead]');
        if (!form) return;
        event.preventDefault();
        if (isDemo()) {
            status(form, DEMO_NOTICE, true);
            return;
        }
        if (!form.reportValidity()) return;

        const data = new FormData(form);
        data.set('need', form.dataset.need ?? '');
        data.set('source', window.location.pathname);
        Object.entries(campaign).forEach(([key, value]) => data.set(`campaign[${key}]`, value));

        // Extra answers (for example the room type) travel in the free-text message.
        const notes = [...form.querySelectorAll('[data-lead-note]')].map((field) => `${field.dataset.leadNote}: ${field.value}`);
        if (notes.length) data.set('message', notes.join('\n'));

        const button = form.querySelector('[type="submit"]');
        button.disabled = true;
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: data,
                headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf() },
            });
            const payload = await response.json().catch(() => ({}));
            if (!response.ok) {
                throw new Error(Object.values(payload.errors ?? {}).flat()[0] ?? payload.message ?? 'Không thể gửi yêu cầu. Vui lòng thử lại.');
            }
            // The conversion, for GA4 / Tag Manager and for a Meta pixel when the page has one.
            track('generate_lead', { form_location: 'landing', page_path: window.location.pathname, need: data.get('need') || undefined });
            window.fbq?.('track', 'Lead');
            form.reset();
            status(form, payload.message, true);
            onSent(payload.message);
        } catch (error) {
            status(form, error.message, false);
        } finally {
            button.disabled = false;
        }
    });
}
