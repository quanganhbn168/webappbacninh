// Lấy ảnh cover hàng loạt từ nhiều link video.
import { ToolKit } from '../site/toolkit';

const root = document.getElementById('bulk-tool');
const $ = selector => root.querySelector(selector);
const list = $('[data-list]');
const cardTemplate = $('[data-card]');
let items = [];

const showError = message => { $('[data-error]').textContent = message; $('[data-error]').hidden = !message; };

function sortItems() {
    const sort = $('#bulk-sort').value;
    const compare = {
        provider: (a, b) => a.provider.localeCompare(b.provider),
        title_asc: (a, b) => a.title.localeCompare(b.title, 'vi'),
        title_desc: (a, b) => b.title.localeCompare(a.title, 'vi'),
    }[sort];
    if (compare) items.sort(compare);
}

function render() {
    list.replaceChildren(...items.map((item, index) => {
        const card = cardTemplate.content.cloneNode(true);
        card.querySelector('[data-thumb]').src = item.thumbnail_url;
        const badge = card.querySelector('[data-provider]');
        badge.textContent = item.provider.toUpperCase();
        badge.classList.add(item.provider === 'tiktok' ? 'text-bg-dark' : 'text-bg-danger');
        const select = card.querySelector('[data-select]');
        select.checked = item.selected;
        select.addEventListener('change', () => { item.selected = select.checked; updateCounts(); });
        const title = card.querySelector('[data-title]');
        title.value = item.title;
        title.addEventListener('input', () => { item.title = title.value; });
        card.querySelector('[data-original]').href = item.original_url;
        card.querySelectorAll('[data-move]').forEach(button => button.addEventListener('click', () => {
            const target = index + Number(button.dataset.move);
            if (target < 0 || target >= items.length) return;
            [items[index], items[target]] = [items[target], items[index]];
            render();
        }));
        return card;
    }));
    $('[data-results]').hidden = items.length === 0;
    updateCounts();
}

function updateCounts() {
    const selected = items.filter(item => item.selected).length;
    $('[data-selected-count]').textContent = selected;
    $('[data-total]').textContent = items.length;
    $('#select-all').checked = selected === items.length && items.length > 0;
}

async function parse() {
    showError('');
    const urls = $('#bulk-urls').value.split('\n').map(url => url.trim()).filter(Boolean);
    if (urls.length === 0) { showError('Vui lòng dán ít nhất 1 đường dẫn.'); return; }

    const button = $('[data-parse]');
    button.disabled = true; $('[data-idle]').hidden = true; $('[data-busy]').hidden = false;
    const skipped = [];
    for (const url of urls) {
        if (items.some(item => item.original_url === url)) continue;
        try {
            const data = await ToolKit.postJson(root.dataset.url, { url });
            let title = data.title || '';
            const tiktok = data.provider === 'tiktok' && url.match(/tiktok\.com\/@([^/]+)\/video\/(\d+)/i);
            if (tiktok) title = `${tiktok[1]}-${tiktok[2]}`;
            items.push({ provider: data.provider, thumbnail_url: data.thumbnail_url, original_url: url, title, selected: true });
        } catch {
            skipped.push(url);
        }
    }
    button.disabled = false; $('[data-idle]').hidden = false; $('[data-busy]').hidden = true;
    $('[data-skipped]').textContent = skipped.length ? `Bỏ qua ${skipped.length} link không đọc được.` : '';
    $('[data-skipped]').hidden = skipped.length === 0;
    sortItems();
    render();
}

function download(onlySelected) {
    const chosen = onlySelected ? items.filter(item => item.selected) : items;
    if (chosen.length === 0) { showError('Vui lòng chọn ít nhất một mục để lưu.'); return; }
    const payload = document.getElementById('bulkPayload');
    payload.replaceChildren();
    chosen.forEach((item, index) => {
        for (const [key, value] of Object.entries({ image_url: item.thumbnail_url, filename: item.title, provider: item.provider })) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `items[${index}][${key}]`;
            input.value = value;
            payload.appendChild(input);
        }
    });
    document.getElementById('bulkForm').submit();
}

$('[data-parse]').addEventListener('click', parse);
$('[data-clear]').addEventListener('click', () => { items = []; $('#bulk-urls').value = ''; showError(''); render(); });
$('#bulk-sort').addEventListener('change', () => { sortItems(); render(); });
$('#select-all').addEventListener('change', event => { items.forEach(item => { item.selected = event.target.checked; }); render(); });
root.querySelectorAll('[data-download]').forEach(button => button.addEventListener('click', () => download(button.dataset.download === 'selected')));
