(() => {
  const formUrl = 'https://form.penlly.com/f/receive-updates-from-us';
  document.querySelectorAll('footer form, #community-form, .community-form, #blog-newsletter-form, .blog-newsletter form').forEach((form) => {
    const panel = document.createElement('div');
    panel.className = `${form.className} newsletter-signup`;
    form.querySelectorAll(':scope > h3, :scope > p').forEach((node) => panel.appendChild(node.cloneNode(true)));
    const link = document.createElement('a');
    link.className = `button newsletter-open${form.closest('.blog-newsletter') ? ' light' : ''}`;
    link.href = formUrl;
    link.target = '_blank';
    link.rel = 'noopener noreferrer';
    link.setAttribute('aria-label', 'Subscribe now (opens in a new tab)');
    link.innerHTML = '<span>Subscribe now</span><span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>';
    if (form.closest('footer') && document.querySelector('.blog-newsletter')) {
      link.className = 'newsletter-footer-link';
      link.setAttribute('aria-label', 'Join the newsletter (opens in a new tab)');
      link.innerHTML = '<span>Join the newsletter</span><span class="material-symbols-outlined" aria-hidden="true">arrow_outward</span>';
    }
    panel.appendChild(link);
    form.querySelectorAll(':scope > small').forEach((node) => panel.appendChild(node.cloneNode(true)));
    form.replaceWith(panel);
  });
})();
