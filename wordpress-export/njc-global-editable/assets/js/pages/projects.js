const menuButton = document.querySelector('.menu');
      const navigation = document.querySelector('nav');
      menuButton.addEventListener('click', () => {
        const isOpen = menuButton.getAttribute('aria-expanded') === 'true';
        menuButton.setAttribute('aria-expanded', String(!isOpen));
        navigation.classList.toggle('open', !isOpen);
        menuButton.firstElementChild.textContent = isOpen ? 'menu' : 'close';
      });
      document.querySelectorAll('form').forEach((form) => form.addEventListener('submit', (event) => {
        event.preventDefault();
        const button = event.currentTarget.querySelector('button');
        if (button) button.textContent = 'Thank you';
      }));
