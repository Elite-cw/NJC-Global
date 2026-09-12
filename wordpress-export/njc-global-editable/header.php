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
<header class="site-header">
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
			<a class="njc-projects-link<?php echo is_page( 'projects' ) ? ' active' : ''; ?>" <?php echo is_page( 'projects' ) ? 'aria-current="page"' : ''; ?> href="<?php echo esc_url( njc_page_url( 'projects' ) ); ?>"><?php esc_html_e( 'Projects', 'njc-global' ); ?></a>
			<a <?php echo ( is_post_type_archive( 'njc_event' ) || is_singular( 'njc_event' ) ) ? 'class="active" aria-current="page"' : ''; ?> href="<?php echo esc_url( njc_events_url() ); ?>"><?php esc_html_e( 'Events', 'njc-global' ); ?></a>
			<a <?php echo is_page( 'about-us' ) ? 'class="active" aria-current="page"' : ''; ?> href="<?php echo esc_url( njc_page_url( 'about-us' ) ); ?>"><?php esc_html_e( 'About Us', 'njc-global' ); ?></a>
			<a <?php echo ( is_home() || is_singular( 'post' ) || is_category() || is_tag() ) ? 'class="active" aria-current="page"' : ''; ?> href="<?php echo esc_url( njc_blog_url() ); ?>"><?php esc_html_e( 'Blog', 'njc-global' ); ?></a>
			<a <?php echo is_page( 'contact-us' ) ? 'class="active" aria-current="page"' : ''; ?> href="<?php echo esc_url( njc_page_url( 'contact-us' ) ); ?>"><?php esc_html_e( 'Contact Us', 'njc-global' ); ?></a>
		</nav>
		<a class="button nav-cta" href="<?php echo esc_url( njc_page_url( 'contact-us', '#work-with-us' ) ); ?>"><?php esc_html_e( 'Work with us', 'njc-global' ); ?></a>
	</div>
</header>
