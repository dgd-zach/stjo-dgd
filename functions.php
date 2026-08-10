<?php
/**
 * St. Joseph's Indian School theme functions.
 *
 * Hybrid classic theme (Tier B): PHP templates + settings-only theme.json.
 * Native-first blocks; homepage assembled from block patterns. No custom blocks.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'STJO_VERSION', '1.0.0' );

/**
 * Theme setup. Presets (palette, font sizes/families, spacing, widths) live in
 * theme.json — do NOT duplicate them via add_theme_support here.
 */
function stjo_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' ); // REQUIRED for editor parity with add_editor_style().
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 103,
		'width'       => 167,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'align-wide' );

	register_nav_menus( array(
		'primary' => __( 'Primary', 'stjo' ),
		'footer'  => __( 'Footer', 'stjo' ),
	) );

	$GLOBALS['content_width'] = 1280; // matches theme.json contentSize (fallback only).
}
add_action( 'after_setup_theme', 'stjo_setup' );

/**
 * Frontend style chain: fonts → tokens → style.css → main.css.
 */
function stjo_enqueue_styles() {
	$uri = get_template_directory_uri();

	wp_enqueue_style( 'stjo-fontawesome', $uri . '/assets/vendor/fontawesome/css/all.min.css', array(), '7.3.1' );
	wp_enqueue_style( 'stjo-fonts', $uri . '/assets/css/fonts.css', array(), STJO_VERSION );
	wp_enqueue_style( 'stjo-tokens', $uri . '/assets/css/tokens.css', array( 'stjo-fonts' ), STJO_VERSION );
	wp_enqueue_style( 'stjo-style', get_stylesheet_uri(), array( 'stjo-tokens' ), STJO_VERSION );
	// filemtime versioning on the frequently-edited CSS so edits actually
	// cache-bust (STJO_VERSION is static, so its URL never changes and
	// browsers keep serving a stale copy).
	$mtime = function ( $rel ) {
		return (string) filemtime( get_template_directory() . $rel );
	};
	wp_enqueue_style( 'stjo-main', $uri . '/assets/css/main.css', array( 'stjo-style' ), $mtime( '/assets/css/main.css' ) );
	wp_enqueue_style( 'stjo-palette-buttons', $uri . '/assets/css/palette-buttons.css', array( 'stjo-main' ), $mtime( '/assets/css/palette-buttons.css' ) );
	wp_enqueue_style( 'stjo-sections', $uri . '/assets/css/sections.css', array( 'stjo-palette-buttons' ), $mtime( '/assets/css/sections.css' ) );
	wp_enqueue_style( 'stjo-hover', $uri . '/assets/css/hover.css', array( 'stjo-sections' ), $mtime( '/assets/css/hover.css' ) );
	wp_enqueue_style( 'stjo-overrides', $uri . '/assets/css/overrides.css', array( 'stjo-hover' ), $mtime( '/assets/css/overrides.css' ) );
	// Utility classes load last so they reliably override defaults.
	wp_enqueue_style( 'stjo-utilities', $uri . '/assets/css/utilities.css', array( 'stjo-overrides' ), $mtime( '/assets/css/utilities.css' ) );
}
add_action( 'wp_enqueue_scripts', 'stjo_enqueue_styles' );

/**
 * Load core block styles on every page. WP 5.8+ only enqueues a block's
 * stylesheet when that block appears in the parsed POST content, but the
 * site-wide pre-footer (Ways to Give) is raw cover/columns/button markup in a
 * template part, which that detection never sees. Without this, interior
 * pages whose content has no cover block render the band unstyled (cover
 * images don't fill, text drops below the photo). Loading the combined
 * stylesheet guarantees the band looks identical everywhere.
 */
add_filter( 'should_load_separate_core_block_assets', '__return_false' );

/**
 * Editor styles — same files, same order, as the frontend chain so the canvas matches.
 */
function stjo_editor_styles() {
	add_editor_style( array(
		'assets/css/fonts.css',
		'assets/css/tokens.css',
		'style.css',
		'assets/css/main.css',
		'assets/css/palette-buttons.css',
		'assets/css/sections.css',
		'assets/css/hover.css',
		'assets/css/overrides.css',
		'assets/css/utilities.css',
	) );
}
add_action( 'after_setup_theme', 'stjo_editor_styles' );

/**
 * Editor-only overrides (Gutenberg-specific selectors). Loads after core editor styles.
 */
function stjo_editor_overrides() {
	wp_enqueue_style(
		'stjo-editor-overrides',
		get_template_directory_uri() . '/assets/css/editor.css',
		array( 'wp-edit-blocks' ),
		STJO_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', 'stjo_editor_overrides' );

/**
 * Register custom blocks compiled into /build (none yet — guarded so it is a no-op).
 */
function stjo_register_blocks() {
	$blocks_dir = get_template_directory() . '/build/blocks/';
	if ( ! is_dir( $blocks_dir ) ) {
		return;
	}
	foreach ( glob( $blocks_dir . '*', GLOB_ONLYDIR ) as $block_dir ) {
		register_block_type( $block_dir );
	}
}
add_action( 'init', 'stjo_register_blocks' );

/**
 * Yoast skips building indexables on non-production environments (Local sets
 * WP_ENVIRONMENT_TYPE), which silently drops page ancestors from breadcrumb
 * trails in dev. Force indexing on so local matches production behavior.
 */
add_filter( 'Yoast\WP\SEO\should_index_indexables', '__return_true' );

/**
 * Breadcrumb separator: the brand chevron (same glyph as the carousel
 * arrows) instead of a text character. The wpseo_titles separator setting
 * remains as a plain-text fallback for feeds/schema contexts.
 */
function stjo_breadcrumb_separator() {
	return '<span class="stjo-breadcrumbs__sep" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 18 18" fill="none" focusable="false"><path d="M7 4l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>';
}
add_filter( 'wpseo_breadcrumb_separator', 'stjo_breadcrumb_separator' );

require_once get_template_directory() . '/inc/theme-config.php';
require_once get_template_directory() . '/inc/asset-helper.php';
require_once get_template_directory() . '/inc/nav-menu.php';
require_once get_template_directory() . '/inc/nav-menu-fields.php';
require_once get_template_directory() . '/inc/cpt-loader.php';
require_once get_template_directory() . '/inc/stories.php';
require_once get_template_directory() . '/inc/blocks.php';
require_once get_template_directory() . '/inc/block-styles.php';
require_once get_template_directory() . '/inc/block-patterns.php';
require_once get_template_directory() . '/inc/video-facade.php';
require_once get_template_directory() . '/inc/blog.php';
require_once get_template_directory() . '/inc/chatbot.php';

/**
 * Posts pagination styled like the site's carousel nav: centered, numbered,
 * with round chevron arrows (same SVG as carousel.js) instead of prev/next
 * text. Shared by search.php and index.php; styled via .pagination in main.css.
 */
function stjo_posts_pagination( $extra = array() ) {
	$prev = '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true" focusable="false"><path d="M11 4 6 9l5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	$next = '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true" focusable="false"><path d="M7 4l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	the_posts_pagination(
		array_merge(
			array(
				'mid_size'           => 1,
				'prev_text'          => $prev . '<span class="screen-reader-text">' . esc_html__( 'Previous page', 'stjo' ) . '</span>',
				'next_text'          => $next . '<span class="screen-reader-text">' . esc_html__( 'Next page', 'stjo' ) . '</span>',
				'screen_reader_text' => __( 'Pagination', 'stjo' ),
			),
			$extra // e.g. add_args to keep the active category on page links.
		)
	);
}

/**
 * A page that exists in the sitemap but hasn't been designed yet: either empty
 * or still carrying the seeded "coming soon" placeholder. These get the default
 * internal hero (see stjo_page_hero / page.php).
 */
function stjo_page_is_stub( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return false;
	}
	$content = trim( $post->post_content );
	if ( '' === $content ) {
		return true;
	}
	return false !== strpos( $content, 'built out during the content phase' );
}

/**
 * Default internal page hero: the full-width blue title band (parent-section
 * eyebrow + page H1) used across built pages, followed by the zigzag divider.
 * Rendered by page.php for stub pages so every created-but-unbuilt page still
 * has the branded hero. Mirrors the .stjo-page-title-band markup that built
 * pages carry in their own content.
 */
function stjo_page_hero( $post = null ) {
	$post   = get_post( $post );
	$parent = ( $post && $post->post_parent ) ? get_the_title( $post->post_parent ) : '';
	?>
	<div class="wp-block-group alignfull stjo-page-title-band has-white-color has-text-color">
		<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
		<?php if ( $parent ) : ?>
			<p class="has-text-align-center is-style-eyebrow has-white-color has-text-color"><?php echo esc_html( $parent ); ?></p>
		<?php endif; ?>
		<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color"><?php echo esc_html( get_the_title( $post ) ); ?></h1>
		<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
	</div>
	<hr class="wp-block-separator has-alpha-channel-opacity alignfull" />
	<?php
}
