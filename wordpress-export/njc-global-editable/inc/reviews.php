<?php
/** Editable reviews using WordPress permissions, revisions and Trash. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function njc_register_reviews() {
	register_post_type( 'njc_review', array(
		'labels' => array( 'name' => __( 'Reviews', 'njc-global' ), 'singular_name' => __( 'Review', 'njc-global' ), 'add_new_item' => __( 'Add Review', 'njc-global' ), 'edit_item' => __( 'Edit Review', 'njc-global' ) ),
		'public' => false, 'show_ui' => true, 'show_in_rest' => true,
		'menu_icon' => 'dashicons-format-quote',
		'supports' => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes' ),
	) );
}
add_action( 'init', 'njc_register_reviews' );

function njc_review_fields() {
	add_meta_box( 'njc-review-details', __( 'Reviewer details', 'njc-global' ), function ( $post ) {
		wp_nonce_field( 'njc_review_details', 'njc_review_nonce' );
		?><p>Use the title for the reviewer's name and the editor for the review text. Publish to display it; use Draft or Trash to hide it. Order controls its position.</p><label>Role / organization <input class="widefat" name="njc_review_role" value="<?php echo esc_attr( get_post_meta( $post->ID, '_njc_review_role', true ) ); ?>"></label><p><label>Rating <select name="njc_review_rating"><?php for ( $rating = 1; $rating <= 5; $rating++ ) : ?><option value="<?php echo esc_attr( $rating ); ?>" <?php selected( (int) ( get_post_meta( $post->ID, '_njc_review_rating', true ) ?: 5 ), $rating ); ?>><?php echo esc_html( $rating ); ?></option><?php endfor; ?></select></label></p><?php
	}, 'njc_review' );
}
add_action( 'add_meta_boxes', 'njc_review_fields' );

function njc_save_review( $post_id ) {
	if ( ! isset( $_POST['njc_review_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['njc_review_nonce'] ) ), 'njc_review_details' ) || ! current_user_can( 'edit_post', $post_id ) || wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) { return; }
	update_post_meta( $post_id, '_njc_review_role', sanitize_text_field( wp_unslash( $_POST['njc_review_role'] ?? '' ) ) );
	update_post_meta( $post_id, '_njc_review_rating', max( 1, min( 5, absint( $_POST['njc_review_rating'] ?? 5 ) ) ) );
}
add_action( 'save_post_njc_review', 'njc_save_review' );

function njc_import_existing_reviews() {
	if ( ! current_user_can( 'manage_options' ) || get_option( 'njc_reviews_imported' ) ) { return; }
	$reviews = json_decode( file_get_contents( NJC_THEME_DIR . '/inc/reviews-defaults.json' ), true );
	foreach ( $reviews ?: array() as $index => $review ) {
		$import_key = isset( $review['import_key'] ) ? (string) $review['import_key'] : (string) $index;
		$existing = get_posts( array( 'post_type' => 'njc_review', 'post_status' => 'any', 'meta_key' => '_njc_review_import_key', 'meta_value' => $import_key, 'numberposts' => 1 ) );
		if ( $existing ) { continue; }
		$id = wp_insert_post( array( 'post_type' => 'njc_review', 'post_status' => 'publish', 'post_title' => $review['name'], 'post_content' => $review['text'], 'menu_order' => $index ), true );
		if ( is_wp_error( $id ) ) { return; }
		update_post_meta( $id, '_njc_review_import_key', $import_key );
		update_post_meta( $id, '_njc_review_role', $review['role'] );
		update_post_meta( $id, '_njc_review_rating', 5 );
		if ( ! empty( $review['image'] ) ) { update_post_meta( $id, '_njc_review_image', $review['image'] ); }
	}
	update_option( 'njc_reviews_imported', 1 );
}
add_action( 'admin_init', 'njc_import_existing_reviews' );

function njc_render_reviews() {
	$reviews = get_posts( array( 'post_type' => 'njc_review', 'post_status' => 'publish', 'numberposts' => -1, 'orderby' => array( 'menu_order' => 'ASC', 'ID' => 'ASC' ) ) );
	foreach ( $reviews as $review ) {
		$name = get_the_title( $review );
		$image = get_the_post_thumbnail_url( $review->ID, 'thumbnail' );
		if ( ! $image && get_post_meta( $review->ID, '_njc_review_image', true ) ) {
			$image = NJC_THEME_URI . '/assets/images/' . get_post_meta( $review->ID, '_njc_review_image', true );
		}
		$rating = max( 1, min( 5, (int) ( get_post_meta( $review->ID, '_njc_review_rating', true ) ?: 5 ) ) );
		?><article class="testimonial-slide"><div class="testimonial-bubble"><blockquote><?php echo esc_html( wp_strip_all_tags( strip_shortcodes( $review->post_content ) ) ); ?></blockquote><span class="testimonial-stars" aria-label="<?php echo esc_attr( sprintf( '%d out of 5 stars', $rating ) ); ?>"><?php for ( $i = 0; $i < $rating; $i++ ) : ?><span class="material-symbols-outlined" aria-hidden="true">star</span><?php endfor; ?></span></div><footer class="testimonial-author"><span class="testimonial-avatar" aria-hidden="true"><?php if ( $image ) : ?><img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $name ); ?>" width="44" height="44" loading="lazy"><?php else : ?><?php echo esc_html( strtoupper( substr( $name, 0, 2 ) ) ); ?><?php endif; ?></span><span><strong><?php echo esc_html( $name ); ?></strong><small><?php echo esc_html( get_post_meta( $review->ID, '_njc_review_role', true ) ); ?></small></span></footer></article><?php
	}
}
