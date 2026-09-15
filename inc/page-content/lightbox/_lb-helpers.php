<?php
/**
 * Shared closures for lightbox-content seed sources. Lightbox bodies are
 * narrow and scroll, so images follow Zach's rules (2026-09-15):
 *  - icon / badge / small graphic: small image ABOVE the heading ($lb_logo)
 *  - portrait photo: media-text with Crop-to-fill, heading ABOVE the block
 *    ($lb_media_text), so the photo fills the copy's height
 *  - landscape photo: full-width image, then heading, then copy ($lb_photo)
 * Sections are separated with a medium spacer ($lb_gap).
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Portrait photo beside its copy: media-text with "Crop image to fill" on, so
 * the photo fills the height of the copy. The section heading goes ABOVE the
 * block (full width), never inside its text column. $width is the media
 * column as a percentage.
 */
$lb_media_text = function ( $file, $inner, $width = 35, $right = false, $fill = true ) {
	$img = stjo_seeded_image( $file );
	if ( ! $img['id'] ) {
		return $inner;
	}
	$alt   = (string) get_post_meta( $img['id'], '_wp_attachment_image_alt', true );
	$attrs = array( 'mediaId' => (int) $img['id'], 'mediaType' => 'image', 'mediaWidth' => (int) $width );
	if ( $right ) {
		$attrs['mediaPosition'] = 'right';
	}
	if ( $fill ) {
		$attrs['imageFill'] = true;
	}
	$cols   = $right ? 'auto ' . (int) $width . '%' : (int) $width . '% auto';
	$cls    = 'wp-block-media-text' . ( $right ? ' has-media-on-the-right' : '' ) . ' is-stacked-on-mobile' . ( $fill ? ' is-image-fill-element' : '' );
	$figure = '<figure class="wp-block-media-text__media"><img src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '" class="wp-image-' . (int) $img['id'] . ' size-full"' . ( $fill ? ' style="object-position:50% 50%"' : '' ) . '/></figure>';
	$body   = '<div class="wp-block-media-text__content">' . $inner . '</div>';
	return '<!-- wp:media-text ' . serialize_block_attributes( $attrs ) . ' -->' . "\n"
		. '<div class="' . $cls . '" style="grid-template-columns:' . $cols . '">'
		. ( $right ? $body . $figure : $figure . $body ) . '</div>' . "\n"
		. '<!-- /wp:media-text -->' . "\n\n";
};

/** Landscape photo: full width, above its heading and copy (never media-text). */
$lb_photo = function ( $file ) {
	$img = stjo_seeded_image( $file );
	if ( ! $img['id'] ) {
		return '';
	}
	$alt = (string) get_post_meta( $img['id'], '_wp_attachment_image_alt', true );
	return '<!-- wp:image {"id":' . (int) $img['id'] . ',"sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->' . "\n"
		. '<figure class="wp-block-image size-full is-style-rounded"><img src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '" class="wp-image-' . (int) $img['id'] . '"/></figure>' . "\n"
		. '<!-- /wp:image -->' . "\n\n";
};

$lb_h3 = function ( $text ) {
	return '<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . $text . '</h3><!-- /wp:heading -->';
};
$lb_sub = function ( $text ) {
	return '<!-- wp:paragraph {"className":"stjo-subhead"} --><p class="stjo-subhead">' . $text . '</p><!-- /wp:paragraph -->';
};
$lb_p = function ( $text ) {
	return '<!-- wp:paragraph --><p>' . $text . '</p><!-- /wp:paragraph -->';
};

/** Small graphic (logo, seal) above its heading, at a modest fixed width. */
$lb_logo = function ( $file, $width = 160 ) {
	$img = stjo_seeded_image( $file );
	if ( ! $img['id'] ) {
		return '';
	}
	$alt = (string) get_post_meta( $img['id'], '_wp_attachment_image_alt', true );
	return '<!-- wp:image {"id":' . (int) $img['id'] . ',"width":"' . (int) $width . 'px","height":"auto","sizeSlug":"full","linkDestination":"none"} -->' . "\n"
		. '<figure class="wp-block-image size-full is-resized"><img src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '" class="wp-image-' . (int) $img['id'] . '" style="width:' . (int) $width . 'px;height:auto"/></figure>' . "\n"
		. '<!-- /wp:image -->' . "\n\n";
};

/** Medium spacer between sections of a lightbox page. */
$lb_gap = function () {
	return '<!-- wp:spacer {"height":"var:preset|spacing|medium"} -->' . "\n"
		. '<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>' . "\n"
		. '<!-- /wp:spacer -->' . "\n\n";
};

