<?php
/**
 * Editable WordPress posts archive.
 *
 * @package NJC_Global
 */

get_header();
$paged = max( 1, get_query_var( 'paged' ) );
$articles_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => (int) get_option( 'posts_per_page', 10 ),
		'paged'               => $paged,
		'ignore_sticky_posts' => true,
	)
);
$featured_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 1,
		'ignore_sticky_posts' => false,
	)
);
?>
<main class="blog-page">
	<section class="blog-hero">
		<div class="container">
			<div class="blog-hero-heading">
				<p class="eyebrow"><?php esc_html_e( 'The NJC Journal', 'njc-global' ); ?></p>
				<h1><?php esc_html_e( 'Ideas, Stories & Opportunities', 'njc-global' ); ?> <em><?php esc_html_e( 'Shaping Africa.', 'njc-global' ); ?></em></h1>
				<p><?php esc_html_e( 'Perspectives on African business, culture, and leadership.', 'njc-global' ); ?></p>
			</div>

			<?php if ( $featured_query->have_posts() ) : $featured_query->the_post(); $featured_categories = get_the_category(); ?>
				<article class="featured-blog-card">
					<a class="featured-blog-image" href="<?php the_permalink(); ?>"<?php if ( has_post_thumbnail() ) : ?> style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ); ?>')"<?php endif; ?> aria-label="<?php echo esc_attr( sprintf( __( 'Read %s', 'njc-global' ), get_the_title() ) ); ?>"></a>
					<div class="featured-blog-copy">
						<div class="blog-meta"><span><?php echo esc_html( $featured_categories ? $featured_categories[0]->name : __( 'Insights', 'njc-global' ) ); ?></span><small><i class="material-symbols-outlined">schedule</i> <?php echo esc_html( njc_estimated_read_time() ); ?> <?php esc_html_e( 'min read', 'njc-global' ); ?></small></div>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p><?php echo esc_html( njc_excerpt( get_the_ID(), 34 ) ); ?></p>
						<div class="blog-author-row">
							<div class="blog-author"><span><?php echo esc_html( strtoupper( substr( get_the_author_meta( 'display_name' ), 0, 2 ) ) ); ?></span><p><strong><?php the_author(); ?></strong><small><?php echo esc_html( get_the_date() ); ?></small></p></div>
							<a class="text-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read story', 'njc-global' ); ?> →</a>
						</div>
					</div>
				</article>
			<?php else : ?>
				<p class="blog-empty"><?php esc_html_e( 'Publish the first post to feature it here.', 'njc-global' ); ?></p>
			<?php endif; wp_reset_postdata(); ?>
		</div>
	</section>

	<section class="blog-dispatches" id="latest-dispatches">
		<div class="container">
			<header class="blog-section-heading"><div><p class="eyebrow"><?php esc_html_e( 'Fresh perspectives', 'njc-global' ); ?></p><h2><?php esc_html_e( 'Latest Dispatches', 'njc-global' ); ?></h2></div><span><?php esc_html_e( 'From the NJC editorial team', 'njc-global' ); ?></span></header>
			<div class="blog-tools" aria-label="<?php esc_attr_e( 'Browse and search articles', 'njc-global' ); ?>">
				<div class="blog-tools-inner">
					<div class="blog-categories" role="group" aria-label="<?php esc_attr_e( 'Filter articles by category', 'njc-global' ); ?>">
						<button class="active" type="button" data-njc-content-filter="all"><?php esc_html_e( 'All Articles', 'njc-global' ); ?></button>
						<?php foreach ( get_categories( array( 'hide_empty' => true ) ) as $category ) : ?>
							<button type="button" data-njc-content-filter="<?php echo esc_attr( $category->slug ); ?>"><?php echo esc_html( $category->name ); ?></button>
						<?php endforeach; ?>
					</div>
					<label class="blog-search"><span class="material-symbols-outlined" aria-hidden="true">search</span><span class="sr-only"><?php esc_html_e( 'Search articles', 'njc-global' ); ?></span><input data-njc-content-search type="search" placeholder="<?php esc_attr_e( 'Search articles...', 'njc-global' ); ?>" autocomplete="off"></label>
				</div>
			</div>

			<div class="blog-grid" id="blog-grid">
				<?php if ( $articles_query->have_posts() ) : while ( $articles_query->have_posts() ) : $articles_query->the_post(); $categories = get_the_category(); $category = $categories ? $categories[0] : null; ?>
					<article class="blog-card" data-njc-content-card data-category="<?php echo esc_attr( $category ? $category->slug : 'uncategorized' ); ?>" data-search="<?php echo esc_attr( strtolower( get_the_title() . ' ' . wp_strip_all_tags( get_the_excerpt() ) . ' ' . get_the_author() ) ); ?>">
						<a class="blog-card-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Read %s', 'njc-global' ), get_the_title() ) ); ?>">
							<div class="blog-card-image"<?php if ( has_post_thumbnail() ) : ?> style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ); ?>')"<?php endif; ?>><span><?php echo esc_html( $category ? $category->name : __( 'Insights', 'njc-global' ) ); ?></span></div>
							<div class="blog-card-copy"><h3><?php the_title(); ?></h3><p><?php echo esc_html( njc_excerpt() ); ?></p><span class="blog-read-link"><?php esc_html_e( 'Read article', 'njc-global' ); ?> <span class="material-symbols-outlined" aria-hidden="true">arrow_outward</span></span><footer><span><?php echo esc_html( sprintf( __( 'By %s', 'njc-global' ), get_the_author() ) ); ?></span><small><?php echo esc_html( njc_estimated_read_time() ); ?> <?php esc_html_e( 'min read', 'njc-global' ); ?></small></footer></div>
						</a>
					</article>
				<?php endwhile; endif; wp_reset_postdata(); ?>
			</div>
			<p class="blog-empty" data-njc-content-empty hidden><?php esc_html_e( 'No articles match your current search and category.', 'njc-global' ); ?></p>
			<div class="pagination"><?php echo wp_kses_post( paginate_links( array( 'current' => $paged, 'total' => $articles_query->max_num_pages, 'mid_size' => 1 ) ) ); ?></div>
		</div>
	</section>

	<section class="blog-newsletter">
		<div class="container blog-newsletter-inner">
			<div><p class="eyebrow"><?php esc_html_e( 'NJC Insider', 'njc-global' ); ?></p><h2><?php esc_html_e( 'Selected insights, sent to you.', 'njc-global' ); ?></h2><p><?php esc_html_e( 'A weekly briefing on African talent and markets.', 'njc-global' ); ?></p></div>
			<form data-njc-server-form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post"><label class="sr-only" for="blog-email"><?php esc_html_e( 'Your corporate email address', 'njc-global' ); ?></label><input id="blog-email" name="email" type="email" placeholder="<?php esc_attr_e( 'Your corporate email address', 'njc-global' ); ?>" required><button class="button light" type="submit"><?php esc_html_e( 'Subscribe now', 'njc-global' ); ?></button><input type="hidden" name="action" value="njc_subscribe"><?php wp_nonce_field( 'njc_subscribe', 'njc_subscribe_nonce' ); ?><label class="njc-form-trap">Website<input name="company_website" type="text" tabindex="-1"></label></form>
		</div>
	</section>
</main>
<?php get_footer(); ?>
