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
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-server-side-render' ),
		STJO_VERSION,
		true
	);
	register_block_type( get_template_directory() . '/src/blocks/timeline' );
}
add_action( 'init', 'stjo_register_custom_blocks' );
