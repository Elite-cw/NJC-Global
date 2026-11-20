<?php
/**
 * NJC Global theme bootstrap.
 *
 * @package NJC_Global
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NJC_THEME_VERSION', '1.3.0' );
define( 'NJC_THEME_DIR', get_template_directory() );
define( 'NJC_THEME_URI', get_template_directory_uri() );

require_once NJC_THEME_DIR . '/inc/events.php';
require_once NJC_THEME_DIR . '/inc/reviews.php';
require_once NJC_THEME_DIR . '/inc/forms.php';
require_once NJC_THEME_DIR . '/inc/editable-pages.php';
require_once NJC_THEME_DIR . '/inc/customizer.php';
require_once NJC_THEME_DIR . '/inc/editor-templates.php';

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

/** Load the public NJC typefaces inside the block editor as well. */
function njc_enqueue_editor_fonts() {
	wp_enqueue_style(
		'njc-editor-fonts',
		'https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap',
		array(),
		null
	);
}
add_action( 'enqueue_block_editor_assets', 'njc_enqueue_editor_fonts' );

/**
 * Turn a paragraph containing only a bare URL into a compact resource card.
 *
 * WordPress still handles supported video and social URLs as rich embeds. This
 * fallback keeps ordinary pasted links readable without printing a long URL.
 */
function njc_render_bare_url_preview( $block_content, $block ) {
	if ( is_admin() || empty( $block['blockName'] ) || 'core/paragraph' !== $block['blockName'] ) {
		return $block_content;
	}

	$plain = trim( wp_strip_all_tags( html_entity_decode( $block_content, ENT_QUOTES, get_bloginfo( 'charset' ) ) ) );
	if ( ! filter_var( $plain, FILTER_VALIDATE_URL ) ) {
		return $block_content;
	}

	$url  = esc_url( $plain );
	$host = wp_parse_url( $plain, PHP_URL_HOST );
	$host = $host ? preg_replace( '/^www\./i', '', $host ) : __( 'External website', 'njc-global' );

	return sprintf(
		'<aside class="njc-link-preview"><a href="%1$s" target="_blank" rel="noopener noreferrer"><span>%2$s</span><strong>%3$s</strong><span class="material-symbols-outlined" aria-hidden="true">arrow_outward</span></a></aside>',
		$url,
		esc_html__( 'Open resource', 'njc-global' ),
		esc_html( $host )
	);
}
add_filter( 'render_block', 'njc_render_bare_url_preview', 10, 2 );

function njc_enqueue_assets() {
	wp_enqueue_style(
		'njc-fonts',
		'https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Material+Symbols+Outlined:FILL@0..1&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'njc-site', NJC_THEME_URI . '/assets/css/site.css', array( 'njc-fonts' ), NJC_THEME_VERSION );
	wp_enqueue_style( 'njc-wordpress', get_stylesheet_uri(), array( 'njc-site' ), NJC_THEME_VERSION );

	wp_enqueue_script( 'njc-site', NJC_THEME_URI . '/assets/js/site.js', array(), NJC_THEME_VERSION, true );
	wp_enqueue_script( 'njc-theme', NJC_THEME_URI . '/assets/js/theme.js', array( 'njc-site' ), NJC_THEME_VERSION, true );
	wp_enqueue_script( 'njc-newsletter', NJC_THEME_URI . '/assets/js/newsletter.js', array( 'njc-theme' ), NJC_THEME_VERSION, true );

	$legacy_script = '';
	if ( is_front_page() ) {
		$legacy_script = 'front-page';
	} elseif ( is_page() ) {
		$legacy_script = njc_legacy_page_slug( get_post_field( 'post_name', get_queried_object_id() ) );
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
		'njc-site',
		'njcTheme',
		array(
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'homeUrl'   => home_url( '/' ),
			'assetUrl'  => trailingslashit( NJC_THEME_URI ) . 'assets/',
			'aboutUrl'  => njc_page_url( 'about-us' ),
			'privacyUrl' => njc_page_url( 'privacy' ),
			'termsUrl'   => njc_page_url( 'terms' ),
			'cookieManaged' => njc_cookie_manager_active(),
			'peopleDirectory' => njc_people_directory(),
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
add_action( 'wp', 'njc_disable_pagelayer_theme_templates', 999 );
add_action( 'template_redirect', 'njc_disable_pagelayer_theme_templates', PHP_INT_MAX );

/** Remove builder presentation assets from pages rendered by this theme. */
function njc_dequeue_pagelayer_presentation_assets() {
	wp_dequeue_style( 'pagelayer-frontend' );
	wp_dequeue_style( 'pagelayer-google-font' );
	wp_dequeue_style( 'pagelayer-google-font-header' );
	wp_dequeue_style( 'pagelayer-google-font-footer' );
	wp_dequeue_style( 'pagelayer-google-font-body' );
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

/**
 * Resolve the established NJC page names against common existing WP slugs.
 *
 * The hosting account already uses About and Contact, while the static project
 * uses About Us and Contact Us. Supporting both keeps the theme portable and
 * avoids renaming or duplicating live content.
 */
function njc_page_slugs( $key ) {
	$aliases = array(
		'about-us'  => array( 'about-us', 'about' ),
		'contact-us' => array( 'contact-us', 'contact' ),
		'privacy'   => array( 'privacy', 'privacy-policy' ),
		'terms'     => array( 'terms', 'terms-of-service' ),
	);

	return isset( $aliases[ $key ] ) ? $aliases[ $key ] : array( $key );
}

function njc_legacy_page_slug( $slug ) {
	foreach ( array( 'about-us', 'contact-us', 'privacy', 'terms', 'projects' ) as $key ) {
		if ( in_array( $slug, njc_page_slugs( $key ), true ) ) {
			return $key;
		}
	}

	return $slug;
}

function njc_is_page_key( $key ) {
	return is_page( njc_page_slugs( $key ) );
}

function njc_page_url( $slug, $fragment = '' ) {
	$page = null;
	foreach ( njc_page_slugs( $slug ) as $candidate ) {
		$page = get_page_by_path( $candidate );
		if ( $page ) {
			break;
		}
	}
	$url  = $page ? get_permalink( $page ) : home_url( '/' . trim( $slug, '/' ) . '/' );
	return $url . $fragment;
}

/**
 * Repoint the original static markup to WordPress routes and theme assets.
 */
function njc_rewrite_legacy_markup( $markup, $name = '' ) {
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
	$markup = njc_apply_copy_overrides( $markup, $name );
	$markup = njc_apply_site_link_overrides( $markup );
	$markup = njc_apply_image_overrides( $markup );

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
	echo njc_rewrite_legacy_markup( $markup, $name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted bundled theme markup.
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
	$classes = array_values( array_diff( $classes, array( 'pagelayer-body' ) ) );
	$classes[] = 'njc-authoritative-shell';

	if ( is_post_type_archive( 'njc_event' ) ) {
		$classes[] = 'events-page-body';
	}
	if ( is_front_page() || njc_is_page_key( 'about-us' ) || njc_is_page_key( 'projects' ) || njc_is_page_key( 'contact-us' ) || njc_is_page_key( 'privacy' ) || njc_is_page_key( 'terms' ) ) {
		$classes[] = 'njc-legacy-page';
	}
	return array_values( array_unique( $classes ) );
}
add_filter( 'body_class', 'njc_body_classes', PHP_INT_MAX );
