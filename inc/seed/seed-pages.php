<?php
/**
 * Seed built-out pages from inc/page-content/*.php, one page at a time.
 *
 *   wp eval-file wp-content/themes/stjo-dgd/inc/seed/seed-pages.php [slug ...] [force]
 *
 * With no slugs, every page in $stjo_manifest is seeded. Only pages that are
 * still "Coming soon" stubs are touched; the word `force` overwrites a built
 * page (wp-cli swallows --flags before eval-file sees them) (the
 * previous content stays in its revision history). Nothing else is written:
 * no menus, no other posts, no full seed.php run.
 *
 * Images the pages need ship in assets/images/pages/ and are imported once
 * (see pages-images.json), tagged `_stjo_seed_source` so stjo_seeded_image()
 * resolves the right attachment on local and staging alike.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/seed-lib.php';

if ( ! function_exists( 'stjo_seeded_image' ) || ! function_exists( 'stjo_page_is_stub' ) ) {
	stjo_seed_abort( 'Theme helpers missing. Is stjo-dgd the active theme?' );
}

// slug => page-content file. Add a line here when a new page is built out.
$stjo_manifest = array(
	'support-us'                   => 'support-us.php',
	'our-children'                 => 'our-children.php',
	'residential-living'           => 'residential-living.php',
	'education-cultural-awareness' => 'education-cultural-awareness.php',
	'accountability-reports'       => 'accountability-reports.php',
	'annual-financial-report'      => 'annual-financial-report.php',
	'beliefs-traditions'           => 'beliefs-traditions.php',
	'four-directions'              => 'four-directions.php',
	'lakota-legends'               => 'lakota-legends.php',
	'privacy-policy'               => 'privacy-policy.php', // WP's draft placeholder page; published on seed
	'careers'                      => 'careers.php',
	'contact'                      => 'contact.php',
	'501c3'                        => '501c3.php',
);
// Pages whose "unbuilt" state is not the Coming-soon stub: WordPress's own
// privacy placeholder counts as unbuilt too, and gets published once seeded.
$stjo_publish_on_seed = array( 'privacy-policy' );

$stjo_args  = isset( $args ) && is_array( $args ) ? $args : array();
$stjo_force = in_array( 'force', $stjo_args, true );
$stjo_slugs = array_values( array_diff( $stjo_args, array( 'force' ) ) );
if ( ! $stjo_slugs ) {
	$stjo_slugs = array_keys( $stjo_manifest );
}

/* ---------------------------------------------------------------- images -- */

stjo_seed_say( '== Images ==' );
$stjo_map = json_decode( (string) file_get_contents( __DIR__ . '/pages-images.json' ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
if ( ! is_array( $stjo_map ) ) {
	stjo_seed_abort( 'Could not read pages-images.json' );
}
stjo_seed_import_images( get_template_directory() . '/assets/images/pages', $stjo_map );

/* --------------------------------------------------------- tertiary stubs -- */

// Pages the sitemap board lists as tertiary (linked from a secondary page, not
// in the nav) that the built pages now link to. Created as the same "Coming
// soon" stub every unbuilt sitemap page carries, so no link is dead while
// they wait their turn; seeding them later replaces the stub like any other.
$stjo_ensure = array(
	// slug => array( title, parent slug )
	'religious-education'    => array( 'Religious Education', 'education-cultural-awareness' ),
	'cultural-trip'          => array( 'Cultural Trip', 'education-cultural-awareness' ),
	'student-bill-of-rights' => array( 'Student Bill of Rights', 'accountability-reports' ),
	'protecting-students'    => array( 'Protecting Students', 'accountability-reports' ),
	'annual-financial-report' => array( 'Annual Financial Report', 'accountability-reports' ),
	// Footer pages the board lists (About children so their URLs match the footer)
	'careers'                => array( 'Careers', 'about' ),
	'501c3'                  => array( '501(c)(3) Status', 'about' ),
	// Beliefs & Traditions children
	'seven-lakota-values'    => array( 'Seven Lakota Values', 'beliefs-traditions' ),
	'four-directions'        => array( 'Four Directions', 'beliefs-traditions' ),
	'star-quilt'             => array( 'Star Quilt', 'beliefs-traditions' ),
	'medicine-wheel'         => array( 'Medicine Wheel', 'beliefs-traditions' ),
	'seasons-moon-calendar'  => array( 'Seasons and Moon Calendar', 'beliefs-traditions' ),
	'quillwork-beadwork'     => array( 'Lakota Quillwork & Beadwork', 'beliefs-traditions' ),
	'winter-count'           => array( 'The Winter Count', 'beliefs-traditions' ),
	'morning-star'           => array( 'The Morning Star', 'beliefs-traditions' ),
	'tipi'                   => array( 'The Thípi', 'beliefs-traditions' ),
	// Lakota Legends children
	'dreamcatcher'           => array( 'Dreamcatcher', 'lakota-legends' ),
	'lakota-pipe'            => array( 'Origin of the Lakota Pipe', 'lakota-legends' ),
	'devils-tower'           => array( 'Devils Tower', 'lakota-legends' ),
	'iktomi'                 => array( 'Iktómi', 'lakota-legends' ),
	'the-great-race'         => array( 'The Great Race', 'lakota-legends' ),
);
$stjo_stub_content = '<!-- wp:paragraph {"className":"is-style-eyebrow"} -->' . "\n"
	. '<p class="wp-block-paragraph is-style-eyebrow">Coming soon</p>' . "\n"
	. '<!-- /wp:paragraph -->' . "\n\n"
	. '<!-- wp:paragraph -->' . "\n"
	. '<p class="wp-block-paragraph">This page is part of the St. Joseph&#8217;s Indian School sitemap and will be built out during the content phase.</p>' . "\n"
	. '<!-- /wp:paragraph -->';

stjo_seed_say( '== Tertiary stubs ==' );
foreach ( $stjo_ensure as $slug => $spec ) {
	$exists = stjo_seed_find_page( $slug );
	if ( $exists ) {
		stjo_seed_say( sprintf( '  %s: exists (#%d)', $slug, $exists->ID ) );
		continue;
	}
	$parent = stjo_seed_find_page( $spec[1] );
	if ( ! $parent ) {
		stjo_seed_say( sprintf( '  %s: parent %s missing, skipped', $slug, $spec[1] ) );
		continue;
	}
	$new_id = wp_insert_post( array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_name'    => $slug,
		'post_title'   => $spec[0],
		'post_parent'  => (int) $parent->ID,
		'post_content' => wp_slash( $stjo_stub_content ),
	), true );
	if ( is_wp_error( $new_id ) ) {
		stjo_seed_say( "  $slug: " . $new_id->get_error_message() );
		continue;
	}
	stjo_seed_say( sprintf( '  %s: created #%d under %s', $slug, $new_id, $spec[1] ) );
}

/* -------------------------------------------------------- lightbox pages -- */

// Long-form copy that opens in "Links that open Lightboxes" cards lives in
// pages categorised lightbox-content (no single view of their own). Created
// once; `force` refreshes their content from the file like any other page.
$stjo_lightbox = array(
	// slug => array( title, file in inc/page-content/lightbox/ )
	'awards'                 => array( 'Awards', 'awards.php' ),
	'accreditation'          => array( 'Accreditation', 'accreditation.php' ),
	'memberships'            => array( 'Memberships', 'memberships.php' ),
	'charity-rating'         => array( 'Charity Rating', 'charity-rating.php' ),
	'four-directions-prayer' => array( 'Four Directions Prayer', 'four-directions-prayer.php' ),
	'hapi-homes'             => array( 'Houseparents and Pets In Homes', 'hapi-homes.php' ),
);

stjo_seed_say( '== Lightbox content pages ==' );
foreach ( $stjo_lightbox as $slug => $spec ) {
	$file = get_template_directory() . '/inc/page-content/lightbox/' . $spec[1];
	if ( ! file_exists( $file ) ) {
		stjo_seed_say( "  $slug: missing $file" );
		continue;
	}
	ob_start();
	include $file;
	$content = trim( (string) ob_get_clean() );

	$exists = stjo_seed_find_page( $slug );
	if ( $exists ) {
		$lb_id = (int) $exists->ID;
		if ( $stjo_force ) {
			wp_update_post( array( 'ID' => $lb_id, 'post_content' => wp_slash( $content ) ) );
			stjo_seed_say( sprintf( '  %s (#%d): content refreshed', $slug, $lb_id ) );
		} else {
			stjo_seed_say( sprintf( '  %s (#%d): exists', $slug, $lb_id ) );
		}
	} else {
		$lb_id = wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $spec[0],
			'post_content' => wp_slash( $content ),
		), true );
		if ( is_wp_error( $lb_id ) ) {
			stjo_seed_say( "  $slug: " . $lb_id->get_error_message() );
			continue;
		}
		stjo_seed_say( sprintf( '  %s: created #%d', $slug, $lb_id ) );
	}
	wp_set_object_terms( $lb_id, 'lightbox-content', 'page-category' );
}

/* ----------------------------------------------------------------- pages -- */

stjo_seed_say( '== Pages ==' );
$stjo_done = 0;
foreach ( $stjo_slugs as $slug ) {
	if ( empty( $stjo_manifest[ $slug ] ) ) {
		stjo_seed_say( "  $slug: not in the manifest, skipped" );
		continue;
	}
	// get_page_by_path() wants the full parent/child path, and get_posts()
	// misses drafts here; match the slug alone via stjo_seed_find_page().
	$page = stjo_seed_find_page( $slug );
	if ( ! $page ) {
		stjo_seed_say( "  $slug: no page with that slug, skipped" );
		continue;
	}
	$is_unbuilt = stjo_page_is_stub( $page ) || false !== strpos( $page->post_content, 'privacy-policy-tutorial' );
	if ( ! $stjo_force && ! $is_unbuilt ) {
		stjo_seed_say( sprintf( '  %s (#%d): already built, skipped (add `force` to overwrite)', $slug, $page->ID ) );
		continue;
	}

	$file = get_template_directory() . '/inc/page-content/' . $stjo_manifest[ $slug ];
	if ( ! file_exists( $file ) ) {
		stjo_seed_say( "  $slug: missing $file" );
		continue;
	}
	ob_start();
	include $file;
	$content = trim( (string) ob_get_clean() );

	$res = wp_update_post( array(
		'ID'           => $page->ID,
		'post_content' => wp_slash( $content ),
	), true );
	if ( is_wp_error( $res ) ) {
		stjo_seed_say( "  $slug: " . $res->get_error_message() );
		continue;
	}
	if ( in_array( $slug, $stjo_publish_on_seed, true ) && 'publish' !== $page->post_status ) {
		wp_publish_post( $page->ID );
	}
	clean_post_cache( $page->ID );
	stjo_seed_say( sprintf( '  %s (#%d) <- %s, %d chars', $slug, $page->ID, $stjo_manifest[ $slug ], strlen( $content ) ) );
	$stjo_done++;
}

wp_cache_flush();
if ( class_exists( 'WpeCommon' ) && method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
	WpeCommon::purge_varnish_cache();
	stjo_seed_say( '  WP Engine page cache purged' );
}
stjo_seed_say( sprintf( 'Done: %d page(s) seeded.', $stjo_done ) );
