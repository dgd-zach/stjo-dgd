<?php
/**
 * Block pattern category + homepage section patterns.
 * Patterns are registered in Phase 3 (see stjo_register_patterns()).
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the pattern category.
 */
function stjo_register_pattern_category() {
	register_block_pattern_category( 'stjo', array(
		'label' => __( "St. Joseph's", 'stjo' ),
	) );
}
add_action( 'init', 'stjo_register_pattern_category' );

// Homepage section patterns are loaded from inc/patterns/*.php below.
function stjo_register_patterns() {
	$dir = get_template_directory() . '/inc/patterns/';
	if ( ! is_dir( $dir ) ) {
		return;
	}
	foreach ( glob( $dir . '*.php' ) as $file ) {
		$slug    = 'stjo/' . basename( $file, '.php' );
		$title   = ucwords( str_replace( '-', ' ', basename( $file, '.php' ) ) );
		ob_start();
		include $file;
		$content = ob_get_clean();
		register_block_pattern( $slug, array(
			'title'      => $title,
			'categories' => array( 'stjo' ),
			'content'    => $content,
			'inserter'   => true,
		) );
	}
}
add_action( 'init', 'stjo_register_patterns' );
