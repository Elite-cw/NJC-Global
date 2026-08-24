(() => {
  'use strict';

  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const loader = document.createElement('div');

  loader.className = 'page-loader';
  loader.setAttribute('role', 'status');
  loader.setAttribute('aria-live', 'polite');
  loader.setAttribute('aria-hidden', 'true');
  loader.innerHTML = `
    <div class="page-loader-panel">
      <span class="page-loader-mark" aria-hidden="true"></span>
      <span class="page-loader-label">Preparing your experience</span>
      <span class="page-loader-progress" aria-hidden="true"><i></i></span>
    </div>
  `;

  document.body.appendChild(loader);

  let revealTimer;
  let safetyTimer;
  let navigating = false;

  function showLoader(immediate = false) {
    window.clearTimeout(revealTimer);
    window.clearTimeout(safetyTimer);

    const reveal = () => {
      loader.classList.add('is-visible');
      loader.setAttribute('aria-hidden', 'false');
    };

    if (immediate || reducedMotion) {
      reveal();
    } else {
      revealTimer = window.setTimeout(reveal, 180);
    }

    safetyTimer = window.setTimeout(hideLoader, 5000);
  }

  function hideLoader() {
    window.clearTimeout(revealTimer);
    window.clearTimeout(safetyTimer);
    loader.classList.remove('is-visible');
    loader.setAttribute('aria-hidden', 'true');
    navigating = false;
  }

  function isEligibleNavigation(event, link) {
    if (
      event.defaultPrevented ||
      event.button !== 0 ||
      event.metaKey ||
      event.ctrlKey ||
      event.shiftKey ||
      event.altKey ||
      link.hasAttribute('download') ||
      link.target === '_blank'
    ) {
      return false;
    }

    const rawHref = link.getAttribute('href');

    if (!rawHref || rawHref.startsWith('#') || /^(mailto:|tel:|javascript:)/i.test(rawHref)) {
      return false;
    }

    const destination = new URL(link.href, window.location.href);
    const current = new URL(window.location.href);
    const sameDocument =
      destination.origin === current.origin &&
      destination.pathname === current.pathname &&
      destination.search === current.search;

    return destination.origin === current.origin && !sameDocument;
  }

  document.addEventListener('click', (event) => {
    const link = event.target.closest('a[href]');

    if (!link || navigating || !isEligibleNavigation(event, link)) {
      return;
    }

    event.preventDefault();
    navigating = true;
    showLoader(true);

    window.setTimeout(() => {
      window.location.assign(link.href);
    }, reducedMotion ? 0 : 240);
  });

  document.querySelectorAll('img').forEach((image) => {
    image.decoding = 'async';

    if (!image.hasAttribute('loading')) {
      image.loading = image.getBoundingClientRect().top > window.innerHeight ? 'lazy' : 'eager';
    }

    const finishImage = () => {
      image.classList.remove('resource-loading');
      image.classList.add('resource-loaded');
    };

    if (image.complete) {
      finishImage();
      return;
    }

    image.classList.add('resource-loading');
    image.addEventListener('load', finishImage, { once: true });
    image.addEventListener('error', finishImage, { once: true });
  });

  if (document.readyState === 'complete') {
    hideLoader();
  } else {
    showLoader(false);
    window.addEventListener('load', hideLoader, { once: true });
  }

  window.addEventListener('pageshow', hideLoader);
  window.addEventListener('pagehide', () => window.clearTimeout(revealTimer));
})();
