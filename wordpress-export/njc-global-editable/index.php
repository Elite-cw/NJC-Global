<?php
/**
 * Fallback template.
 *
 * @package NJC_Global
 */

get_header();
?>
<main class="legal-page">
	<div class="container legal-shell">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class(); ?>>
					<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					<?php the_excerpt(); ?>
				</article>
			<?php endwhile; ?>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<h1><?php esc_html_e( 'Nothing found', 'njc-global' ); ?></h1>
		<?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>
