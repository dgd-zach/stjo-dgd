<?php
/**
 * Internal (staff-only) pages: Pattern Library, STJO Block Training, the
 * example/template pages. Anything tagged with the `internal` page-category
 * term, plus whatever page uses page-pattern-library.php.
 *
 * They are not for the public or for crawlers:
 * - front end: logged-out visitors are sent to the login screen and back;
 *   a logged-in user without edit_posts gets a 404
 * - robots: noindex,nofollow (meta + X-Robots-Tag) and robots.txt Disallow
 * - left out of on-site search, the Yoast and core XML sitemaps, and the
 *   public REST API
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * IDs of every internal page (any status), cached per request.
 *
 * @return int[]
 */
function stjo_internal_page_ids() {
	static $ids = null;
	if ( null !== $ids ) {
		return $ids;
	}
	$common = array(
		'post_type'      => 'page',
		'post_status'    => array( 'publish', 'private', 'draft', 'pending', 'future' ),
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'no_found_rows'  => true,
	);
	$tagged = get_posts( $common + array(
		'tax_query' => array( array( 'taxonomy' => 'page-category', 'field' => 'slug', 'terms' => 'internal' ) ), // phpcs:ignore WordPress.DB.SlowDBQuery
	) );
	$library = get_posts( $common + array(
		'meta_key'   => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery
		'meta_value' => 'page-pattern-library.php', // phpcs:ignore WordPress.DB.SlowDBQuery
	) );
	$ids = array_values( array_unique( array_map( 'intval', array_merge( $tagged, $library ) ) ) );
	return $ids;
}

/**
 * @param int|WP_Post|null $post Page.
 * @return bool
 */
function stjo_is_internal_page( $post = null ) {
	$post = get_post( $post );
	return $post && 'page' === $post->post_type && in_array( (int) $post->ID, stjo_internal_page_ids(), true );
}

/** Who may see internal pages: the same audience as the Content Guide. */
function stjo_internal_can_view() {
	return is_user_logged_in() && current_user_can( 'edit_posts' );
}

/* -------------------------------------------------------------- front end -- */

/**
 * Gate the page itself. Runs early on template_redirect, before the theme's
 * other redirects, so nothing about the page is rendered for outsiders.
 */
function stjo_internal_page_gate() {
	if ( ! is_singular( 'page' ) || ! stjo_is_internal_page( get_queried_object_id() ) || stjo_internal_can_view() ) {
		return;
	}
	nocache_headers();
	header( 'X-Robots-Tag: noindex, nofollow', true );
	if ( ! is_user_logged_in() ) {
		auth_redirect(); // login, then straight back to the page
	}
	// Logged in, but not staff: behave as if the page did not exist.
	global $wp_query;
	$wp_query->set_404();
	status_header( 404 );
	nocache_headers();
	include get_query_template( '404' );
	exit;
}
add_action( 'template_redirect', 'stjo_internal_page_gate', 1 );

/** Robots meta for staff viewing the page (so a copied URL is never indexed). */
function stjo_internal_page_robots( $robots ) {
	if ( is_singular( 'page' ) && stjo_is_internal_page( get_queried_object_id() ) ) {
		$robots['noindex']   = true;
		$robots['nofollow']  = true;
		$robots['noarchive'] = true;
		unset( $robots['max-image-preview'] );
	}
	return $robots;
}
add_filter( 'wp_robots', 'stjo_internal_page_robots', 20 );

/** robots.txt: keep crawlers off the URLs too. */
function stjo_internal_pages_robots_txt( $output, $public ) {
	if ( ! $public ) {
		return $output;
	}
	$lines = '';
	foreach ( stjo_internal_page_ids() as $id ) {
		$path = wp_parse_url( get_permalink( $id ), PHP_URL_PATH );
		if ( $path && '/' !== $path ) {
			$lines .= 'Disallow: ' . $path . "\n";
		}
	}
	return $lines ? $output . "\n# Staff-only pages\nUser-agent: *\n" . $lines : $output;
}
add_filter( 'robots_txt', 'stjo_internal_pages_robots_txt', 10, 2 );

/* --------------------------------------------------- search + sitemaps + REST -- */

/** On-site search never lists them (for anyone, so results do not differ by login). */
function stjo_internal_pages_exclude_search( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}
	$ids = stjo_internal_page_ids();
	if ( $ids ) {
		$query->set( 'post__not_in', array_merge( (array) $query->get( 'post__not_in' ), $ids ) );
	}
}
add_action( 'pre_get_posts', 'stjo_internal_pages_exclude_search' );

/** Yoast XML sitemap. */
add_filter( 'wpseo_exclude_from_sitemap_by_post_ids', function ( $ids ) {
	return array_merge( (array) $ids, stjo_internal_page_ids() );
} );

/** Core XML sitemap (in case Yoast's is ever switched off). */
add_filter( 'wp_sitemaps_posts_query_args', function ( $args, $post_type ) {
	if ( 'page' === $post_type ) {
		$args['post__not_in'] = array_merge( (array) ( $args['post__not_in'] ?? array() ), stjo_internal_page_ids() );
	}
	return $args;
}, 10, 2 );

/** Public REST API: not in the pages collection, and no single-item read. */
add_filter( 'rest_page_query', function ( $args ) {
	if ( ! stjo_internal_can_view() ) {
		$args['post__not_in'] = array_merge( (array) ( $args['post__not_in'] ?? array() ), stjo_internal_page_ids() );
	}
	return $args;
} );
add_filter( 'rest_request_before_callbacks', function ( $response, $handler, $request ) {
	if ( ! stjo_internal_can_view() && preg_match( '#^/wp/v2/pages/(\d+)$#', $request->get_route(), $m ) && stjo_is_internal_page( (int) $m[1] ) ) {
		return new WP_Error( 'rest_post_invalid_id', __( 'Invalid post ID.', 'stjo' ), array( 'status' => 404 ) );
	}
	return $response;
}, 10, 3 );
