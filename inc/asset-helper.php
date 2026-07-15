<?php
/**
 * Design-asset resolver. Pattern markup references images by FILENAME; this
 * helper resolves them to Media Library URLs via media-map.json (written by
 * setup/media_sync.py into the theme root), falling back to the theme's
 * assets/images/ copy so dev environments without the import still render.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Media Library URL (preferred) or theme-asset URL for a design image.
 */
function stjo_asset( $file ) {
	static $map = null;
	if ( null === $map ) {
		$json = get_template_directory() . '/media-map.json';
		$map  = file_exists( $json )
			? (array) json_decode( (string) file_get_contents( $json ), true )
			: array();
	}
	$file = ltrim( (string) $file, '/' );
	if ( ! empty( $map[ $file ]['url'] ) ) {
		return $map[ $file ]['url'];
	}
	return get_template_directory_uri() . '/assets/images/' . $file;
}

/**
 * Attachment ID for a design image (0 when not imported).
 */
function stjo_asset_id( $file ) {
	static $map = null;
	if ( null === $map ) {
		$json = get_template_directory() . '/media-map.json';
		$map  = file_exists( $json )
			? (array) json_decode( (string) file_get_contents( $json ), true )
			: array();
	}
	return (int) ( $map[ ltrim( (string) $file, '/' ) ]['id'] ?? 0 );
}
