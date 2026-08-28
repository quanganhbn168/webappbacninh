(() => {
  'use strict';

  const cards = [...document.querySelectorAll('[data-theme-card]')];
  if (!cards.length) return;

  const pageSize = 6;
  const state = { search:'', types:[], industry:'all', price:'all', features:[], sort:'featured', page:1, quick:'all' };
  const typeLabels = {'doanh-nghiep':'Website doanh nghiệp','ban-hang':'Website bán hàng','landing-page':'Landing page','dich-vu':'Website dịch vụ'};
  const featureLabels = {multilang:'Đa ngôn ngữ',ecommerce:'Giỏ hàng',booking:'Booking',lead:'Form lead'};
  const industryLabels = {'san-xuat':'Sản xuất','thuong-mai':'Thương mại','du-lich':'Du lịch','giao-duc':'Giáo dục','noi-that':'Nội thất - xây dựng','nha-hang':'Nhà hàng - ẩm thực','spa':'Spa - làm đẹp','ky-thuat':'Thiết bị - kỹ thuật'};
  const themeList = document.getElementById('themeList');
  const resultCount = document.getElementById('resultCount');
  const activeFilterText = document.getElementById('activeFilterText');
  const activeFilters = document.getElementById('activeFilters');
  const empty = document.getElementById('themeEmpty');
  const pagination = document.getElementById('themePagination');
  const sortSelect = document.getElementById('themeSort');
  const modalEl = document.getElementById('themeQuickView');
  const modalBody = document.getElementById('themeModalBody');
  const quickViewModal = modalEl ? {
    show: () => window.tailwindUi?.open(modalEl),
    hide: () => window.tailwindUi?.close(modalEl),
  } : null;
  const themes = JSON.parse(document.getElementById('themesJson')?.textContent || '[]');
  const themeMap = new Map(themes.map(theme => [String(theme.id), theme]));

  const normalize = value => String(value || '').normalize('NFD').replace(/[\u0300-\u036f]/g,'').toLowerCase();
  const money = value => new Intl.NumberFormat('vi-VN').format(Number(value)) + 'đ';

  function readControls(scope = document) {
    const search = scope.querySelector('[data-filter-search]');
    if (search) state.search = search.value.trim();
    state.types = [...scope.querySelectorAll('[data-filter-type]:checked')].map(el => el.value);
    const industry = scope.querySelector('[data-filter-industry]');
    if (industry) state.industry = industry.value;
    const price = scope.querySelector('[data-filter-price]:checked');
    if (price) state.price = price.value;
    state.features = [...scope.querySelectorAll('[data-filter-feature]:checked')].map(el => el.value);
    state.page = 1;
    syncControls(scope);
    render();
  }

  function syncControls(origin) {
    document.querySelectorAll('[data-filter-search]').forEach(el => { if (!origin || !origin.contains(el)) el.value = state.search; });
    document.querySelectorAll('[data-filter-type]').forEach(el => { el.checked = state.types.includes(el.value); });
    document.querySelectorAll('[data-filter-industry]').forEach(el => { el.value = state.industry; });
    document.querySelectorAll('[data-filter-price]').forEach(el => { el.checked = el.value === state.price; });
    document.querySelectorAll('[data-filter-feature]').forEach(el => { el.checked = state.features.includes(el.value); });
    document.querySelectorAll('[data-quick-category]').forEach(el => el.classList.toggle('is-active', el.dataset.quickCategory === state.quick));
  }

  function matches(card) {
    const searchOk = !state.search || normalize(card.dataset.search).includes(normalize(state.search));
    const typeOk = !state.types.length || state.types.includes(card.dataset.type);
    const industryOk = state.industry === 'all' || card.dataset.industry === state.industry;
    const cardFeatures = (card.dataset.features || '').split(',').filter(Boolean);
    const featureOk = !state.features.length || state.features.every(feature => cardFeatures.includes(feature));
    const price = Number(card.dataset.price);
    const priceOk = state.price === 'all' || (state.price === 'under10' && price < 10000000) || (state.price === '10to20' && price >= 10000000 && price <= 20000000) || (state.price === 'over20' && price > 20000000);
    const quickOk = state.quick === 'all' || card.dataset.type === state.quick || card.dataset.industry === state.quick;
    return searchOk && typeOk && industryOk && featureOk && priceOk && quickOk;
  }

  function sortCards(items) {
    return items.sort((a,b) => {
      switch(state.sort){
        case 'newest': return Number(b.dataset.year) - Number(a.dataset.year) || Number(b.dataset.themeId) - Number(a.dataset.themeId);
        case 'price-asc': return Number(a.dataset.price) - Number(b.dataset.price);
        case 'price-desc': return Number(b.dataset.price) - Number(a.dataset.price);
        case 'name-asc': return a.dataset.name.localeCompare(b.dataset.name,'vi');
        default: return Number(b.dataset.featured) - Number(a.dataset.featured);
      }
    });
  }

  function renderSummary() {
    const labels = [];
    if (state.search) labels.push(`Từ khóa: ${state.search}`);
    state.types.forEach(v => labels.push(typeLabels[v]));
    if (state.industry !== 'all') labels.push(industryLabels[state.industry]);
    if (state.price !== 'all') labels.push({under10:'Dưới 10 triệu','10to20':'10 - 20 triệu',over20:'Trên 20 triệu'}[state.price]);
    state.features.forEach(v => labels.push(featureLabels[v]));
    if (state.quick !== 'all') labels.push(`Danh mục nhanh: ${typeLabels[state.quick] || industryLabels[state.quick] || state.quick}`);
    activeFilterText.textContent = labels.length ? `· ${labels.length} bộ lọc đang áp dụng` : '';
    activeFilters.classList.toggle('d-none', !labels.length);
    activeFilters.innerHTML = labels.map(label => `<span class="active-filter">${label}<i class="fa-solid fa-xmark"></i></span>`).join('') + (labels.length ? '<button class="active-filter" type="button" data-reset-filters>Xóa tất cả</button>' : '');
  }

  function renderPagination(totalPages) {
    if (totalPages <= 1) { pagination.innerHTML = ''; return; }
    let html = `<li class="page-item ${state.page===1?'disabled':''}"><button class="page-link" data-page="${state.page-1}"><i class="fa-solid fa-angle-left"></i></button></li>`;
    for(let i=1;i<=totalPages;i++) html += `<li class="page-item ${state.page===i?'active':''}"><button class="page-link" data-page="${i}">${i}</button></li>`;
    html += `<li class="page-item ${state.page===totalPages?'disabled':''}"><button class="page-link" data-page="${state.page+1}"><i class="fa-solid fa-angle-right"></i></button></li>`;
    pagination.innerHTML = html;
  }

  function render() {
    const filtered = sortCards(cards.filter(matches));
    filtered.forEach(card => themeList.appendChild(card));
    const totalPages = Math.max(1, Math.ceil(filtered.length / pageSize));
    if (state.page > totalPages) state.page = totalPages;
    const start = (state.page - 1) * pageSize;
    cards.forEach(card => card.classList.add('d-none'));
    filtered.slice(start, start + pageSize).forEach(card => card.classList.remove('d-none'));
    resultCount.textContent = filtered.length;
    empty.classList.toggle('d-none', filtered.length > 0);
    themeList.classList.toggle('d-none', filtered.length === 0);
    renderPagination(totalPages);
    renderSummary();
    if (window.AOS) AOS.refreshHard();
  }

  function openQuickView(id) {
    const theme = themeMap.get(String(id));
    if (!theme || !quickViewModal) return;
    modalBody.innerHTML = `<div class="theme-modal__layout"><div class="theme-modal__image"><img src="${theme.imageUrl}" alt="${theme.name}"></div><div class="theme-modal__content"><span>${theme.code} · ${theme.industryLabel}</span><h2>${theme.name}</h2><p>${theme.description}</p><div class="theme-modal__specs"><div><small>Loại website</small><strong>${theme.typeLabel}</strong></div><div><small>Chi phí tham khảo</small><strong>Từ ${money(theme.price)}</strong></div><div><small>Thời gian dự kiến</small><strong>${theme.duration}</strong></div><div><small>Tùy chỉnh</small><strong>Màu sắc, nội dung, chức năng</strong></div></div><ul class="theme-modal__features">${theme.tags.map(tag => `<li>${tag}</li>`).join('')}<li>Tối ưu hiển thị mobile</li><li>Trang quản trị nội dung</li><li>SEO nền tảng và form liên hệ</li></ul><div class="theme-modal__buttons"><a href="${theme.detailUrl}" class="btn btn-primary">Xem trang chi tiết</a><a href="#themeContact" class="btn btn-outline-primary" data-theme-select="${theme.code}" data-ui-dismiss="modal">Chọn mẫu ${theme.code}</a></div></div></div>`;
    quickViewModal.show();
  }

  document.addEventListener('input', event => { if(event.target.matches('[data-filter-search]')) readControls(event.target.closest('#desktopFilters,#mobileFilters') || document); });
  document.addEventListener('change', event => {
    if(event.target.matches('[data-filter-type],[data-filter-industry],[data-filter-price],[data-filter-feature]')) readControls(event.target.closest('#desktopFilters,#mobileFilters') || document);
    if(event.target === sortSelect){ state.sort = sortSelect.value; state.page=1; render(); }
  });
  document.addEventListener('click', event => {
    const reset = event.target.closest('[data-reset-filters]');
    if(reset){ state.search='';state.types=[];state.industry='all';state.price='all';state.features=[];state.quick='all';state.page=1;syncControls();render();return; }
    const quick = event.target.closest('[data-quick-category]');
    if(quick){ state.quick=quick.dataset.quickCategory;state.page=1;syncControls();render();document.getElementById('themeGrid').scrollIntoView({behavior:'smooth'});return; }
    const page = event.target.closest('[data-page]');
    if(page && !page.closest('.disabled')){ state.page=Number(page.dataset.page);render();document.getElementById('themeGrid').scrollIntoView({behavior:'smooth'});return; }
    const view = event.target.closest('[data-theme-quick]');
    if(view){ openQuickView(view.dataset.themeQuick);return; }
    const select = event.target.closest('[data-theme-select]');
    if(select){ const input=document.getElementById('themeCode'); if(input) input.value=select.dataset.themeSelect; }
  });

  document.getElementById('themeForm')?.addEventListener('submit', event => {
    event.preventDefault();
    const form = event.currentTarget;
    if(!form.checkValidity()){ form.classList.add('was-validated'); return; }
    document.getElementById('themeFormSuccess')?.classList.remove('d-none');
  });

  render();
})();
