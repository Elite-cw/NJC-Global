(() => {
  const menuButton = document.querySelector('.menu');
  const navigation = document.querySelector('#nav');

  if (menuButton && navigation && !document.body.classList.contains('njc-legacy-page')) {
    menuButton.addEventListener('click', () => {
      const isOpen = menuButton.getAttribute('aria-expanded') === 'true';
      menuButton.setAttribute('aria-expanded', String(!isOpen));
      navigation.classList.toggle('open', !isOpen);
      const icon = menuButton.querySelector('.material-symbols-outlined');
      if (icon) icon.textContent = isOpen ? 'menu' : 'close';
    });
  }

  // Stop legacy demo handlers from intercepting forms that now submit to WordPress.
  document.addEventListener('submit', (event) => {
    if (event.target.matches('[data-njc-server-form]')) {
      event.stopPropagation();
    }
  }, true);

  const search = document.querySelector('[data-njc-content-search]');
  const filters = document.querySelectorAll('[data-njc-content-filter]');
  const cards = document.querySelectorAll('[data-njc-content-card]');

  const filterContent = () => {
    const query = search ? search.value.trim().toLowerCase() : '';
    const active = document.querySelector('[data-njc-content-filter].active');
    const category = active ? active.dataset.njcContentFilter : 'all';
    let visible = 0;

    cards.forEach((card) => {
      const matchesCategory = category === 'all' || card.dataset.category === category;
      const matchesQuery = !query || card.dataset.search.includes(query);
      card.hidden = !(matchesCategory && matchesQuery);
      if (!card.hidden) visible += 1;
    });

    const empty = document.querySelector('[data-njc-content-empty]');
    if (empty) empty.hidden = visible !== 0;
  };

  filters.forEach((button) => {
    button.addEventListener('click', () => {
      filters.forEach((item) => item.classList.remove('active'));
      button.classList.add('active');
      filterContent();
    });
  });

  if (search) search.addEventListener('input', filterContent);
})();
