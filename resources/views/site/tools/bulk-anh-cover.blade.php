@extends('layouts.tool')

@section('tool-content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4" id="bulk-tool" data-url="{{ route('cover.getInfo') }}">
        <div class="card-body p-4 p-md-5">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif

            <div class="text-center mb-5">
                <h1 class="h2 fw-bold mb-2">Lấy Ảnh Cover Video — Hàng loạt</h1>
                <p class="text-secondary">Dán nhiều link YouTube/TikTok, lấy thumbnail, sửa tiêu đề và tải tất cả trong một nốt nhạc.</p>
                <a href="{{ route('cover.page') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4 mt-2">
                    <x-icon name="arrow-left" class="me-1" /> Quay lại chế độ tải đơn lẻ
                </a>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold small text-uppercase text-secondary" for="bulk-urls">Dán danh sách link (Mỗi link 1 dòng)</label>
                <textarea id="bulk-urls" class="form-control form-control-lg bg-light" rows="8" placeholder="https://www.tiktok.com/@user/video/123...&#10;https://www.youtube.com/watch?v=abc..."></textarea>

                <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
                    <button type="button" class="btn btn-primary" data-parse>
                        <span data-idle><x-icon name="search" class="me-1" /> Phân tích danh sách</span>
                        <span data-busy hidden><span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span> Đang xử lý...</span>
                    </button>
                    <button type="button" class="btn btn-light border" data-clear>Xoá tất cả</button>
                    <div class="ms-auto d-flex align-items-center gap-2">
                        <label class="small text-secondary mb-0" for="bulk-sort">Sắp xếp:</label>
                        <select id="bulk-sort" class="form-select form-select-sm w-auto">
                            <option value="none">Mặc định</option>
                            <option value="title_asc">Tiêu đề A→Z</option>
                            <option value="title_desc">Tiêu đề Z→A</option>
                            <option value="provider">Theo nền tảng</option>
                        </select>
                    </div>
                </div>

                <div class="alert alert-danger mt-3" data-error hidden role="alert"></div>
                <div class="alert alert-warning mt-3" data-skipped hidden role="status"></div>
            </div>

            <div class="mt-5" data-results hidden>
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="select-all" checked>
                        <label class="form-check-label user-select-none" for="select-all">Chọn tất cả (<span data-selected-count>0</span>/<span data-total>0</span>)</label>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" data-download="selected"><x-icon name="file-archive" class="me-1" /> Tải ZIP (Đã chọn)</button>
                        <button type="button" class="btn btn-outline-success" data-download="all"><x-icon name="download" class="me-1" /> Tải ZIP (Tất cả)</button>
                    </div>
                </div>
                <div class="row g-3" data-list></div>
            </div>

            <template data-card>
                <div class="col-lg-6">
                    <div class="card h-100 border shadow-sm">
                        <div class="card-body p-2">
                            <div class="d-flex gap-3">
                                <div class="flex-shrink-0" style="width: 120px"><img class="img-fluid rounded border" loading="lazy" alt="" data-thumb></div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge" data-provider></span>
                                        <input class="form-check-input" type="checkbox" aria-label="Chọn video" data-select>
                                    </div>
                                    <input type="text" class="form-control form-control-sm mb-2" placeholder="Nhập tên file..." aria-label="Tên file" data-title>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-secondary py-0" title="Lên" data-move="-1">↑</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary py-0" title="Xuống" data-move="1">↓</button>
                                        <a target="_blank" rel="noopener" class="ms-auto small text-decoration-none" data-original>Xem gốc <x-icon name="external-link" class="small" /></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <form id="bulkForm" class="d-none" method="POST" action="{{ route('cover.download.bulk') }}">
                @csrf
                <div id="bulkPayload"></div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
    const { ToolKit } = window;
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
</script>
@endpush
