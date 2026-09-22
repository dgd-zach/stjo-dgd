<?php
/**
 * Custom block registration (no build chain — plain JS editor scripts).
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stjo_register_custom_blocks() {
	wp_register_script(
		'stjo-donation-selector-editor',
		get_template_directory_uri() . '/src/blocks/donation-selector/edit.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render' ),
		(string) filemtime( get_template_directory() . '/src/blocks/donation-selector/edit.js' ), // mtime version: stale copies stick in the editor on STJO_VERSION
		true
	);
	register_block_type( get_template_directory() . '/src/blocks/donation-selector' );

	wp_register_script(
		'stjo-timeline-editor',
		get_template_directory_uri() . '/src/blocks/timeline/edit.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render' ),
		(string) filemtime( get_template_directory() . '/src/blocks/timeline/edit.js' ), // mtime version: STJO_VERSION let stale copies stick in the editor
		true
	);
	register_block_type( get_template_directory() . '/src/blocks/timeline' );

	wp_register_script(
		'stjo-lightbox-card-editor',
		get_template_directory_uri() . '/src/blocks/lightbox-card/edit.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render' ),
		(string) filemtime( get_template_directory() . '/src/blocks/lightbox-card/edit.js' ),
		true
	);
	register_block_type( get_template_directory() . '/src/blocks/lightbox-card' );

	// Page categories, a taxonomy of their own. Sharing the built-in blog
	// category taxonomy with pages was tried first and leaks both ways — terms
	// live on the taxonomy, not the post type, so blog categories appeared on
	// the pages screen, page terms bumped blog pill counts, and
	// edit-tags.php?post_type=page still listed everything. The Links that
	// open Lightboxes block's page picker lists pages filed under this
	// taxonomy's "Lightbox Content" term; show_in_rest powers that query.
	register_taxonomy( 'page-category', 'page', array(
		'labels'             => array(
			'name'          => __( 'Page Categories', 'stjo' ),
			'singular_name' => __( 'Page Category', 'stjo' ),
			'add_new_item'  => __( 'Add New Page Category', 'stjo' ),
		),
		'hierarchical'       => true,
		'public'             => false,
		'show_ui'            => true,
		'show_in_rest'       => true,
		'show_admin_column'  => true,
		'show_in_quick_edit' => true,
		'rewrite'            => false,
	) );

	wp_register_script(
		'stjo-stories-section-editor',
		get_template_directory_uri() . '/src/blocks/stories-section/edit.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-data', 'wp-html-entities', 'wp-server-side-render' ),
		(string) filemtime( get_template_directory() . '/src/blocks/stories-section/edit.js' ),
		true
	);
	register_block_type( get_template_directory() . '/src/blocks/stories-section' );

	wp_register_script(
		'stjo-stat-figure-editor',
		get_template_directory_uri() . '/src/blocks/stat-figure/edit.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components' ),
		(string) filemtime( get_template_directory() . '/src/blocks/stat-figure/edit.js' ),
		true
	);
	register_block_type( get_template_directory() . '/src/blocks/stat-figure' );

	wp_register_script(
		'stjo-generosity-band-editor',
		get_template_directory_uri() . '/src/blocks/generosity-band/edit.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render' ),
		(string) filemtime( get_template_directory() . '/src/blocks/generosity-band/edit.js' ),
		true
	);
	register_block_type( get_template_directory() . '/src/blocks/generosity-band' );

	// block.json assets default to the WP core version string; pin filemtime
	// so edits actually cache-bust (same stale-script trap as unregister.js).
	$stjo_lb_view = wp_scripts()->query( 'stjo-lightbox-card-view-script' );
	if ( $stjo_lb_view ) {
		$stjo_lb_view->ver = (string) filemtime( get_template_directory() . '/src/blocks/lightbox-card/view.js' );
	}
	$stjo_lb_style = wp_styles()->query( 'stjo-lightbox-card-style' );
	if ( $stjo_lb_style ) {
		$stjo_lb_style->ver = (string) filemtime( get_template_directory() . '/src/blocks/lightbox-card/style.css' );
	}
}
add_action( 'init', 'stjo_register_custom_blocks' );

/**
 * Query-loop cards are one click target with three links inside (featured
 * image, title, Read More), so keyboard users hit each card three times and
 * screen readers hear the same destination three times. Keep the title as the
 * real link and take the image and Read More links out of the tab order and
 * the accessibility tree. The card ring (:focus-within) is the focus indicator.
 * Only demotes when the card actually has a title link to fall back on.
 */
function stjo_query_card_single_tab_stop( $content ) {
	if ( false === strpos( $content, 'wp-block-post-title' ) ) {
		return $content;
	}
	return preg_replace_callback(
		'~<li\b[^>]*>.*?</li>~s',
		function ( $m ) {
			$li = $m[0];
			if ( ! preg_match( '~<h\d[^>]*class="[^"]*wp-block-post-title[^"]*"[^>]*>\s*<a\b~s', $li ) ) {
				return $li;
			}
			$demote = function ( $tag ) {
				if ( false !== strpos( $tag, 'tabindex=' ) ) {
					return $tag;
				}
				return substr( $tag, 0, -1 ) . ' tabindex="-1" aria-hidden="true">';
			};
			$li = preg_replace_callback( '~(<figure[^>]*class="[^"]*wp-block-post-featured-image[^"]*"[^>]*>\s*)(<a\b[^>]*>)~s', function ( $x ) use ( $demote ) { return $x[1] . $demote( $x[2] ); }, $li );
			$li = preg_replace_callback( '~<a\b[^>]*class="[^"]*wp-block-read-more[^"]*"[^>]*>~', function ( $x ) use ( $demote ) { return $demote( $x[0] ); }, $li );
			return $li;
		},
		$content
	);
}
add_filter( 'render_block_core/post-template', 'stjo_query_card_single_tab_stop' );

/**
 * The Preview Lightbox modal (wp.components.Modal) lives in the admin
 * document, OUTSIDE the editor canvas iframe, so block styles registered via
 * block.json never reach it. Enqueue the lightbox styles (plus fonts and
 * tokens they build on) into the admin document for frontend-matching
 * previews.
 */
function stjo_lightbox_card_admin_assets() {
	$uri = get_template_directory_uri();
	wp_enqueue_style( 'stjo-fonts-admin', $uri . '/assets/css/fonts.css', array(), STJO_VERSION );
	wp_enqueue_style( 'stjo-tokens-admin', $uri . '/assets/css/tokens.css', array(), STJO_VERSION );
	wp_enqueue_style(
		'stjo-lightbox-card-admin',
		$uri . '/src/blocks/lightbox-card/style.css',
		array( 'stjo-tokens-admin' ),
		(string) filemtime( get_template_directory() . '/src/blocks/lightbox-card/style.css' )
	);
}
add_action( 'enqueue_block_editor_assets', 'stjo_lightbox_card_admin_assets' );

/**
 * Lightbox content pages have no single view. Their content exists to be
 * cloned into a dialog; the page URL 404s like the student-story CPT's
 * singles (that one uses publicly_queryable, which has no per-page
 * equivalent). Editor previews still work so drafts can be checked before
 * the page goes live in a lightbox.
 */
function stjo_lightbox_content_no_single() {
	if ( ! is_page() || is_preview() ) {
		return;
	}
	$stjo_page = get_queried_object();
	if ( $stjo_page && has_term( 'lightbox-content', 'page-category', $stjo_page ) ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
	}
}
add_action( 'template_redirect', 'stjo_lightbox_content_no_single' );

/**
 * Blocks that misbehave inside the lightbox are not offered on lightbox
 * content pages. The dialog clones content out of an inert <template>, so
 * anything wired up at page load never initialises there: a carousel's slides
 * stay visibility:hidden (invisible content), video facades' play buttons go
 * dead, Gravity Forms loses validation and conditional logic, the timeline
 * and donation selector are view.js-driven, and a nested lightbox link
 * replaces the open dialog with no way back.
 *
 * Denylist, not allowlist, so newly registered blocks stay available by
 * default. Applies once the page is saved with the category — a brand-new
 * unsaved page is unrestricted until then. Existing instances of a denied
 * block keep rendering; only the inserter withholds them.
 */
function stjo_lightbox_content_denied_blocks() {
	return array(
		'stjo/lightbox-card',
		'stjo/timeline',
		'stjo/donation-selector',
		'gravityforms/form',
		'core/video',
		'core/embed',
	);
}

function stjo_lightbox_content_allowed_blocks( $allowed, $context ) {
	if ( empty( $context->post ) || 'page' !== $context->post->post_type ) {
		return $allowed;
	}
	if ( ! has_term( 'lightbox-content', 'page-category', $context->post ) ) {
		return $allowed;
	}
	// Respect any restriction already in force (true = everything).
	$base = is_array( $allowed )
		? $allowed
		: array_keys( WP_Block_Type_Registry::get_instance()->get_all_registered() );
	return array_values( array_diff( $base, stjo_lightbox_content_denied_blocks() ) );
}
add_filter( 'allowed_block_types_all', 'stjo_lightbox_content_allowed_blocks', 10, 2 );

/**
 * Lightbox content pages: headings start at h3 in the editor.
 *
 * In the modal the lightbox's own heading is the h2, so the page copy below
 * it belongs at h3 and deeper. The block's render demotes anything shallower
 * anyway (so an h2 typed here still comes out as an h3), but offering only
 * h3-h6 in the heading block's level picker keeps what editors see honest.
 * Runs before block-library registers, on the same handle it depends on.
 */
function stjo_lightbox_content_heading_levels() {
	$post = get_post();
	if ( ! $post || 'page' !== $post->post_type || ! has_term( 'lightbox-content', 'page-category', $post ) ) {
		return;
	}
	$js = "wp.hooks.addFilter( 'blocks.registerBlockType', 'stjo/lightbox-content-heading-levels', function ( settings, name ) {"
		. " if ( 'core/heading' !== name || ! settings.attributes || ! settings.attributes.levelOptions ) { return settings; }"
		. " settings.attributes.levelOptions = Object.assign( {}, settings.attributes.levelOptions, { default: [ 3, 4, 5, 6 ] } );"
		. " settings.attributes.level = Object.assign( {}, settings.attributes.level, { default: 3 } );"
		. " return settings; } );";
	wp_add_inline_script( 'wp-blocks', $js, 'after' );
}
add_action( 'enqueue_block_editor_assets', 'stjo_lightbox_content_heading_levels' );

/**
 * Published pages/posts that host a lightbox showing $post_id: their content
 * carries a stjo/lightbox-card with contentPageId = $post_id. The delimited
 * LIKE (trailing , or }) keeps page 167 from sweeping up 1678's hosts. Shared
 * by the host-cache purge and the search-result deep link.
 *
 * @param int $post_id Lightbox content page ID.
 * @return int[] Host post IDs, lowest first.
 */
function stjo_lightbox_content_hosts( $post_id ) {
	global $wpdb;
	$post_id = (int) $post_id;
	if ( ! $post_id ) {
		return array();
	}
	return array_map( 'intval', $wpdb->get_col( $wpdb->prepare(
		"SELECT ID FROM {$wpdb->posts}
		 WHERE post_status = 'publish' AND post_type IN ( 'page', 'post' )
		   AND ( post_content LIKE %s OR post_content LIKE %s )
		 ORDER BY ID ASC",
		'%' . $wpdb->esc_like( '"contentPageId":' . $post_id . ',' ) . '%',
		'%' . $wpdb->esc_like( '"contentPageId":' . $post_id . '}' ) . '%'
	) ) );
}

/**
 * Deep link to a lightbox content page's content: the first published page
 * that hosts it, plus the fragment (#<slug>) view.js opens on load. These
 * pages 404 on their own URL (stjo_lightbox_content_no_single), so search
 * results and other links point here instead of at the dead single. Returns
 * '' when $post is not a lightbox content page or nothing hosts it (an orphan
 * has no lightbox to open, so callers fall back to the permalink).
 *
 * @param int|WP_Post $post Lightbox content page.
 * @return string URL with #<slug> fragment, or ''.
 */
function stjo_lightbox_content_link( $post ) {
	$post = get_post( $post );
	if ( ! $post || 'page' !== $post->post_type || ! has_term( 'lightbox-content', 'page-category', $post ) ) {
		return '';
	}
	$hosts = stjo_lightbox_content_hosts( $post->ID );
	if ( ! $hosts ) {
		return '';
	}
	$host_url = get_permalink( $hosts[0] );
	return $host_url ? $host_url . '#' . $post->post_name : '';
}

/**
 * Editing a lightbox content page must refresh every page whose lightbox
 * shows it: the modal body is baked into each HOST page's HTML, so the host
 * page's cache is what goes stale (this bit twice locally already — the FAQ
 * looked missing, an edit looked ignored). Finds hosts by the block attribute
 * and purges post cache plus WP Engine's page cache when its API is present.
 */
function stjo_lightbox_content_purge_hosts( $post_id, $post ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}
	if ( ! has_term( 'lightbox-content', 'page-category', $post ) ) {
		return;
	}
	$hosts = stjo_lightbox_content_hosts( $post_id );
	foreach ( $hosts as $stjo_host_id ) {
		clean_post_cache( (int) $stjo_host_id );
		if ( class_exists( 'WpeCommon' ) && method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
			WpeCommon::purge_varnish_cache( (int) $stjo_host_id );
		}
	}
}
add_action( 'save_post_page', 'stjo_lightbox_content_purge_hosts', 20, 2 );
