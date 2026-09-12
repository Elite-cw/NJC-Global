<?php
/**
 * Server-side contact and newsletter handling.
 *
 * @package NJC_Global
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function njc_register_private_records() {
	register_post_type(
		'njc_inquiry',
		array(
			'labels' => array(
				'name'          => __( 'Inquiries', 'njc-global' ),
				'singular_name' => __( 'Inquiry', 'njc-global' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'menu_icon'    => 'dashicons-email-alt',
			'supports'     => array( 'title', 'editor' ),
			'capabilities' => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap' => true,
		)
	);

	register_post_type(
		'njc_subscriber',
		array(
			'labels' => array(
				'name'          => __( 'Subscribers', 'njc-global' ),
				'singular_name' => __( 'Subscriber', 'njc-global' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'menu_icon'    => 'dashicons-megaphone',
			'supports'     => array( 'title' ),
			'capabilities' => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap' => true,
		)
	);
}
add_action( 'init', 'njc_register_private_records' );

function njc_form_redirect( $status ) {
	$referer = wp_get_referer();
	$url     = $referer ? $referer : home_url( '/' );
	wp_safe_redirect( add_query_arg( 'njc_form', sanitize_key( $status ), $url ) );
	exit;
}

function njc_handle_subscription() {
	if ( ! isset( $_POST['njc_subscribe_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['njc_subscribe_nonce'] ) ), 'njc_subscribe' ) ) {
		njc_form_redirect( 'invalid' );
	}
	if ( ! empty( $_POST['company_website'] ) ) {
		njc_form_redirect( 'success' );
	}

	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	if ( ! is_email( $email ) ) {
		njc_form_redirect( 'invalid' );
	}

	$existing = get_posts(
		array(
			'post_type'      => 'njc_subscriber',
			'post_status'    => 'private',
			'title'          => $email,
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	if ( ! $existing ) {
		wp_insert_post(
			array(
				'post_type'   => 'njc_subscriber',
				'post_status' => 'private',
				'post_title'  => $email,
			)
		);
	}
	njc_form_redirect( 'subscribed' );
}
add_action( 'admin_post_nopriv_njc_subscribe', 'njc_handle_subscription' );
add_action( 'admin_post_njc_subscribe', 'njc_handle_subscription' );

function njc_handle_inquiry() {
	if ( ! isset( $_POST['njc_inquiry_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['njc_inquiry_nonce'] ) ), 'njc_inquiry' ) ) {
		njc_form_redirect( 'invalid' );
	}
	if ( ! empty( $_POST['company_website'] ) ) {
		njc_form_redirect( 'success' );
	}

	$name         = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email        = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$organization = isset( $_POST['organization'] ) ? sanitize_text_field( wp_unslash( $_POST['organization'] ) ) : '';
	$phone        = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$type         = isset( $_POST['inquiry_type'] ) ? sanitize_text_field( wp_unslash( $_POST['inquiry_type'] ) ) : '';
	$message      = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( ! $name || ! is_email( $email ) || ! $message ) {
		njc_form_redirect( 'invalid' );
	}

	$content = sprintf(
		"Email: %s\nOrganization: %s\nPhone: %s\nInquiry type: %s\n\n%s",
		$email,
		$organization,
		$phone,
		$type,
		$message
	);
	$inquiry_id = wp_insert_post(
		array(
			'post_type'    => 'njc_inquiry',
			'post_status'  => 'private',
			'post_title'   => sprintf( '%s - %s', $name, current_time( 'Y-m-d H:i' ) ),
			'post_content' => $content,
		)
	);

	if ( is_wp_error( $inquiry_id ) ) {
		njc_form_redirect( 'error' );
	}

	wp_mail(
		get_option( 'admin_email' ),
		sprintf( 'New NJC website inquiry from %s', $name ),
		$content,
		array( 'Reply-To: ' . $name . ' <' . $email . '>' )
	);
	njc_form_redirect( 'sent' );
}
add_action( 'admin_post_nopriv_njc_inquiry', 'njc_handle_inquiry' );
add_action( 'admin_post_njc_inquiry', 'njc_handle_inquiry' );

function njc_form_message() {
	if ( empty( $_GET['njc_form'] ) ) {
		return;
	}
	$status = sanitize_key( wp_unslash( $_GET['njc_form'] ) );
	$messages = array(
		'sent'       => __( 'Thank you. Your message has been sent.', 'njc-global' ),
		'subscribed' => __( 'Thank you. You have joined the NJC community.', 'njc-global' ),
		'invalid'    => __( 'Please check the information and try again.', 'njc-global' ),
		'error'      => __( 'The request could not be saved. Please try again.', 'njc-global' ),
	);
	if ( isset( $messages[ $status ] ) ) {
		printf( '<p class="njc-notice" role="status">%s</p>', esc_html( $messages[ $status ] ) );
	}
}
add_action( 'wp_body_open', 'njc_form_message' );
