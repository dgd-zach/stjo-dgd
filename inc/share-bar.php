<?php
/**
 * Share Bar: fill each sharer link's empty u=/url= param with the rendered
 * page's canonical URL at render time, so the buttons are pre-set to share
 * the page they sit on — real hrefs in the HTML, no JS required. The X link
 * also gets a predrafted post body (text=); Facebook and LinkedIn ignore
 * prefilled text and build their preview from the page's OG tags instead.
 *
 * assets/js/share.js stays as the fallback for contexts this render pass
 * doesn't cover: it only touches params that are still empty, so the two
 * never double-fill.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stjo_share_bar_fill_urls( $block_content ) {
	if ( ! is_string( $block_content ) || false === strpos( $block_content, 'stjo-share-btn' ) ) {
		return $block_content;
	}
	$page_url = wp_get_canonical_url();
	if ( ! $page_url ) {
		$page_url = get_permalink();
	}
	if ( ! $page_url ) {
		return $block_content;
	}
	return preg_replace_callback(
		'/href="([^"]*[?&](?:u|url)=)"/',
		function ( $m ) use ( $page_url ) {
			$url = $m[1] . rawurlencode( $page_url );
			if ( preg_match( '#^https?://(www\.)?(x|twitter)\.com/#i', $url ) ) {
				$url .= '&text=' . rawurlencode( wp_strip_all_tags( get_the_title() ) );
			}
			return 'href="' . esc_url( $url ) . '"';
		},
		$block_content
	);
}
add_filter( 'render_block_core/button', 'stjo_share_bar_fill_urls' );
