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

	wp_enqueue_style( 'stjo-fonts', $uri . '/assets/css/fonts.css', array(), STJO_VERSION );
	wp_enqueue_style( 'stjo-tokens', $uri . '/assets/css/tokens.css', array( 'stjo-fonts' ), STJO_VERSION );
	wp_enqueue_style( 'stjo-style', get_stylesheet_uri(), array( 'stjo-tokens' ), STJO_VERSION );
	wp_enqueue_style( 'stjo-main', $uri . '/assets/css/main.css', array( 'stjo-style' ), STJO_VERSION );
	wp_enqueue_style( 'stjo-sections', $uri . '/assets/css/sections.css', array( 'stjo-main' ), STJO_VERSION );
	wp_enqueue_style( 'stjo-overrides', $uri . '/assets/css/overrides.css', array( 'stjo-sections' ), STJO_VERSION );
}
add_action( 'wp_enqueue_scripts', 'stjo_enqueue_styles' );

/**
 * Editor styles — same files, same order, as the frontend chain so the canvas matches.
 */
function stjo_editor_styles() {
	add_editor_style( array(
		'assets/css/fonts.css',
		'assets/css/tokens.css',
		'style.css',
		'assets/css/main.css',
		'assets/css/sections.css',
		'assets/css/overrides.css',
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

require_once get_template_directory() . '/inc/theme-config.php';
require_once get_template_directory() . '/inc/cpt-loader.php';
require_once get_template_directory() . '/inc/block-styles.php';
require_once get_template_directory() . '/inc/block-patterns.php';
