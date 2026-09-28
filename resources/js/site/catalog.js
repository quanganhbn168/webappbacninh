// Client-side filtering for short lists rendered by the server:
//   [data-catalog]            wrapper
//   [data-catalog-filter=x]   buttons, "all" shows everything
//   [data-catalog-search]     text input (accent-insensitive)
//   [data-catalog-sort]       select: featured | az | za
//   [data-catalog-item]       items with data-categories="a b" and data-title
//   [data-catalog-empty]      shown when nothing matches
const fold = value => String(value ?? '').normalize('NFD').replace(/[̀-ͯ]/g, '').replace(/đ/gi, 'd').toLowerCase().trim();

function initCatalog(root) {
    const items = [...root.querySelectorAll('[data-catalog-item]')];
    const list = items[0]?.parentElement;
    const empty = root.querySelector('[data-catalog-empty]');
    const state = { category: 'all', query: '', sort: 'featured' };

    function render() {
        const query = fold(state.query);
        let visible = 0;
        items.forEach(item => {
            const categories = (item.dataset.categories ?? '').split(' ');
            const show = (state.category === 'all' || categories.includes(state.category)) && fold(item.dataset.title).includes(query);
            item.hidden = !show;
            if (show) visible++;
        });
        const ordered = state.sort === 'featured'
            ? items
            : [...items].sort((a, b) => (state.sort === 'za' ? -1 : 1) * a.dataset.title.localeCompare(b.dataset.title, 'vi'));
        ordered.forEach(item => list.append(item));
        root.querySelectorAll('[data-catalog-filter]').forEach(button => {
            const active = button.dataset.catalogFilter === state.category;
            button.classList.toggle('active', active);
            button.setAttribute('aria-pressed', String(active));
        });
        if (empty) empty.hidden = visible > 0;
    }

    root.addEventListener('click', event => {
        const button = event.target.closest('[data-catalog-filter]');
        if (!button) return;
        state.category = button.dataset.catalogFilter;
        render();
    });
    root.querySelector('[data-catalog-search]')?.addEventListener('input', event => { state.query = event.target.value; render(); });
    root.querySelector('[data-catalog-sort]')?.addEventListener('change', event => { state.sort = event.target.value; render(); });

    const initial = new URLSearchParams(location.search).get('loai');
    if (initial && root.querySelector(`[data-catalog-filter="${CSS.escape(initial)}"]`)) state.category = initial;
    render();
}

export function initCatalogs() {
    document.querySelectorAll('[data-catalog]').forEach(initCatalog);
}
