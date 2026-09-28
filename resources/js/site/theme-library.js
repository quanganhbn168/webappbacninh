// Theme showcase (/kho-giao-dien): filters, sort and pagination over server-rendered cards.
// Cards carry data-type, data-industry, data-features, data-price, data-year, data-featured, data-search.
const fold = value => String(value ?? '').normalize('NFD').replace(/[̀-ͯ]/g, '').replace(/đ/gi, 'd').toLowerCase();
const PAGE_SIZE = 9;
const PRICE = {
    all: () => true,
    under10: price => price < 10_000_000,
    '10to20': price => price >= 10_000_000 && price <= 20_000_000,
    over20: price => price > 20_000_000,
};

export function initThemeLibrary() {
    const root = document.querySelector('[data-theme-library]');
    if (!root) return;

    const form = root.querySelector('[data-theme-filters]');
    const list = root.querySelector('[data-theme-list]');
    const cards = [...list.querySelectorAll('[data-theme-card]')];
    // Cards sit in grid columns; hide and reorder the column, not just the card.
    const cell = card => card.closest('[data-theme-card-wrapper]') ?? card;
    const count = root.querySelector('[data-theme-count]');
    const empty = root.querySelector('[data-theme-empty]');
    const pager = root.querySelector('[data-theme-pager]');
    const chips = root.querySelector('[data-theme-chips]');
    let page = 1;
    let quick = 'all';

    const values = name => [...form.querySelectorAll(`[name="${name}"]:checked`)].map(input => input.value);

    function state() {
        return {
            search: fold(form.elements.search.value.trim()),
            types: values('type'),
            industry: form.elements.industry.value,
            price: form.querySelector('[name="price"]:checked')?.value ?? 'all',
            features: values('feature'),
            sort: root.querySelector('[data-theme-sort]').value,
        };
    }

    function matches(card, s) {
        const features = (card.dataset.features ?? '').split(',').filter(Boolean);
        return (!s.search || fold(card.dataset.search).includes(s.search))
            && (!s.types.length || s.types.includes(card.dataset.type))
            && (s.industry === 'all' || card.dataset.industry === s.industry)
            && s.features.every(feature => features.includes(feature))
            && (PRICE[s.price] ?? PRICE.all)(Number(card.dataset.price))
            && (quick === 'all' || card.dataset.type === quick || card.dataset.industry === quick);
    }

    const sorters = {
        featured: (a, b) => Number(b.dataset.featured) - Number(a.dataset.featured),
        newest: (a, b) => Number(b.dataset.year) - Number(a.dataset.year),
        'price-asc': (a, b) => Number(a.dataset.price) - Number(b.dataset.price),
        'price-desc': (a, b) => Number(b.dataset.price) - Number(a.dataset.price),
        'name-asc': (a, b) => a.dataset.name.localeCompare(b.dataset.name, 'vi'),
    };

    function renderChips(s) {
        const labels = [];
        const label = input => input.closest('.form-check')?.querySelector('label')?.textContent.trim() ?? input.value;
        form.querySelectorAll('[name="type"]:checked, [name="feature"]:checked').forEach(input => labels.push(label(input)));
        if (s.industry !== 'all') labels.push(form.elements.industry.selectedOptions[0].textContent.trim());
        if (s.price !== 'all') labels.push(label(form.querySelector('[name="price"]:checked')));
        if (s.search) labels.push(`“${form.elements.search.value.trim()}”`);
        chips.replaceChildren(...labels.map(text => Object.assign(document.createElement('span'), { className: 'tag', textContent: text })));
        if (labels.length) {
            const reset = Object.assign(document.createElement('button'), { type: 'button', className: 'btn btn-link btn-sm p-0', textContent: 'Xóa bộ lọc' });
            reset.dataset.themeReset = '';
            chips.append(reset);
        }
        chips.hidden = !labels.length;
    }

    function renderPager(pages) {
        pager.replaceChildren();
        if (pages <= 1) return;
        for (let i = 1; i <= pages; i++) {
            const item = document.createElement('li');
            item.className = `page-item${i === page ? ' active' : ''}`;
            const button = Object.assign(document.createElement('button'), { type: 'button', className: 'page-link', textContent: String(i) });
            button.dataset.page = String(i);
            if (i === page) button.setAttribute('aria-current', 'page');
            item.append(button);
            pager.append(item);
        }
    }

    function render() {
        const s = state();
        const filtered = cards.filter(card => matches(card, s)).sort(sorters[s.sort] ?? sorters.featured);
        const pages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
        page = Math.min(page, pages);
        const shown = new Set(filtered.slice((page - 1) * PAGE_SIZE, page * PAGE_SIZE));
        filtered.forEach(card => list.append(cell(card)));
        cards.forEach(card => { cell(card).hidden = !shown.has(card); });
        count.textContent = String(filtered.length);
        empty.hidden = filtered.length > 0;
        root.querySelectorAll('[data-quick]').forEach(button => button.classList.toggle('active', button.dataset.quick === quick));
        renderChips(s);
        renderPager(pages);
    }

    const scrollToList = () => list.scrollIntoView({ behavior: 'smooth', block: 'start' });

    form.addEventListener('input', () => { page = 1; render(); });
    form.addEventListener('submit', event => event.preventDefault());
    root.querySelector('[data-theme-sort]').addEventListener('change', () => { page = 1; render(); });
    root.addEventListener('click', event => {
        const target = event.target.closest('[data-page], [data-quick], [data-theme-reset]');
        if (!target) return;
        if (target.dataset.page) page = Number(target.dataset.page);
        if (target.dataset.quick) { quick = target.dataset.quick; page = 1; }
        if (target.hasAttribute('data-theme-reset')) { form.reset(); quick = 'all'; page = 1; }
        render();
        scrollToList();
    });

    render();
}
