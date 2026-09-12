<?php
/**
 * Page template with preserved NJC layouts for known pages.
 *
 * @package NJC_Global
 */

get_header();

$slug = get_post_field( 'post_name', get_queried_object_id() );
if ( njc_page_uses_editor() ) {
	njc_render_editor_page();
} elseif ( ! njc_render_legacy_part( $slug ) ) {
	?>
	<main class="legal-page">
		<div class="container legal-shell">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class(); ?>>
					<p class="eyebrow"><?php esc_html_e( 'NJC Global', 'njc-global' ); ?></p>
					<h1><?php the_title(); ?></h1>
					<div class="entry-content"><?php the_content(); ?></div>
				</article>
				<?php
			endwhile;
			?>
		</div>
	</main>
	<?php
}

get_footer();
