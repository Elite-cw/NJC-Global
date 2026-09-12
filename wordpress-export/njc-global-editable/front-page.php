<?php
/**
 * Static-design homepage.
 *
 * @package NJC_Global
 */

get_header();
if ( njc_page_uses_editor() ) {
	njc_render_editor_page();
} else {
	njc_render_legacy_part( 'front-page' );
}
get_footer();
