<?php
/**
 * Not-found template.
 *
 * @package NJC_Global
 */

get_header();
?>
<main class="legal-page">
	<div class="container legal-shell">
		<p class="eyebrow">404</p>
		<h1><?php esc_html_e( 'This page could not be found.', 'njc-global' ); ?></h1>
		<p><?php esc_html_e( 'The link may have changed. Return home or explore the latest NJC work.', 'njc-global' ); ?></p>
		<a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Return home', 'njc-global' ); ?></a>
	</div>
</main>
<?php get_footer(); ?>
