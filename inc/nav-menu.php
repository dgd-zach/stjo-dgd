<?php
/**
 * Mega menu: renders the primary nav with per-section panels.
 *
 * Everything editors manage lives in Appearance > Menus:
 *   depth 0  section item. Its URL is the section landing page; its native
 *            Description field is the panel intro blurb; promo card fields
 *            are menu-item meta (see inc/nav-menu-fields.php).
 *   depth 1  a child WITH children renders as a column group heading
 *            (linked when it has a real URL); a leaf child renders as a
 *            plain link in an implicit first column.
 *   depth 2  links inside a group.
 *
 * Top-level items with children are disclosure BUTTONS, not links: click
 * (or Enter/Space) toggles the panel, ArrowDown opens and focuses the first
 * link, Escape closes (assets/js/nav.js). They never navigate; link a
 * section landing page from inside the panel (group heading URL or a link
 * item) when one exists. Panels render inside their <li>; on desktop nav.js
 * moves them into the header's .mega-panels band, which opens in flow
 * (pushing content down) and crossfades between sections.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Meta keys for the promo card on depth-0 items (shared with admin fields).
 */
function stjo_nav_promo_keys() {
	return array(
		'heading'      => '_stjo_promo_heading',
		'body'         => '_stjo_promo_body',
		'image_id'     => '_stjo_promo_image_id',
		'button_label' => '_stjo_promo_button_label',
		'button_url'   => '_stjo_promo_button_url',
	);
}

/**
 * Render the primary mega nav. Falls back to the flat config menu until a
 * menu is assigned to the location.
 */
function stjo_mega_nav() {
	$locations = get_nav_menu_locations();
	if ( empty( $locations['primary'] ) ) {
		stjo_primary_menu_fallback();
		return;
	}
	$items = wp_get_nav_menu_items( $locations['primary'] );
	if ( ! $items ) {
		stjo_primary_menu_fallback();
		return;
	}

	// id => children, in menu order.
	$tree = array();
	foreach ( $items as $item ) {
		$tree[ (int) $item->menu_item_parent ][] = $item;
	}
	$sections = $tree[0] ?? array();
	if ( ! $sections ) {
		return;
	}

	echo '<ul class="primary-menu" data-mega-nav>';
	foreach ( $sections as $section ) {
		stjo_mega_nav_section( $section, $tree );
	}
	echo '</ul>';
}

/**
 * One top-level item: plain link when childless, link + panel otherwise.
 */
function stjo_mega_nav_section( $section, $tree ) {
	$children = $tree[ $section->ID ] ?? array();
	$current  = array_intersect(
		array( 'current-menu-item', 'current-menu-ancestor', 'current-menu-parent', 'current_page_ancestor', 'current_page_item' ),
		(array) $section->classes
	) || in_array( untrailingslashit( (string) $section->url ), array( untrailingslashit( stjo_current_url_path() ) ), true );

	if ( ! $children ) {
		printf(
			'<li class="menu-item%1$s"><a class="menu-item__link" href="%2$s">%3$s</a></li>',
			$current ? ' is-current' : '',
			esc_url( $section->url ),
			esc_html( $section->title )
		);
		return;
	}

	$panel_id = 'mega-panel-' . ( $section->post_name ? $section->post_name : $section->ID );

	printf(
		'<li class="menu-item menu-item--section%1$s"><button type="button" class="menu-item__link" aria-expanded="false" aria-controls="%2$s" data-mega-trigger>%3$s</button>',
		$current ? ' is-current' : '',
		esc_attr( $panel_id ),
		esc_html( $section->title )
	);

	echo '<div class="mega-panel" id="' . esc_attr( $panel_id ) . '" data-mega-panel>';
	echo '<div class="mega-panel__inner">';

	// Intro: section name, rule, blurb (the item's native Description).
	echo '<div class="mega-panel__intro">';
	echo '<p class="mega-panel__heading">' . esc_html( $section->title ) . '</p>';
	if ( ! empty( $section->description ) ) {
		echo '<p class="mega-panel__blurb">' . esc_html( $section->description ) . '</p>';
	}
	echo '</div>';

	// Link columns.
	echo '<div class="mega-panel__columns">';
	$loose = array(); // depth-1 leaves collect into one implicit group.
	foreach ( $children as $child ) {
		if ( empty( $tree[ $child->ID ] ) ) {
			$loose[] = $child;
		}
	}
	if ( $loose ) {
		echo '<div class="mega-panel__group">';
		stjo_mega_nav_links( $loose );
		echo '</div>';
	}
	foreach ( $children as $child ) {
		$grandchildren = $tree[ $child->ID ] ?? array();
		if ( ! $grandchildren ) {
			continue;
		}
		echo '<div class="mega-panel__group">';
		$heading_url = ( ! empty( $child->url ) && '#' !== $child->url ) ? $child->url : '';
		if ( $heading_url ) {
			printf(
				'<p class="mega-panel__group-heading"><a href="%1$s">%2$s</a></p>',
				esc_url( $heading_url ),
				esc_html( $child->title )
			);
		} else {
			echo '<p class="mega-panel__group-heading">' . esc_html( $child->title ) . '</p>';
		}
		stjo_mega_nav_links( $grandchildren );
		echo '</div>';
	}
	echo '</div>';

	stjo_mega_nav_promo( $section );

	echo '</div></div></li>';
}

/**
 * <ul> of plain links.
 */
function stjo_mega_nav_links( $items ) {
	echo '<ul class="mega-panel__links">';
	foreach ( $items as $item ) {
		printf(
			'<li><a href="%1$s">%2$s</a></li>',
			esc_url( $item->url ),
			esc_html( $item->title )
		);
	}
	echo '</ul>';
}

/**
 * Promo card from the section item's meta. Skipped when empty.
 */
function stjo_mega_nav_promo( $section ) {
	$keys    = stjo_nav_promo_keys();
	$heading = get_post_meta( $section->ID, $keys['heading'], true );
	$img_id  = (int) get_post_meta( $section->ID, $keys['image_id'], true );
	if ( ! $heading && ! $img_id ) {
		return;
	}
	$body   = get_post_meta( $section->ID, $keys['body'], true );
	$label  = get_post_meta( $section->ID, $keys['button_label'], true );
	$url    = get_post_meta( $section->ID, $keys['button_url'], true );

	echo '<div class="mega-panel__promo">';
	if ( $img_id ) {
		echo '<div class="mega-panel__promo-media">';
		echo wp_get_attachment_image( $img_id, 'medium_large', false, array( 'class' => 'mega-panel__promo-img' ) );
		echo '</div>';
	}
	echo '<div class="mega-panel__promo-content">';
	if ( $heading ) {
		echo '<p class="mega-panel__promo-heading">' . esc_html( $heading ) . '</p>';
	}
	if ( $body ) {
		echo '<p class="mega-panel__promo-body">' . esc_html( $body ) . '</p>';
	}
	if ( $label && $url ) {
		printf(
			'<div class="wp-block-button is-style-outline mega-panel__promo-button"><a class="wp-block-button__link has-white-color has-text-color has-white-background-color has-background wp-element-button" href="%1$s">%2$s</a></div>',
			esc_url( 0 === strpos( $url, 'http' ) ? $url : home_url( $url ) ),
			esc_html( $label )
		);
	}
	echo '</div></div>';
}

/**
 * Path of the current request, for a cheap is-current check on custom links.
 */
function stjo_current_url_path() {
	return home_url( wp_parse_url( add_query_arg( array() ), PHP_URL_PATH ) ?? '/' );
}

/**
 * Front-end assets and the no-js class swap (CSS keeps hover panels usable
 * without JS; the script upgrades them to the full disclosure behavior).
 */
function stjo_nav_assets() {
	wp_enqueue_script(
		'stjo-nav',
		get_template_directory_uri() . '/assets/js/nav.js',
		array(),
		STJO_VERSION,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);
	wp_enqueue_script(
		'stjo-carousel',
		get_template_directory_uri() . '/assets/js/carousel.js',
		array(),
		STJO_VERSION,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);
	wp_enqueue_script(
		'stjo-count-up',
		get_template_directory_uri() . '/assets/js/count-up.js',
		array(),
		STJO_VERSION,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);
	wp_enqueue_script(
		'stjo-share',
		get_template_directory_uri() . '/assets/js/share.js',
		array(),
		STJO_VERSION,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);
}
add_action( 'wp_enqueue_scripts', 'stjo_nav_assets' );

function stjo_nav_no_js_class( $output ) {
	return $output . ' class="no-js"';
}
add_filter( 'language_attributes', 'stjo_nav_no_js_class' );

function stjo_nav_js_class_swap() {
	echo "<script>document.documentElement.classList.replace('no-js','js');</script>\n";
}
add_action( 'wp_head', 'stjo_nav_js_class_swap', 0 );
