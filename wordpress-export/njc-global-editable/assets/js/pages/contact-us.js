const menuButton = document.querySelector('.menu');
      const navigation = document.querySelector('#nav');
      const contactForm = document.querySelector('#contact-form');
      const contactStatus = document.querySelector('.contact-form-status');
      const messageField = contactForm.querySelector('textarea[name="message"]');

      function resizeMessageField() {
        messageField.style.height = 'auto';
        messageField.style.height = `${messageField.scrollHeight}px`;
      }

      messageField.addEventListener('input', resizeMessageField);
      resizeMessageField();

      menuButton.addEventListener('click', () => {
        const isOpen = menuButton.getAttribute('aria-expanded') === 'true';
        menuButton.setAttribute('aria-expanded', String(!isOpen));
        navigation.classList.toggle('open', !isOpen);
        menuButton.firstElementChild.textContent = isOpen ? 'menu' : 'close';
      });

      contactForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const button = contactForm.querySelector('button[type="submit"]');
        button.disabled = true;
        button.querySelector('span').textContent = 'Sending...';
        button.querySelector('i').textContent = 'progress_activity';
        contactStatus.textContent = '';

        window.setTimeout(() => {
          button.querySelector('span').textContent = 'Message sent';
          button.querySelector('i').textContent = 'check';
          contactStatus.textContent = 'Thank you. Your message has been received and our team will be in touch.';
          contactForm.reset();
          resizeMessageField();
          button.disabled = false;
        }, 900);
      });

      document.querySelectorAll('#community-form, .footer-contact-subscribe').forEach((form) => {
        form.addEventListener('submit', (event) => {
          event.preventDefault();
          form.querySelector('button').textContent = 'Joined';
          form.querySelector('input').value = '';
        });
      });
