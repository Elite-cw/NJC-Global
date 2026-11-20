<main class="contact-page">
      <section class="contact-hero">
        <div class="container contact-hero-heading">
          <div>
            <p class="eyebrow">Connect with us</p>
            <h1>Let's Build Something <em>Visible.</em></h1>
          </div>
          <p>Talk to us about your brand, talent, or next event.</p>
        </div>
      </section>

      <section class="contact-main" id="work-with-us">
        <div class="container contact-main-grid">
          <div class="contact-form-panel">
            <form id="contact-form" data-njc-server-form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
              <input type="hidden" name="action" value="njc_inquiry">
              <?php wp_nonce_field( 'njc_inquiry', 'njc_inquiry_nonce' ); ?>
              <label class="njc-form-trap" aria-hidden="true">Website<input name="company_website" type="text" tabindex="-1" autocomplete="off"></label>
              <div class="contact-field-grid">
                <label>
                  <span>Full name</span>
                  <input name="name" type="text" placeholder="Jane Doe" autocomplete="name" required>
                </label>
                <label>
                  <span>Organization</span>
                  <input name="organization" type="text" placeholder="Your company" autocomplete="organization">
                </label>
              </div>

              <div class="contact-field-grid">
                <label>
                  <span>Email address</span>
                  <input name="email" type="email" placeholder="jane@example.com" autocomplete="email" required>
                </label>
                <label>
                  <span>Phone number <small>Optional</small></span>
                  <input name="phone" type="tel" placeholder="+234 000 000 0000" autocomplete="tel">
                </label>
              </div>

              <label>
                <span>What can we help you with?</span>
                <select name="inquiry_type" required>
                  <option value="" selected disabled>Select an area of interest...</option>
                  <option value="media">Media &amp; Press</option>
                  <option value="event-strategy">Event Strategy &amp; Curation</option>
                  <option value="talent">Talent Execution</option>
                  <option value="advisory">Strategic Advisory</option>
                  <option value="other">Other Inquiries</option>
                </select>
              </label>

              <label>
                <span>Message</span>
                <textarea name="message" rows="5" placeholder="Tell us about your project or goals..." required></textarea>
              </label>

              <div class="contact-form-actions">
                <p>By submitting, you agree to our privacy policy.</p>
                <button class="button" type="submit">
                  <span>Send message</span>
                  <i class="material-symbols-outlined">arrow_forward</i>
                </button>
              </div>

              <p class="contact-form-status" role="status" aria-live="polite"></p>
            </form>
          </div>

          <aside class="contact-information">
            <div class="contact-image-card">
              <div role="img" aria-label="Sophisticated NJC Global office with African design accents"></div>
              <p>Global Reach,<br><em>African Roots.</em></p>
            </div>

            <div class="contact-details">
              <div>
                <p class="eyebrow">Direct inquiries</p>
                <a class="contact-main-email" href="mailto:info@njcglobal.site">info@njcglobal.site</a>
              </div>
              <div class="contact-email-grid">
                <div><h2>Talent &amp; Partnerships</h2><a href="mailto:joyce@njcglobal.site">joyce@njcglobal.site</a></div>
                <div><h2>Media Relations</h2><a href="mailto:info@njcglobal.site">info@njcglobal.site</a></div>
              </div>
              <div class="contact-location-card">
                <iframe title="Map showing NJC Global's base in Nigeria" loading="lazy" src="https://www.openstreetmap.org/export/embed.html?bbox=2.6%2C4.0%2C14.7%2C13.9&amp;layer=mapnik"></iframe>
                <div class="contact-location-copy">
                  <p class="eyebrow">Our base</p>
                  <strong>Nigeria · Working across Africa</strong>
                  <a href="tel:+2347013158699">+234 701 315 8699</a>
                  <a href="mailto:info@njcglobal.site">info@njcglobal.site</a>
                </div>
              </div>
              <div>
                <p class="eyebrow">Social</p>
                <div class="contact-socials"><a href="https://www.linkedin.com/company/njc-global/" target="_blank" rel="noopener noreferrer" aria-label="NJC Global on LinkedIn">IN</a><a href="https://www.instagram.com/njcglobal?stkn=MWZxZnlibXY5bG5xZQ==" target="_blank" rel="noopener noreferrer" aria-label="NJC Global on Instagram">IG</a><a href="#" aria-label="X">X</a></div>
              </div>
            </div>
          </aside>
        </div>
      </section>

      <section id="testimonials" class="testimonial-section">
        <div class="container">
          <div class="testimonial-showcase" data-testimonial-slider>
            <header class="testimonial-intro">
              <span class="material-symbols-outlined testimonial-mark" aria-hidden="true">format_quote</span>
              <p class="eyebrow">Client testimonials</p>
              <h2>What our partners are saying.</h2>
              <p>Feedback from the people we work with.</p>

              <div class="testimonial-controls" aria-label="Testimonial navigation">
                <button type="button" data-testimonial-direction="previous" aria-label="View previous testimonial">
                  <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
                </button>
                <span aria-hidden="true"></span>
                <button type="button" data-testimonial-direction="next" aria-label="View next testimonial">
                  <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                </button>
              </div>
            </header>

            <div class="testimonial-viewport" tabindex="0" aria-label="Client testimonials">
              <div class="testimonial-track"><?php njc_render_reviews(); ?></div>
            </div>
          </div>

          <dl class="contact-proof-stats">
            <div><dt>5+</dt><dd>African markets</dd></div>
            <div><dt>10</dt><dd>Projects delivered</dd></div>
            <div><dt>98%</dt><dd>Partner satisfaction</dd></div>
          </dl>
        </div>
      </section>
    </main>
