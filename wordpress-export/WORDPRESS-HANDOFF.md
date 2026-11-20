# NJC WordPress browser handoff

Upload file:

`C:\Users\HP\Documents\NJC\wordpress-export\njc-global-editable-0.2.2.zip`

Suggested prompt for the ChatGPT app:

> Access my open NJC Global WordPress dashboard in Chrome. Upload and install
> `C:\Users\HP\Documents\NJC\wordpress-export\njc-global-editable-0.2.2.zip` as a
> separate theme. Do not delete, overwrite, or activate the current live theme.
> Use Live Preview first. The NJC header must replace every existing public site
> header; do not keep a Pagelayer, prior-theme, Elementor, or other builder
> header. Confirm there is exactly one visible element marked
> `data-njc-site-header` and exactly one NJC footer. Keep the WordPress admin
> toolbar. Then check Home, Projects, Events, About Us, Blog, Contact Us,
> Privacy, Terms, one article, and one event on desktop and mobile. Stop before
> activation and tell me what you found.

## Important safeguards

- Keep the current theme active until the preview is approved.
- Do not remove Pagelayer or CookieAdmin.
- Do not edit or delete saved headers globally; the NJC theme owns its header
  only while it is previewed or active.
- Do not overwrite the GitHub Pages portfolio project.
- Back up WordPress before activation.
- The theme recognizes the existing `/about/` and `/contact/` pages without
  renaming them. It also supports `/about-us/` and `/contact-us/` on other sites.
- The current WordPress Privacy Policy is still a draft and no Terms page was
  found during the 0.2.2 preview audit. Publish the approved Privacy page and
  create an approved Terms page before activation; do not publish placeholder
  legal copy without owner review.
