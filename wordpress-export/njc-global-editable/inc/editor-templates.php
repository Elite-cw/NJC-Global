<?php
/**
 * Guided editorial templates and dashboard instructions.
 *
 * @package NJC_Global
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Give new articles and events a useful, editable writing structure.
 * Existing content is never changed.
 */
function njc_editor_starter_content( $content, $post ) {
	if ( ! $post || trim( $content ) ) {
		return $content;
	}

	if ( 'post' === $post->post_type ) {
		return '<!-- wp:paragraph {"placeholder":"Open with a short introduction that explains why this story matters."} -->
<p></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"placeholder":"Add a clear section heading"} -->
<h2 class="wp-block-heading"></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"placeholder":"Develop the main idea with useful context, evidence, or examples."} -->
<p></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"placeholder":"Add another section heading"} -->
<h2 class="wp-block-heading"></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"placeholder":"Continue the story in short, readable paragraphs."} -->
<p></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"placeholder":"Add a closing heading"} -->
<h2 class="wp-block-heading"></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"placeholder":"Close with the key takeaway or a clear next step."} -->
<p></p>
<!-- /wp:paragraph -->';
	}

	if ( 'njc_event' === $post->post_type ) {
		return '<!-- wp:paragraph {"placeholder":"Give a concise overview of the event and why people should attend."} -->
<p></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"placeholder":"What to expect"} -->
<h2 class="wp-block-heading"></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"placeholder":"Describe the programme, experience, speakers, or main activities."} -->
<p></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"placeholder":"Who should attend"} -->
<h2 class="wp-block-heading"></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"placeholder":"Explain who the event is designed for and what they will gain."} -->
<p></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"placeholder":"Important information"} -->
<h2 class="wp-block-heading"></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"placeholder":"Add any final attendance, access, or participation details."} -->
<p></p>
<!-- /wp:paragraph -->';
	}

	return $content;
}
add_filter( 'default_content', 'njc_editor_starter_content', 10, 2 );

/** Make the standard Posts area read clearly as Blog Posts for editors. */
function njc_rename_posts_menu() {
	global $menu, $submenu;

	if ( isset( $menu[5][0] ) ) {
		$menu[5][0] = __( 'Blog Posts', 'njc-global' );
	}
	if ( isset( $submenu['edit.php'][5][0] ) ) {
		$submenu['edit.php'][5][0] = __( 'All Blog Posts', 'njc-global' );
	}
	if ( isset( $submenu['edit.php'][10][0] ) ) {
		$submenu['edit.php'][10][0] = __( 'Add New Blog Post', 'njc-global' );
	}
}
add_action( 'admin_menu', 'njc_rename_posts_menu', 99 );

/** Add a permanent editorial guide beneath Dashboard. */
function njc_register_publishing_guide() {
	add_dashboard_page(
		__( 'NJC Publishing Guide', 'njc-global' ),
		__( 'NJC Publishing Guide', 'njc-global' ),
		'edit_posts',
		'njc-publishing-guide',
		'njc_render_publishing_guide_page'
	);
}
add_action( 'admin_menu', 'njc_register_publishing_guide' );

/** Add quick workflows to the main Dashboard screen. */
function njc_register_dashboard_widget() {
	if ( current_user_can( 'edit_posts' ) ) {
		wp_add_dashboard_widget(
			'njc-publishing-guide-widget',
			__( 'NJC Publishing Guide', 'njc-global' ),
			'njc_render_dashboard_widget'
		);
	}
}
add_action( 'wp_dashboard_setup', 'njc_register_dashboard_widget' );

function njc_blog_workflow_steps() {
	return array(
		__( 'Add the title and write the article using the starter blocks.', 'njc-global' ),
		__( 'Choose a category and add a short excerpt for the blog card.', 'njc-global' ),
		__( 'Set a Featured image for the article hero. Use Image or Gallery blocks for pictures inside the story.', 'njc-global' ),
		__( 'Paste supported video or social links on their own line for a rich preview. Other bare links become neat resource cards.', 'njc-global' ),
		__( 'Publish immediately or schedule it for later.', 'njc-global' ),
	);
}

function njc_event_workflow_steps() {
	return array(
		__( 'Add the event title and complete the starter content blocks.', 'njc-global' ),
		__( 'Complete the Event details panel: dates, location, region, type, display group, and registration URL.', 'njc-global' ),
		__( 'Add a short excerpt for the event card and upload the event artwork as the featured image.', 'njc-global' ),
		__( 'Preview the event page, then publish or schedule it.', 'njc-global' ),
	);
}

function njc_render_workflow_steps( $steps ) {
	echo '<ol class="njc-guide-steps">';
	foreach ( $steps as $step ) {
		echo '<li>' . esc_html( $step ) . '</li>';
	}
	echo '</ol>';
}

function njc_render_dashboard_widget() {
	?>
	<div class="njc-guide-widget">
		<p><?php esc_html_e( 'The theme protects the public layouts while you edit the content in WordPress.', 'njc-global' ); ?></p>
		<div class="njc-guide-widget__grid">
			<section>
				<h3><?php esc_html_e( 'New blog post', 'njc-global' ); ?></h3>
				<?php njc_render_workflow_steps( njc_blog_workflow_steps() ); ?>
				<a class="button button-primary" href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><?php esc_html_e( 'Add Blog Post', 'njc-global' ); ?></a>
			</section>
			<section>
				<h3><?php esc_html_e( 'New event', 'njc-global' ); ?></h3>
				<?php njc_render_workflow_steps( njc_event_workflow_steps() ); ?>
				<a class="button button-primary" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=njc_event' ) ); ?>"><?php esc_html_e( 'Add Event', 'njc-global' ); ?></a>
			</section>
		</div>
		<p class="njc-guide-widget__footer"><a href="<?php echo esc_url( admin_url( 'index.php?page=njc-publishing-guide' ) ); ?>"><?php esc_html_e( 'Open the complete publishing guide', 'njc-global' ); ?> &rarr;</a></p>
	</div>
	<?php
}

function njc_render_publishing_guide_page() {
	?>
	<div class="wrap njc-publishing-guide">
		<p class="njc-guide-eyebrow"><?php esc_html_e( 'NJC GLOBAL CONTENT', 'njc-global' ); ?></p>
		<h1><?php esc_html_e( 'Publish without rebuilding the design.', 'njc-global' ); ?></h1>
		<p class="njc-guide-intro"><?php esc_html_e( 'Choose a content type below. The NJC layout, typography, colours, cards, and detail pages are applied automatically.', 'njc-global' ); ?></p>
		<div class="njc-guide-cards">
			<section class="njc-guide-card">
				<span class="dashicons dashicons-welcome-write-blog" aria-hidden="true"></span>
				<h2><?php esc_html_e( 'Create a blog post', 'njc-global' ); ?></h2>
				<p><?php esc_html_e( 'Best for insights, announcements, interviews, and thought leadership.', 'njc-global' ); ?></p>
				<?php njc_render_workflow_steps( njc_blog_workflow_steps() ); ?>
				<a class="button button-primary button-hero" href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><?php esc_html_e( 'Start a Blog Post', 'njc-global' ); ?></a>
			</section>
			<section class="njc-guide-card">
				<span class="dashicons dashicons-calendar-alt" aria-hidden="true"></span>
				<h2><?php esc_html_e( 'Create an event', 'njc-global' ); ?></h2>
				<p><?php esc_html_e( 'Best for upcoming, featured, and past NJC events.', 'njc-global' ); ?></p>
				<?php njc_render_workflow_steps( njc_event_workflow_steps() ); ?>
				<a class="button button-primary button-hero" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=njc_event' ) ); ?>"><?php esc_html_e( 'Start an Event', 'njc-global' ); ?></a>
			</section>
		</div>
		<div class="njc-guide-note">
			<h2><?php esc_html_e( 'Before publishing', 'njc-global' ); ?></h2>
			<p><?php esc_html_e( 'Use Preview first. Check the title, excerpt, featured image, body content, links, and mobile layout. Publishing content never requires editing the NJC page templates.', 'njc-global' ); ?></p>
		</div>
	</div>
	<?php
}

/** Show a compact checklist at the top of new post and event screens. */
function njc_editor_guidance_notice() {
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, array( 'post', 'njc_event' ), true ) || 'add' !== $screen->action ) {
		return;
	}

	$is_event = 'njc_event' === $screen->post_type;
	?>
	<div class="notice njc-editor-notice">
		<p><strong><?php echo esc_html( $is_event ? __( 'Creating an NJC event', 'njc-global' ) : __( 'Creating an NJC blog post', 'njc-global' ) ); ?></strong></p>
		<p><?php echo esc_html( $is_event ? __( 'Complete the starter blocks, Event details, Excerpt, and Featured image. Preview before publishing.', 'njc-global' ) : __( 'Complete the starter blocks, Category, Excerpt, and Featured image. Preview before publishing.', 'njc-global' ) ); ?></p>
	</div>
	<?php
}
add_action( 'admin_notices', 'njc_editor_guidance_notice' );

/** Apply the NJC visual system only to the guide and editorial helper UI. */
function njc_enqueue_admin_guide_styles( $hook_suffix ) {
	$is_editor = in_array( $hook_suffix, array( 'post-new.php', 'post.php', 'index.php' ), true );
	$is_guide  = 'dashboard_page_njc-publishing-guide' === $hook_suffix;
	if ( ! $is_editor && ! $is_guide ) {
		return;
	}

	wp_enqueue_style(
		'njc-admin-fonts',
		'https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap',
		array(),
		null
	);

	$css = '
		#njc-publishing-guide-widget, .njc-publishing-guide, .njc-editor-notice { --njc-green:#0b4f3d; --njc-ink:#153f36; --njc-cream:#f7f4eb; --njc-gold:#b67d35; font-family:"Hanken Grotesk",sans-serif; }
		#njc-publishing-guide-widget .inside { margin:0; padding:0 18px 18px; }
		.njc-guide-widget__grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; }
		.njc-guide-widget__grid section { background:var(--njc-cream); border:1px solid #ddd8cc; border-radius:14px; padding:16px; }
		.njc-guide-widget h3 { color:var(--njc-ink); font-family:"Playfair Display",serif; font-size:18px; margin:0 0 8px; }
		.njc-guide-steps { margin:10px 0 16px 20px; }
		.njc-guide-steps li { margin-bottom:8px; }
		.njc-guide-widget__footer { margin:14px 0 0; }
		.njc-guide-widget .button-primary, .njc-publishing-guide .button-primary { background:var(--njc-green); border-color:var(--njc-green); }
		.njc-guide-widget .button-primary:hover, .njc-publishing-guide .button-primary:hover { background:#083f31; border-color:#083f31; }
		.njc-publishing-guide { max-width:1120px; padding-top:26px; }
		.njc-guide-eyebrow { color:var(--njc-gold); font-size:12px; font-weight:700; letter-spacing:.18em; margin-bottom:10px; }
		.njc-publishing-guide > h1 { color:var(--njc-ink); font-family:"Playfair Display",serif; font-size:42px; line-height:1.1; margin:0; }
		.njc-guide-intro { color:#526761; font-size:18px; max-width:760px; }
		.njc-guide-cards { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:24px; margin-top:28px; }
		.njc-guide-card { background:#fff; border:1px solid #d9dedb; border-radius:22px; box-shadow:0 16px 40px rgba(21,63,54,.08); padding:28px; }
		.njc-guide-card > .dashicons { color:var(--njc-gold); font-size:28px; height:28px; width:28px; }
		.njc-guide-card h2, .njc-guide-note h2 { color:var(--njc-ink); font-family:"Playfair Display",serif; font-size:26px; }
		.njc-guide-note { background:var(--njc-green); border-radius:20px; color:#fff; margin-top:24px; padding:24px 28px; }
		.njc-guide-note h2 { color:#fff; margin-top:0; }
		.njc-editor-notice { border-left-color:var(--njc-gold); }
		@media (max-width:782px) { .njc-guide-widget__grid, .njc-guide-cards { grid-template-columns:1fr; } .njc-publishing-guide > h1 { font-size:34px; } }
	';
	wp_add_inline_style( 'njc-admin-fonts', $css );
}
add_action( 'admin_enqueue_scripts', 'njc_enqueue_admin_guide_styles' );
