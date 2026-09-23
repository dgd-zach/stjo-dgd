<?php
/**
 * Pages list: a "Built out" column plus Built / Not built views.
 *
 * A page counts as built out when it carries real block content. Sitemap
 * placeholders (the seeded "Coming soon" stub, or an empty page) count as
 * not built. Pages rendered by a PHP template instead of their content
 * (the posts page, the Pattern Library) are marked "Template".
 *
 * The rule lives in stjo_page_build_state(); the column, the view links,
 * the sort and the list filter all read from it, so there is one source
 * of truth and it matches what page.php and the seeder call a stub.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build state for one page.
 *
 * @param int|WP_Post|null $post Page.
 * @return string 'built' | 'stub' | 'template' | '' (not a page).
 */
function stjo_page_build_state( $post = null ) {
	$post = get_post( $post );
	if ( ! $post || 'page' !== $post->post_type ) {
		return '';
	}
	if ( (int) get_option( 'page_for_posts' ) === (int) $post->ID ) {
		return 'template';
	}
	if ( '' !== get_page_template_slug( $post ) ) {
		return 'template';
	}
	// Same rule as the seeder's "unbuilt" check: the Coming-soon stub, an
	// empty page, or WordPress's own privacy-policy placeholder.
	if ( stjo_page_is_stub( $post ) || false !== strpos( $post->post_content, 'privacy-policy-tutorial' ) ) {
		return 'stub';
	}
	return 'built';
}

/**
 * Human labels for each state, in display order (Not built first when sorting).
 */
function stjo_page_build_state_labels() {
	return array(
		'stub'     => __( 'Not built', 'stjo' ),
		'built'    => __( 'Built', 'stjo' ),
		'template' => __( 'Template', 'stjo' ),
	);
}

/**
 * Page ids grouped by build state. Trash is left out, matching the list
 * table's "All" count. Each group is ordered by title.
 *
 * @return array<string, int[]>
 */
function stjo_page_ids_by_build_state() {
	static $groups = null;
	if ( null !== $groups ) {
		return $groups;
	}
	$groups = array_fill_keys( array_keys( stjo_page_build_state_labels() ), array() );
	$pages  = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);
	foreach ( $pages as $page ) {
		$state = stjo_page_build_state( $page );
		if ( isset( $groups[ $state ] ) ) {
			$groups[ $state ][] = (int) $page->ID;
		}
	}
	return $groups;
}

/* ---------------------------------------------------------------- column -- */

function stjo_page_build_columns( $columns ) {
	$out = array();
	foreach ( $columns as $key => $label ) {
		$out[ $key ] = $label;
		if ( 'title' === $key ) {
			$out['stjo_built'] = __( 'Built out', 'stjo' );
		}
	}
	return $out;
}
add_filter( 'manage_page_posts_columns', 'stjo_page_build_columns' );

function stjo_page_build_column_content( $column, $post_id ) {
	if ( 'stjo_built' !== $column ) {
		return;
	}
	$state  = stjo_page_build_state( $post_id );
	$labels = stjo_page_build_state_labels();
	if ( ! isset( $labels[ $state ] ) ) {
		return;
	}
	printf(
		'<span class="stjo-build-state stjo-build-state--%s">%s</span>',
		esc_attr( $state ),
		esc_html( $labels[ $state ] )
	);
}
add_action( 'manage_page_posts_custom_column', 'stjo_page_build_column_content', 10, 2 );

function stjo_page_build_sortable_columns( $columns ) {
	$columns['stjo_built'] = 'stjo_built';
	return $columns;
}
add_filter( 'manage_edit-page_sortable_columns', 'stjo_page_build_sortable_columns' );

function stjo_page_build_column_styles() {
	$screen = get_current_screen();
	if ( ! $screen || 'edit-page' !== $screen->id ) {
		return;
	}
	echo '<style id="stjo-page-build-status">
		.fixed .column-stjo_built { width: 9em; }
		.stjo-build-state { display: inline-block; padding: 1px 9px; border-radius: 999px; border: 1px solid; font-size: 12px; line-height: 1.6; font-weight: 600; white-space: nowrap; }
		.stjo-build-state--built { color: #00450c; background: #edfaef; border-color: #68de7c; }
		.stjo-build-state--stub { color: #8a2424; background: #fcf0f1; border-color: #f86368; }
		.stjo-build-state--template { color: #1d2327; background: #f0f0f1; border-color: #c3c4c7; }
		@media screen and (max-width: 782px) { .stjo-build-state { font-size: 13px; } }
	</style>';
}
add_action( 'admin_print_styles-edit.php', 'stjo_page_build_column_styles' );

/* ----------------------------------------------------------------- views -- */

/**
 * "Built (n)" and "Not built (n)" links next to All / Published.
 */
function stjo_page_build_views( $views ) {
	$groups  = stjo_page_ids_by_build_state();
	$labels  = stjo_page_build_state_labels();
	$current = isset( $_GET['stjo_built'] ) ? sanitize_key( wp_unslash( $_GET['stjo_built'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	foreach ( array( 'stub', 'built' ) as $state ) {
		$url = add_query_arg(
			array(
				'post_type'  => 'page',
				'stjo_built' => $state,
			),
			admin_url( 'edit.php' )
		);
		$views[ 'stjo_' . $state ] = sprintf(
			'<a href="%s"%s>%s <span class="count">(%s)</span></a>',
			esc_url( $url ),
			$current === $state ? ' class="current" aria-current="page"' : '',
			esc_html( $labels[ $state ] ),
			number_format_i18n( count( $groups[ $state ] ) )
		);
	}
	return $views;
}
add_filter( 'views_edit-page', 'stjo_page_build_views' );

/* ---------------------------------------------------------- filter + sort -- */

/**
 * Narrow the Pages list to one build state (?stjo_built=stub|built) and
 * sort by the column (?orderby=stjo_built). Both work off the id groups
 * above, so no SQL copy of the rule is needed.
 */
function stjo_page_build_filter_query( $query ) {
	global $pagenow;
	if ( ! is_admin() || 'edit.php' !== $pagenow || ! $query->is_main_query() || 'page' !== $query->get( 'post_type' ) ) {
		return;
	}
	$labels = stjo_page_build_state_labels();
	$state  = isset( $_GET['stjo_built'] ) ? sanitize_key( wp_unslash( $_GET['stjo_built'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$ids    = null;

	if ( $state && isset( $labels[ $state ] ) ) {
		$ids = stjo_page_ids_by_build_state()[ $state ];
	}

	if ( 'stjo_built' === $query->get( 'orderby' ) ) {
		$groups = stjo_page_ids_by_build_state();
		if ( 'DESC' === strtoupper( (string) $query->get( 'order' ) ) ) {
			$groups = array_reverse( $groups, true );
		}
		$sorted = array_merge( ...array_values( $groups ) );
		$ids    = null === $ids ? $sorted : array_values( array_intersect( $sorted, $ids ) );
		$query->set( 'orderby', 'post__in' );
	}

	if ( null !== $ids ) {
		// post__in with an empty list means "everything" to WP_Query; pin it to nothing instead.
		$query->set( 'post__in', $ids ? $ids : array( 0 ) );
	}
}
add_action( 'pre_get_posts', 'stjo_page_build_filter_query' );
