<?php
/**
 * Editable NJC content, brand, links, and imagery.
 *
 * @package NJC_Global
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function njc_global_copy_fields() {
	return array(
		'loader_label' => array( 'label' => __( 'Loading message', 'njc-global' ), 'default' => 'Preparing your experience', 'type' => 'text' ),
		'nav_cta_label' => array( 'label' => __( 'Header button label', 'njc-global' ), 'default' => 'Work with us', 'type' => 'text' ),
		'footer_tagline' => array( 'label' => __( 'Footer introduction', 'njc-global' ), 'default' => 'Pioneering African excellence through media, events, and talent execution.', 'type' => 'textarea' ),
		'footer_newsletter_title' => array( 'label' => __( 'Newsletter heading', 'njc-global' ), 'default' => 'Stay Connected', 'type' => 'text' ),
		'footer_newsletter_intro' => array( 'label' => __( 'Newsletter introduction', 'njc-global' ), 'default' => 'Join our inner circle for updates on African excellence.', 'type' => 'textarea' ),
	);
}

function njc_get_copy( $key ) {
	$fields = njc_global_copy_fields();
	return isset( $fields[ $key ] ) ? get_theme_mod( 'njc_copy_' . $key, $fields[ $key ]['default'] ) : '';
}

function njc_legacy_page_definitions() {
	return array(
		'front-page' => __( 'Home Page Text', 'njc-global' ),
		'about-us' => __( 'About Page Text', 'njc-global' ),
		'projects' => __( 'Projects Page Text', 'njc-global' ),
		'contact-us' => __( 'Contact Page Text', 'njc-global' ),
		'privacy' => __( 'Privacy Page Text', 'njc-global' ),
		'terms' => __( 'Terms Page Text', 'njc-global' ),
	);
}

function njc_editable_copy_pattern() {
	return '#<(h[1-6]|p|dt|dd|small|strong|figcaption|blockquote)([^>]*)>(.*?)</\\1>#is';
}

function njc_copy_inner_is_safe( $inner ) {
	if ( '' === trim( wp_strip_all_tags( $inner ) ) || false !== strpos( $inner, '<?php' ) ) {
		return false;
	}

	$without_safe_markup = preg_replace( '#</?(?:em|strong|br)\\b[^>]*>#i', '', $inner );
	return false === strpos( $without_safe_markup, '<' );
}

function njc_page_copy_fields( $page ) {
	static $cache = array();
	if ( isset( $cache[ $page ] ) ) {
		return $cache[ $page ];
	}

	$file = NJC_THEME_DIR . '/template-parts/legacy/' . sanitize_file_name( $page ) . '.php';
	if ( ! file_exists( $file ) ) {
		return array();
	}

	$source = file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local bundled template.
	preg_match_all( njc_editable_copy_pattern(), $source, $matches, PREG_SET_ORDER );
	$fields = array();

	foreach ( $matches as $index => $match ) {
		$inner = trim( $match[3] );
		if ( ! njc_copy_inner_is_safe( $inner ) ) {
			continue;
		}

		$plain = trim( preg_replace( '/\\s+/', ' ', html_entity_decode( wp_strip_all_tags( $inner ), ENT_QUOTES, 'UTF-8' ) ) );
		$key = 'njc_page_copy_' . str_replace( '-', '_', $page ) . '_' . $index . '_' . substr( md5( $inner ), 0, 8 );
		$fields[ $index ] = array(
			'id' => $key,
			'tag' => strtolower( $match[1] ),
			'label' => strtoupper( $match[1] ) . ': ' . wp_html_excerpt( $plain, 68, '…' ),
			'default' => html_entity_decode( $inner, ENT_QUOTES, 'UTF-8' ),
		);
	}

	$cache[ $page ] = $fields;
	return $fields;
}

function njc_sanitize_editable_copy( $value ) {
	return wp_kses( $value, array( 'em' => array(), 'strong' => array(), 'br' => array() ) );
}

function njc_apply_copy_overrides( $markup, $page = '' ) {
	$fields = njc_page_copy_fields( $page );
	if ( empty( $fields ) ) {
		return $markup;
	}

	$index = -1;
	return preg_replace_callback(
		njc_editable_copy_pattern(),
		function ( $match ) use ( &$index, $fields ) {
			++$index;
			if ( ! isset( $fields[ $index ] ) ) {
				return $match[0];
			}

			$field = $fields[ $index ];
			$value = get_theme_mod( $field['id'], $field['default'] );
			if ( $value === $field['default'] ) {
				return $match[0];
			}

			return '<' . $match[1] . $match[2] . '>' . njc_sanitize_editable_copy( $value ) . '</' . $match[1] . '>';
		},
		$markup
	);
}

function njc_brand_colors() {
	return array(
		'green' => array( 'label' => __( 'Primary green', 'njc-global' ), 'default' => '#064e3b' ),
		'green2' => array( 'label' => __( 'Secondary green', 'njc-global' ), 'default' => '#0b684f' ),
		'ink' => array( 'label' => __( 'Heading and ink', 'njc-global' ), 'default' => '#14352c' ),
		'cream' => array( 'label' => __( 'Cream background', 'njc-global' ), 'default' => '#f8f5ed' ),
		'gold_deep' => array( 'label' => __( 'Gold on light backgrounds', 'njc-global' ), 'default' => '#b67d35' ),
		'gold_bright' => array( 'label' => __( 'Gold on dark backgrounds', 'njc-global' ), 'default' => '#feaf35' ),
		'gold_soft' => array( 'label' => __( 'Soft gold on darkest green', 'njc-global' ), 'default' => '#ffcf88' ),
		'muted' => array( 'label' => __( 'Muted body text', 'njc-global' ), 'default' => '#64716c' ),
	);
}

function njc_contact_fields() {
	return array(
		'email' => array( 'label' => __( 'Public email address', 'njc-global' ), 'default' => 'info@njcglobal.site', 'type' => 'email', 'sanitize' => 'sanitize_email' ),
		'phone' => array( 'label' => __( 'Public phone number', 'njc-global' ), 'default' => '+234 701 315 8699', 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
		'linkedin' => array( 'label' => __( 'LinkedIn URL', 'njc-global' ), 'default' => 'https://www.linkedin.com/company/njc-global/', 'type' => 'url', 'sanitize' => 'esc_url_raw' ),
		'instagram' => array( 'label' => __( 'Instagram URL', 'njc-global' ), 'default' => 'https://www.instagram.com/njcglobal?stkn=MWZxZnlibXY5bG5xZQ==', 'type' => 'url', 'sanitize' => 'esc_url_raw' ),
		'x' => array( 'label' => __( 'X / Twitter URL', 'njc-global' ), 'default' => '', 'type' => 'url', 'sanitize' => 'esc_url_raw' ),
	);
}

function njc_get_contact( $key ) {
	$fields = njc_contact_fields();
	return isset( $fields[ $key ] ) ? get_theme_mod( 'njc_contact_' . $key, $fields[ $key ]['default'] ) : '';
}

function njc_image_fields() {
	return array(
		'header_logo' => array( 'label' => __( 'Header logo', 'njc-global' ), 'section' => 'njc_brand_images', 'selector' => '.brand-logo' ),
		'footer_logo' => array( 'label' => __( 'Footer logo', 'njc-global' ), 'section' => 'njc_brand_images', 'selector' => 'body>footer .brand-logo' ),
		'home_hero_main' => array( 'label' => __( 'Home hero main image', 'njc-global' ), 'section' => 'njc_home_images', 'selector' => '.hero-main', 'asset' => 'home-front.jpg', 'overlay' => 'linear-gradient(180deg,transparent 45%,rgba(2,42,31,.6))' ),
		'home_hero_small' => array( 'label' => __( 'Home hero supporting image', 'njc-global' ), 'section' => 'njc_home_images', 'selector' => '.hero-small', 'asset' => 'home-back.jpg' ),
		'home_talent' => array( 'label' => __( 'Talent capability image', 'njc-global' ), 'section' => 'njc_home_images', 'selector' => '.talent' ),
		'home_media' => array( 'label' => __( 'Content capability image', 'njc-global' ), 'section' => 'njc_home_images', 'selector' => '.media' ),
		'home_event' => array( 'label' => __( 'Events capability image', 'njc-global' ), 'section' => 'njc_home_images', 'selector' => '.event' ),
		'home_brand' => array( 'label' => __( 'Brand growth capability image', 'njc-global' ), 'section' => 'njc_home_images', 'selector' => '.brand-growth' ),
		'work_1' => array( 'label' => __( 'Selected work image 1', 'njc-global' ), 'section' => 'njc_home_images', 'selector' => '.work-one', 'asset' => 'events/nzuko-africa-2026.jpg' ),
		'work_2' => array( 'label' => __( 'Selected work image 2', 'njc-global' ), 'section' => 'njc_home_images', 'selector' => '.work-two', 'asset' => 'nia-nexus-project.jpg' ),
		'work_3' => array( 'label' => __( 'Selected work image 3', 'njc-global' ), 'section' => 'njc_home_images', 'selector' => '.work-three', 'asset' => 'events/pawes-2025.jpg' ),
		'work_4' => array( 'label' => __( 'Selected work image 4', 'njc-global' ), 'section' => 'njc_home_images', 'selector' => '.work-four', 'asset' => 'partners/renewberry.png' ),
		'work_5' => array( 'label' => __( 'Selected work image 5', 'njc-global' ), 'section' => 'njc_home_images', 'selector' => '.work-five', 'asset' => 'cbw-zimbabwe-group.jpg' ),
		'work_6' => array( 'label' => __( 'Selected work image 6', 'njc-global' ), 'section' => 'njc_home_images', 'selector' => '.work-six', 'asset' => 'partners/the-afrikafora.png' ),
		'about_left' => array( 'label' => __( 'About hero left image', 'njc-global' ), 'section' => 'njc_about_images', 'selector' => '.about-photo-event', 'asset' => 'about-joyce-speaking.jpg' ),
		'about_right' => array( 'label' => __( 'About hero right image', 'njc-global' ), 'section' => 'njc_about_images', 'selector' => '.about-photo-women', 'asset' => 'about-womens-leadership-summit.jpg' ),
		'team_joyce' => array( 'label' => __( 'Joyce portrait', 'njc-global' ), 'section' => 'njc_people_images', 'asset' => 'team/joycepic1.jpg' ),
		'partner_angeline' => array( 'label' => __( 'Angeline Mhlanga portrait', 'njc-global' ), 'section' => 'njc_people_images', 'asset' => 'partners/angeline-mhlanga.jpg' ),
		'partner_winifred' => array( 'label' => __( 'Winifred Gaillard portrait', 'njc-global' ), 'section' => 'njc_people_images', 'asset' => 'partners/winifred-uloaku-gaillard.jpg' ),
		'partner_jimi' => array( 'label' => __( 'Jimi Obawole portrait', 'njc-global' ), 'section' => 'njc_people_images' ),
		'team_daniel_friday' => array( 'label' => __( 'Daniel Ugochi portrait', 'njc-global' ), 'section' => 'njc_people_images', 'asset' => 'team/daniel-friday.jpg' ),
		'team_daniel_nwezeh' => array( 'label' => __( 'Sullivan Agbata portrait', 'njc-global' ), 'section' => 'njc_people_images', 'asset' => 'team/sullivan-agbata.jpg' ),
		'partner_abongwa' => array( 'label' => __( 'Abongwa Celestin portrait', 'njc-global' ), 'section' => 'njc_people_images', 'asset' => 'team/abongwa-celestin.jpg' ),
		'logo_afrikafora' => array( 'label' => __( 'The AfrikaFora logo', 'njc-global' ), 'section' => 'njc_partner_images', 'asset' => 'partners/the-afrikafora.png' ),
		'logo_cbw' => array( 'label' => __( 'CBW Africa Zimbabwe logo', 'njc-global' ), 'section' => 'njc_partner_images', 'asset' => 'partners/cbw-africa-zimbabwe.jpg' ),
		'logo_nia' => array( 'label' => __( 'Nia Nexus logo', 'njc-global' ), 'section' => 'njc_partner_images', 'asset' => 'partners/nia-nexus.png' ),
		'logo_renewberry' => array( 'label' => __( 'Renewberry logo', 'njc-global' ), 'section' => 'njc_partner_images', 'asset' => 'partners/renewberry.png' ),
		'logo_africa_women' => array( 'label' => __( 'Africa for Africa Women logo', 'njc-global' ), 'section' => 'njc_partner_images', 'asset' => 'partners/africa-for-africa-women.png' ),
		'events_hero' => array( 'label' => __( 'Events hero image', 'njc-global' ), 'section' => 'njc_page_images', 'selector' => '.events-hero-image', 'asset' => 'events/african-summit-hero.jpg', 'overlay' => 'linear-gradient(90deg,rgba(3,39,30,.82),rgba(3,39,30,.38) 62%,rgba(3,39,30,.15)),linear-gradient(180deg,rgba(3,39,30,.08) 25%,rgba(3,39,30,.62))' ),
		'projects_hero' => array( 'label' => __( 'Projects hero image', 'njc-global' ), 'section' => 'njc_page_images', 'selector' => '.projects-hero-art', 'asset' => 'projects-summit.jpg', 'overlay' => 'linear-gradient(180deg,rgba(6,78,59,.02) 35%,rgba(3,45,34,.88))' ),
		'contact_alternate' => array( 'label' => __( 'Contact alternate image', 'njc-global' ), 'section' => 'njc_page_images', 'selector' => '.contact-image-card>div::after', 'asset' => 'contact-womens-leadership.jpg', 'overlay' => 'linear-gradient(180deg,transparent 35%,rgba(3,44,32,.88))' ),
		'contact_office' => array( 'label' => __( 'Contact office image', 'njc-global' ), 'section' => 'njc_page_images', 'selector' => '.contact-image-card>div', 'asset' => 'home-back.jpg', 'overlay' => 'linear-gradient(180deg,transparent 35%,rgba(3,44,32,.88))' ),
	);
}

function njc_get_image( $key ) {
	$fields = njc_image_fields();
	if ( ! isset( $fields[ $key ] ) ) {
		return '';
	}
	$default = isset( $fields[ $key ]['asset'] ) ? NJC_THEME_URI . '/assets/images/' . $fields[ $key ]['asset'] : '';
	return get_theme_mod( 'njc_image_' . $key, $default );
}

function njc_people_fields() {
	return array(
		'joyce_name' => array( 'label' => __( 'Joyce name', 'njc-global' ), 'default' => 'Joyce Chiamaka Nwezeh' ),
		'joyce_role' => array( 'label' => __( 'Joyce role', 'njc-global' ), 'default' => 'Founder & Managing Partner' ),
		'daniel_friday_name' => array( 'label' => __( 'Daniel Ugochi name', 'njc-global' ), 'default' => 'Daniel Ugochi' ),
		'daniel_friday_role' => array( 'label' => __( 'Daniel Ugochi role', 'njc-global' ), 'default' => 'Team' ),
		'daniel_nwezeh_name' => array( 'label' => __( 'Sullivan Agbata name', 'njc-global' ), 'default' => 'Sullivan Agbata' ),
		'daniel_nwezeh_role' => array( 'label' => __( 'Sullivan Agbata role', 'njc-global' ), 'default' => 'Team' ),
		'jimi_location' => array( 'label' => __( 'Jimi Obawole location', 'njc-global' ), 'default' => 'US' ),
		'jimi_name' => array( 'label' => __( 'Jimi Obawole name', 'njc-global' ), 'default' => 'Jimi Obawole' ),
		'jimi_role' => array( 'label' => __( 'Jimi Obawole role', 'njc-global' ), 'default' => 'Founder, Nia Nexus Africa' ),
		'winifred_location' => array( 'label' => __( 'Winifred Gaillard location', 'njc-global' ), 'default' => 'France' ),
		'winifred_name' => array( 'label' => __( 'Winifred Gaillard name', 'njc-global' ), 'default' => 'Winifred Gaillard' ),
		'winifred_role' => array( 'label' => __( 'Winifred Gaillard role', 'njc-global' ), 'default' => 'Founder, The AfrikaFora' ),
		'angeline_location' => array( 'label' => __( 'Angeline Mhlanga location', 'njc-global' ), 'default' => 'South Africa' ),
		'angeline_name' => array( 'label' => __( 'Angeline Mhlanga name', 'njc-global' ), 'default' => 'Angeline Mhlanga' ),
		'angeline_role' => array( 'label' => __( 'Angeline Mhlanga role', 'njc-global' ), 'default' => 'Country Director, CBW Africa, Zimbabwe Chapter' ),
		'abongwa_name' => array( 'label' => __( 'Abongwa Celestin name', 'njc-global' ), 'default' => 'Abongwa Celestin' ),
		'abongwa_role' => array( 'label' => __( 'Abongwa Celestin role', 'njc-global' ), 'default' => 'Founder & Visionary Leader, RenewBerry' ),
		'abongwa_location' => array( 'label' => __( 'Abongwa location', 'njc-global' ), 'default' => 'Cameroon' ),
		'abongwa_bio' => array( 'label' => __( 'Abongwa biography', 'njc-global' ), 'default' => 'Empowering creators and spreading hope through visual storytelling.', 'type' => 'textarea' ),
	);
}

function njc_get_person_text( $key ) {
	$fields = njc_people_fields();
	return isset( $fields[ $key ] ) ? get_theme_mod( 'njc_person_' . $key, $fields[ $key ]['default'] ) : '';
}

function njc_people_directory() {
	return array(
		'team' => array(
			array( 'id' => 'team-joyce-chiamaka-nwezeh', 'name' => njc_get_person_text( 'joyce_name' ), 'role' => njc_get_person_text( 'joyce_role' ), 'image' => njc_get_image( 'team_joyce' ), 'imageAlt' => __( 'Portrait of Joyce Chiamaka Nwezeh', 'njc-global' ), 'width' => 4016, 'height' => 5572 ),
			array( 'id' => 'team-daniel-friday', 'name' => njc_get_person_text( 'daniel_friday_name' ), 'role' => njc_get_person_text( 'daniel_friday_role' ), 'image' => njc_get_image( 'team_daniel_friday' ), 'imageAlt' => __( 'Profile portrait for Daniel Ugochi', 'njc-global' ), 'width' => 1254, 'height' => 1254 ),
			array( 'id' => 'team-daniel-nwezeh', 'name' => njc_get_person_text( 'daniel_nwezeh_name' ), 'role' => njc_get_person_text( 'daniel_nwezeh_role' ), 'image' => njc_get_image( 'team_daniel_nwezeh' ), 'imageAlt' => __( 'Profile portrait for Sullivan Agbata', 'njc-global' ), 'width' => 1254, 'height' => 1254 ),
		),
		'partners' => array(
            array( 'id' => 'partner-jimi-obawole', 'name' => njc_get_person_text( 'jimi_name' ), 'role' => njc_get_person_text( 'jimi_role' ), 'location' => njc_get_person_text( 'jimi_location' ), 'image' => njc_get_image( 'partner_jimi' ), 'imageAlt' => __( 'Portrait of Jimi Obawole', 'njc-global' ), 'width' => 200, 'height' => 200 ),
            array( 'id' => 'partner-winifred-gaillard', 'name' => njc_get_person_text( 'winifred_name' ), 'role' => njc_get_person_text( 'winifred_role' ), 'location' => njc_get_person_text( 'winifred_location' ), 'image' => njc_get_image( 'partner_winifred' ), 'imageAlt' => __( 'Portrait of Winifred Gaillard', 'njc-global' ), 'width' => 200, 'height' => 200 ),
            array( 'id' => 'partner-angeline-mhlanga', 'name' => njc_get_person_text( 'angeline_name' ), 'role' => njc_get_person_text( 'angeline_role' ), 'location' => njc_get_person_text( 'angeline_location' ), 'image' => njc_get_image( 'partner_angeline' ), 'imageAlt' => __( 'Portrait of Angeline Mhlanga', 'njc-global' ), 'width' => 1070, 'height' => 1337 ),
        ),
	);
}

function njc_apply_site_link_overrides( $markup ) {
	$email = njc_get_contact( 'email' );
	$phone = njc_get_contact( 'phone' );
	$phone_href = preg_replace( '/[^0-9+]/', '', $phone );
	$replacements = array(
		'https://www.linkedin.com/company/njc-global/' => esc_url( njc_get_contact( 'linkedin' ) ),
		'https://www.instagram.com/njcglobal?stkn=MWZxZnlibXY5bG5xZQ==' => esc_url( njc_get_contact( 'instagram' ) ),
		'mailto:info@njcglobal.site' => 'mailto:' . antispambot( sanitize_email( $email ) ),
		'info@njcglobal.site' => antispambot( sanitize_email( $email ) ),
		'tel:+2347013158699' => 'tel:' . $phone_href,
		'+234 701 315 8699' => esc_html( $phone ),
	);

	$x_url = njc_get_contact( 'x' );
	if ( $x_url ) {
		$markup = str_replace( 'href="#" aria-label="X"', 'href="' . esc_url( $x_url ) . '" target="_blank" rel="noopener noreferrer" aria-label="X"', $markup );
	} else {
		$markup = str_replace( '<a href="#" aria-label="X">X</a>', '', $markup );
	}

	return str_replace( array_keys( $replacements ), array_values( $replacements ), $markup );
}

function njc_apply_image_overrides( $markup ) {
	foreach ( njc_image_fields() as $key => $field ) {
		if ( empty( $field['asset'] ) ) {
			continue;
		}
		$default = NJC_THEME_URI . '/assets/images/' . $field['asset'];
		$value = njc_get_image( $key );
		if ( $value && $value !== $default ) {
			$markup = str_replace( $default, esc_url( $value ), $markup );
		}
	}
	return $markup;
}

function njc_customize_register( $wp_customize ) {
	$wp_customize->add_panel( 'njc_theme_options', array( 'title' => __( 'NJC Theme Options', 'njc-global' ), 'priority' => 30 ) );
	$wp_customize->add_section( 'njc_brand_colors', array( 'title' => __( 'Brand Colours', 'njc-global' ), 'panel' => 'njc_theme_options', 'description' => __( 'Shared contrast-aware NJC colour tokens.', 'njc-global' ) ) );
	$wp_customize->add_section( 'njc_global_content', array( 'title' => __( 'Header, Loader & Footer Text', 'njc-global' ), 'panel' => 'njc_theme_options' ) );
	$wp_customize->add_section( 'njc_contact_links', array( 'title' => __( 'Contact & Social Links', 'njc-global' ), 'panel' => 'njc_theme_options' ) );

	foreach ( njc_legacy_page_definitions() as $page => $title ) {
		$wp_customize->add_section( 'njc_text_' . str_replace( '-', '_', $page ), array( 'title' => $title, 'panel' => 'njc_theme_options', 'description' => __( 'Edit every heading and body paragraph without changing the approved layout. The em tag preserves italic accent words.', 'njc-global' ) ) );
	}

	$image_sections = array(
		'njc_brand_images' => __( 'Logo Images', 'njc-global' ),
		'njc_home_images' => __( 'Home Page Images', 'njc-global' ),
		'njc_about_images' => __( 'About Page Images', 'njc-global' ),
		'njc_people_images' => __( 'Team & Partner Portraits', 'njc-global' ),
		'njc_partner_images' => __( 'Partner Logos', 'njc-global' ),
		'njc_page_images' => __( 'Projects & Contact Images', 'njc-global' ),
	);
	foreach ( $image_sections as $id => $title ) {
		$wp_customize->add_section( $id, array( 'title' => $title, 'panel' => 'njc_theme_options' ) );
	}
	$wp_customize->add_section( 'njc_people_content', array( 'title' => __( 'Team & Partner Details', 'njc-global' ), 'panel' => 'njc_theme_options' ) );

	foreach ( njc_brand_colors() as $key => $color ) {
		$id = 'njc_color_' . $key;
		$wp_customize->add_setting( $id, array( 'default' => $color['default'], 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'refresh' ) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array( 'label' => $color['label'], 'section' => 'njc_brand_colors' ) ) );
	}

	foreach ( njc_global_copy_fields() as $key => $field ) {
		$id = 'njc_copy_' . $key;
		$wp_customize->add_setting( $id, array( 'default' => $field['default'], 'sanitize_callback' => 'sanitize_textarea_field', 'transport' => 'refresh' ) );
		$wp_customize->add_control( $id, array( 'label' => $field['label'], 'section' => 'njc_global_content', 'type' => $field['type'] ) );
	}

	foreach ( njc_legacy_page_definitions() as $page => $title ) {
		$section = 'njc_text_' . str_replace( '-', '_', $page );
		foreach ( njc_page_copy_fields( $page ) as $field ) {
			$wp_customize->add_setting( $field['id'], array( 'default' => $field['default'], 'sanitize_callback' => 'njc_sanitize_editable_copy', 'transport' => 'refresh' ) );
			$wp_customize->add_control( $field['id'], array( 'label' => $field['label'], 'section' => $section, 'type' => 'textarea' ) );
		}
	}

	foreach ( njc_contact_fields() as $key => $field ) {
		$id = 'njc_contact_' . $key;
		$wp_customize->add_setting( $id, array( 'default' => $field['default'], 'sanitize_callback' => $field['sanitize'], 'transport' => 'refresh' ) );
		$wp_customize->add_control( $id, array( 'label' => $field['label'], 'section' => 'njc_contact_links', 'type' => $field['type'] ) );
	}

	foreach ( njc_people_fields() as $key => $field ) {
		$id = 'njc_person_' . $key;
		$wp_customize->add_setting( $id, array( 'default' => $field['default'], 'sanitize_callback' => 'sanitize_textarea_field', 'transport' => 'refresh' ) );
		$wp_customize->add_control( $id, array( 'label' => $field['label'], 'section' => 'njc_people_content', 'type' => isset( $field['type'] ) ? $field['type'] : 'text' ) );
	}

	foreach ( njc_image_fields() as $key => $field ) {
		$id = 'njc_image_' . $key;
		$default = isset( $field['asset'] ) ? NJC_THEME_URI . '/assets/images/' . $field['asset'] : '';
		$wp_customize->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'esc_url_raw', 'transport' => 'refresh' ) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $id, array( 'label' => $field['label'], 'section' => $field['section'] ) ) );
	}
}
add_action( 'customize_register', 'njc_customize_register' );

function njc_enqueue_custom_styles() {
	$properties = array( 'green' => '--green', 'green2' => '--green2', 'ink' => '--ink', 'cream' => '--cream', 'gold_deep' => '--brand-yellow-deep', 'gold_bright' => '--brand-yellow', 'gold_soft' => '--brand-yellow-light', 'muted' => '--muted' );
	$declarations = array();
	foreach ( njc_brand_colors() as $key => $color ) {
		$value = sanitize_hex_color( get_theme_mod( 'njc_color_' . $key, $color['default'] ) );
		$declarations[] = $properties[ $key ] . ':' . ( $value ? $value : $color['default'] );
	}
	$css = ':root{' . implode( ';', $declarations ) . '}';

	foreach ( njc_image_fields() as $key => $field ) {
		if ( empty( $field['selector'] ) ) {
			continue;
		}
		$url = njc_get_image( $key );
		if ( ! $url ) {
			continue;
		}
		$image = 'url(' . wp_json_encode( esc_url_raw( $url ), JSON_UNESCAPED_SLASHES ) . ')';
		$background = empty( $field['overlay'] ) ? $image : $field['overlay'] . ',' . $image;
		$css .= $field['selector'] . '{background-image:' . $background . '!important}';
	}

	wp_add_inline_style( 'njc-site', $css );
}
add_action( 'wp_enqueue_scripts', 'njc_enqueue_custom_styles', 20 );
