<?php
/**
 * 404 template.
 *
 * Uses the shared page hero (stjo_page_hero) so a missing page is framed like
 * every other interior page: blue title band, eyebrow, H1, zigzag ribbon. The
 * band's own markup carried .stjo-page-hero before, which has no background of
 * its own, so the heading rendered on bare white.
 *
 * The recovery copy, the home button and the search form sit below the band in
 * .entry-content, matching how built pages put content under their hero rather
 * than inside it.
 *
 * @package stjo
 */

get_header();

stjo_page_hero(
	null,
	array(
		'eyebrow' => __( '404', 'stjo' ),
		'title'   => __( 'Page not found', 'stjo' ),
	)
);
?>
<div class="entry-content">
	<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
	<p class="has-text-align-center"><?php esc_html_e( 'The page you are looking for may have moved.', 'stjo' ); ?></p>
	<div class="wp-block-buttons is-layout-flex" style="justify-content:center">
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'stjo' ); ?></a></div>
	</div>
	<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
	<?php get_search_form(); ?>
	<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
</div>
<?php
get_footer();
