<?php
/**
 * Site footer.
 *
 * @package NJC_Global
 */
?>
<footer>
	<div class="container footer-grid">
		<div>
			<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="brand-logo" aria-hidden="true"></span><span class="sr-only"><?php bloginfo( 'name' ); ?></span></a>
			<p><?php echo esc_html( njc_get_copy( 'footer_tagline' ) ); ?></p>
		</div>
		<div>
			<h3><?php esc_html_e( 'Projects & Events', 'njc-global' ); ?></h3>
			<a class="njc-projects-link" href="<?php echo esc_url( njc_page_url( 'projects' ) ); ?>"><?php esc_html_e( 'Projects', 'njc-global' ); ?></a>
			<a href="<?php echo esc_url( njc_events_url() ); ?>"><?php esc_html_e( 'Events', 'njc-global' ); ?></a>
		</div>
		<div>
			<h3><?php esc_html_e( 'Connect', 'njc-global' ); ?></h3>
			<a href="<?php echo esc_url( njc_page_url( 'contact-us' ) ); ?>"><?php esc_html_e( 'Contact us', 'njc-global' ); ?></a>
			<a href="mailto:<?php echo esc_attr( antispambot( njc_get_contact( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( njc_get_contact( 'email' ) ) ); ?></a>
			<a href="<?php echo esc_url( njc_get_contact( 'linkedin' ) ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'LinkedIn', 'njc-global' ); ?></a>
			<a href="<?php echo esc_url( njc_get_contact( 'instagram' ) ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Instagram', 'njc-global' ); ?></a>
		</div>
		<form class="njc-server-form" data-njc-server-form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
			<h3><?php echo esc_html( njc_get_copy( 'footer_newsletter_title' ) ); ?></h3>
			<p><?php echo esc_html( njc_get_copy( 'footer_newsletter_intro' ) ); ?></p>
			<label for="footer-email"><?php esc_html_e( 'Email Address', 'njc-global' ); ?></label>
			<div>
				<input id="footer-email" name="email" type="email" placeholder="Email Address" autocomplete="email" required>
				<button type="submit" aria-label="<?php esc_attr_e( 'Join newsletter', 'njc-global' ); ?>"><span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></button>
			</div>
			<input type="hidden" name="action" value="njc_subscribe">
			<?php wp_nonce_field( 'njc_subscribe', 'njc_subscribe_nonce' ); ?>
			<label class="njc-form-trap" aria-hidden="true">Website<input name="company_website" type="text" tabindex="-1" autocomplete="off"></label>
		</form>
	</div>
	<div class="container footer-bottom">
		<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'njc-global' ); ?></p>
		<p><a href="<?php echo esc_url( njc_page_url( 'privacy' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'njc-global' ); ?></a><a href="<?php echo esc_url( njc_page_url( 'terms' ) ); ?>"><?php esc_html_e( 'Terms of Service', 'njc-global' ); ?></a></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
