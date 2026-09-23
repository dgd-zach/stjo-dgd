<?php
/**
 * Student Stories placeholder importer. DEV TOOL — delete before launch.
 *
 * Per the PM, the Student Stories section features general blog posts about
 * students until the client supplies real picks. Pulls the latest posts from
 * the blog.stjo.org RSS feed and creates external-link story cards (same
 * shape as the alumni import): title, excerpt from the post's
 * .entry-content, og:image as the featured image, blog URL in
 * stjo_external_url. Category: Student Stories.
 *
 * Usage: wp eval-file wp-content/themes/stjo-dgd/importers/import-blog-stories.php [count]
 *
 * @package stjo
 */

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$count = isset( $args[0] ) ? max( 1, (int) $args[0] ) : 6;

$res = wp_remote_get( 'https://blog.stjo.org/feed/', array( 'timeout' => 30 ) );
if ( is_wp_error( $res ) || 200 !== wp_remote_retrieve_response_code( $res ) ) {
	echo "Could not fetch the blog feed\n";
	exit( 1 );
}
preg_match_all( '#<item>(.*?)</item>#s', wp_remote_retrieve_body( $res ), $items );
$items = array_slice( $items[1], 0, $count );

if ( ! term_exists( 'Student Stories', 'story-category' ) ) {
	wp_insert_term( 'Student Stories', 'story-category' );
}

$made = 0;
$skipped = 0;
$fails = array();

foreach ( $items as $i => $item ) {
	preg_match( '#<title>(?:<!\[CDATA\[)?(.*?)(?:\]\]>)?</title>#s', $item, $t );
	preg_match( '#<link>(.*?)</link>#', $item, $l );
	$title = $t ? trim( html_entity_decode( $t[1], ENT_QUOTES | ENT_HTML5 ) ) : '';
	$href  = $l ? trim( $l[1] ) : '';
	if ( ! $title || ! $href ) {
		continue;
	}

	$existing = get_posts( array(
		'post_type'   => 'student-story',
		'post_status' => 'any',
		'title'       => $title,
		'numberposts' => -1,
	) );
	if ( $existing ) {
		$skipped++;
		continue;
	}

	// Excerpt + photo from the post itself.
	$excerpt = '';
	$img     = '';
	$post_res = wp_remote_get( $href, array( 'timeout' => 30 ) );
	if ( ! is_wp_error( $post_res ) && 200 === wp_remote_retrieve_response_code( $post_res ) ) {
		$body = wp_remote_retrieve_body( $post_res );
		if ( preg_match( '#class="entry-content[^"]*"(.*?)(<div class="|</article)#s', $body, $ec ) ) {
			$text    = preg_replace( '#<(figure|figcaption|blockquote)[^>]*>.*?</\1>#s', ' ', $ec[1] );
			$text    = trim( preg_replace( '/^[\s>]+/', '', wp_strip_all_tags( $text ) ) );
			$excerpt = wp_trim_words( $text, 36 );
		}
		if ( ! $excerpt && preg_match( '#property="og:description" content="([^"]+)"#', $body, $og ) ) {
			$excerpt = html_entity_decode( $og[1], ENT_QUOTES | ENT_HTML5 );
		}
		if ( preg_match( '#property="og:image" content="([^"]+)"#', $body, $ogi ) ) {
			$img = html_entity_decode( $ogi[1], ENT_QUOTES | ENT_HTML5 );
		}
	}

	$post_id = wp_insert_post( array(
		'post_type'    => 'student-story',
		'post_status'  => 'publish',
		'post_title'   => $title,
		'post_excerpt' => wp_trim_words( $excerpt, 36 ),
		'post_content' => '<!-- wp:paragraph --><p>' . esc_html( $excerpt ) . '</p><!-- /wp:paragraph -->'
			. '<!-- wp:paragraph --><p><a href="' . esc_url( $href ) . '">Read the full story on the St. Joseph&#8217;s blog</a></p><!-- /wp:paragraph -->',
		// post_date is LOCAL time; feed order survives newest-first queries.
		'post_date'    => date( 'Y-m-d H:i:s', current_time( 'timestamp' ) - ( $i + 1 ) * 60 ),
	), true );
	if ( is_wp_error( $post_id ) ) {
		$fails[] = "$title: " . $post_id->get_error_message();
		continue;
	}

	wp_set_object_terms( $post_id, 'Student Stories', 'story-category' );
	update_post_meta( $post_id, 'stjo_external_url', esc_url_raw( $href ) );

	if ( $img ) {
		$found  = get_posts( array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_query'  => array( array( 'key' => '_source_url', 'value' => $img ) ),
		) );
		$att_id = $found ? $found[0] : media_sideload_image( $img, $post_id, $title, 'id' );
		if ( is_wp_error( $att_id ) ) {
			$fails[] = "$title image: " . $att_id->get_error_message();
		} else {
			set_post_thumbnail( $post_id, $att_id );
			if ( ! get_post_meta( $att_id, '_wp_attachment_image_alt', true ) ) {
				update_post_meta( $att_id, '_wp_attachment_image_alt', $title );
			}
		}
	}

	echo "imported: $title\n";
	$made++;
	usleep( 250000 );
}

echo "DONE: imported $made, skipped $skipped\n";
if ( $fails ) {
	echo "ISSUES:\n" . implode( "\n", $fails ) . "\n";
}
