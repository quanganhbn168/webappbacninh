// The theme demo in a frame sized like a computer, a tablet or a phone. The frame
// really has that width, so the demo lays itself out as it would on the device;
// a frame wider than the screen is scaled down to fit.
import { initContactTracking, track } from '../site/analytics';

// A computer is never narrower than this, even when the visitor's own window is.
const DESKTOP_WIDTH = 1280;
const DEVICES = {
    desktop: null,
    tablet: { width: 768, height: 1024 },
    mobile: { width: 390, height: 844 },
};
// On a phone the demo simply fills the screen (the device buttons are hidden by CSS).
const phone = window.matchMedia('(max-width: 767.98px)');

const preview = document.querySelector('[data-demo-preview]');
const stage = preview.querySelector('[data-stage]');
const screen = preview.querySelector('[data-screen]');
const frame = preview.querySelector('[data-frame]');
let device = 'desktop';

function fit() {
    preview.dataset.view = device;
    if (phone.matches) {
        screen.removeAttribute('style');
        frame.removeAttribute('style');
        return;
    }

    const style = getComputedStyle(stage);
    const room = {
        width: stage.clientWidth - parseFloat(style.paddingLeft) - parseFloat(style.paddingRight),
        height: stage.clientHeight - parseFloat(style.paddingTop) - parseFloat(style.paddingBottom),
    };
    const size = DEVICES[device];
    const width = size?.width ?? Math.max(room.width, DESKTOP_WIDTH);
    const scale = Math.min(1, room.width / width);
    const height = Math.min(size?.height ?? Infinity, room.height / scale);

    screen.style.width = `${width * scale}px`;
    screen.style.height = `${height * scale}px`;
    frame.style.width = `${width}px`;
    frame.style.height = `${height}px`;
    frame.style.transform = `scale(${scale})`;
}

preview.querySelectorAll('[data-device]').forEach((button) => {
    button.addEventListener('click', () => {
        device = button.dataset.device;
        preview.querySelectorAll('[data-device]').forEach((other) => {
            other.classList.toggle('active', other === button);
            other.setAttribute('aria-pressed', String(other === button));
        });
        fit();
        track('preview_device', { device, theme_code: preview.dataset.themeCode });
    });
});

new ResizeObserver(fit).observe(stage);
phone.addEventListener('change', fit);
fit();
initContactTracking();
