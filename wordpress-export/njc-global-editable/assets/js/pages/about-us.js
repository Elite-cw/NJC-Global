const menuButton = document.querySelector('.menu');
      const navigation = document.querySelector('nav');

      menuButton.addEventListener('click', () => {
        const isOpen = menuButton.getAttribute('aria-expanded') === 'true';
        menuButton.setAttribute('aria-expanded', String(!isOpen));
        navigation.classList.toggle('open', !isOpen);
        menuButton.firstElementChild.textContent = isOpen ? 'menu' : 'close';
      });

      navigation.addEventListener('click', (event) => {
        if (event.target.matches('a')) {
          navigation.classList.remove('open');
          menuButton.setAttribute('aria-expanded', 'false');
          menuButton.firstElementChild.textContent = 'menu';
        }
      });

      document.querySelector('form').addEventListener('submit', (event) => {
        event.preventDefault();
        event.currentTarget.querySelector('button').textContent = 'Joined';
        event.currentTarget.querySelector('input').value = '';
      });

      const ecosystem = document.querySelector('.ecosystem');
      let previousScrollPosition = window.scrollY;
      let scrollDirection = 'down';

      window.addEventListener('scroll', () => {
        const currentScrollPosition = window.scrollY;

        if (Math.abs(currentScrollPosition - previousScrollPosition) > 4) {
          scrollDirection = currentScrollPosition > previousScrollPosition ? 'down' : 'up';
          previousScrollPosition = currentScrollPosition;
        }
      }, { passive: true });

      const ecosystemObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting && entry.intersectionRatio >= 0.18) {
            ecosystem.dataset.scrollDirection = scrollDirection;
            requestAnimationFrame(() => {
              requestAnimationFrame(() => ecosystem.classList.add('is-visible'));
            });
          } else if (!entry.isIntersecting) {
            ecosystem.classList.remove('is-visible');
          }
        });
      }, {
        threshold: [0, 0.18],
        rootMargin: '-4% 0px -4% 0px'
      });

      ecosystemObserver.observe(ecosystem);
