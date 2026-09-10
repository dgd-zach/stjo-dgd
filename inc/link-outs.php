<?php
/**
 * Link-out pages: WP pages that exist for site structure (nav hierarchy,
 * sitemap wireframe) but whose real destination is Luminate Online — the
 * one-time gift form, the DreamMaker monthly form, the prayer request /
 * builder / tie surveys, the prayer book download.
 *
 * Config-driven from theme-config.json `link_outs` (slug => URL), so the
 * client can be handed a new destination without a code change and nothing
 * lives in post content or menus that would need porting between
 * environments. Two layers:
 *
 *  1. get_permalink() for those pages returns the LO URL, so the nav panels,
 *     breadcrumbs, and any template building links from the page go straight
 *     to LO with no redirect hop. Frontend only — admin "View" links keep
 *     pointing at the WP page so editors can still reach it.
 *  2. A direct visit to the page's own URL (typed, bookmarked, or a hardcoded
 *     href in content) 302s to LO. 302 not 301: destinations are giving-form
 *     URLs with source codes that the client changes over time.
 *
 * Yoast is told to leave these pages out of the sitemap, since listing them
 * would either advertise a redirect or an off-site URL.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * slug => URL map from theme-config, minus the comment key.
 */
function stjo_link_outs() {
	$map = (array) stjo_config_get( 'link_outs', array() );
	unset( $map['_comment'] );
	return array_filter( $map, 'is_string' );
}

/**
 * The LO URL for a page, or '' if it is not a link-out.
 */
function stjo_link_out_url( $post ) {
	$post = get_post( $post );
	if ( ! $post || 'page' !== $post->post_type ) {
		return '';
	}
	$map = stjo_link_outs();
	return isset( $map[ $post->post_name ] ) ? $map[ $post->post_name ] : '';
}

function stjo_link_out_permalink( $link, $post_id ) {
	if ( is_admin() ) {
		return $link;
	}
	$url = stjo_link_out_url( $post_id );
	return $url ? $url : $link;
}
add_filter( 'page_link', 'stjo_link_out_permalink', 10, 2 );

function stjo_link_out_redirect() {
	if ( ! is_page() || is_preview() ) {
		return;
	}
	$url = stjo_link_out_url( get_queried_object_id() );
	if ( $url ) {
		wp_redirect( $url, 302 ); // phpcs:ignore WordPress.Security.SafeRedirect -- deliberately off-site (give.stjo.org).
		exit;
	}
}
add_action( 'template_redirect', 'stjo_link_out_redirect' );

/**
 * Menu items for these pages were saved as custom links with the internal
 * path (/prayers/prayer-tie/ etc.), which the page_link filter never sees.
 * Rewrite them as each item is set up so the nav links straight to LO with
 * no redirect hop. Matches on path, so it survives the host changing between
 * local, staging and production.
 */
function stjo_link_out_menu_item( $item ) {
	if ( is_admin() || empty( $item->url ) || 'custom' !== ( $item->type ?? '' ) ) {
		return $item;
	}
	static $by_path = null;
	if ( null === $by_path ) {
		$by_path = array();
		foreach ( stjo_link_outs() as $slug => $url ) {
			$page = get_posts( array( 'post_type' => 'page', 'name' => $slug, 'numberposts' => 1, 'fields' => 'ids' ) );
			if ( $page ) {
				$by_path[ '/' . trim( get_page_uri( $page[0] ), '/' ) ] = $url;
			}
		}
	}
	$path = '/' . trim( (string) wp_parse_url( $item->url, PHP_URL_PATH ), '/' );
	if ( isset( $by_path[ $path ] ) ) {
		$item->url = $by_path[ $path ];
	}
	return $item;
}
add_filter( 'wp_setup_nav_menu_item', 'stjo_link_out_menu_item' );

function stjo_link_out_sitemap_exclude( $ids ) {
	$slugs = array_keys( stjo_link_outs() );
	if ( ! $slugs ) {
		return $ids;
	}
	$pages = get_posts( array(
		'post_type'      => 'page',
		'post_name__in'  => $slugs,
		'posts_per_page' => -1,
		'fields'         => 'ids',
	) );
	return array_merge( (array) $ids, $pages );
}
add_filter( 'wpseo_exclude_from_sitemap_by_post_ids', 'stjo_link_out_sitemap_exclude' );
