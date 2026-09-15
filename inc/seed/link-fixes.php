<?php
/**
 * Links the sitemap board (uXjVHzJD47c) expects on already-built pages.
 *
 *   wp eval-file wp-content/themes/stjo-dgd/inc/seed/link-fixes.php
 *
 * Each fix is an exact-string edit of one block on one page, applied only
 * when the old markup is still there and the new link is not, so re-runs and
 * pages the client has since edited are left alone. Revisions keep the
 * previous content.
 *
 *  - Your Impact: the DreamMaker and College Scholarship cards pointed at
 *    About / About Our Children with pattern leftover labels; they now point
 *    at the LO monthly form and the College Scholarship page.
 *  - About: the board lists Contact Us as a child; the second card row gains
 *    a Contact Us card beside Our Blog and Our Podcast.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/seed-lib.php';

$stjo_dreammaker = esc_url( (string) stjo_config_get( 'give.dreammaker_url', 'https://give.stjo.org/site/Donation2?mfc_pref=T&idb=444651902&df_id=10023&10023.donation=form1' ) );

$stjo_fixes = array(
	'your-impact' => array(
		array(
			'from' => 'href="/about/">See Our Mission</a>',
			'to'   => 'href="' . $stjo_dreammaker . '">Become a DreamMaker</a>',
			'skip_if' => 'idb=444651902',
		),
		array(
			'from' => 'href="/about/our-children/">Meet Our Children</a>',
			'to'   => 'href="/your-impact/college-scholarship/">About the Scholarship</a>',
			'skip_if' => '/your-impact/college-scholarship/',
		),
	),
	'about' => array(
		array(
			'from'    => 'href="/about/our-podcast/">Learn more</a></div>' . "\n" . '<!-- /wp:button --></div>' . "\n" . '<!-- /wp:buttons --></div></div>' . "\n" . '<!-- /wp:cover --></div>' . "\n" . '<!-- /wp:column --></div>' . "\n" . '<!-- /wp:columns -->',
			'to'      => 'href="/about/our-podcast/">Learn more</a></div>' . "\n" . '<!-- /wp:button --></div>' . "\n" . '<!-- /wp:buttons --></div></div>' . "\n" . '<!-- /wp:cover --></div>' . "\n" . '<!-- /wp:column -->' . "\n\n"
				. '<!-- wp:column -->' . "\n"
				. '<div class="wp-block-column"><!-- wp:cover {"url":"' . esc_url( stjo_asset( 'card-6.png' ) ) . '","dimRatio":40,"overlayColor":"black","isUserOverlayColor":true,"minHeight":400,"className":"stjo-card"} -->' . "\n"
				. '<div class="wp-block-cover stjo-card" style="min-height:400px"><img class="wp-block-cover__image-background" alt="" src="' . esc_url( stjo_asset( 'card-6.png' ) ) . '" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-40 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":3,"textColor":"light"} -->' . "\n"
				. '<h3 class="wp-block-heading has-light-color has-text-color">Contact Us</h3>' . "\n"
				. '<!-- /wp:heading -->' . "\n\n"
				. '<!-- wp:paragraph {"className":"stjo-card__reveal","textColor":"light"} -->' . "\n"
				. '<p class="stjo-card__reveal has-light-color has-text-color">Call 1-800-341-2235, start a LiveChat, or write to St. Joseph’s Indian School, P.O. Box 326, Chamberlain, SD 57326.</p>' . "\n"
				. '<!-- /wp:paragraph -->' . "\n\n"
				. '<!-- wp:buttons -->' . "\n"
				. '<div class="wp-block-buttons"><!-- wp:button {"textColor":"white","className":"is-style-arrow-link"} -->' . "\n"
				. '<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link has-white-color has-text-color wp-element-button" href="/contact/">Contact Us</a></div>' . "\n"
				. '<!-- /wp:button --></div>' . "\n"
				. '<!-- /wp:buttons --></div></div>' . "\n"
				. '<!-- /wp:cover --></div>' . "\n"
				. '<!-- /wp:column --></div>' . "\n" . '<!-- /wp:columns -->',
			'skip_if' => 'href="/contact/"',
		),
	),
);

foreach ( $stjo_fixes as $slug => $edits ) {
	$page = stjo_seed_find_page( $slug );
	if ( ! $page ) {
		stjo_seed_say( "  $slug: no page, skipped" );
		continue;
	}
	$content = $page->post_content;
	$applied = 0;
	foreach ( $edits as $edit ) {
		if ( false !== strpos( $content, $edit['skip_if'] ) ) {
			continue; // already linked
		}
		if ( false === strpos( $content, $edit['from'] ) ) {
			stjo_seed_say( sprintf( '  %s: expected markup not found for "%s" (page edited since?), skipped', $slug, mb_substr( wp_strip_all_tags( $edit['from'] ), 0, 40 ) ) );
			continue;
		}
		$content = str_replace( $edit['from'], $edit['to'], $content );
		$applied++;
	}
	if ( ! $applied ) {
		stjo_seed_say( "  $slug: nothing to change" );
		continue;
	}
	$res = wp_update_post( array( 'ID' => $page->ID, 'post_content' => wp_slash( $content ) ), true );
	if ( is_wp_error( $res ) ) {
		stjo_seed_say( "  $slug: " . $res->get_error_message() );
		continue;
	}
	clean_post_cache( $page->ID );
	stjo_seed_say( sprintf( '  %s (#%d): %d link fix(es) applied', $slug, $page->ID, $applied ) );
}
wp_cache_flush();
if ( class_exists( 'WpeCommon' ) && method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
	WpeCommon::purge_varnish_cache();
}
stjo_seed_say( 'Done.' );
