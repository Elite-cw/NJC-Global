# NJC Global Editable WordPress Theme

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

Every published article uses one protected NJC Journal template with the NJC
breadcrumb, category label, editorial headline treatment, excerpt, author/date/
reading-time row, featured-image hero, styled body content, author panel, share
links, and closing navigation. Editors cannot accidentally replace this layout;
they edit only the post fields and article body.

Featured images appear as the wide article hero. Editors can also insert Image
and Gallery blocks anywhere in the article body. Supported video and social
URLs pasted on their own line use WordPress rich embeds; any other paragraph
containing only a URL is displayed as a branded resource card instead of a
long raw address.

New blog posts open with editable starter blocks for the introduction, section
headings, body paragraphs, and conclusion. The placeholders guide the writer
without publishing sample copy.

### Events

The theme adds an **Events** area to the WordPress dashboard. Each event supports:

1. Title, excerpt, featured image, and full block-editor content
2. Start and end date/time
3. Location and region
4. Event type
5. Upcoming, featured, or past display group
6. Registration or enquiry URL

New events open with editable starter blocks for the overview, what to expect,
the intended audience, and important information. The structured Event details
panel remains separate from the page body.

### Dashboard publishing guide

When this theme is active, **Dashboard > NJC Publishing Guide** explains both
publishing workflows. The main Dashboard also shows a compact NJC Publishing
Guide widget with direct **Add Blog Post** and **Add Event** buttons. The normal
Posts menu is labelled **Blog Posts** to make its purpose clearer for clients.

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

### Complete designed-page editing

Use **Appearance > Customize > NJC Theme Options** to edit all headings and body
paragraphs on Home, About, Projects, Contact, Privacy, and Terms without
rebuilding their layouts. The same panel contains the shared colour system,
contact and social URLs, team details, team and partner imagery, logos, hero
images, capability images, and selected-work images. Resetting a field restores
the supplied NJC wording, link, image, or colour.

The theme continues to use Playfair Display for display headings and Hanken
Grotesk for body copy. Posts, events, and editor-enabled pages remain editable
through the normal WordPress block editor.

## Forms

Contact and project enquiries are validated and saved privately under
**Inquiries**. Newsletter addresses are saved privately under **Subscribers**.
The theme also attempts to send enquiries to the WordPress administrator email.
Reliable production email requires SMTP or a transactional-email service.

## Required WordPress pages

Create or retain pages with these slugs. The aliases in parentheses are also
recognized, so existing WordPress content does not need to be renamed:

- `about-us` (`about`)
- `projects`
- `contact-us` (`contact`)
- `privacy` (`privacy-policy`)
- `terms` (`terms-of-service`)
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
Pagelayer's global font class is removed only from pages rendered by this theme,
so the NJC Hanken Grotesk and Playfair Display typography remains authoritative.
CookieAdmin's consent control remains functional and receives a flat NJC-styled
fallback icon when the plugin supplies an empty image URL.

## Header ownership

This theme's `header.php` is the only public site header while NJC Global
Editable is active or being previewed. The theme disables Pagelayer's saved
global shell and suppresses common theme-builder header containers if another
plugin injects one. Page and article headings inside `main` remain untouched,
and the WordPress admin toolbar remains available to signed-in administrators.

During Live Preview, inspect the page source or browser elements and confirm:

1. Exactly one visible element has `data-njc-site-header`.
2. No Pagelayer, Elementor, or previous-theme header is visible above it.
3. The desktop and mobile NJC navigation both open and link correctly.

## Updating from the portfolio source

Run `wordpress-export/sync-static-reference.ps1` from the preserved static
project to refresh the copied layouts and assets. Review WordPress-specific
forms and routes after every sync, then run
`wordpress-export/optimize-theme-images.ps1` before creating a new versioned ZIP.
Never replace the original static website with the WordPress theme folder.
