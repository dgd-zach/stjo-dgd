<?php
/**
 * Rebuild the Our History page: images -> timeline events -> page content.
 *
 *   wp eval-file wp-content/themes/stjo-dgd/inc/seed/seed-history.php
 *
 * Safe to re-run. Images are matched by source filename and reused rather than
 * re-uploaded, so repeat runs do not stack duplicates in the library.
 *
 * DESTRUCTIVE: every existing timeline-event post is deleted and recreated from
 * history-timeline.json. That is the point (the year/decade terms and ordering
 * are derived, not authored), but it means editor changes made directly to
 * timeline events are lost. The images themselves are never deleted.
 *
 * Environment portability: attachment IDs are resolved from whatever media
 * library this runs against, via the `_stjo_seed_source` tag and
 * stjo_seeded_image(). No local ID is ever written into content.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

if ( ! function_exists( 'stjo_seeded_image' ) ) {
	stjo_seed_abort( 'stjo_seeded_image() missing. Is inc/asset-helper.php loaded?' );
}

function stjo_seed_say( $msg ) {
	if ( class_exists( 'WP_CLI' ) ) {
		WP_CLI::log( $msg );
		return;
	}
	echo $msg . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput
}

function stjo_seed_abort( $msg ) {
	if ( class_exists( 'WP_CLI' ) ) {
		WP_CLI::error( $msg );
	}
	exit( $msg . "\n" ); // phpcs:ignore WordPress.Security.EscapeOutput
}

/**
 * An existing attachment whose file is this exact filename, however it got there.
 *
 * Both environments run the media-sync plugin, which scans wp-content/uploads
 * and registers anything new as an attachment (marked `_msc`). It is not on a
 * cron, so it runs when someone triggers a scan in wp-admin. After a files-only
 * deploy push that can produce real attachment rows for these images before the
 * seed ever runs: correct paths and generated sizes, but no alt text and none of
 * our `_stjo_seed_source` tag. Without this lookup the seed would treat them as
 * absent and upload a second copy as history-thrift-store-1.jpg.
 *
 * @param string $file Source filename, e.g. 'history-thrift-store.jpg'.
 * @return int Attachment ID, or 0.
 */
function stjo_seed_find_attachment_by_filename( $file ) {
	$candidates = get_posts( array(
		'post_type'      => 'attachment',
		'post_status'    => 'any',
		'posts_per_page' => 20,
		'fields'         => 'ids',
		'no_found_rows'  => true,
		'meta_query'     => array( array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			'key'     => '_wp_attached_file',
			'value'   => $file,
			'compare' => 'LIKE',
		) ),
	) );
	foreach ( $candidates as $id ) {
		// LIKE '%name%' can also catch other-history-thrift-store.jpg, so the
		// basename has to match exactly.
		if ( basename( (string) get_post_meta( $id, '_wp_attached_file', true ) ) === $file ) {
			return (int) $id;
		}
	}
	return 0;
}

/**
 * An uploads file with this exact name that no attachment row points at.
 *
 * Produced by a files-only deploy push: the file lands in wp-content/uploads
 * but the DB (and so the attachment record) does not come with it. Returns the
 * absolute path, or '' when there is no such file or it is already attached.
 *
 * @param string $file Source filename, e.g. 'history-thrift-store.jpg'.
 * @return string
 */
function stjo_seed_find_orphan_upload( $file ) {
	$dir  = wp_upload_dir();
	$base = trailingslashit( $dir['basedir'] );

	// Standard year/month layout, plus a flat uploads root.
	$hits = array_merge(
		(array) glob( $base . '*/*/' . $file ),
		(array) glob( $base . $file )
	);

	foreach ( array_filter( $hits ) as $hit ) {
		$relative = ltrim( str_replace( $base, '', $hit ), '/' );
		// attachment_url_to_postid() is unreliable for this; match the stored
		// _wp_attached_file path directly.
		$attached = get_posts( array(
			'post_type'      => 'attachment',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_query'     => array( array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'key'     => '_wp_attached_file',
				'value'   => $relative,
				'compare' => '=',
			) ),
		) );
		if ( ! $attached ) {
			return $hit;
		}
	}
	return '';
}

$stjo_seed_dir = get_template_directory() . '/inc/seed';
$stjo_img_dir  = get_template_directory() . '/assets/images/history';

$stjo_images = json_decode( (string) file_get_contents( $stjo_seed_dir . '/history-images.json' ), true );
$stjo_events = json_decode( (string) file_get_contents( $stjo_seed_dir . '/history-timeline.json' ), true );

if ( ! is_array( $stjo_images ) || ! is_array( $stjo_events ) ) {
	stjo_seed_abort( 'Could not read history-images.json / history-timeline.json' );
}

/* ---------------------------------------------------------------- images -- */

stjo_seed_say( '== Images ==' );
$stjo_img_ids  = array();
$stjo_imported = 0;
$stjo_reused   = 0;
$stjo_adopted  = 0;

foreach ( $stjo_images as $stjo_file => $stjo_alt ) {
	$existing = stjo_seeded_image( $stjo_file );
	if ( $existing['id'] ) {
		// Keep alt text authoritative in history-images.json, not the library.
		update_post_meta( $existing['id'], '_wp_attachment_image_alt', $stjo_alt );
		$stjo_img_ids[ $stjo_file ] = $existing['id'];
		$stjo_reused++;
		continue;
	}

	// Someone else's attachment for the same file, typically media-sync having
	// registered what a deploy push dropped into uploads. Adopt it (tag it, give
	// it its alt text) rather than uploading a suffixed second copy.
	$adopted = stjo_seed_find_attachment_by_filename( $stjo_file );
	if ( $adopted ) {
		update_post_meta( $adopted, '_wp_attachment_image_alt', $stjo_alt );
		update_post_meta( $adopted, '_stjo_seed_source', $stjo_file );
		$stjo_img_ids[ $stjo_file ] = $adopted;
		$stjo_adopted++;
		continue;
	}

	$path = $stjo_img_dir . '/' . $stjo_file;
	if ( ! file_exists( $path ) ) {
		stjo_seed_say( '  MISSING FILE: ' . $stjo_file );
		continue;
	}

	$bytes = (string) file_get_contents( $path );

	// A files-only WPE Connect push carries wp-content/uploads too (MagicSync
	// off = whole tree), so these very filenames can already be sitting in the
	// target uploads dir with no attachment row behind them, since the DB was not
	// pushed. wp_upload_bits() would then dodge the "collision" by writing
	// history-thrift-store-1.jpg, which is exactly the suffix the namespacing
	// was meant to prevent. Adopt the orphan file instead.
	$orphan = stjo_seed_find_orphan_upload( $stjo_file );
	if ( $orphan ) {
		// The theme copy is the source of truth; refresh only if they differ.
		if ( md5_file( $orphan ) !== md5( $bytes ) ) {
			file_put_contents( $orphan, $bytes ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		}
		$file_path = $orphan;
	} else {
		$upload = wp_upload_bits( $stjo_file, null, $bytes );
		if ( ! empty( $upload['error'] ) ) {
			stjo_seed_say( '  upload failed: ' . $stjo_file . ': ' . $upload['error'] );
			continue;
		}
		$file_path = $upload['file'];
	}

	$type   = wp_check_filetype( $file_path, null );
	$att_id = wp_insert_attachment( array(
		'post_mime_type' => $type['type'],
		'post_title'     => sanitize_text_field( pathinfo( $stjo_file, PATHINFO_FILENAME ) ),
		'post_status'    => 'inherit',
	), $file_path );

	if ( is_wp_error( $att_id ) || ! $att_id ) {
		stjo_seed_say( '  attachment insert failed: ' . $stjo_file );
		continue;
	}

	wp_update_attachment_metadata( $att_id, wp_generate_attachment_metadata( $att_id, $file_path ) );
	update_post_meta( $att_id, '_wp_attachment_image_alt', $stjo_alt );
	update_post_meta( $att_id, '_stjo_seed_source', $stjo_file ); // how stjo_seeded_image() finds it again
	$stjo_img_ids[ $stjo_file ] = (int) $att_id;
	$stjo_imported++;
}
stjo_seed_say( sprintf(
	'  %d imported, %d already seeded, %d adopted from the library, %d total',
	$stjo_imported,
	$stjo_reused,
	$stjo_adopted,
	count( $stjo_img_ids )
) );

/* ------------------------------------------------------- timeline events -- */

stjo_seed_say( '== Timeline events ==' );

// wp_delete_post() one at a time, because the wp-cli batch form (`wp post delete a b c`)
// silently deletes only the first ID on this stack.
$stjo_old = get_posts( array(
	'post_type'      => 'timeline-event',
	'post_status'    => 'any',
	'posts_per_page' => -1,
	'fields'         => 'ids',
) );
foreach ( $stjo_old as $stjo_old_id ) {
	wp_delete_post( $stjo_old_id, true );
}
stjo_seed_say( sprintf( '  %d existing events removed', count( $stjo_old ) ) );

$stjo_made   = 0;
$stjo_imaged = 0;
foreach ( $stjo_events as $stjo_e ) {
	$content = '';
	foreach ( $stjo_e['paragraphs'] as $stjo_p ) {
		$content .= "<!-- wp:paragraph -->\n<p>" . $stjo_p . "</p>\n<!-- /wp:paragraph -->\n\n";
	}

	$post_id = wp_insert_post( array(
		'post_type'    => 'timeline-event',
		'post_status'  => 'publish',
		'post_title'   => $stjo_e['title'],
		'post_content' => trim( $content ),
		'menu_order'   => 0,
	), true );

	if ( is_wp_error( $post_id ) ) {
		stjo_seed_say( '  insert failed: ' . $stjo_e['title'] );
		continue;
	}

	update_post_meta( $post_id, 'stjo_timeline_year', (int) $stjo_e['year'] );
	if ( ! empty( $stjo_e['end_year'] ) ) {
		update_post_meta( $post_id, 'stjo_timeline_end_year', (int) $stjo_e['end_year'] );
	}
	update_post_meta( $post_id, 'stjo_timeline_image_layout', $stjo_e['layout'] );
	update_post_meta( $post_id, 'stjo_timeline_image_focus', 'center center' );

	// wp_insert_post() does not fire the admin save handler, so the year and
	// decade terms have to be synced by hand or decade grouping comes out empty.
	stjo_timeline_sync_terms( $post_id );

	if ( ! empty( $stjo_e['image'] ) && ! empty( $stjo_img_ids[ $stjo_e['image'] ] ) ) {
		set_post_thumbnail( $post_id, $stjo_img_ids[ $stjo_e['image'] ] );
		$stjo_imaged++;
	}
	$stjo_made++;
}
stjo_seed_say( sprintf( '  %d events created, %d with featured images', $stjo_made, $stjo_imaged ) );

/* ----------------------------------------------------------- page content -- */

stjo_seed_say( '== Page content ==' );
$stjo_page = get_page_by_path( 'our-history' );
if ( ! $stjo_page ) {
	stjo_seed_abort( 'No page with slug our-history' );
}

$stjo_content_file = get_template_directory() . '/inc/page-content/history.php';
if ( ! file_exists( $stjo_content_file ) ) {
	stjo_seed_abort( 'Missing inc/page-content/history.php' );
}

ob_start();
include $stjo_content_file;
$stjo_markup = trim( (string) ob_get_clean() );

if ( '' === $stjo_markup ) {
	stjo_seed_abort( 'history.php produced no markup' );
}

wp_update_post( array(
	'ID'           => $stjo_page->ID,
	'post_content' => $stjo_markup,
) );
stjo_seed_say( sprintf( '  page #%d (/our-history/) updated, %d bytes', $stjo_page->ID, strlen( $stjo_markup ) ) );

wp_cache_flush();
stjo_seed_say( 'Done.' );
