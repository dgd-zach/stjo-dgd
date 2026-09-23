<?php
/**
 * Your Generosity band as ONE synced pattern.
 *
 * The band is a wp_block post (slug your-generosity-band) that editors edit in
 * Appearance > Design > Patterns, or via "Edit original" from Home / Support
 * Us. Every inner page's pre-footer renders that same pattern
 * (template-parts/global/generosity-band.php) and Home / Support Us reference
 * it in their content as <!-- wp:block {"ref":ID} /-->, so one edit lands on
 * every page. inc/patterns/band-cards-tiles-cta.php is the seed source the
 * pattern is created from, and the fallback markup while it does not exist.
 *
 * The pattern's wrapper group carries templateLock "contentOnly", so in the
 * Pattern editor the team edits wording, photos, icons and links but cannot
 * delete or reorder the rows; an administrator can lift that with Modify.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const STJO_GENEROSITY_PATTERN_SLUG = 'your-generosity-band';

/**
 * The synced pattern post, or null. Cached per request; pass true after
 * creating it in the same request.
 *
 * @param bool $refresh Re-query.
 * @return WP_Post|null
 */
function stjo_generosity_band_pattern( $refresh = false ) {
	static $post = false;
	if ( false !== $post && ! $refresh ) {
		return $post;
	}
	$found = get_posts( array(
		'post_type'      => 'wp_block',
		'name'           => STJO_GENEROSITY_PATTERN_SLUG,
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'no_found_rows'  => true,
	) );
	$post = $found ? $found[0] : null;
	return $post;
}

/** The block that places the synced pattern, or '' when it does not exist. */
function stjo_generosity_band_ref_block() {
	$pattern = stjo_generosity_band_pattern();
	return $pattern ? '<!-- wp:block {"ref":' . (int) $pattern->ID . '} /-->' : '';
}

/** The theme pattern file rendered to block markup (seed source, fallback). */
function stjo_generosity_band_source_markup() {
	$file = get_template_directory() . '/inc/patterns/band-cards-tiles-cta.php';
	if ( ! file_exists( $file ) ) {
		return '';
	}
	ob_start();
	include $file;
	return trim( (string) ob_get_clean() );
}

/** What a page seed prints where the band goes: the reference, else inline blocks. */
function stjo_generosity_band_seed_markup() {
	$ref = stjo_generosity_band_ref_block();
	return $ref ? $ref : stjo_generosity_band_source_markup();
}

/**
 * Does this post's content already carry the band, as the legacy dynamic
 * block, as inline blocks (the stjo-generosity class), or as a reference to
 * a synced pattern that is or contains the band? footer.php uses this to
 * skip the automatic pre-footer band.
 *
 * @param int $post_id Post.
 * @return bool
 */
function stjo_content_has_generosity_band( $post_id ) {
	$content = (string) get_post_field( 'post_content', $post_id );
	if ( '' === $content ) {
		return false;
	}
	if ( false !== strpos( $content, 'stjo-generosity' ) || has_block( 'stjo/generosity-band', $post_id ) ) {
		return true;
	}
	if ( ! has_block( 'core/block', $post_id ) ) {
		return false;
	}
	$seen = array();
	return stjo_blocks_reference_generosity_band( parse_blocks( $content ), $seen );
}

/**
 * Do these blocks, at any depth, reference a synced pattern that is or
 * contains the band? The original pattern counts, and so does ANY wp_block
 * whose content carries the stjo-generosity wrapper, so a duplicated and
 * edited copy of the band pattern placed on a page keeps the automatic band
 * away too. Follows references inside patterns; $seen stops loops.
 *
 * @param array $blocks parse_blocks() output.
 * @param array $seen   Pattern ids already inspected (by reference).
 * @return bool
 */
function stjo_blocks_reference_generosity_band( array $blocks, array &$seen ) {
	$primary    = stjo_generosity_band_pattern();
	$primary_id = $primary ? (int) $primary->ID : 0;
	foreach ( $blocks as $block ) {
		if ( 'core/block' === ( $block['blockName'] ?? '' ) ) {
			$ref = (int) ( $block['attrs']['ref'] ?? 0 );
			if ( $ref && ! isset( $seen[ $ref ] ) ) {
				$seen[ $ref ] = true;
				if ( $ref === $primary_id ) {
					return true;
				}
				$pattern = get_post( $ref );
				if ( $pattern && 'wp_block' === $pattern->post_type ) {
					$inner = (string) $pattern->post_content;
					if ( false !== strpos( $inner, 'stjo-generosity' ) ) {
						return true;
					}
					if ( has_blocks( $inner ) && stjo_blocks_reference_generosity_band( parse_blocks( $inner ), $seen ) ) {
						return true;
					}
				}
			}
		}
		if ( ! empty( $block['innerBlocks'] ) && stjo_blocks_reference_generosity_band( $block['innerBlocks'], $seen ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Create the synced pattern from the theme pattern file when it is missing.
 * Idempotent (the seeder calls it on every run).
 *
 * @return int Pattern post ID, 0 on failure.
 */
function stjo_generosity_band_ensure_pattern() {
	$existing = stjo_generosity_band_pattern( true );
	if ( $existing ) {
		return (int) $existing->ID;
	}
	$markup = stjo_generosity_band_source_markup();
	if ( '' === $markup ) {
		return 0;
	}
	$id = wp_insert_post( array(
		'post_type'    => 'wp_block',
		'post_status'  => 'publish',
		'post_title'   => 'Your Generosity Band',
		'post_name'    => STJO_GENEROSITY_PATTERN_SLUG,
		'post_content' => wp_slash( $markup ),
	), true );
	if ( is_wp_error( $id ) ) {
		return 0;
	}
	// Synced is the default (no wp_pattern_sync_status meta); make sure of it.
	delete_post_meta( $id, 'wp_pattern_sync_status' );
	stjo_generosity_band_pattern( true );
	return (int) $id;
}
