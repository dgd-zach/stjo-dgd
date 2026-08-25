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

/**
 * Media Library lookup for an image the history seed imported.
 *
 * media-map.json is committed to the repo, so the IDs in it are whichever
 * environment generated it: fine for `url` (root-relative, portable), wrong
 * for `id`. Seeded history images are instead tagged with `_stjo_seed_source`
 * at import time, so this resolves against whatever library it is running in.
 * That is what keeps `mediaId` / `wp-image-N` correct on local and staging
 * alike without hand-editing block markup between environments.
 *
 * @param string $file Source filename, e.g. 'history-fr-dehon.jpg'.
 * @return array{id:int,url:string} id 0 and url '' when not imported yet.
 */
function stjo_seeded_image( $file ) {
	$ids = get_posts( array(
		'post_type'        => 'attachment',
		'post_status'      => 'any',
		'posts_per_page'   => 1,
		'fields'           => 'ids',
		'no_found_rows'    => true,
		'suppress_filters' => false,
		'meta_query'       => array( array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			'key'     => '_stjo_seed_source',
			'value'   => (string) $file,
			'compare' => '=',
		) ),
	) );
	if ( ! $ids ) {
		return array( 'id' => 0, 'url' => '' );
	}
	$id = (int) $ids[0];
	// Root-relative, matching the rest of the seeded content so a local→staging
	// push cannot drag a hostname along.
	return array( 'id' => $id, 'url' => (string) wp_make_link_relative( (string) wp_get_attachment_url( $id ) ) );
}
