<?php
/**
 * Protected NJC single blog-post design.
 *
 * Editors manage the post fields and article body. The theme owns the visual
 * hierarchy so every new article follows the NJC Global design system.
 *
 * @package NJC_Global
 */

get_header();
while ( have_posts() ) :
	the_post();
	$categories      = get_the_category();
	$category        = $categories ? $categories[0] : null;
	$category_name   = $category ? $category->name : __( 'Insights', 'njc-global' );
	$author_name     = get_the_author_meta( 'display_name' );
	$author_initials = strtoupper( substr( preg_replace( '/\s+/', '', $author_name ), 0, 2 ) );
	$permalink       = get_permalink();
	$linkedin_url    = 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( $permalink );
	$x_url           = 'https://twitter.com/intent/tweet?url=' . rawurlencode( $permalink ) . '&text=' . rawurlencode( get_the_title() );
	?>
	<main class="njc-article-template">
		<article <?php post_class( 'njc-article' ); ?>>
			<header class="article-header njc-article-hero">
				<div class="container">
					<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'njc-global' ); ?>">
						<ol>
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'njc-global' ); ?></a></li>
							<li><a href="<?php echo esc_url( njc_blog_url() ); ?>"><?php esc_html_e( 'Blog', 'njc-global' ); ?></a></li>
							<li aria-current="page"><?php echo esc_html( wp_trim_words( get_the_title(), 5 ) ); ?></li>
						</ol>
					</nav>
					<p class="article-category"><?php echo esc_html( $category_name ); ?></p>
					<h1><?php the_title(); ?></h1>
				</div>
			</header>

			<section class="article-section">
				<div class="container article-body">
					<div class="article-content entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages(); ?>
					</div>

					<aside class="article-author-card" aria-label="<?php esc_attr_e( 'Article author', 'njc-global' ); ?>">
						<div class="article-author-mark" aria-hidden="true"><?php echo esc_html( $author_initials ?: 'NJ' ); ?></div>
						<div><span><?php esc_html_e( 'Written for the NJC Journal by', 'njc-global' ); ?></span><strong><?php echo esc_html( $author_name ); ?></strong></div>
					</aside>

					<div class="article-share">
						<strong><?php esc_html_e( 'Share this insight', 'njc-global' ); ?></strong>
						<div>
							<a href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'LinkedIn', 'njc-global' ); ?></a>
							<a href="<?php echo esc_url( $x_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'X', 'njc-global' ); ?></a>
						</div>
					</div>

					<nav class="article-navigation" aria-label="<?php esc_attr_e( 'Article navigation', 'njc-global' ); ?>">
						<a href="<?php echo esc_url( njc_blog_url() ); ?>"><span class="material-symbols-outlined" aria-hidden="true">arrow_back</span><?php esc_html_e( 'Back to all insights', 'njc-global' ); ?></a>
						<a href="<?php echo esc_url( njc_page_url( 'contact-us', '#work-with-us' ) ); ?>"><?php esc_html_e( 'Continue the conversation', 'njc-global' ); ?><span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
					</nav>
				</div>
			</section>
		</article>
	</main>
	<?php
endwhile;
get_footer();
