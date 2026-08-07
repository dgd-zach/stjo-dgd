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

	// Pill / ghost link arrow button ("See Our Mission ›", "More").
	register_block_style( 'core/button', array(
		'name'  => 'arrow-link',
		'label' => __( 'Arrow Link', 'stjo' ),
	) );

	// Same ghost link, no caret.
	register_block_style( 'core/button', array(
		'name'  => 'plain',
		'label' => __( 'Plain Link', 'stjo' ),
	) );

	// Separators: the zigzag ribbon is the DEFAULT; 'basic' is the plain line.
	register_block_style( 'core/separator', array(
		'name'  => 'basic',
		'label' => __( 'Basic line', 'stjo' ),
	) );

	// Group whose last child hangs halfway below the band edge (donation card).
	register_block_style( 'core/group', array(
		'name'  => 'overhang-last-child',
		'label' => __( 'Overhang Last Child', 'stjo' ),
	) ); 
	// Group whose last child hangs halfway below the band edge (donation card).
	register_block_style( 'core/group', array(
		'name'  => 'overhang-first-child',
		'label' => __( 'Overhang First Child', 'stjo' ),
	) );
	// Group of Cover blocks presented one at a time (carousel.js adds
	// controls, ARIA, fades, and swipe). Direct-child Covers = slides.
	register_block_style( 'core/group', array(
		'name'  => 'carousel',
		'label' => __( 'Carousel', 'stjo' ),
	) );
	// Number that counts up from zero when scrolled into view (count-up.js).
	// stjo-stat__figure paragraphs get this behavior automatically.
	register_block_style( 'core/paragraph', array(
		'name'  => 'count-up',
		'label' => __( 'Count Up', 'stjo' ),
	) );
	register_block_style( 'core/heading', array(
		'name'  => 'count-up',
		'label' => __( 'Count Up', 'stjo' ),
	) );

	// Yoast FAQ block as a collapsible accordion (matches the native-Details
	// accordion look). faq-accordion.js supplies the toggle behavior; without
	// JS the answers stay visible.
	register_block_style( 'yoast/faq-block', array(
		'name'  => 'accordion',
		'label' => __( 'Accordion', 'stjo' ),
	) );

}
add_action( 'init', 'stjo_register_block_styles' );

/**
 * Remove unwanted core style variants in the editor. Core registers these
 * client-side, so server-side unregister_block_style() can't touch them —
 * it has to be JS (assets/js/unregister.js).
 */
function stjo_unregister_block_styles() {
	wp_enqueue_script(
		'stjo-unregister-block-styles',
		get_template_directory_uri() . '/assets/js/unregister.js',
		array( 'wp-blocks', 'wp-dom-ready' ),
		(string) filemtime( get_template_directory() . '/assets/js/unregister.js' ), // cache-busts on edit, unlike the static theme version
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'stjo_unregister_block_styles' );
