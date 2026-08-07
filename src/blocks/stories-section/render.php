<?php
/**
 * stjo/stories-section — renders one story-category band (carousel, optional
 * Class Year filter, optional lightbox cards) via the shared renderer in
 * inc/stories.php. Lets the Student Stories archive live as an editable Page.
 *
 * @package stjo
 */

if ( ! function_exists( 'stjo_render_stories_section' ) ) {
	return;
}

// Lightbox cards reuse the stjo/lightbox-card block's modal JS/CSS; pull in
// its registered handles when this section uses them (frontend only).
if ( ! empty( $attributes['lightbox'] ) && ! is_admin() ) {
	if ( wp_script_is( 'stjo-lightbox-card-view-script', 'registered' ) ) {
		wp_enqueue_script( 'stjo-lightbox-card-view-script' );
	}
	if ( wp_style_is( 'stjo-lightbox-card-style', 'registered' ) ) {
		wp_enqueue_style( 'stjo-lightbox-card-style' );
	}
}

echo stjo_render_stories_section( $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput -- renderer escapes its own output.
