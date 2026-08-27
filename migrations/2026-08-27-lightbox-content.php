<?php
/**
 * Migration: lightbox content pages + Lakota Culture triggers.
 *
 * Applies the DB side of the "Links that open Lightboxes" work to whichever
 * environment it runs on. Targeted alternative to a blanket local -> staging
 * DB push: it creates exactly these things and touches nothing else.
 *
 *   1. Creates/updates two lightbox content pages (Oceti Sakowin, Seven
 *      Lakota Rites) from the .html files beside this script, parented to
 *      Lakota Culture, filed under the "Lightbox Content" page category.
 *   2. Inserts an Arrow Link "Links that open Lightboxes" block into each of
 *      the two media-text sections on Lakota Culture, pointing at those
 *      pages by whatever IDs this environment assigned them.
 *   3. Deletes the empty demo blog categories (news, culture, events) if
 *      they exist here and are still empty.
 *
 * Idempotent: pages are matched by slug and updated in place; trigger blocks
 * are skipped when one for that page already exists; category deletion skips
 * anything non-empty. Safe to run twice.
 *
 * Run (from the WP root of the target environment):
 *   wp eval-file wp-content/themes/stjo-dgd/migrations/2026-08-27-lightbox-content.php
 *
 * Delete this directory once staging has it (same policy as seed.php).
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	echo "Run via: wp eval-file " . __FILE__ . "\n"; // phpcs:ignore
	exit( 1 );
}

$stjo_mig_parent = get_page_by_path( 'lakota-culture', OBJECT, 'page' );
if ( ! $stjo_mig_parent ) {
	echo "ABORT: no lakota-culture page here - wrong environment?\n";
	return;
}

// ── 1. Content pages ────────────────────────────────────────────────────────
$stjo_mig_term = term_exists( 'lightbox-content', 'page-category' );
if ( ! $stjo_mig_term ) {
	$stjo_mig_term = wp_insert_term( 'Lightbox Content', 'page-category', array( 'slug' => 'lightbox-content' ) );
}
if ( is_wp_error( $stjo_mig_term ) ) {
	echo 'ABORT: page-category term failed: ' . $stjo_mig_term->get_error_message() . "\n";
	echo "Is the theme with inc/blocks.php (registers the taxonomy) deployed here?\n";
	return;
}
$stjo_mig_term_id = (int) ( is_array( $stjo_mig_term ) ? $stjo_mig_term['term_id'] : $stjo_mig_term );

$stjo_mig_pages = array(
	array(
		'slug'  => 'oceti-sakowin-seven-council-fires',
		'title' => 'Oceti Sakowin — Seven Council Fires',
		'file'  => __DIR__ . '/content-oceti-sakowin.html',
		'match' => 'Oceti Sakowin',
		'label' => 'More About the Seven Council Fires',
	),
	array(
		'slug'  => 'seven-lakota-rites',
		'title' => 'Seven Lakota Rites',
		'file'  => __DIR__ . '/content-seven-lakota-rites.html',
		'match' => 'Seven Lakota Rites',
		'label' => 'More About the Seven Rites',
	),
);

$stjo_mig_blocks = array();
foreach ( $stjo_mig_pages as $stjo_mig_def ) {
	if ( ! file_exists( $stjo_mig_def['file'] ) ) {
		echo 'ABORT: missing ' . basename( $stjo_mig_def['file'] ) . "\n";
		return;
	}
	// By post_name, not path: these are children of Lakota Culture, so their
	// path is lakota-culture/<slug> and a bare-slug get_page_by_path misses
	// (which is how a test run minted duplicates locally).
	$stjo_mig_found    = get_posts( array(
		'post_type'   => 'page',
		'name'        => $stjo_mig_def['slug'],
		'post_status' => 'any',
		'numberposts' => 1,
	) );
	$stjo_mig_existing = $stjo_mig_found ? $stjo_mig_found[0] : null;
	$stjo_mig_args     = array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_title'   => $stjo_mig_def['title'],
		'post_name'    => $stjo_mig_def['slug'],
		'post_parent'  => $stjo_mig_parent->ID,
		// wp_insert_post/wp_update_post unslash their input.
		'post_content' => wp_slash( file_get_contents( $stjo_mig_def['file'] ) ),
	);
	if ( $stjo_mig_existing ) {
		$stjo_mig_args['ID'] = $stjo_mig_existing->ID;
		$stjo_mig_id         = wp_update_post( $stjo_mig_args, true );
		$stjo_mig_verb       = 'updated';
	} else {
		$stjo_mig_id   = wp_insert_post( $stjo_mig_args, true );
		$stjo_mig_verb = 'created';
	}
	if ( is_wp_error( $stjo_mig_id ) ) {
		echo 'ABORT: page ' . $stjo_mig_def['slug'] . ' failed: ' . $stjo_mig_id->get_error_message() . "\n";
		return;
	}
	wp_set_object_terms( $stjo_mig_id, $stjo_mig_term_id, 'page-category' );
	echo $stjo_mig_verb . ' page ' . $stjo_mig_id . ' (' . $stjo_mig_def['slug'] . ")\n";

	$stjo_mig_blocks[ $stjo_mig_def['match'] ] = array(
		'page_id' => (int) $stjo_mig_id,
		'block'   => sprintf(
			'<!-- wp:stjo/lightbox-card {"title":"%s","linkLabel":"%s","contentPageId":%d,"className":"is-style-arrow-link"} /-->',
			esc_attr( $stjo_mig_def['title'] ),
			esc_attr( $stjo_mig_def['label'] ),
			(int) $stjo_mig_id
		),
	);
}

// ── 2. Trigger blocks on Lakota Culture ─────────────────────────────────────
$stjo_mig_content  = get_post_field( 'post_content', $stjo_mig_parent->ID );
$stjo_mig_inserted = 0;
$stjo_mig_new      = preg_replace_callback(
	'/<!-- wp:media-text.*?\/wp:media-text -->/s',
	function ( $stjo_mig_m ) use ( $stjo_mig_blocks, &$stjo_mig_inserted ) {
		$stjo_mig_block_html = $stjo_mig_m[0];
		foreach ( $stjo_mig_blocks as $stjo_mig_needle => $stjo_mig_def ) {
			if ( false === strpos( $stjo_mig_block_html, $stjo_mig_needle ) ) {
				continue;
			}
			// A lightbox trigger already in this section - whatever page it
			// points at - means an editor has wired it; leave it alone.
			if ( false !== strpos( $stjo_mig_block_html, 'wp:stjo/lightbox-card' ) ) {
				return $stjo_mig_block_html;
			}
			// Insert after the content column's last paragraph. The column
			// closes with </div></div> when media sits left and </div><figure
			// when media sits right, so anchor on the last paragraph-close.
			$stjo_mig_anchor = '<!-- /wp:paragraph --></div>';
			$stjo_mig_pos    = strrpos( $stjo_mig_block_html, $stjo_mig_anchor );
			if ( false !== $stjo_mig_pos ) {
				$stjo_mig_block_html = substr_replace(
					$stjo_mig_block_html,
					'<!-- /wp:paragraph -->' . "\n\n" . $stjo_mig_def['block'] . '</div>',
					$stjo_mig_pos,
					strlen( $stjo_mig_anchor )
				);
				$stjo_mig_inserted++;
			}
			return $stjo_mig_block_html;
		}
		return $stjo_mig_block_html;
	},
	$stjo_mig_content
);

if ( $stjo_mig_inserted > 0 ) {
	wp_update_post( array( 'ID' => $stjo_mig_parent->ID, 'post_content' => wp_slash( $stjo_mig_new ) ) );
	clean_post_cache( $stjo_mig_parent->ID );
	if ( class_exists( 'WpeCommon' ) && method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
		WpeCommon::purge_varnish_cache( (int) $stjo_mig_parent->ID );
	}
}
echo 'lakota-culture (' . $stjo_mig_parent->ID . '): ' . $stjo_mig_inserted . " trigger block(s) inserted\n";

// ── 3. Empty demo blog categories ───────────────────────────────────────────
foreach ( array( 'news', 'culture', 'events' ) as $stjo_mig_slug ) {
	$stjo_mig_cat = get_term_by( 'slug', $stjo_mig_slug, 'category' );
	if ( $stjo_mig_cat && 0 === (int) $stjo_mig_cat->count ) {
		wp_delete_term( $stjo_mig_cat->term_id, 'category' );
		echo "deleted empty category: {$stjo_mig_slug}\n";
	} elseif ( $stjo_mig_cat ) {
		echo "kept category {$stjo_mig_slug} (not empty: {$stjo_mig_cat->count})\n";
	}
}

echo "done\n";
