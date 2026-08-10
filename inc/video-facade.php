<?php
/**
 * YouTube embed facade.
 *
 * A YouTube Embed block renders a cross-origin <iframe> we can't overlay or
 * restyle, so the design's play button can't be applied to it directly. This
 * filter replaces the eager iframe with a lightweight poster + our own
 * .stjo-play button (see sections.css / play-video.js) and loads the real
 * player only when the button is clicked. Editors still see the normal embed
 * preview in the block editor; this only changes front-end output.
 *
 * The outer <figure class="wp-block-embed …"> and .wp-block-embed__wrapper are
 * kept exactly as core renders them, so responsive sizing and the overhang
 * system (which measures the figure's height) are unaffected.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Swap a rendered YouTube embed for the poster + play-button facade.
 *
 * @param string $content Rendered block HTML.
 * @param array  $block   Parsed block.
 * @return string
 */
function stjo_youtube_facade( $content, $block ) {
	if ( empty( $block['blockName'] ) || 'core/embed' !== $block['blockName'] ) {
		return $content;
	}
	$attrs = isset( $block['attrs'] ) ? $block['attrs'] : array();
	if ( ! isset( $attrs['providerNameSlug'] ) || 'youtube' !== $attrs['providerNameSlug'] ) {
		return $content;
	}
	$id = stjo_youtube_id( isset( $attrs['url'] ) ? $attrs['url'] : '' );
	if ( ! $id ) {
		return $content;
	}

	$player = 'https://www.youtube-nocookie.com/embed/' . rawurlencode( $id ) . '?autoplay=1&rel=0';
	$poster = 'https://i.ytimg.com/vi/' . rawurlencode( $id ) . '/maxresdefault.jpg';

	// Rebuild the figure classes core would output (align + type/provider +
	// the block's own className, which carries the aspect-ratio classes).
	$fig_class = 'wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube';
	if ( ! empty( $attrs['align'] ) ) {
		$fig_class .= ' align' . $attrs['align'];
	}
	if ( ! empty( $attrs['className'] ) ) {
		$fig_class .= ' ' . $attrs['className'];
	}

	$facade  = sprintf(
		'<div class="stjo-video-facade" data-embed-src="%1$s" data-title="%2$s">',
		esc_url( $player ),
		esc_attr__( 'Video player', 'stjo' )
	);
	// skip-lazy: Smush's lazy-loader otherwise swaps src for a 1x1 placeholder,
	// leaving the facade blank (same gotcha as the masked tile icons).
	$facade .= sprintf(
		'<img class="stjo-video-facade__poster skip-lazy" src="%1$s" alt="" width="1280" height="720" />',
		esc_url( $poster )
	);
	$facade .= '<button type="button" class="stjo-play" aria-label="' . esc_attr__( 'Play video', 'stjo' ) . '"></button>';
	$facade .= '</div>';

	return sprintf(
		'<figure class="%1$s"><div class="wp-block-embed__wrapper">%2$s</div></figure>',
		esc_attr( $fig_class ),
		$facade
	);
}
add_filter( 'render_block', 'stjo_youtube_facade', 10, 2 );

/**
 * Pull the 11-char video id out of any common YouTube URL form.
 *
 * @param string $url Watch / short / embed / youtu.be URL.
 * @return string Video id, or '' if none matched.
 */
function stjo_youtube_id( $url ) {
	if ( preg_match( '~(?:youtube(?:-nocookie)?\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/|v/)|youtu\.be/)([A-Za-z0-9_-]{11})~', (string) $url, $m ) ) {
		return $m[1];
	}
	return '';
}
