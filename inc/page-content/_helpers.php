<?php
/**
 * Shared closures for the page-content seed sources built from the General
 * Content template. Each file `require`s this; nothing here is loaded by the
 * theme at runtime (page-content files are seed sources only).
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sp = function ( $size ) {
	return '<!-- wp:spacer {"height":"var:preset|spacing|' . $size . '"} -->'
		. '<div style="height:var(--wp--preset--spacing--' . $size . ')" aria-hidden="true" class="wp-block-spacer"></div>'
		. '<!-- /wp:spacer -->';
};

/** The blue title band: eyebrow + H1. The zigzag ribbon under it is CSS. */
$title_band = function ( $eyebrow, $h1 ) use ( $sp ) {
	return '<!-- wp:group {"metadata":{"name":"Page Title Band"},"align":"full","textColor":"white","className":"stjo-page-title-band","layout":{"type":"constrained"}} -->' . "\n"
		. '<div class="wp-block-group alignfull stjo-page-title-band has-white-color has-text-color">' . $sp( 'large' ) . "\n\n"
		. '<!-- wp:paragraph {"align":"center","textColor":"yellow","className":"is-style-eyebrow"} -->' . "\n"
		. '<p class="has-text-align-center is-style-eyebrow has-yellow-color has-text-color">' . $eyebrow . '</p>' . "\n"
		. '<!-- /wp:paragraph -->' . "\n\n"
		. '<!-- wp:heading {"textAlign":"center","level":1,"textColor":"white"} -->' . "\n"
		. '<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">' . $h1 . '</h1>' . "\n"
		. '<!-- /wp:heading -->' . "\n\n"
		. $sp( 'large' ) . '</div>' . "\n"
		. '<!-- /wp:group -->' . "\n\n";
};

/**
 * Text info card (the wireframe's "Item / short description / Learn more"):
 * H3, blurb, arrow link. $img (root-relative URL) and $alt add a photo on top.
 */
$card = function ( $title, $text, $href, $cta, $img = '', $alt = '' ) {
	$figure = $img
		? '<!-- wp:image {"sizeSlug":"large","className":"stjo-info-card__image"} -->'
			. '<figure class="wp-block-image size-large stjo-info-card__image"><img src="' . esc_url( $img ) . '" alt="' . esc_attr( $alt ) . '"/></figure>'
			. '<!-- /wp:image -->'
		: '';
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:group {"className":"stjo-info-card"} --><div class="wp-block-group stjo-info-card">'
		. $figure
		. '<!-- wp:group {"className":"stjo-info-card__body"} --><div class="wp-block-group stjo-info-card__body">'
		. '<!-- wp:heading {"level":3,"textColor":"blue-900"} -->'
		. '<h3 class="wp-block-heading has-blue-900-color has-text-color">' . $title . '</h3>'
		. '<!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>' . $text . '</p><!-- /wp:paragraph -->'
		. '<!-- wp:buttons --><div class="wp-block-buttons">'
		. '<!-- wp:button {"textColor":"blue-900","className":"is-style-arrow-link"} -->'
		. '<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link has-blue-900-color has-text-color wp-element-button" href="' . esc_url( $href ) . '">' . $cta . '</a></div>'
		. '<!-- /wp:button --></div><!-- /wp:buttons -->'
		. '</div><!-- /wp:group -->'
		. '</div><!-- /wp:group -->'
		. '</div><!-- /wp:column -->';
};

/** ID of a page by slug (any status), 0 when it does not exist yet. */
$page_id = function ( $slug ) {
	if ( function_exists( 'stjo_seed_find_page' ) ) {
		$p = stjo_seed_find_page( $slug );
		return $p ? (int) $p->ID : 0;
	}
	$p = get_posts( array( 'post_type' => 'page', 'name' => $slug, 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) );
	return $p ? (int) $p[0] : 0;
};

/**
 * "Links that open Lightboxes" card (text style) whose body is a
 * lightbox-content page; $excerpt is the card's blurb. Sits in a column like
 * $card so the two can share a row.
 */
$lightbox_card = function ( $title, $excerpt, $page_slug, $label, $style = 'is-style-text', $hide_title = false ) use ( $page_id ) {
	$attrs = array(
		'title'     => $title,
		'linkLabel' => $label,
		'content'   => $excerpt,
		'className' => $style,
	);
	if ( $hide_title ) {
		$attrs['hideTitle'] = true; // the content page opens with its own heading
	}
	$id = $page_id( $page_slug );
	if ( $id ) {
		$attrs['contentPageId'] = $id;
	}
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:stjo/lightbox-card ' . serialize_block_attributes( $attrs ) . ' /-->'
		. '</div><!-- /wp:column -->';
};

/**
 * Row(s) of cards inside the related-cards columns wrapper, $per_row (3 by
 * default) to a row. Keep a row to one card kind: info cards and "Links that
 * open Lightboxes" cards are styled differently and must not share a row.
 */
$card_rows = function ( array $cards, $per_row = 3 ) {
	$out = '';
	foreach ( array_chunk( $cards, max( 1, (int) $per_row ) ) as $row ) {
		$out .= '<!-- wp:columns {"className":"stjo-related-cards"} -->' . "\n"
			. '<div class="wp-block-columns stjo-related-cards">' . implode( '', $row ) . '</div>' . "\n"
			. '<!-- /wp:columns -->' . "\n\n";
	}
	return $out;
};

$give_once = esc_url( (string) stjo_config_get( 'give.once_url', 'https://give.stjo.org/site/Donation2?df_id=6740&6740.donation=form1' ) );
