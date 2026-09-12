<?php
/**
 * Single event template. Owner content fills a protected layout.
 *
 * @package NJC_Global
 */

get_header();
while ( have_posts() ) : the_post();
	$cta_url = njc_event_meta( 'cta_url' ) ?: njc_page_url( 'contact-us', '#work-with-us' );
	?>
	<main class="event-details-page">
		<section class="event-details-hero">
			<div class="container event-details-grid">
				<div>
					<a class="event-back-link" href="<?php echo esc_url( njc_events_url() ); ?>">← <?php esc_html_e( 'Back to Events', 'njc-global' ); ?></a>
					<p class="eyebrow"><?php echo esc_html( njc_event_meta( 'type' ) ?: __( 'NJC Global Event', 'njc-global' ) ); ?></p>
					<h1><?php the_title(); ?></h1>
					<p class="event-details-lead"><?php echo esc_html( njc_excerpt( get_the_ID(), 45 ) ); ?></p>
					<div class="event-detail-meta">
						<?php if ( njc_event_date() ) : ?><span><span class="material-symbols-outlined">calendar_today</span> <?php echo esc_html( njc_event_date( get_the_ID(), 'M j, Y · g:i a' ) ); ?></span><?php endif; ?>
						<?php if ( njc_event_meta( 'location' ) ) : ?><span><span class="material-symbols-outlined">location_on</span> <?php echo esc_html( njc_event_meta( 'location' ) ); ?></span><?php endif; ?>
					</div>
					<a class="button" href="<?php echo esc_url( $cta_url ); ?>"><?php esc_html_e( 'Register Your Interest', 'njc-global' ); ?></a>
				</div>
				<div class="event-detail-mockup"><div class="event-detail-image"<?php if ( has_post_thumbnail() ) : ?> style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ); ?>')"<?php endif; ?>></div></div>
			</div>
		</section>
		<section class="event-information"><div class="container entry-content"><?php the_content(); ?></div></section>
	</main>
	<?php
endwhile;
get_footer();
