<?php
/**
 * Publish-date backfill for external-link stories. DEV TOOL — delete before
 * launch. For every student-story with an stjo_external_url, fetches the
 * source post and copies its exact article:published_time into post_date
 * (converted to site-local time). Sections order newest-first, so this makes
 * the story order mirror real blog chronology.
 *
 * Usage: wp eval-file wp-content/themes/stjo-dgd/importers/backfill-dates.php
 *
 * @package stjo
 */

$posts = get_posts( array(
	'post_type'   => 'student-story',
	'post_status' => 'any',
	'numberposts' => -1,
	'meta_key'    => 'stjo_external_url',
) );

$updated = 0;
$fails = array();

foreach ( $posts as $p ) {
	$url = get_post_meta( $p->ID, 'stjo_external_url', true );
	$res = wp_remote_get( set_url_scheme( $url, 'https' ), array( 'timeout' => 30 ) );
	if ( is_wp_error( $res ) || 200 !== wp_remote_retrieve_response_code( $res ) ) {
		$fails[] = $p->post_title . ': fetch failed';
		continue;
	}
	if ( ! preg_match( '#property="article:published_time" content="([^"]+)"#', wp_remote_retrieve_body( $res ), $m ) ) {
		$fails[] = $p->post_title . ': no published_time meta';
		continue;
	}
	$ts = strtotime( $m[1] ); // ISO 8601 with offset -> UTC timestamp
	if ( ! $ts ) {
		$fails[] = $p->post_title . ': unparseable date ' . $m[1];
		continue;
	}
	$gmt   = gmdate( 'Y-m-d H:i:s', $ts );
	$local = get_date_from_gmt( $gmt );
	wp_update_post( array(
		'ID'            => $p->ID,
		'post_date'     => $local,
		'post_date_gmt' => $gmt,
	) );
	$updated++;
	usleep( 150000 );
}

echo "DONE: dated $updated of " . count( $posts ) . " external stories\n";
if ( $fails ) {
	echo "ISSUES:\n" . implode( "\n", $fails ) . "\n";
}
