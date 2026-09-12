<?php
/**
 * Editable NJC event content type and fields.
 *
 * @package NJC_Global
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function njc_register_event_type() {
	$labels = array(
		'name'               => __( 'Events', 'njc-global' ),
		'singular_name'      => __( 'Event', 'njc-global' ),
		'add_new_item'       => __( 'Add New Event', 'njc-global' ),
		'edit_item'          => __( 'Edit Event', 'njc-global' ),
		'new_item'           => __( 'New Event', 'njc-global' ),
		'view_item'          => __( 'View Event', 'njc-global' ),
		'search_items'       => __( 'Search Events', 'njc-global' ),
		'not_found'          => __( 'No events found.', 'njc-global' ),
		'not_found_in_trash' => __( 'No events found in Trash.', 'njc-global' ),
	);

	register_post_type(
		'njc_event',
		array(
			'labels'       => $labels,
			'public'       => true,
			'show_in_rest' => true,
			'has_archive'  => 'events',
			'rewrite'      => array( 'slug' => 'events' ),
			'menu_icon'    => 'dashicons-calendar-alt',
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author' ),
			'menu_position' => 21,
		)
	);
}
add_action( 'init', 'njc_register_event_type' );

function njc_add_event_metabox() {
	add_meta_box(
		'njc-event-details',
		__( 'Event details', 'njc-global' ),
		'njc_render_event_metabox',
		'njc_event',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'njc_add_event_metabox' );

function njc_render_event_metabox( $post ) {
	wp_nonce_field( 'njc_save_event', 'njc_event_nonce' );
	$fields = array(
		'njc_event_start'    => array( 'label' => __( 'Start date and time', 'njc-global' ), 'type' => 'datetime-local' ),
		'njc_event_end'      => array( 'label' => __( 'End date and time', 'njc-global' ), 'type' => 'datetime-local' ),
		'njc_event_location' => array( 'label' => __( 'Location', 'njc-global' ), 'type' => 'text' ),
		'njc_event_region'   => array( 'label' => __( 'Region', 'njc-global' ), 'type' => 'text' ),
		'njc_event_type'     => array( 'label' => __( 'Event type', 'njc-global' ), 'type' => 'text' ),
		'njc_event_cta_url'  => array( 'label' => __( 'Registration or enquiry URL', 'njc-global' ), 'type' => 'url' ),
	);

	echo '<div class="njc-event-fields" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px">';
	foreach ( $fields as $key => $field ) {
		$value = get_post_meta( $post->ID, '_' . $key, true );
		printf(
			'<label style="display:grid;gap:6px"><strong>%1$s</strong><input type="%2$s" name="%3$s" value="%4$s" class="widefat"></label>',
			esc_html( $field['label'] ),
			esc_attr( $field['type'] ),
			esc_attr( $key ),
			esc_attr( $value )
		);
	}

	$status = get_post_meta( $post->ID, '_njc_event_status', true );
	?>
	<label style="display:grid;gap:6px">
		<strong><?php esc_html_e( 'Display group', 'njc-global' ); ?></strong>
		<select name="njc_event_status" class="widefat">
			<option value="upcoming" <?php selected( $status, 'upcoming' ); ?>><?php esc_html_e( 'Upcoming', 'njc-global' ); ?></option>
			<option value="featured" <?php selected( $status, 'featured' ); ?>><?php esc_html_e( 'Featured', 'njc-global' ); ?></option>
			<option value="past" <?php selected( $status, 'past' ); ?>><?php esc_html_e( 'Past event', 'njc-global' ); ?></option>
		</select>
	</label>
	</div>
	<p><?php esc_html_e( 'Use the Featured Image for the event artwork, the Excerpt for the card summary, and the main editor for the full event page.', 'njc-global' ); ?></p>
	<?php
}

function njc_save_event_fields( $post_id ) {
	if ( ! isset( $_POST['njc_event_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['njc_event_nonce'] ) ), 'njc_save_event' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$text_fields = array( 'njc_event_start', 'njc_event_end', 'njc_event_location', 'njc_event_region', 'njc_event_type', 'njc_event_status' );
	foreach ( $text_fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, '_' . $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}
	if ( isset( $_POST['njc_event_cta_url'] ) ) {
		update_post_meta( $post_id, '_njc_event_cta_url', esc_url_raw( wp_unslash( $_POST['njc_event_cta_url'] ) ) );
	}
}
add_action( 'save_post_njc_event', 'njc_save_event_fields' );

function njc_event_meta( $key, $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	return get_post_meta( $post_id, '_njc_event_' . $key, true );
}

function njc_event_date( $post_id = 0, $format = 'M j, Y' ) {
	$value = njc_event_meta( 'start', $post_id );
	if ( ! $value ) {
		return '';
	}
	$timestamp = strtotime( $value );
	return $timestamp ? wp_date( $format, $timestamp ) : $value;
}

function njc_flush_event_rules() {
	njc_register_event_type();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'njc_flush_event_rules' );
