<?php
/**
 * Student story CSV importer. DEV TOOL — delete before launch (like seed.php).
 *
 * Usage (from the WP root, with this site's --exec socket define):
 *   wp eval-file wp-content/themes/stjo-dgd/importers/import-stories.php <csv> <story-category> [excerpt-words]
 *   e.g. wp eval-file .../import-stories.php eigth-grade.csv "Eighth Grade Graduates"
 *
 * CSV columns: year, name, image_url, content.
 * Each row becomes a published student-story:
 *   name    -> post_title
 *   content -> post_content (paragraph block) + excerpt (first N words, default 36)
 *   year    -> story-year term (created if missing) + post_date {year}-06-01
 *              (minus row-index minutes, so CSV order = date order within a year)
 *   image   -> sideloaded into the media library once per URL (dedupes on the
 *              _source_url meta WP stores), set as featured image, alt = name
 * Always: story-category (the section the story renders in).
 * Idempotent: rows whose title + year already exist are skipped.
 *
 * @package stjo
 */

if ( empty( $args[0] ) || empty( $args[1] ) ) {
	echo "Usage: wp eval-file import-stories.php <csv> <story-category> [excerpt-words]\n";
	exit( 1 );
}

$csv_path = $args[0];
foreach ( array( get_template_directory() . '/importers/', get_template_directory() . '/' ) as $stjo_base ) {
	if ( ! file_exists( $csv_path ) ) {
		$csv_path = $stjo_base . ltrim( $args[0], '/' );
	}
}
if ( ! file_exists( $csv_path ) ) {
	echo "CSV not found: {$args[0]}\n";
	exit( 1 );
}

$cat_name      = $args[1];
$excerpt_words = isset( $args[2] ) ? max( 1, (int) $args[2] ) : 36;

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

if ( ! term_exists( $cat_name, 'story-category' ) ) {
	wp_insert_term( $cat_name, 'story-category' );
}

$fh      = fopen( $csv_path, 'r' );
$headers = array_map( function ( $h ) {
	return strtolower( trim( str_replace( "\xEF\xBB\xBF", '', $h ) ) );
}, fgetcsv( $fh ) );

$made = 0;
$skipped = 0;
$img_fail = array();
$row_i = 0;

while ( ( $cols = fgetcsv( $fh ) ) !== false ) {
	$row_i++;
	$row = array_combine( $headers, array_pad( $cols, count( $headers ), '' ) );

	$name    = trim( $row['name'] ?? '' );
	$year    = trim( $row['year'] ?? '' );
	$content = trim( $row['content'] ?? '' );
	$img_url = trim( $row['image_url'] ?? '' );

	if ( ! $name || ! preg_match( '/^\d{4}$/', $year ) ) {
		echo "row $row_i: SKIP (bad name/year)\n";
		$skipped++;
		continue;
	}

	// Idempotency: same title + same year term = already imported.
	$existing = get_posts( array(
		'post_type'   => 'student-story',
		'post_status' => 'any',
		'title'       => $name,
		'numberposts' => -1,
		'tax_query'   => array( array( 'taxonomy' => 'story-year', 'field' => 'name', 'terms' => $year ) ),
	) );
	if ( $existing ) {
		$skipped++;
		continue;
	}

	if ( ! term_exists( $year, 'story-year' ) ) {
		wp_insert_term( $year, 'story-year' );
	}

	$post_id = wp_insert_post( array(
		'post_type'    => 'student-story',
		'post_status'  => 'publish',
		'post_title'   => $name,
		'post_content' => '<!-- wp:paragraph --><p>' . wp_kses_post( $content ) . '</p><!-- /wp:paragraph -->',
		'post_excerpt' => wp_trim_words( $content, $excerpt_words ),
		// {year}-06-01 minus row-index minutes: CSV order = date order within
		// a year (the archive queries newest-first, so earlier rows rank first).
		'post_date'    => gmdate( 'Y-m-d H:i:s', strtotime( "$year-06-01 12:00:00" ) - $row_i * 60 ),
	), true );
	if ( is_wp_error( $post_id ) ) {
		echo "row $row_i ($name): ERROR " . $post_id->get_error_message() . "\n";
		continue;
	}

	wp_set_object_terms( $post_id, $cat_name, 'story-category' );
	wp_set_object_terms( $post_id, $year, 'story-year' );

	if ( $img_url ) {
		// Reuse an already-sideloaded copy of this exact URL if present.
		$found = get_posts( array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_query'  => array( array( 'key' => '_source_url', 'value' => $img_url ) ),
		) );
		$att_id = $found ? $found[0] : media_sideload_image( $img_url, $post_id, $name, 'id' );
		if ( is_wp_error( $att_id ) ) {
			$img_fail[] = "$name ($year): " . $att_id->get_error_message();
		} else {
			set_post_thumbnail( $post_id, $att_id );
			if ( ! get_post_meta( $att_id, '_wp_attachment_image_alt', true ) ) {
				update_post_meta( $att_id, '_wp_attachment_image_alt', $name );
			}
		}
		usleep( 200000 ); // be gentle with the remote host
	}

	$made++;
	if ( 0 === $made % 20 ) {
		echo "... $made imported\n";
	}
}
fclose( $fh );

echo "DONE: imported $made, skipped $skipped (already present / bad rows)\n";
if ( $img_fail ) {
	echo "IMAGE FAILURES (" . count( $img_fail ) . "):\n" . implode( "\n", $img_fail ) . "\n";
}
