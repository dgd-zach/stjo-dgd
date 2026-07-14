<?php
/**
 * Native block style variants + small native-block default tweaks.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Make a fresh Spacer default to the Medium spacing preset (not 100px), so the
 * editor shows the Small/Medium/Large stepper instead of the freeform px slider.
 */
add_filter( 'register_block_type_args', function ( $args, $name ) {
	if ( 'core/spacer' === $name && isset( $args['attributes']['height'] ) ) {
		$args['attributes']['height']['default'] = 'var:preset|spacing|medium';
	}
	return $args;
}, 10, 2 );

/**
 * Register block style variants used in the design.
 */
function stjo_register_block_styles() {
	// Eyebrow / caps label (e.g. "ABOUT US", "FROM THE BLOG").
	register_block_style( 'core/paragraph', array(
		'name'  => 'eyebrow',
		'label' => __( 'Eyebrow (Caps)', 'stjo' ),
	) );

	// Rounded image (cards, media).
	register_block_style( 'core/image', array(
		'name'  => 'rounded',
		'label' => __( 'Rounded', 'stjo' ),
	) );

	// Pill / ghost link arrow button ("See Our Mission →", "More").
	register_block_style( 'core/button', array(
		'name'  => 'arrow-link',
		'label' => __( 'Arrow Link', 'stjo' ),
	) );
}
add_action( 'init', 'stjo_register_block_styles' );
