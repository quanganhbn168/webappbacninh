(() => {
  'use strict';
  const cards = [...document.querySelectorAll('.knowledge-card')];
  const search = document.getElementById('articleSearch');
  const count = document.getElementById('articleCount');
  const list = document.getElementById('articleList');
  const empty = document.getElementById('articleEmpty');
  const sort = document.getElementById('articleSort');
  let category = 'all';
  const normalize = (value) => String(value || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
  function render() {
    const term = normalize(search?.value);
    const visible = cards.filter(card => (category === 'all' || card.dataset.category === category) && (!term || normalize(card.dataset.search).includes(term)));
    const mode = sort?.value || 'featured';
    visible.sort((a,b) => mode === 'title' ? a.dataset.title.localeCompare(b.dataset.title,'vi') : Number(b.dataset.featured)-Number(a.dataset.featured));
    cards.forEach(card => card.classList.add('d-none'));
    visible.forEach(card => { card.classList.remove('d-none'); list?.appendChild(card); });
    if (count) count.textContent = String(visible.length);
    empty?.classList.toggle('d-none', visible.length > 0);
  }
  document.querySelectorAll('[data-article-category]').forEach(btn => btn.addEventListener('click', () => {
    category = btn.dataset.articleCategory || 'all';
    document.querySelectorAll('[data-article-category]').forEach(item => item.classList.toggle('is-active', item === btn));
    render();
  }));
  search?.addEventListener('input', render);
  sort?.addEventListener('change', render);
  render();
})();