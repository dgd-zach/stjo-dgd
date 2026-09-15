<?php
/**
 * Normalise every Page Hero cover (className stjo-page-hero) to a 450px
 * minimum height, matching the pattern in inc/patterns/page-hero.php.
 *
 *   wp eval-file wp-content/themes/stjo-dgd/inc/seed/hero-min-height.php
 *
 * Touches only the hero cover's own "minHeight" attribute and the matching
 * inline min-height on its wrapper; nothing else in the content changes.
 * Re-runnable; pages already at 450 are reported and skipped. Revisions keep
 * the previous content.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/seed-lib.php';

global $wpdb;
$stjo_rows = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	"SELECT ID, post_name, post_content FROM {$wpdb->posts}
	 WHERE post_content LIKE '%stjo-page-hero%'
	   AND post_type IN ('page','post','student-story','wp_block')
	   AND post_status NOT IN ('trash','auto-draft','inherit')"
);

$stjo_changed = 0;
foreach ( $stjo_rows as $row ) {
	$content = $row->post_content;
	$count   = 0;
	// The hero block: its attribute JSON on the comment line, then the wrapper
	// div carrying the same height inline. Replace both, only inside covers
	// whose className is stjo-page-hero and whose height is not already 450.
	$new = preg_replace_callback(
		'/(<!-- wp:cover \{[^\n]*?"className":"[^"\n]*?stjo-page-hero[^"\n]*?"[^\n]*?\} -->\s*<div class="wp-block-cover[^"]*?stjo-page-hero[^"]*?"[^>]*?>)/s',
		function ( $m ) use ( &$count ) {
			$block = $m[1];
			if ( ! preg_match( '/"minHeight":(\d+)(?:\.\d+)?/', $block, $h ) || 450 === (int) $h[1] ) {
				return $block;
			}
			$old   = (int) $h[1];
			$block = preg_replace( '/"minHeight":' . $old . '(?:\.\d+)?/', '"minHeight":450', $block, 1 );
			$block = preg_replace( '/min-height:' . $old . 'px/', 'min-height:450px', $block, 1 );
			$count++;
			return $block;
		},
		$content
	);

	if ( null === $new || $new === $content ) {
		stjo_seed_say( sprintf( '  %s (#%d): no change', $row->post_name, $row->ID ) );
		continue;
	}
	$res = wp_update_post( array( 'ID' => $row->ID, 'post_content' => wp_slash( $new ) ), true );
	if ( is_wp_error( $res ) ) {
		stjo_seed_say( sprintf( '  %s (#%d): %s', $row->post_name, $row->ID, $res->get_error_message() ) );
		continue;
	}
	clean_post_cache( $row->ID );
	stjo_seed_say( sprintf( '  %s (#%d): %d hero(s) set to 450px', $row->post_name, $row->ID, $count ) );
	$stjo_changed++;
}

wp_cache_flush();
if ( class_exists( 'WpeCommon' ) && method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
	WpeCommon::purge_varnish_cache();
}
stjo_seed_say( sprintf( 'Done: %d post(s) updated.', $stjo_changed ) );
