<?php
/**
 * Alumni story importer. DEV TOOL — delete before launch (like seed.php).
 *
 * Scrapes https://www.stjo.org/native-american-children/alumni/: each person
 * block's a.AlumniImg pair carries the photo, caption and blog href. Each
 * href (blog.stjo.org) is fetched for an excerpt (first ~50 words of
 * .entry-content, og:description fallback). Creates published student-story
 * posts in story-category Alumni with:
 *   caption -> post_title, photo -> featured image (alt from the page),
 *   excerpt -> post_excerpt + a content paragraph linking the full story,
 *   href    -> stjo_external_url meta (archive cards link there, new tab).
 * Idempotent: existing Alumni titles are skipped.
 *
 * Usage: wp eval-file wp-content/themes/stjo-dgd/importers/import-alumni.php
 *
 * @package stjo
 */

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$source = 'https://www.stjo.org/native-american-children/alumni/';
$res    = wp_remote_get( $source, array( 'timeout' => 30 ) );
if ( is_wp_error( $res ) || 200 !== wp_remote_retrieve_response_code( $res ) ) {
	echo "Could not fetch $source\n";
	exit( 1 );
}
$page = wp_remote_retrieve_body( $res );

// Person blocks: <div id=Name class=col-xs-4> ... two a.AlumniImg (photo, caption).
preg_match_all( '#<div\s+id=\w+ class=col-xs-4>(.*?)</a></div>#s', $page, $blocks );
if ( ! $blocks[1] ) {
	echo "No alumni blocks found (page markup changed?)\n";
	exit( 1 );
}

if ( ! term_exists( 'Alumni', 'story-category' ) ) {
	wp_insert_term( 'Alumni', 'story-category' );
}

$made = 0;
$skipped = 0;
$fails = array();

foreach ( $blocks[1] as $i => $block ) {
	preg_match( '#class=AlumniImg href=([^\s>]+)#', $block, $href_m );
	preg_match( '#<noscript><img[^>]*src=([^\s>]+)#', $block, $img_m );
	preg_match( '#class=caption>(.*?)</div>#s', $block, $cap_m );
	preg_match( '#alt="([^"]+)"#', $block, $alt_m );

	$href    = $href_m ? trim( $href_m[1], '\'"' ) : '';
	$img     = $img_m ? trim( $img_m[1], '\'"' ) : '';
	$caption = $cap_m ? trim( wp_strip_all_tags( html_entity_decode( $cap_m[1], ENT_QUOTES | ENT_HTML5 ) ) ) : '';
	$alt     = $alt_m ? $alt_m[1] : $caption;

	if ( ! $href || ! $caption ) {
		$fails[] = "block $i: missing href/caption";
		continue;
	}
	if ( 0 === strpos( $img, '/' ) ) {
		$img = 'https://www.stjo.org' . $img;
	}

	$existing = get_posts( array(
		'post_type'   => 'student-story',
		'post_status' => 'any',
		'title'       => $caption,
		'numberposts' => -1,
		'tax_query'   => array( array( 'taxonomy' => 'story-category', 'field' => 'name', 'terms' => 'Alumni' ) ),
	) );
	if ( $existing ) {
		$skipped++;
		continue;
	}

	// Excerpt from the linked blog post.
	$excerpt = '';
	$post_res = wp_remote_get( set_url_scheme( $href, 'https' ), array( 'timeout' => 30 ) );
	if ( ! is_wp_error( $post_res ) && 200 === wp_remote_retrieve_response_code( $post_res ) ) {
		$body = wp_remote_retrieve_body( $post_res );
		if ( preg_match( '#class="entry-content[^"]*"(.*?)(<div class="|</article)#s', $body, $ec ) ) {
			// Photo captions and pull quotes lead many posts; drop them so the
			// excerpt starts with the story itself.
			$text    = preg_replace( '#<(figure|figcaption|blockquote)[^>]*>.*?</\1>#s', ' ', $ec[1] );
			$text    = trim( preg_replace( '/^[\s>]+/', '', wp_strip_all_tags( $text ) ) );
			$excerpt = wp_trim_words( $text, 36 );
		}
		if ( ! $excerpt && preg_match( '#property="og:description" content="([^"]+)"#', $body, $og ) ) {
			$excerpt = html_entity_decode( $og[1], ENT_QUOTES | ENT_HTML5 );
		}
	}
	if ( ! $excerpt ) {
		$fails[] = "$caption: no excerpt extracted from $href";
	}

	$post_id = wp_insert_post( array(
		'post_type'    => 'student-story',
		'post_status'  => 'publish',
		'post_title'   => $caption,
		'post_excerpt' => $excerpt,
		'post_content' => '<!-- wp:paragraph --><p>' . esc_html( $excerpt ) . '</p><!-- /wp:paragraph -->'
			. '<!-- wp:paragraph --><p><a href="' . esc_url( $href ) . '">Read the full story on the St. Joseph&#8217;s blog</a></p><!-- /wp:paragraph -->',
		// Page order survives newest-first queries. current_time: post_date is
		// a LOCAL time field, a UTC value here schedules posts into the future.
		'post_date'    => date( 'Y-m-d H:i:s', current_time( 'timestamp' ) - ( $i + 1 ) * 60 ),
	), true );
	if ( is_wp_error( $post_id ) ) {
		$fails[] = "$caption: " . $post_id->get_error_message();
		continue;
	}

	wp_set_object_terms( $post_id, 'Alumni', 'story-category' );
	update_post_meta( $post_id, 'stjo_external_url', esc_url_raw( $href ) );

	if ( $img ) {
		// Reuse an already-sideloaded copy of this exact URL if present.
		$found  = get_posts( array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_query'  => array( array( 'key' => '_source_url', 'value' => $img ) ),
		) );
		$att_id = $found ? $found[0] : media_sideload_image( $img, $post_id, $caption, 'id' );
		if ( is_wp_error( $att_id ) ) {
			$fails[] = "$caption image: " . $att_id->get_error_message();
		} else {
			set_post_thumbnail( $post_id, $att_id );
			if ( ! get_post_meta( $att_id, '_wp_attachment_image_alt', true ) ) {
				update_post_meta( $att_id, '_wp_attachment_image_alt', $alt );
			}
		}
	}

	echo "imported: $caption\n";
	$made++;
	usleep( 250000 );
}

echo "DONE: imported $made, skipped $skipped\n";
if ( $fails ) {
	echo "ISSUES:\n" . implode( "\n", $fails ) . "\n";
}
