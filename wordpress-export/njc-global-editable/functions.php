<?php
/**
 * NJC Global theme bootstrap.
 *
 * @package NJC_Global
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NJC_THEME_VERSION', '0.2.0' );
define( 'NJC_THEME_DIR', get_template_directory() );
define( 'NJC_THEME_URI', get_template_directory_uri() );

require_once NJC_THEME_DIR . '/inc/events.php';
require_once NJC_THEME_DIR . '/inc/forms.php';
require_once NJC_THEME_DIR . '/inc/editable-pages.php';

function njc_theme_setup() {
	load_theme_textdomain( 'njc-global', NJC_THEME_DIR . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'align-wide' );
	add_editor_style( array( 'assets/css/site.css', 'style.css' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary navigation', 'njc-global' ),
			'footer'  => __( 'Footer navigation', 'njc-global' ),
		)
	);
}
add_action( 'after_setup_theme', 'njc_theme_setup' );

function njc_enqueue_assets() {
	wp_enqueue_style(
		'njc-fonts',
		'https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Material+Symbols+Outlined&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'njc-site', NJC_THEME_URI . '/assets/css/site.css', array( 'njc-fonts' ), NJC_THEME_VERSION );
	wp_enqueue_style( 'njc-wordpress', get_stylesheet_uri(), array( 'njc-site' ), NJC_THEME_VERSION );

	wp_enqueue_script( 'njc-site', NJC_THEME_URI . '/assets/js/site.js', array(), NJC_THEME_VERSION, true );
	wp_enqueue_script( 'njc-theme', NJC_THEME_URI . '/assets/js/theme.js', array( 'njc-site' ), NJC_THEME_VERSION, true );

	$legacy_script = '';
	if ( is_front_page() ) {
		$legacy_script = 'front-page';
	} elseif ( is_page( array( 'about-us', 'projects', 'contact-us', 'privacy', 'terms' ) ) ) {
		$legacy_script = get_post_field( 'post_name', get_queried_object_id() );
	}

	if ( $legacy_script && file_exists( NJC_THEME_DIR . '/assets/js/pages/' . $legacy_script . '.js' ) ) {
		wp_enqueue_script(
			'njc-page-' . $legacy_script,
			NJC_THEME_URI . '/assets/js/pages/' . $legacy_script . '.js',
			array( 'njc-theme' ),
			NJC_THEME_VERSION,
			true
		);
	}

	wp_localize_script(
		'njc-theme',
		'njcTheme',
		array(
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'homeUrl'   => home_url( '/' ),
			'assetUrl'  => trailingslashit( NJC_THEME_URI ) . 'assets/',
			'aboutUrl'  => njc_page_url( 'about-us' ),
			'privacyUrl' => njc_page_url( 'privacy' ),
			'termsUrl'   => njc_page_url( 'terms' ),
			'cookieManaged' => njc_cookie_manager_active(),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'njc_enqueue_assets' );

function njc_cookie_manager_active() {
	$active_plugins = (array) get_option( 'active_plugins', array() );

	if ( is_multisite() ) {
		$active_plugins = array_merge( $active_plugins, array_keys( (array) get_site_option( 'active_sitewide_plugins', array() ) ) );
	}

	foreach ( $active_plugins as $plugin ) {
		if ( false !== stripos( $plugin, 'cookieadmin' ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Keep Pagelayer's site-wide templates from replacing this theme's own shell.
 *
 * The existing site can continue using Pagelayer with its current theme. This
 * callback only runs while NJC Global itself is being previewed or is active.
 */
function njc_disable_pagelayer_theme_templates() {
	if ( empty( $GLOBALS['pagelayer'] ) || ! is_object( $GLOBALS['pagelayer'] ) ) {
		return;
	}

	$pagelayer = $GLOBALS['pagelayer'];
	foreach ( array( 'template_header', 'template_footer', 'template_post', 'template_sidebar' ) as $property ) {
		$pagelayer->{$property} = false;
	}
}
add_action( 'template_redirect', 'njc_disable_pagelayer_theme_templates', 999 );

/** Remove builder presentation assets from pages rendered by this theme. */
function njc_dequeue_pagelayer_presentation_assets() {
	wp_dequeue_style( 'pagelayer-frontend' );
	wp_dequeue_style( 'pagelayer-google-font' );
	wp_dequeue_script( 'pagelayer-frontend' );
}
add_action( 'wp_enqueue_scripts', 'njc_dequeue_pagelayer_presentation_assets', 10000 );

function njc_blog_url() {
	$page_for_posts = (int) get_option( 'page_for_posts' );
	return $page_for_posts ? get_permalink( $page_for_posts ) : home_url( '/blog/' );
}

function njc_events_url() {
	$url = get_post_type_archive_link( 'njc_event' );
	return $url ? $url : home_url( '/events/' );
}

function njc_page_url( $slug, $fragment = '' ) {
	$page = get_page_by_path( $slug );
	$url  = $page ? get_permalink( $page ) : home_url( '/' . trim( $slug, '/' ) . '/' );
	return $url . $fragment;
}

/**
 * Repoint the original static markup to WordPress routes and theme assets.
 */
function njc_rewrite_legacy_markup( $markup ) {
	$asset_url = trailingslashit( NJC_THEME_URI ) . 'assets/';
	$asset_token = '{{NJC_THEME_ASSET_URL}}';
	$routes    = array(
		'../index.html'                          => home_url( '/' ),
		'view/about-us.html'                    => njc_page_url( 'about-us' ),
		'about-us.html'                         => njc_page_url( 'about-us' ),
		'view/projects.html'                    => njc_page_url( 'projects' ),
		'projects.html'                         => njc_page_url( 'projects' ),
		'view/contact-us.html'                  => njc_page_url( 'contact-us' ),
		'contact-us.html'                       => njc_page_url( 'contact-us' ),
		'view/events.html'                      => njc_events_url(),
		'events.html'                           => njc_events_url(),
		'view/blog.html'                        => njc_blog_url(),
		'blog.html'                             => njc_blog_url(),
		'view/privacy.html'                     => njc_page_url( 'privacy' ),
		'privacy.html'                          => njc_page_url( 'privacy' ),
		'view/terms.html'                       => njc_page_url( 'terms' ),
		'terms.html'                            => njc_page_url( 'terms' ),
		'view/amplifying-african-excellence.html' => njc_blog_url(),
		'amplifying-african-excellence.html'    => njc_blog_url(),
		'view/event-details.html'               => njc_events_url(),
		'event-details.html'                    => njc_events_url(),
	);

	$markup = str_replace( array( '../assets/', 'assets/' ), $asset_token, $markup );
	$markup = str_replace( array_keys( $routes ), array_values( $routes ), $markup );
	$markup = str_replace( $asset_token, $asset_url, $markup );

	return $markup;
}

function njc_render_legacy_part( $name ) {
	$file = NJC_THEME_DIR . '/template-parts/legacy/' . sanitize_file_name( $name ) . '.php';
	if ( ! file_exists( $file ) ) {
		return false;
	}

	ob_start();
	include $file;
	$markup = ob_get_clean();
	echo njc_rewrite_legacy_markup( $markup ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted bundled theme markup.
	return true;
}

function njc_estimated_read_time( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$words   = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ) );
	return max( 1, (int) ceil( $words / 220 ) );
}

function njc_excerpt( $post_id = 0, $words = 28 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$text    = get_the_excerpt( $post_id );
	if ( ! $text ) {
		$text = get_post_field( 'post_content', $post_id );
	}
	return wp_trim_words( wp_strip_all_tags( $text ), $words );
}

function njc_body_classes( $classes ) {
	if ( is_post_type_archive( 'njc_event' ) ) {
		$classes[] = 'events-page-body';
	}
	if ( is_front_page() || is_page( array( 'about-us', 'projects', 'contact-us', 'privacy', 'terms' ) ) ) {
		$classes[] = 'njc-legacy-page';
	}
	return $classes;
}
add_filter( 'body_class', 'njc_body_classes' );
