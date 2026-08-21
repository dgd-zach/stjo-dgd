<?php
/**
 * Stat figure — a single impact number that counts up on scroll.
 *
 * Outputs the same shape the impact band used before this block existed: a
 * paragraph carrying .stjo-stat__figure, with the real value in the markup so
 * it is correct with JS off and for search engines. assets/js/count-up.js
 * picks it up by that class and reads the per-block duration from
 * data-count-duration, so hand-written .stjo-stat__figure paragraphs elsewhere
 * keep animating at the default.
 *
 * @package stjo
 */

$stjo_sf_value = isset( $attributes['value'] ) ? trim( wp_strip_all_tags( (string) $attributes['value'] ) ) : '';
if ( '' === $stjo_sf_value ) {
	return;
}

// Guard the sidebar value: RangeControl is bounded, but attributes can be set
// by anything that writes post content.
$stjo_sf_duration = isset( $attributes['duration'] ) ? absint( $attributes['duration'] ) : 4000;
if ( $stjo_sf_duration < 500 || $stjo_sf_duration > 20000 ) {
	$stjo_sf_duration = 4000;
}

$stjo_sf_classes = 'stjo-stat__figure';
if ( ! empty( $attributes['textAlign'] ) ) {
	$stjo_sf_classes .= ' has-text-align-' . sanitize_html_class( $attributes['textAlign'] );
}

printf(
	'<p %s data-count-duration="%d">%s</p>',
	wp_kses_post( get_block_wrapper_attributes( array( 'class' => $stjo_sf_classes ) ) ),
	(int) $stjo_sf_duration,
	esc_html( $stjo_sf_value )
);
