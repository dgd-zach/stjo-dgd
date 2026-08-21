<?php
/**
 * Hero slide controls — extra per-slide framing controls on core/cover.
 *
 * The carousel is core/cover blocks inside a group with the Carousel style
 * (inc/patterns/hero-carousel.php). Everything an editor needed to control
 * framing used to live in magic "Additional CSS class" strings —
 * focus-sm-23-62, scrim-sm-40 — which nobody can discover and which are easy
 * to mistype. This registers real attributes on core/cover and renders them as
 * CSS custom properties, so the same behaviour gets a sidebar panel
 * (assets/js/hero-slide-controls.js).
 *
 * Deliberately a filter on core/cover rather than a new block: existing slides
 * keep working untouched and no post content has to be migrated. The old
 * classes still work too — CSS keeps them — so nothing that was set by hand
 * breaks.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the extra attributes on core/cover so the editor persists them.
 */
function stjo_hero_slide_attributes( $args, $name ) {
	if ( 'core/cover' !== $name ) {
		return $args;
	}
	$args['attributes'] = array_merge(
		(array) ( $args['attributes'] ?? array() ),
		array(
			// Image scale. 1 = untouched. Acts as a floor when an anchor is
			// set: the anchor raises it if more is needed to avoid a gap.
			'stjoZoom'    => array( 'type' => 'number', 'default' => 1 ),
			// Move the photo sideways, in percent of the band's width.
			// Negative is left. 0 = untouched.
			'stjoShiftX'  => array( 'type' => 'number', 'default' => 0 ),
			// Phone scale. 0 means "use the desktop value".
			'stjoZoomSm'  => array( 'type' => 'number', 'default' => 0 ),
			// Phone focal point, 0-100. null = fall back to the theme default.
			'stjoFocalSmX' => array( 'type' => 'number' ),
			'stjoFocalSmY' => array( 'type' => 'number' ),
			// Auto scrim: 'auto' keeps the contrast-guaranteed scrim,
			// 'off' opts this slide out entirely.
			'stjoScrim'   => array( 'type' => 'string', 'default' => 'auto' ),
			// Phone scrim strength 0-100. null = theme default.
			'stjoScrimSm' => array( 'type' => 'number' ),
		)
	);
	return $args;
}
add_filter( 'register_block_type_args', 'stjo_hero_slide_attributes', 10, 2 );

/**
 * Render the attributes onto the cover as CSS custom properties and classes.
 */
function stjo_hero_slide_render( $block_content, $block ) {
	if ( empty( $block['blockName'] ) || 'core/cover' !== $block['blockName'] || '' === trim( (string) $block_content ) ) {
		return $block_content;
	}
	$attrs = isset( $block['attrs'] ) ? (array) $block['attrs'] : array();

	$vars    = array();
	$classes = array();

	$zoom = isset( $attrs['stjoZoom'] ) ? (float) $attrs['stjoZoom'] : 1.0;
	if ( $zoom < 1.0 || $zoom > 3.0 ) {
		$zoom = 1.0;
	}

	// Core writes the focal point as an inline object-position on the <img>,
	// which CSS cannot read back. Mirror it into custom properties so the zoom
	// origin and the crop can agree.
	$focal_x = isset( $attrs['focalPoint']['x'] ) ? (float) $attrs['focalPoint']['x'] : null;
	$focal_y = isset( $attrs['focalPoint']['y'] ) ? (float) $attrs['focalPoint']['y'] : null;
	if ( null !== $focal_x && null !== $focal_y ) {
		// Zoom-only slides still scale about the focal point.
		$vars[] = '--stjo-focus:'
			. round( $focal_x * 100, 2 ) . '% '
			. round( $focal_y * 100, 2 ) . '%';
	}
	if ( null !== $focal_y ) {
		// Wanted on its own for shifted slides: the slider owns the horizontal
		// axis there, so only the vertical still comes from the focal point.
		$vars[] = '--stjo-focus-y:' . round( $focal_y * 100, 2 ) . '%';
	}

	// Horizontal nudge, straight through: the slider's number IS how far the
	// photo moves, as a percentage of the band's width.
	$shift = isset( $attrs['stjoShiftX'] ) ? (float) $attrs['stjoShiftX'] : 0.0;
	if ( abs( $shift ) > 0.01 && $shift >= -100 && $shift <= 100 ) {
		$vars[]    = '--stjo-slide-x:' . round( $shift, 2 ) . '%';
		$vars[]    = '--stjo-slide-zoom:' . round( $zoom, 3 );
		$classes[] = 'has-slide-shift';
	} elseif ( $zoom > 1.0 ) {
		$vars[]    = '--stjo-slide-zoom:' . round( $zoom, 3 );
		$classes[] = 'has-slide-zoom';
	}

	$zoom_sm = isset( $attrs['stjoZoomSm'] ) ? (float) $attrs['stjoZoomSm'] : 0.0;
	if ( $zoom_sm >= 1.0 && $zoom_sm <= 3.0 ) {
		$vars[] = '--stjo-slide-zoom-sm:' . round( $zoom_sm, 3 );
		$classes[] = 'has-slide-zoom-sm';
	}

	// Phone focal point. Same custom property the focus-sm-X-Y classes feed,
	// so the two routes cannot disagree.
	$fx = isset( $attrs['stjoFocalSmX'] ) ? (float) $attrs['stjoFocalSmX'] : null;
	$fy = isset( $attrs['stjoFocalSmY'] ) ? (float) $attrs['stjoFocalSmY'] : null;
	if ( null !== $fx && null !== $fy && $fx >= 0 && $fx <= 100 && $fy >= 0 && $fy <= 100 ) {
		$vars[]    = '--stjo-focus-sm:' . round( $fx, 2 ) . '% ' . round( $fy, 2 ) . '%';
		$classes[] = 'has-focus-sm';
	}

	if ( isset( $attrs['stjoScrim'] ) && 'off' === $attrs['stjoScrim'] ) {
		// carousel.js checks for this before injecting a scrim element.
		$classes[] = 'no-auto-scrim';
	}

	$scrim_sm = isset( $attrs['stjoScrimSm'] ) ? (float) $attrs['stjoScrimSm'] : null;
	if ( null !== $scrim_sm && $scrim_sm >= 0 && $scrim_sm <= 100 ) {
		$vars[] = '--stjo-scrim-sm:' . round( $scrim_sm / 100, 3 );
	}

	if ( ! $vars && ! $classes ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );
	if ( ! $processor->next_tag( array( 'class_name' => 'wp-block-cover' ) ) ) {
		return $block_content;
	}
	foreach ( $classes as $class ) {
		$processor->add_class( $class );
	}
	if ( $vars ) {
		$existing = (string) $processor->get_attribute( 'style' );
		$existing = '' === trim( $existing ) ? '' : rtrim( trim( $existing ), ';' ) . ';';
		$processor->set_attribute( 'style', $existing . implode( ';', $vars ) . ';' );
	}
	return $processor->get_updated_html();
}
add_filter( 'render_block', 'stjo_hero_slide_render', 10, 2 );

/**
 * Editor script for the sidebar panel.
 */
function stjo_hero_slide_editor_assets() {
	wp_enqueue_script(
		'stjo-hero-slide-controls',
		get_template_directory_uri() . '/assets/js/hero-slide-controls.js',
		array( 'wp-hooks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-compose' ),
		(string) filemtime( get_template_directory() . '/assets/js/hero-slide-controls.js' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'stjo_hero_slide_editor_assets' );
