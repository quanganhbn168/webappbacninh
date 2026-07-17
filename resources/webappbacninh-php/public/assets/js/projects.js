(() => {
  'use strict';

  const grid = document.getElementById('projectsGrid');
  if (!grid) return;

  const cards = [...grid.querySelectorAll('[data-project-card]')];
  const search = document.getElementById('projectSearch');
  const sort = document.getElementById('projectSort');
  const count = document.getElementById('projectCount');
  const empty = document.getElementById('projectsEmpty');
  const reset = document.getElementById('projectReset');
  let category = 'all';

  const normalize = value => String(value || '')
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .toLowerCase();

  function render() {
    const keyword = normalize(search?.value);
    const visible = cards.filter(card => {
      const categoryOk = category === 'all' || card.dataset.category === category;
      const searchOk = !keyword || normalize(card.dataset.search).includes(keyword);
      return categoryOk && searchOk;
    });

    const compare = {
      newest: (a, b) => Number(b.dataset.year) - Number(a.dataset.year),
      name: (a, b) => a.dataset.name.localeCompare(b.dataset.name, 'vi'),
      featured: (a, b) => Number(b.dataset.featured) - Number(a.dataset.featured),
    }[sort?.value || 'featured'];

    visible.sort(compare).forEach(card => grid.appendChild(card));
    cards.forEach(card => card.classList.toggle('d-none', !visible.includes(card)));
    if (count) count.textContent = String(visible.length);
    empty?.classList.toggle('d-none', visible.length > 0);
    grid.classList.toggle('d-none', visible.length === 0);
    window.AOS?.refreshHard();
  }

  document.querySelectorAll('[data-project-filter]').forEach(button => {
    button.addEventListener('click', () => {
      category = button.dataset.projectFilter || 'all';
      document.querySelectorAll('[data-project-filter]').forEach(item => item.classList.toggle('is-active', item === button));
      render();
    });
  });

  search?.addEventListener('input', render);
  sort?.addEventListener('change', render);
  reset?.addEventListener('click', () => {
    category = 'all';
    if (search) search.value = '';
    if (sort) sort.value = 'featured';
    document.querySelectorAll('[data-project-filter]').forEach(item => item.classList.toggle('is-active', item.dataset.projectFilter === 'all'));
    render();
  });

  document.getElementById('projectForm')?.addEventListener('submit', event => {
    event.preventDefault();
    const form = event.currentTarget;
    if (!form.checkValidity()) {
      form.classList.add('was-validated');
      return;
    }
    form.classList.add('was-validated');
    document.getElementById('projectFormSuccess')?.classList.remove('d-none');
  });

  render();
})();
