<?php
/**
 * Custom block registration (no build chain — plain JS editor scripts).
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stjo_register_custom_blocks() {
	wp_register_script(
		'stjo-donation-selector-editor',
		get_template_directory_uri() . '/src/blocks/donation-selector/edit.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render' ),
		STJO_VERSION,
		true
	);
	register_block_type( get_template_directory() . '/src/blocks/donation-selector' );

	wp_register_script(
		'stjo-timeline-editor',
		get_template_directory_uri() . '/src/blocks/timeline/edit.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render' ),
		(string) filemtime( get_template_directory() . '/src/blocks/timeline/edit.js' ), // mtime version: STJO_VERSION let stale copies stick in the editor
		true
	);
	register_block_type( get_template_directory() . '/src/blocks/timeline' );

	wp_register_script(
		'stjo-lightbox-card-editor',
		get_template_directory_uri() . '/src/blocks/lightbox-card/edit.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render' ),
		(string) filemtime( get_template_directory() . '/src/blocks/lightbox-card/edit.js' ),
		true
	);
	register_block_type( get_template_directory() . '/src/blocks/lightbox-card' );

	// block.json assets default to the WP core version string; pin filemtime
	// so edits actually cache-bust (same stale-script trap as unregister.js).
	$stjo_lb_view = wp_scripts()->query( 'stjo-lightbox-card-view-script' );
	if ( $stjo_lb_view ) {
		$stjo_lb_view->ver = (string) filemtime( get_template_directory() . '/src/blocks/lightbox-card/view.js' );
	}
	$stjo_lb_style = wp_styles()->query( 'stjo-lightbox-card-style' );
	if ( $stjo_lb_style ) {
		$stjo_lb_style->ver = (string) filemtime( get_template_directory() . '/src/blocks/lightbox-card/style.css' );
	}
}
add_action( 'init', 'stjo_register_custom_blocks' );

/**
 * The Preview Lightbox modal (wp.components.Modal) lives in the admin
 * document, OUTSIDE the editor canvas iframe, so block styles registered via
 * block.json never reach it. Enqueue the lightbox styles (plus fonts and
 * tokens they build on) into the admin document for frontend-matching
 * previews.
 */
function stjo_lightbox_card_admin_assets() {
	$uri = get_template_directory_uri();
	wp_enqueue_style( 'stjo-fonts-admin', $uri . '/assets/css/fonts.css', array(), STJO_VERSION );
	wp_enqueue_style( 'stjo-tokens-admin', $uri . '/assets/css/tokens.css', array(), STJO_VERSION );
	wp_enqueue_style(
		'stjo-lightbox-card-admin',
		$uri . '/src/blocks/lightbox-card/style.css',
		array( 'stjo-tokens-admin' ),
		(string) filemtime( get_template_directory() . '/src/blocks/lightbox-card/style.css' )
	);
}
add_action( 'enqueue_block_editor_assets', 'stjo_lightbox_card_admin_assets' );
