// Article table of contents: the box inside the article ([data-toc]) and a round floating
// button ([data-toc-float]) that appears once the box has scrolled away.

// A long list in the box is cut short behind a fade, with "Xem thêm" to open all of it.
function initLongList(box) {
    const body = box.querySelector('[data-toc-body]');
    const more = box.querySelector('[data-toc-more]');
    if (!body || !more) return;

    // Not worth a button when only a line or two would be hidden.
    body.classList.add('is-clamped');
    if (body.scrollHeight <= body.clientHeight + 48) {
        body.classList.remove('is-clamped');
        return;
    }
    more.hidden = false;

    const setOpen = open => {
        body.classList.toggle('is-clamped', !open);
        more.setAttribute('aria-expanded', String(open));
        more.querySelector('span').textContent = open ? 'Thu gọn' : 'Xem thêm';
    };

    more.addEventListener('click', () => setOpen(body.classList.contains('is-clamped')));
    // Tabbing into a hidden link opens the list.
    body.addEventListener('focusin', event => {
        if (event.target.getBoundingClientRect().bottom > body.getBoundingClientRect().bottom) setOpen(true);
    });
}

export function initToc() {
    const box = document.querySelector('[data-toc]');
    const float = document.querySelector('[data-toc-float]');
    if (!box || !float) return;

    const button = float.querySelector('[data-toc-button]');
    const panel = float.querySelector('[data-toc-panel]');
    const links = [...document.querySelectorAll('[data-toc] a, [data-toc-panel] a')];
    const headings = [...box.querySelectorAll('a')].map(link => document.getElementById(link.hash.slice(1))).filter(Boolean);

    const setOpen = open => {
        panel.hidden = !open;
        button.setAttribute('aria-expanded', String(open));
        button.setAttribute('aria-label', open ? 'Đóng nội dung bài viết' : 'Mở nội dung bài viết');
    };

    // Marks the section being read: the last heading that has passed the top part of the window.
    let pending = false;
    const update = () => {
        pending = false;
        const past = box.getBoundingClientRect().bottom < 0;
        float.hidden = !past;
        if (!past) setOpen(false);

        const current = headings.filter(heading => heading.getBoundingClientRect().top < window.innerHeight * .3).pop();
        links.forEach(link => {
            if (current && link.hash === `#${current.id}`) link.setAttribute('aria-current', 'location');
            else link.removeAttribute('aria-current');
        });
    };

    window.addEventListener('scroll', () => {
        if (pending) return;
        pending = true;
        requestAnimationFrame(update);
    }, { passive: true });

    button.addEventListener('click', () => setOpen(panel.hidden));
    panel.addEventListener('click', event => { if (event.target.closest('a')) setOpen(false); });
    document.addEventListener('click', event => { if (!float.contains(event.target)) setOpen(false); });
    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape' || panel.hidden) return;
        setOpen(false);
        button.focus();
    });

    initLongList(box);
    update();
}
