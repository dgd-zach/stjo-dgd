<?php
/**
 * Content Guide: exposes the standalone client content guide
 * (docs/content-guide.html) inside wp-admin as Dashboard > Content Guide.
 *
 * The guide is served through an admin-post endpoint (not a direct file
 * URL) so it stays behind the same capability check as the admin page
 * that iframes it, and logged-out visitors get nothing.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Dashboard > Content Guide submenu page.
 *
 * Capability is edit_posts (not manage_options) so editors, not only
 * admins, can see it.
 */
function stjo_content_guide_menu() {
	add_dashboard_page(
		__( 'Content Guide', 'stjo' ),
		__( 'Content Guide', 'stjo' ),
		'edit_posts',
		'stjo-content-guide',
		'stjo_content_guide_page'
	);
}
add_action( 'admin_menu', 'stjo_content_guide_menu' );

/**
 * Render the Dashboard > Content Guide admin page: a short intro plus
 * an iframe of the guide, both pointing at the admin-post endpoint below.
 */
function stjo_content_guide_page() {
	$endpoint_url = admin_url( 'admin-post.php?action=stjo_content_guide' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Content Guide', 'stjo' ); ?></h1>
		<p>
			<?php esc_html_e( 'How to add, edit, and remove content on this site.', 'stjo' ); ?>
			<a href="<?php echo esc_url( $endpoint_url ); ?>" target="_blank" rel="noopener">
				<?php esc_html_e( 'Open the guide in a new tab', 'stjo' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'stjo' ); ?></span>
			</a>
		</p>
		<iframe
			title="<?php esc_attr_e( 'Website Content Guide', 'stjo' ); ?>"
			src="<?php echo esc_url( $endpoint_url ); ?>"
			style="width:100%; height:calc(100vh - 160px); min-height:600px; border:1px solid #c3c4c7; border-radius:4px; background:#fff;"
		></iframe>
	</div>
	<?php
}

/**
 * Serve docs/content-guide.html directly, guarded by the same capability
 * as the admin page. No admin_post_nopriv_ handler is registered, so a
 * logged-out request never reaches this callback.
 */
function stjo_content_guide_serve() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_die( esc_html__( 'You do not have permission to view this guide.', 'stjo' ), 403 );
	}

	$file = get_template_directory() . '/docs/content-guide.html';

	if ( ! file_exists( $file ) ) {
		wp_die( esc_html__( 'Content guide not found.', 'stjo' ), 404 );
	}

	nocache_headers();
	header( 'Content-Type: text/html; charset=utf-8' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	readfile( $file );
	exit;
}
add_action( 'admin_post_stjo_content_guide', 'stjo_content_guide_serve' );
