# NJC Global Website

NJC Global is a responsive multi-page website presenting the organization’s Pan-African media, events, talent, visibility, and strategic execution services.

The project currently uses semantic HTML, a shared CSS stylesheet, and lightweight vanilla JavaScript. It does not require a package manager, build process, or framework.

- Live website: [https://elite-cw.github.io/NJC-Global/](https://elite-cw.github.io/NJC-Global/)
- Repository: [https://github.com/Elite-cw/NJC-Global](https://github.com/Elite-cw/NJC-Global)

## Project structure

```text
NJC/
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── images/
│   │   ├── partners/
│   │   ├── team/
│   │   ├── about-hero-triptych.png
│   │   ├── njc-global.png
│   │   └── njc-global_(no-bg).png
│   └── js/
│       └── site.js
├── view/
│   ├── about-us.html
│   ├── amplifying-african-excellence.html
│   ├── blog.html
│   ├── contact-us.html
│   ├── event-details.html
│   ├── events.html
│   ├── privacy.html
│   ├── projects.html
│   ├── services.html
│   └── terms.html
├── 404.html
├── index.html
└── README.md
```

## Pages

- `index.html` — homepage and primary landing page.
- `view/about-us.html` — company story, ecosystem, leadership, and mission.
- `view/services.html` — services, delivery process, and service calls to action.
- `view/projects.html` — filterable collaboration portfolio, testimonials, and project inquiry form.
- `view/events.html` — searchable and filterable event directory.
- `view/event-details.html` — detailed event information.
- `view/blog.html` — featured stories, category filters, search, audio preview, and articles.
- `view/amplifying-african-excellence.html` — individual editorial article page.
- `view/contact-us.html` — inquiries, testimonials, social proof, calls to action, and community signup.
- `view/privacy.html` — website privacy policy.
- `view/terms.html` — website terms of service.
- `404.html` — custom not-found page for invalid routes.

## Running locally

The site can be opened directly by double-clicking `index.html`. A local web server is recommended because it more closely matches deployed behavior.

From the project directory, run:

```powershell
python -m http.server 8000
```

Then open:

```text
http://localhost:8000
```

Stop the server with `Ctrl+C`.

## Styling

All pages share:

```text
assets/css/style.css
```

Use the existing components and classes for:

- Navigation, the Explore dropdown, and animated mobile menus
- Buttons and text links
- Rounded cards and floating-card animation
- Page spacing and responsive layouts
- Forms, filters, modals, and calls to action
- Horizontal testimonial carousels
- Partner and team showcases
- Footer structure

Avoid adding page-level Tailwind CSS unless the project is intentionally migrated. Mixing utility-generated styles into individual pages can conflict with the shared stylesheet and produce inconsistent results.

## Brand color guide

The supplied official brand colors are:

| Color | Hex |
| --- | --- |
| Yellow | `#FEAF35` |
| Purple | `#A96AA1` |
| Green | `#1B4A2F` |
| Light grey | `#EFF4F8` |

These colors are reference tokens for selective use when a section or component is explicitly designated. The website currently retains its earlier working palette until those placements are specified.

## Adding images

Place general local images in:

```text
assets/images/
```

Use the existing subfolders for organized collections:

```text
assets/images/partners/
assets/images/team/
```

From a page inside `view/`, reference an image with:

```html
<img src="../assets/images/example.jpg" alt="Useful image description">
```

From `index.html`, use:

```html
<img src="assets/images/example.jpg" alt="Useful image description">
```

For CSS backgrounds in `assets/css/style.css`, use:

```css
background-image: url('../images/example.jpg');
```

## Adding pages

1. Create the HTML file inside `view/`.
2. Link the shared stylesheet using `../assets/css/style.css`.
3. Link the shared script using `../assets/js/site.js`.
4. Reuse the existing header, navigation, and footer.
5. Add the new page link to navigation where appropriate. Projects and Events are grouped automatically inside the Explore dropdown.
6. Increase the CSS or JavaScript query-string version when shared assets change so deployed browsers receive the update.
7. Check desktop, tablet, and mobile layouts.
8. Confirm all local links resolve before deployment.

## Adding blog articles

Blog cards in `view/blog.html` use:

- `data-category` for category filtering.
- `data-search` for live search terms.
- A matching image class defined in the shared stylesheet.

New cards automatically participate in search and category filtering when these attributes are supplied.

Create a separate HTML page for a full article, then point the card’s link to that file. `view/amplifying-african-excellence.html` is the current article-page reference.

## Adding events

Event entries are maintained in `view/events.html`. Ensure that:

- The event has the correct upcoming, featured, or past category.
- Search and filter metadata are supplied.
- Its details link points to `event-details.html` or another valid event-detail page.
- Event images use the existing compact card proportions for consistent sizing.
- Mobile card titles remain on one line and truncate with an ellipsis to preserve equal heights and alignment.

## Forms and functionality

Current JavaScript provides front-end behavior for:

- Explore dropdown navigation with active-page feedback
- Animated mobile navigation and nested dropdown behavior
- Event filtering and search
- Project filtering
- Blog filtering and search
- Load-more article behavior
- Audio-preview simulation
- Auto-growing contact message field
- Contact and newsletter confirmation states
- Horizontal testimonial controls
- Cookie-preference storage
- Page and image loading feedback
- Scroll and reveal animations

The contact and newsletter forms do not currently send data to a server. Real submissions require a backend endpoint or a form provider such as Formspree, Netlify Forms, or a custom API.

## Deployment

The live site is deployed from the repository’s `main` branch through GitHub Pages:

```text
https://elite-cw.github.io/NJC-Global/
```

After changes are tested, commit and push them to `main`. GitHub Pages will rebuild the website automatically. The update may take a few minutes to appear, and a hard refresh may be required when cached assets were changed.

Before deployment:

1. Test every navigation link.
2. Confirm remote and local images load.
3. Check filters, forms, menus, and modals.
4. Test common mobile and desktop widths.
5. Connect forms to a real submission service if required.

## Maintenance notes

- Keep HTML vertically formatted and readable.
- Keep shared styling in `assets/css/style.css`.
- Keep shared site behavior in `assets/js/site.js`.
- Store new local media in `assets/images/`.
- Keep Projects and Events inside the Explore dropdown unless the navigation structure is intentionally redesigned.
- Preserve rounded corners, whitespace, and restrained ambient animation.
- Ensure hidden animation states never prevent content from appearing in full-page screenshots.
- Use accessible labels, descriptive alternative text, and keyboard-friendly controls.
- Test interactive and responsive changes in a rendered browser before committing them.
