<?php
/**
 * Editable Events archive.
 *
 * @package NJC_Global
 */

get_header();
$events = new WP_Query(
	array(
		'post_type'      => 'njc_event',
		'post_status'    => 'publish',
		'posts_per_page' => 50,
		'meta_key'       => '_njc_event_start',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
	)
);
?>
<main class="events-page">
	<section class="events-hero">
		<div class="events-hero-image" role="img" aria-label="<?php esc_attr_e( 'A prestigious business event with people connecting', 'njc-global' ); ?>"></div>
		<div class="events-hero-badge" aria-hidden="true"><span class="material-symbols-outlined">event_available</span><small><?php esc_html_e( 'Curated encounters', 'njc-global' ); ?></small></div>
		<div class="container events-hero-copy"><p class="eyebrow"><?php esc_html_e( 'NJC Global Events', 'njc-global' ); ?></p><h1><?php esc_html_e( 'Where Ideas, People and', 'njc-global' ); ?> <em><?php esc_html_e( 'Opportunity', 'njc-global' ); ?></em> <?php esc_html_e( 'Meet.', 'njc-global' ); ?></h1></div>
	</section>

	<section class="events-browser reveal-event">
		<div class="container">
			<div class="events-tabs" role="group" aria-label="<?php esc_attr_e( 'Filter events', 'njc-global' ); ?>">
				<button class="tab-button active" type="button" data-njc-content-filter="all"><?php esc_html_e( 'All Events', 'njc-global' ); ?></button>
				<button class="tab-button" type="button" data-njc-content-filter="upcoming"><?php esc_html_e( 'Upcoming', 'njc-global' ); ?></button>
				<button class="tab-button" type="button" data-njc-content-filter="featured"><?php esc_html_e( 'Featured', 'njc-global' ); ?></button>
				<button class="tab-button" type="button" data-njc-content-filter="past"><?php esc_html_e( 'Past Events', 'njc-global' ); ?></button>
				<div class="event-search-tools"><label class="event-search-box"><span class="material-symbols-outlined">search</span><input data-njc-content-search type="search" placeholder="<?php esc_attr_e( 'Search events by name or location', 'njc-global' ); ?>" aria-label="<?php esc_attr_e( 'Search events', 'njc-global' ); ?>"></label></div>
			</div>

			<div class="compact-event-list">
				<?php if ( $events->have_posts() ) : while ( $events->have_posts() ) : $events->the_post(); $region = njc_event_meta( 'region' ); $status = njc_event_meta( 'status' ) ?: 'upcoming'; ?>
					<article class="compact-event-card event-filter-card" data-njc-content-card data-category="<?php echo esc_attr( $status ); ?>" data-search="<?php echo esc_attr( strtolower( get_the_title() . ' ' . njc_excerpt() . ' ' . $region . ' ' . njc_event_meta( 'location' ) ) ); ?>">
						<a class="compact-event-thumb" href="<?php the_permalink(); ?>"<?php if ( has_post_thumbnail() ) : ?> style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ) ); ?>')"<?php endif; ?> aria-label="<?php echo esc_attr( get_the_title() ); ?>"></a>
						<div class="compact-event-copy"><span class="event-type"><?php echo esc_html( njc_event_meta( 'type' ) ?: __( 'NJC Event', 'njc-global' ) ); ?></span><h3><?php the_title(); ?></h3><p><?php echo esc_html( njc_event_date() ); ?> <i></i> <?php echo esc_html( njc_event_meta( 'location' ) ); ?></p></div>
						<a class="compact-event-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View Details', 'njc-global' ); ?> <span class="material-symbols-outlined">arrow_outward</span></a>
					</article>
				<?php endwhile; else : ?>
					<p><?php esc_html_e( 'No events are published yet. Add the first event from the WordPress dashboard.', 'njc-global' ); ?></p>
				<?php endif; wp_reset_postdata(); ?>
			</div>
			<p class="filter-empty" data-njc-content-empty hidden><?php esc_html_e( 'No events match the current filters.', 'njc-global' ); ?></p>
		</div>
	</section>

	<section class="events-cta">
		<div class="container"><div class="events-cta-icon"><span class="material-symbols-outlined">celebration</span></div><div><h2><?php esc_html_e( 'Planning a high-stakes event?', 'njc-global' ); ?></h2><p><?php esc_html_e( 'Leverage our network and operational experience to create an event with lasting impact.', 'njc-global' ); ?></p></div><a class="button" href="<?php echo esc_url( njc_page_url( 'contact-us', '#work-with-us' ) ); ?>"><?php esc_html_e( 'Plan Your Event With Us', 'njc-global' ); ?></a></div>
	</section>
</main>
<?php get_footer(); ?>
