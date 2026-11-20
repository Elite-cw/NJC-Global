(() => {
  'use strict';

  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const isViewPage = window.location.pathname.includes('/view/');
  const themeRuntime = window.njcTheme || {};
  const pagePrefix = isViewPage ? '' : 'view/';
  const assetPrefix = themeRuntime.assetUrl || (isViewPage ? '../' : '');
  const aboutUrl = themeRuntime.aboutUrl || `${pagePrefix}about-us.html`;
  const privacyUrl = themeRuntime.privacyUrl || `${pagePrefix}privacy.html`;
  const termsUrl = themeRuntime.termsUrl || `${pagePrefix}terms.html`;

  const defaultPeopleDirectory = {
    team: [
      {
        id: 'team-joyce-chiamaka-nwezeh',
        name: 'Joyce Chiamaka Nwezeh',
        role: 'Founder & Managing Partner',
        image: 'assets/images/team/joycepic1.jpg',
        imageAlt: 'Portrait of Joyce Chiamaka Nwezeh',
        width: 4016,
        height: 5572
      },
      {
        id: 'team-daniel-friday',
        name: 'Daniel Ugochi',
        role: 'Team',
        image: 'assets/images/team/daniel-friday.jpg',
        imageAlt: 'Profile placeholder for Daniel Ugochi',
        width: 1254,
        height: 1254
      },
      {
        id: 'team-daniel-nwezeh',
        name: 'Sullivan Agbata',
        role: 'Team',
        image: 'assets/images/team/sullivan-agbata.jpg',
        imageAlt: 'Portrait of Sullivan Agbata',
        width: 1254,
        height: 1254
      }
    ],
    partners: [
      { id: 'partner-jimi-obawole', name: 'Jimi Obawole', role: 'Founder, Nia Nexus Africa', location: 'US' },
      { id: 'partner-winifred-gaillard', name: 'Winifred Gaillard', role: 'Founder, The AfrikaFora', location: 'France', image: 'assets/images/partners/winifred-uloaku-gaillard.jpg', imageAlt: 'Portrait of Winifred Gaillard', width: 200, height: 200 },
      { id: 'partner-angeline-mhlanga', name: 'Angeline Mhlanga', role: 'Country Director, CBW Africa, Zimbabwe Chapter', location: 'South Africa', image: 'assets/images/partners/angeline-mhlanga.jpg', imageAlt: 'Portrait of Angeline Mhlanga', width: 1070, height: 1337 }
    ]
  };
  const peopleDirectory = Object.freeze(themeRuntime.peopleDirectory || defaultPeopleDirectory);

  function renderPortrait(person, className) {
    if (person.image) {
      const imagePath = /^(?:https?:|data:|blob:)/i.test(person.image)
        ? person.image
        : themeRuntime.assetUrl
          ? `${assetPrefix}${person.image.replace(/^assets\//, '')}`
          : `${assetPrefix}${person.image}`;
      return `
        <span class="${className} has-image">
          <img src="${imagePath}" alt="${person.imageAlt}" width="${person.width}" height="${person.height}" loading="lazy">
        </span>
      `;
    }

    return `
      <span class="${className}" role="img" aria-label="Profile icon for ${person.name}">
        <span class="material-symbols-outlined" aria-hidden="true">person</span>
      </span>
    `;
  }

  const teamTree = document.querySelector('[data-team-tree]');

  if (teamTree) {
    const [leader, ...teamMembers] = peopleDirectory.team;
    const renderTreePerson = (person, isLeader = false) => `
      <a class="team-tree-person${isLeader ? ' team-tree-lead' : ''}" href="${aboutUrl}#${person.id}"${isLeader ? '' : ' role="listitem"'}>
        ${renderPortrait(person, 'team-tree-portrait')}
        <span class="team-tree-copy">
          <strong>${person.name}</strong>
          <small>${person.role}</small>
        </span>
      </a>
    `;

    teamTree.innerHTML = `
      ${renderTreePerson(leader, true)}
      <div class="team-tree-branches" role="list">
        ${teamMembers.map((person) => renderTreePerson(person)).join('')}
      </div>
    `;
  }

  document.querySelectorAll('[data-people-group]').forEach((grid) => {
    const people = peopleDirectory[grid.dataset.peopleGroup] || [];

    grid.innerHTML = people.map((person) => `
      <article class="team-member-card" id="${person.id}">
        ${person.id === 'partner-jimi-obawole' ? '' : renderPortrait(person, 'team-member-portrait')}
        <div class="team-member-copy">
          <small>${person.role}</small>
          <h3>${person.name}</h3>
          ${person.location ? `<span class="team-member-location">${person.location}</span>` : ''}
          ${grid.dataset.peopleGroup !== 'partners' && person.bio ? `<p>${person.bio}</p>` : ''}
        </div>
      </article>
    `).join('');
  });

  document.querySelectorAll('.footer-bottom a').forEach((link) => {
    const label = link.textContent.trim().toLowerCase();

    if (label.includes('privacy')) {
      link.href = privacyUrl;
    }

    if (label.includes('terms')) {
      link.href = termsUrl;
    }
  });

  if (!themeRuntime.cookieManaged && !window.localStorage.getItem('njc-cookie-choice')) {
    const cookieBanner = document.createElement('aside');

    cookieBanner.className = 'cookie-banner';
    cookieBanner.setAttribute('aria-label', 'Cookie preferences');
    cookieBanner.innerHTML = `
      <div>
        <strong>We respect your privacy</strong>
        <p>We use essential storage to keep the website working. You can also allow optional analytics that help us improve the NJC Global experience. <a href="${privacyUrl}">Read our Privacy Policy</a>.</p>
      </div>
      <div class="cookie-actions">
        <button class="cookie-secondary" type="button" data-cookie-choice="essential">Essential only</button>
        <button class="button" type="button" data-cookie-choice="all">Accept all</button>
      </div>
    `;

    document.body.appendChild(cookieBanner);

    cookieBanner.querySelectorAll('[data-cookie-choice]').forEach((button) => {
      button.addEventListener('click', () => {
        window.localStorage.setItem('njc-cookie-choice', button.dataset.cookieChoice);
        cookieBanner.remove();
      });
    });
  }

  const projectFilters = document.querySelectorAll('[data-project-filter]');
  const projectCards = document.querySelectorAll('[data-project-category]');

  projectFilters.forEach((button) => {
    button.addEventListener('click', () => {
      const selectedCategory = button.dataset.projectFilter;

      projectFilters.forEach((filter) => {
        const isActive = filter === button;
        filter.classList.toggle('active', isActive);
        filter.setAttribute('aria-pressed', String(isActive));
      });

      projectCards.forEach((card) => {
        card.hidden = selectedCategory !== 'all' && card.dataset.projectCategory !== selectedCategory;
      });
    });
  });

  document.querySelectorAll('footer form').forEach((form) => {
    form.addEventListener('submit', (event) => {
      event.preventDefault();

      const button = form.querySelector('button');
      const input = form.querySelector('input');

      if (button) {
        button.textContent = 'Joined';
      }

      if (input) {
        input.value = '';
      }
    });
  });

  const loader = document.querySelector('[data-njc-page-loader]');

  let revealTimer;
  let safetyTimer;
  let navigating = false;

  function showLoader(immediate = false) {
    if (!loader) return;

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
    if (!loader) return;

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

  const partnerCards = document.querySelectorAll('.partner-profile-card[id]');
  let partnerReturnState = null;

  partnerCards.forEach((card) => {
    const profileLink = card.querySelector('a[href$="-details"]');

    if (!profileLink) {
      return;
    }

    profileLink.addEventListener('click', () => {
      partnerReturnState = {
        cardId: card.id,
        viewportTop: card.getBoundingClientRect().top
      };
    });
  });

  document.querySelectorAll('.partner-detail-actions a[href^="#partner-"]:not([href$="-details"])').forEach((closeLink) => {
    closeLink.addEventListener('click', (event) => {
      const cardSelector = closeLink.getAttribute('href');
      const card = cardSelector ? document.querySelector(cardSelector) : null;

      if (!card) {
        return;
      }

      event.preventDefault();
      window.history.pushState(null, '', cardSelector);

      window.requestAnimationFrame(() => {
        window.requestAnimationFrame(() => {
          const header = document.querySelector('.site-header');
          const headerOffset = (header ? header.getBoundingClientRect().height : 80) + 24;
          const rememberedTop = partnerReturnState?.cardId === card.id
            ? Math.max(partnerReturnState.viewportTop, headerOffset)
            : headerOffset;
          const destination = window.scrollY + card.getBoundingClientRect().top - rememberedTop;

          window.scrollTo({
            top: Math.max(0, destination),
            behavior: reducedMotion ? 'auto' : 'smooth'
          });

          card.focus({ preventScroll: true });
        });
      });
    });
  });


  const reviewDialog = document.createElement('dialog');
  reviewDialog.id = 'review-dialog';
  reviewDialog.className = 'review-dialog';
  reviewDialog.setAttribute('aria-labelledby', 'review-dialog-title');
  reviewDialog.innerHTML = '<div class="review-dialog-header"><div><p class="eyebrow">Client review</p><h2 id="review-dialog-title"></h2><p class="review-dialog-role"></p></div><button class="review-dialog-close" type="button" autofocus aria-label="Close review"><span class="material-symbols-outlined" aria-hidden="true">close</span></button></div><blockquote class="review-dialog-text"></blockquote>';
  document.body.appendChild(reviewDialog);
  let reviewTrigger;
  reviewDialog.querySelector('.review-dialog-close').addEventListener('click', () => reviewDialog.close());
  reviewDialog.addEventListener('click', (event) => {
    const bounds = reviewDialog.getBoundingClientRect();
    if (event.target === reviewDialog && (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom)) reviewDialog.close();
  });
  reviewDialog.addEventListener('close', () => {
    document.body.classList.remove('review-modal-open');
    reviewTrigger?.focus({ preventScroll: true });
  });
  document.querySelectorAll('.testimonial-bubble').forEach((bubble) => {
    const quote = bubble.querySelector('blockquote');
    if (!quote) return;
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'testimonial-more';
    button.textContent = 'See more';
    button.setAttribute('aria-controls', reviewDialog.id);
    button.setAttribute('aria-haspopup', 'dialog');
    quote.after(button);
    const update = () => { button.hidden = quote.scrollHeight <= quote.clientHeight + 1; };
    button.addEventListener('click', () => {
      const author = bubble.closest('.testimonial-slide').querySelector('.testimonial-author');
      reviewDialog.querySelector('h2').textContent = author?.querySelector('strong')?.textContent || 'Client review';
      reviewDialog.querySelector('.review-dialog-role').textContent = author?.querySelector('small')?.textContent || '';
      reviewDialog.querySelector('.review-dialog-text').textContent = quote.textContent;
      reviewTrigger = button;
      reviewDialog.showModal();
      reviewDialog.scrollTop = 0;
      document.body.classList.add('review-modal-open');
    });
    new ResizeObserver(update).observe(quote);
    new MutationObserver(update).observe(quote, { childList: true, characterData: true, subtree: true });
    document.fonts.ready.then(update);
    update();
  });

  document.querySelectorAll('[data-testimonial-slider]').forEach((slider) => {
    const viewport = slider.querySelector('.testimonial-viewport');
    const track = slider.querySelector('.testimonial-track');
    const previousButton = slider.querySelector('[data-testimonial-direction="previous"]');
    const nextButton = slider.querySelector('[data-testimonial-direction="next"]');

    if (!viewport || !track || !previousButton || !nextButton) {
      return;
    }

    const getStep = () => {
      const card = track.querySelector('.testimonial-slide');
      const gap = Number.parseFloat(window.getComputedStyle(track).columnGap) || 24;

      return card ? card.getBoundingClientRect().width + gap : viewport.clientWidth;
    };

    const updateControls = () => {
      const maximumScroll = viewport.scrollWidth - viewport.clientWidth;

      previousButton.disabled = viewport.scrollLeft <= 4;
      nextButton.disabled = viewport.scrollLeft >= maximumScroll - 4;
    };

    const move = (direction) => {
      viewport.scrollBy({
        left: getStep() * direction,
        behavior: reducedMotion ? 'auto' : 'smooth'
      });
    };

    previousButton.addEventListener('click', () => move(-1));
    nextButton.addEventListener('click', () => move(1));

    viewport.addEventListener('keydown', (event) => {
      if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') {
        return;
      }

      event.preventDefault();
      move(event.key === 'ArrowLeft' ? -1 : 1);
    });

    let scrollFrame;

    viewport.addEventListener('scroll', () => {
      window.cancelAnimationFrame(scrollFrame);
      scrollFrame = window.requestAnimationFrame(updateControls);
    }, { passive: true });

    window.addEventListener('resize', updateControls);
    updateControls();
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
