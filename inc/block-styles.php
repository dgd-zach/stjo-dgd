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

	// Subhead under a section title. Same treatment the subsection patterns get
	// from .stjo-subhead, exposed so it can be applied to any paragraph — both
	// selectors are paired in sections.css, including the rule that tightens a
	// heading directly above one.
	register_block_style( 'core/paragraph', array(
		'name'  => 'subhead',
		'label' => __( 'Sub Header', 'stjo' ),
	) );

	// Rounded image (cards, media).
	register_block_style( 'core/image', array(
		'name'  => 'rounded',
		'label' => __( 'Rounded', 'stjo' ),
	) );

	// Scalloped yellow ring (the impact-stats photo treatment) on any image.
	register_block_style( 'core/image', array(
		'name'  => 'bordered',
		'label' => __( 'Bordered', 'stjo' ),
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
	// (The "Count Up" paragraph/heading block style was removed 2026-08-10 —
	// too niche to expose. The count-up animation still runs on the impact
	// band automatically via .stjo-stat__figure in count-up.js.)

	// Yoast FAQ block as a collapsible accordion (matches the native-Details
	// accordion look). faq-accordion.js supplies the toggle behavior; without
	// JS the answers stay visible.
	register_block_style( 'yoast/faq-block', array(
		'name'  => 'accordion',
		'label' => __( 'Accordion', 'stjo' ),
	) );

	// Opt-in: centre this heading on phones, whatever its desktop alignment.
	// Deliberately NOT registered isDefault — a default style serialises no
	// class, which would silently recentre every heading on the site.
	register_block_style( 'core/heading', array(
		'name'  => 'center-mobile',
		'label' => __( 'Centered on Mobile', 'stjo' ),
	) );

	// Cover whose overlay renders on phones only — the colour and opacity set
	// in the Overlay panel still apply, they just stop above the phone
	// breakpoint (main.css). For a hero that needs a scrim for legibility on a
	// narrow crop but reads better as a clean photo on desktop.
	// editor.css badges the block with "Overlay shows on mobile only", because
	// the canvas previews this honestly and the overlay vanishing at desktop
	// width otherwise looks like a bug.
	register_block_style( 'core/cover', array(
		'name'  => 'overlay-mobile-only',
		'label' => __( 'Overlay on Mobile Only', 'stjo' ),
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

/**
 * "Text width" inspector control (measure-control.js): a toggle + range slider
 * on paragraph/heading/list/group blocks that stamps the stjo-measure class +
 * --stjo-measure custom property (see utilities.css).
 */
function stjo_measure_control_assets() {
	wp_enqueue_script(
		'stjo-measure-control',
		get_template_directory_uri() . '/assets/js/measure-control.js',
		array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-compose', 'wp-hooks', 'wp-block-editor', 'wp-i18n' ),
		(string) filemtime( get_template_directory() . '/assets/js/measure-control.js' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'stjo_measure_control_assets' );

/*
 * The Cover "Overlay on Mobile Only" style used to ship a JS notice here
 * (core/notices). It is a CSS badge on the block instead now — see the
 * .is-style-overlay-mobile-only::before rule in editor.css. Same information,
 * on the block where the eye already is, and no script to maintain.
 */
