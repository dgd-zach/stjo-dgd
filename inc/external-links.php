<?php
/**
 * External links open in a new tab, site-wide.
 *
 * Content links are stored with target/rel in the editor (the house rule in
 * the Content Guide). Everything the THEME prints (mega nav, footer menu and
 * social links, header CTAs, the Your Generosity band, blog cards) goes
 * through stjo_external_link_attrs() so it needs no per-link setting, and a
 * small front-end script adds the screen-reader "(opens in a new tab)" hint
 * to every new-tab link, content included, so the behaviour is announced.
 *
 * "External" = an http(s) URL whose host is neither this site nor the main
 * stjo.org domain (those links become internal at launch). Link-out pages
 * (theme-config link_outs) already resolve to their off-site URL before they
 * reach these helpers, so they count as external too.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Is this URL off-site?
 *
 * @param string $url Any href.
 * @return bool
 */
function stjo_is_external_url( $url ) {
	$url = html_entity_decode( (string) $url, ENT_QUOTES );
	// A root-relative link to one of the theme-config link-out pages leads
	// off-site too (the page only redirects), so it opens in a new tab like
	// the destination would.
	if ( '/' === substr( $url, 0, 1 ) && '//' !== substr( $url, 0, 2 ) ) {
		return in_array( '/' . trim( (string) wp_parse_url( $url, PHP_URL_PATH ), '/' ) . '/', stjo_link_out_paths(), true );
	}
	if ( ! preg_match( '#^https?://#i', $url ) ) {
		return false;
	}
	$host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
	if ( '' === $host ) {
		return false;
	}
	$home = strtolower( (string) wp_parse_url( home_url(), PHP_URL_HOST ) );
	$internal = array_filter( array( $home, 'www.stjo.org', 'stjo.org' ) );
	return ! in_array( $host, $internal, true );
}

/**
 * Root-relative paths of the link-out pages (theme-config `link_outs`),
 * computed once per request.
 *
 * @return string[]
 */
function stjo_link_out_paths() {
	static $paths = null;
	if ( null !== $paths ) {
		return $paths;
	}
	$paths = array();
	if ( function_exists( 'stjo_link_outs' ) ) {
		foreach ( array_keys( (array) stjo_link_outs() ) as $slug ) {
			$pages = get_posts( array( 'post_type' => 'page', 'name' => $slug, 'post_status' => 'publish', 'posts_per_page' => 1, 'no_found_rows' => true ) );
			if ( $pages ) {
				$paths[] = '/' . trim( get_page_uri( $pages[0] ), '/' ) . '/';
			}
		}
	}
	return $paths;
}

/**
 * Attribute string for an anchor: new tab + safe rel when external, else ''.
 * Ready to drop straight after the href attribute (leading space included).
 *
 * @param string $url The anchor's href.
 * @return string
 */
function stjo_external_link_attrs( $url ) {
	return stjo_is_external_url( $url ) ? ' target="_blank" rel="noopener noreferrer"' : '';
}

/**
 * Menus rendered by WordPress's own walker (the footer): external items open
 * in a new tab even when the "open in new tab" box was never ticked.
 *
 * @param array   $atts Anchor attributes.
 * @param WP_Post $item Menu item.
 * @return array
 */
function stjo_menu_external_link_attributes( $atts, $item ) {
	if ( empty( $atts['target'] ) && ! empty( $atts['href'] ) && stjo_is_external_url( $atts['href'] ) ) {
		$atts['target'] = '_blank';
		$rel            = preg_split( '#\s+#', trim( (string) ( $atts['rel'] ?? '' ) ), -1, PREG_SPLIT_NO_EMPTY );
		$atts['rel']    = implode( ' ', array_unique( array_merge( $rel, array( 'noopener', 'noreferrer' ) ) ) );
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'stjo_menu_external_link_attributes', 10, 2 );

/**
 * Screen-reader hint for every new-tab link (progressive enhancement).
 */
function stjo_new_tab_hint_script() {
	wp_enqueue_script(
		'stjo-new-tab-hint',
		get_template_directory_uri() . '/assets/js/new-tab-hint.js',
		array(),
		(string) filemtime( get_template_directory() . '/assets/js/new-tab-hint.js' ),
		array( 'in_footer' => true, 'strategy' => 'defer' )
	);
	wp_localize_script( 'stjo-new-tab-hint', 'stjoNewTabHint', array(
		'text' => __( '(opens in a new tab)', 'stjo' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'stjo_new_tab_hint_script' );
