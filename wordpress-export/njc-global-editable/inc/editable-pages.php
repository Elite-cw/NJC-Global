<?php
/**
 * Safe opt-in support for replacing a bundled page layout with block-editor content.
 *
 * @package NJC_Global
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function njc_add_page_display_metabox() {
	add_meta_box(
		'njc-page-display',
		__( 'NJC page display', 'njc-global' ),
		'njc_render_page_display_metabox',
		'page',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes_page', 'njc_add_page_display_metabox' );

function njc_render_page_display_metabox( $post ) {
	wp_nonce_field( 'njc_save_page_display', 'njc_page_display_nonce' );
	$use_editor = (bool) get_post_meta( $post->ID, '_njc_use_editor_content', true );
	?>
	<label>
		<input type="checkbox" name="njc_use_editor_content" value="1" <?php checked( $use_editor ); ?>>
		<?php esc_html_e( 'Use the WordPress editor content instead of the bundled NJC layout', 'njc-global' ); ?>
	</label>
	<p class="description"><?php esc_html_e( 'Leave this off to preserve the supplied design. Enable it only when you want this page to use blocks from the editor.', 'njc-global' ); ?></p>
	<?php
}

function njc_save_page_display( $post_id ) {
	if ( ! isset( $_POST['njc_page_display_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['njc_page_display_nonce'] ) ), 'njc_save_page_display' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['njc_use_editor_content'] ) ) {
		update_post_meta( $post_id, '_njc_use_editor_content', '1' );
	} else {
		delete_post_meta( $post_id, '_njc_use_editor_content' );
	}
}
add_action( 'save_post_page', 'njc_save_page_display' );

function njc_page_uses_editor( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_queried_object_id();
	return $post_id && (bool) get_post_meta( $post_id, '_njc_use_editor_content', true );
}

function njc_render_editor_page() {
	while ( have_posts() ) {
		the_post();
		?>
		<main class="legal-page njc-editor-page">
			<div class="container legal-shell">
				<article <?php post_class(); ?>>
					<p class="eyebrow"><?php esc_html_e( 'NJC Global', 'njc-global' ); ?></p>
					<h1><?php the_title(); ?></h1>
					<div class="entry-content"><?php the_content(); ?></div>
				</article>
			</div>
		</main>
		<?php
	}
}
