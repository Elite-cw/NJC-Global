# NJC Global Editable WordPress Theme 0.2.0

This is a separate, installable WordPress theme built from the preserved NJC
static website. Installing or previewing this package does not change the
GitHub Pages portfolio version.

## What is editable

### Blog posts

Use **Posts** in WordPress to create, edit, preview, schedule, publish, unpublish,
or delete articles. Each post supports:

1. Title
2. Excerpt for the article card and introduction
3. Category
4. Featured image
5. Author and publish date
6. Full article content in the block editor

The theme supplies the clickable blog archive and readable single-article
layout, so editors do not need to rebuild cards or page templates.

### Events

The theme adds an **Events** area to the WordPress dashboard. Each event supports:

1. Title, excerpt, featured image, and full block-editor content
2. Start and end date/time
3. Location and region
4. Event type
5. Upcoming, featured, or past display group
6. Registration or enquiry URL

### Designed pages

Home, About Us, Projects, Contact Us, Privacy, and Terms use the preserved NJC
layouts by default. This protects the approved design from accidental changes.

To replace one of these layouts with normal WordPress blocks:

1. Open the page in **Pages**.
2. Add or edit the page content with the block editor.
3. In the **NJC page display** panel, enable **Use the WordPress editor content**.
4. Preview before publishing.

Turning the option off restores the bundled NJC layout without deleting the
page's saved block content.

## Forms

Contact and project enquiries are validated and saved privately under
**Inquiries**. Newsletter addresses are saved privately under **Subscribers**.
The theme also attempts to send enquiries to the WordPress administrator email.
Reliable production email requires SMTP or a transactional-email service.

## Required WordPress pages

Create or retain pages with these slugs:

- `about-us`
- `projects`
- `contact-us`
- `privacy`
- `terms`
- `blog` and assign it as the Posts page

Events use the `/events/` archive supplied by the theme and do not need a normal
WordPress page.

## Safe installation

1. Back up the existing WordPress site.
2. Upload the ZIP through **Appearance > Themes > Add New > Upload Theme**.
3. Install it without deleting or modifying the currently active theme.
4. Use **Live Preview** or staging first.
5. Confirm there is one NJC header and one NJC footer.
6. Confirm Projects, Events, Blog, and the legal-page links resolve.
7. Activate only after the preview is approved.

The theme contains compatibility guards for the site's existing Pagelayer and
CookieAdmin plugins. It does not delete or globally reconfigure either plugin.

## Updating from the portfolio source

Run `wordpress-export/sync-static-reference.ps1` from the preserved static
project to refresh the copied layouts and assets. Review WordPress-specific
forms and routes after every sync, then run
`wordpress-export/optimize-theme-images.ps1` before creating a new versioned ZIP.
Never replace the original static website with the WordPress theme folder.
