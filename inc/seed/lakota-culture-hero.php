<?php
/**
 * Lakota Culture landing: swap the Page Title Band for the Page Hero pattern.
 *
 *   wp eval-file wp-content/themes/stjo-dgd/inc/seed/lakota-culture-hero.php
 *
 * Section landings carry the photo hero (Youth Programs, Your Impact, Support
 * Us); Lakota Culture still had the blue title band. This replaces that one
 * leading group (and the stray zigzag separator after it, the ribbon is CSS)
 * with a 450px hero on the "man playing a traditional flute" photo from the
 * homepage carousel, keeping the band's eyebrow and H1. Nothing else in the
 * content changes. Re-runnable: a page already on the hero is left alone.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/seed-lib.php';

$stjo_page = get_posts( array( 'post_type' => 'page', 'name' => 'lakota-culture', 'post_status' => 'any', 'posts_per_page' => 1 ) );
if ( ! $stjo_page ) {
	stjo_seed_abort( 'No lakota-culture page.' );
}
$stjo_page = $stjo_page[0];
$content   = $stjo_page->post_content;

if ( false !== strpos( $content, 'stjo-page-hero' ) ) {
	stjo_seed_say( 'lakota-culture already has the page hero, nothing to do.' );
	return;
}

$att_id = stjo_seed_find_attachment_by_filename( 'SlideEvent.jpg' );
if ( ! $att_id ) {
	stjo_seed_abort( 'SlideEvent.jpg is not in the media library.' );
}
$url = (string) wp_make_link_relative( (string) wp_get_attachment_url( $att_id ) );
$alt = (string) get_post_meta( $att_id, '_wp_attachment_image_alt', true );
if ( '' === $alt ) {
	$alt = 'A man playing a traditional flute';
	update_post_meta( $att_id, '_wp_attachment_image_alt', $alt );
}

// The leading title band: one flat group (no nested groups), so the first
// closing group marker after it is its own. Pull eyebrow + H1 from it.
if ( ! preg_match( '/^\s*<!-- wp:group \{"metadata":\{"name":"Page Title Band"\}.*?<!-- \/wp:group -->\s*(?:<!-- wp:separator[^\n]*-->\s*<hr[^>]*>\s*<!-- \/wp:separator -->\s*)?/s', $content, $band ) ) {
	stjo_seed_abort( 'Content does not start with the Page Title Band; not touching it.' );
}
preg_match( '/<p class="[^"]*is-style-eyebrow[^"]*">(.*?)<\/p>/s', $band[0], $eyebrow );
preg_match( '/<h1[^>]*>(.*?)<\/h1>/s', $band[0], $h1 );
$eyebrow = isset( $eyebrow[1] ) ? trim( $eyebrow[1] ) : 'Lakota Culture';
$h1      = isset( $h1[1] ) ? trim( $h1[1] ) : get_the_title( $stjo_page );

$attrs = serialize_block_attributes( array(
	'url'                => $url,
	'id'                 => (int) $att_id,
	'alt'                => $alt,
	'dimRatio'           => 0,
	'isUserOverlayColor' => true,
	'focalPoint'         => array( 'x' => 0.24, 'y' => 0.4 ),
	'minHeight'          => 450,
	'contentPosition'    => 'bottom center',
	'metadata'           => array( 'name' => 'Page Hero' ),
	'align'              => 'full',
	'className'          => 'stjo-page-hero',
) );

$hero = '<!-- wp:cover ' . $attrs . " -->\n"
	. '<div class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center stjo-page-hero" style="min-height:450px">'
	. '<img class="wp-block-cover__image-background wp-image-' . (int) $att_id . '" alt="' . esc_attr( $alt ) . '" src="' . esc_url( $url ) . '" style="object-position:24% 40%" data-object-fit="cover" data-object-position="24% 40%"/>'
	. '<span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span>'
	. '<div class="wp-block-cover__inner-container"><!-- wp:group {"align":"full","className":"hero-content","layout":{"type":"constrained"}} -->' . "\n"
	. '<div class="wp-block-group alignfull hero-content"><!-- wp:paragraph {"align":"center","textColor":"white","className":"is-style-eyebrow"} -->' . "\n"
	. '<p class="has-text-align-center is-style-eyebrow has-white-color has-text-color">' . $eyebrow . '</p>' . "\n"
	. '<!-- /wp:paragraph -->' . "\n\n"
	. '<!-- wp:heading {"textAlign":"center","level":1,"textColor":"white"} -->' . "\n"
	. '<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">' . $h1 . '</h1>' . "\n"
	. '<!-- /wp:heading --></div>' . "\n"
	. '<!-- /wp:group --></div></div>' . "\n"
	. '<!-- /wp:cover -->' . "\n\n";

$new = $hero . substr( $content, strlen( $band[0] ) );
$res = wp_update_post( array( 'ID' => $stjo_page->ID, 'post_content' => wp_slash( $new ) ), true );
if ( is_wp_error( $res ) ) {
	stjo_seed_abort( $res->get_error_message() );
}
clean_post_cache( $stjo_page->ID );
wp_cache_flush();
if ( class_exists( 'WpeCommon' ) && method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
	WpeCommon::purge_varnish_cache();
}
stjo_seed_say( sprintf( 'lakota-culture (#%d): title band -> page hero on %s (#%d); removed %d chars, added %d.', $stjo_page->ID, basename( $url ), $att_id, strlen( $band[0] ), strlen( $hero ) ) );
