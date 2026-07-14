<?php
/**
 * 404 template.
 *
 * @package stjo
 */

get_header();
?>
<div class="wp-block-group alignfull stjo-page-hero">
	<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
	<h1 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Page not found', 'stjo' ); ?></h1>
	<p class="has-text-align-center"><?php esc_html_e( 'The page you are looking for may have moved.', 'stjo' ); ?></p>
	<div class="wp-block-buttons is-layout-flex" style="justify-content:center">
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'stjo' ); ?></a></div>
	</div>
	<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
</div>
<hr class="wp-block-separator has-alpha-channel-opacity stjo-zigzag alignfull"/>
<div class="entry-content">
	<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
	<?php get_search_form(); ?>
	<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
</div>
<?php
get_footer();
