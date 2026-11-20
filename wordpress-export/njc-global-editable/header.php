<?php
/**
 * Site header.
 *
 * @package NJC_Global
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="<?php echo esc_url( NJC_THEME_URI . '/assets/images/njc-global.png' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="page-loader" data-njc-page-loader role="status" aria-label="Loading" aria-live="polite" aria-hidden="true">
	<div class="page-loader-panel">
		<span class="page-loader-mark" aria-hidden="true"><svg class="loader-globe" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg"><defs><clipPath id="loader-orbit-back"><rect x="-80" y="-80" width="160" height="80"/></clipPath><clipPath id="loader-orbit-front"><rect x="-80" y="0" width="160" height="80"/></clipPath></defs><g transform="translate(60 60) rotate(-25) scale(1 .42)"><g clip-path="url(#loader-orbit-back)"><circle r="52" stroke="var(--cream, #f8f5ed)" stroke-width="11"/><circle r="52" stroke="currentColor" stroke-width="4"/><g class="loader-globe-arrow"><path d="M-5-52H8" stroke="var(--cream, #f8f5ed)" stroke-width="12" stroke-linecap="round"/><path d="M-5-52H8" stroke="currentColor" stroke-width="6" stroke-linecap="round"/><path d="M5-64 21-52 5-40Z" fill="currentColor"/></g></g></g><circle cx="60" cy="60" r="39" fill="currentColor"/><path d="M41 25 51 24 54 31 50 37 57 43 68 40 73 47 72 54 80 61 75 70 69 72 66 87 58 96 53 85 51 74 44 68 42 58 34 53 29 44 33 34Z M78 28 87 33 94 43 86 44 80 38Z M82 76 87 80 84 91 80 93Z" fill="var(--cream, #f8f5ed)"/><g transform="translate(60 60) rotate(-25) scale(1 .42)"><g clip-path="url(#loader-orbit-front)"><circle r="52" stroke="var(--cream, #f8f5ed)" stroke-width="11"/><circle r="52" stroke="currentColor" stroke-width="4"/><g class="loader-globe-arrow"><path d="M-5-52H8" stroke="var(--cream, #f8f5ed)" stroke-width="12" stroke-linecap="round"/><path d="M-5-52H8" stroke="currentColor" stroke-width="6" stroke-linecap="round"/><path d="M5-64 21-52 5-40Z" fill="currentColor"/></g></g></g></svg></span>
	</div>
</div>
<noscript><style>.page-loader{display:none!important}</style></noscript>
<header id="njc-site-header" class="site-header" data-njc-site-header>
	<div class="container nav-wrap">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<span class="brand-logo" aria-hidden="true"></span>
			<span class="sr-only"><?php bloginfo( 'name' ); ?></span>
		</a>
		<button class="menu" type="button" aria-expanded="false" aria-controls="nav">
			<span class="material-symbols-outlined">menu</span>
			<i><?php esc_html_e( 'Menu', 'njc-global' ); ?></i>
		</button>
		<nav id="nav" aria-label="<?php esc_attr_e( 'Main navigation', 'njc-global' ); ?>">
			<a <?php echo is_front_page() ? 'class="active" aria-current="page"' : ''; ?> href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'njc-global' ); ?></a>
			<a class="njc-projects-link<?php echo njc_is_page_key( 'projects' ) ? ' active' : ''; ?>" <?php echo njc_is_page_key( 'projects' ) ? 'aria-current="page"' : ''; ?> href="<?php echo esc_url( njc_page_url( 'projects' ) ); ?>"><?php esc_html_e( 'Projects', 'njc-global' ); ?></a>
			<a <?php echo ( is_post_type_archive( 'njc_event' ) || is_singular( 'njc_event' ) ) ? 'class="active" aria-current="page"' : ''; ?> href="<?php echo esc_url( njc_events_url() ); ?>"><?php esc_html_e( 'Events', 'njc-global' ); ?></a>
			<a <?php echo njc_is_page_key( 'about-us' ) ? 'class="active" aria-current="page"' : ''; ?> href="<?php echo esc_url( njc_page_url( 'about-us' ) ); ?>"><?php esc_html_e( 'About Us', 'njc-global' ); ?></a>
			<a <?php echo ( is_home() || is_singular( 'post' ) || is_category() || is_tag() ) ? 'class="active" aria-current="page"' : ''; ?> href="<?php echo esc_url( njc_blog_url() ); ?>"><?php esc_html_e( 'Blog', 'njc-global' ); ?></a>
			<a <?php echo njc_is_page_key( 'contact-us' ) ? 'class="active" aria-current="page"' : ''; ?> href="<?php echo esc_url( njc_page_url( 'contact-us' ) ); ?>"><?php esc_html_e( 'Contact Us', 'njc-global' ); ?></a>
		</nav>
		<a class="button nav-cta" href="<?php echo esc_url( njc_page_url( 'contact-us', '#work-with-us' ) ); ?>"><?php echo esc_html( njc_get_copy( 'nav_cta_label' ) ); ?></a>
	</div>
</header>
