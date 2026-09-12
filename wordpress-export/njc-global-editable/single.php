<?php
/**
 * Single blog post template. Content is editable; layout is fixed by the theme.
 *
 * @package NJC_Global
 */

get_header();
while ( have_posts() ) : the_post();
	$categories = get_the_category();
	?>
	<main>
		<article>
			<header class="article-header">
				<div class="container">
					<p class="eyebrow"><a href="<?php echo esc_url( njc_blog_url() ); ?>"><?php esc_html_e( 'NJC Journal', 'njc-global' ); ?></a> / <?php echo esc_html( $categories ? $categories[0]->name : __( 'Insights', 'njc-global' ) ); ?></p>
					<h1><?php the_title(); ?></h1>
					<p class="article-deck"><?php echo esc_html( njc_excerpt( get_the_ID(), 40 ) ); ?></p>
					<div class="article-meta"><span><?php the_author(); ?></span><span><?php echo esc_html( get_the_date() ); ?></span><span><?php echo esc_html( njc_estimated_read_time() ); ?> <?php esc_html_e( 'min read', 'njc-global' ); ?></span></div>
				</div>
			</header>
			<section class="article-section"><div class="container article-body entry-content"><?php if ( has_post_thumbnail() ) the_post_thumbnail( 'full' ); ?><?php the_content(); ?><nav class="article-navigation" aria-label="<?php esc_attr_e( 'Article navigation', 'njc-global' ); ?>"><a href="<?php echo esc_url( njc_blog_url() ); ?>">← <?php esc_html_e( 'Back to all insights', 'njc-global' ); ?></a><a href="<?php echo esc_url( njc_page_url( 'contact-us', '#work-with-us' ) ); ?>"><?php esc_html_e( 'Continue the conversation', 'njc-global' ); ?> →</a></nav></div></section>
		</article>
	</main>
	<?php
endwhile;
get_footer();
