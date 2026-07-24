<?php
/**
 * CPT: Timeline Event — powers the Our History scroll timeline (stjo/timeline
 * block). Auto-loaded by inc/cpt-loader.php.
 *
 * Content model: one post per milestone. The client fills a single Year field;
 * saving syncs a `timeline-year` term ("1927") and a `timeline-decade` term
 * ("1920s") so both taxonomies stay filterable in the admin without anyone
 * having to set them by hand. No single views — events render only inside the
 * timeline block.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stjo_register_timeline_event() {
	register_post_type( 'timeline-event', array(
		'labels'              => array(
			'name'          => __( 'Timeline', 'stjo' ),
			'singular_name' => __( 'Timeline Event', 'stjo' ),
			'add_new_item'  => __( 'Add New Timeline Event', 'stjo' ),
			'edit_item'     => __( 'Edit Timeline Event', 'stjo' ),
			'menu_name'     => __( 'Timeline', 'stjo' ),
			'not_found'     => __( 'No timeline events found.', 'stjo' ),
		),
		'public'              => false,
		'show_ui'             => true,
		'show_in_rest'        => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'has_archive'         => false,
		'rewrite'             => false,
		'menu_icon'           => 'dashicons-clock',
		'menu_position'       => 21,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'custom-fields' ), // custom-fields: REST meta for the focus panel
	) );

	register_taxonomy( 'timeline-decade', 'timeline-event', array(
		'labels'             => array(
			'name'          => __( 'Decades', 'stjo' ),
			'singular_name' => __( 'Decade', 'stjo' ),
		),
		'hierarchical'       => true,
		'public'             => false,
		'show_ui'            => true,
		'show_in_rest'       => false, // hidden from the editor sidebar — synced from the Year field.
		'meta_box_cb'        => false,
		'show_in_quick_edit' => false,
		'show_admin_column'  => true,
		'rewrite'            => false,
	) );

	register_taxonomy( 'timeline-year', 'timeline-event', array(
		'labels'             => array(
			'name'          => __( 'Years', 'stjo' ),
			'singular_name' => __( 'Year', 'stjo' ),
		),
		'hierarchical'       => false,
		'public'             => false,
		'show_ui'            => true,
		'show_in_rest'       => false, // hidden from the editor sidebar — synced from the Year field.
		'meta_box_cb'        => false,
		'show_in_quick_edit' => false,
		'show_admin_column'  => true,
		'rewrite'            => false,
	) );

	register_post_meta( 'timeline-event', 'stjo_timeline_year', array(
		'type'              => 'integer',
		'single'            => true,
		'sanitize_callback' => 'absint',
		'show_in_rest'      => true,
		'auth_callback'     => function () {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'timeline-event', 'stjo_timeline_image_layout', array(
		'type'              => 'string',
		'single'            => true,
		'default'           => 'horizontal',
		'sanitize_callback' => 'stjo_timeline_sanitize_layout',
		'show_in_rest'      => true,
		'auth_callback'     => function () {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'timeline-event', 'stjo_timeline_image_focus', array(
		'type'              => 'string',
		'single'            => true,
		'default'           => 'center center',
		'sanitize_callback' => 'stjo_timeline_sanitize_focus',
		'show_in_rest'      => true,
		'auth_callback'     => function () {
			return current_user_can( 'edit_posts' );
		},
	) );
}
add_action( 'init', 'stjo_register_timeline_event' );

function stjo_timeline_sanitize_layout( $value ) {
	return in_array( $value, array( 'horizontal', 'vertical' ), true ) ? $value : 'horizontal';
}

/**
 * Focus is stored as a CSS object-position value: "X% Y%" from the editor's
 * FocalPointPicker panel (focus-panel.js), with legacy keyword pairs
 * ("center top") still accepted.
 */
function stjo_timeline_sanitize_focus( $value ) {
	$value    = trim( (string) $value );
	$keywords = array( 'left', 'center', 'right' );
	$parts    = preg_split( '/\s+/', $value );
	if ( 2 === count( $parts ) ) {
		if ( in_array( $parts[0], $keywords, true ) && in_array( $parts[1], array( 'top', 'center', 'bottom' ), true ) ) {
			return $parts[0] . ' ' . $parts[1];
		}
		if ( preg_match( '/^(\d{1,3}(?:\.\d+)?)%$/', $parts[0], $mx ) && preg_match( '/^(\d{1,3}(?:\.\d+)?)%$/', $parts[1], $my ) ) {
			$x = min( 100, max( 0, (float) $mx[1] ) );
			$y = min( 100, max( 0, (float) $my[1] ) );
			return $x . '% ' . $y . '%';
		}
	}
	return 'center center';
}

/**
 * FocalPointPicker panel (block editor sidebar) for the focus meta.
 */
function stjo_timeline_editor_assets() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'timeline-event' !== $screen->post_type ) {
		return;
	}
	wp_enqueue_script(
		'stjo-timeline-focus-panel',
		get_template_directory_uri() . '/src/blocks/timeline/focus-panel.js',
		array( 'wp-plugins', 'wp-editor', 'wp-edit-post', 'wp-components', 'wp-element', 'wp-data' ),
		STJO_VERSION,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'stjo_timeline_editor_assets' );

/**
 * Year + image layout meta box (settings sidebar).
 */
function stjo_timeline_add_meta_box() {
	add_meta_box(
		'stjo-timeline-details',
		__( 'Timeline Event Details', 'stjo' ),
		'stjo_timeline_render_meta_box',
		'timeline-event',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'stjo_timeline_add_meta_box' );

function stjo_timeline_render_meta_box( $post ) {
	$year   = get_post_meta( $post->ID, 'stjo_timeline_year', true );
	$layout = get_post_meta( $post->ID, 'stjo_timeline_image_layout', true );
	$layout = stjo_timeline_sanitize_layout( $layout ? $layout : 'horizontal' );
	wp_nonce_field( 'stjo_timeline_details', 'stjo_timeline_details_nonce' );
	?>
	<p>
		<label for="stjo-timeline-year"><strong><?php esc_html_e( 'Year', 'stjo' ); ?></strong></label><br>
		<input type="number" id="stjo-timeline-year" name="stjo_timeline_year"
			value="<?php echo esc_attr( $year ? $year : '' ); ?>"
			min="1900" max="2100" step="1" style="width:100%"
			placeholder="<?php esc_attr_e( 'e.g. 1927', 'stjo' ); ?>">
	</p>
	<p class="description"><?php esc_html_e( 'The decade group and year chip come from this field. Events sort oldest first; use Order (Attributes) to break ties within the same year.', 'stjo' ); ?></p>
	<fieldset>
		<legend><strong><?php esc_html_e( 'Featured image layout', 'stjo' ); ?></strong></legend>
		<p>
			<label>
				<input type="radio" name="stjo_timeline_image_layout" value="horizontal" <?php checked( $layout, 'horizontal' ); ?>>
				<?php esc_html_e( 'Horizontal (image across the top)', 'stjo' ); ?>
			</label><br>
			<label>
				<input type="radio" name="stjo_timeline_image_layout" value="vertical" <?php checked( $layout, 'vertical' ); ?>>
				<?php esc_html_e( 'Vertical (image down the left side)', 'stjo' ); ?>
			</label>
		</p>
	</fieldset>
	<p class="description"><?php esc_html_e( 'Use the Image Focus panel below to pick which part of the featured image stays in view.', 'stjo' ); ?></p>
	<?php
}

function stjo_timeline_save_meta( $post_id ) {
	if ( ! isset( $_POST['stjo_timeline_details_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['stjo_timeline_details_nonce'] ), 'stjo_timeline_details' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$year = isset( $_POST['stjo_timeline_year'] ) ? absint( $_POST['stjo_timeline_year'] ) : 0;
	if ( $year ) {
		update_post_meta( $post_id, 'stjo_timeline_year', $year );
	} else {
		delete_post_meta( $post_id, 'stjo_timeline_year' );
	}

	$layout = isset( $_POST['stjo_timeline_image_layout'] ) ? sanitize_key( $_POST['stjo_timeline_image_layout'] ) : 'horizontal';
	update_post_meta( $post_id, 'stjo_timeline_image_layout', stjo_timeline_sanitize_layout( $layout ) );

	// Image focus is saved through the editor's meta REST update (focus-panel.js), not this box.

	stjo_timeline_sync_terms( $post_id );
}
add_action( 'save_post_timeline-event', 'stjo_timeline_save_meta' );

/**
 * Mirror the Year meta into the timeline-year + timeline-decade taxonomies.
 * Callable directly (seed scripts) as well as from the save handler.
 */
function stjo_timeline_sync_terms( $post_id ) {
	$year = absint( get_post_meta( $post_id, 'stjo_timeline_year', true ) );
	if ( ! $year ) {
		wp_set_object_terms( $post_id, array(), 'timeline-year' );
		wp_set_object_terms( $post_id, array(), 'timeline-decade' );
		return;
	}
	$decade = (string) ( (int) floor( $year / 10 ) * 10 ) . 's';
	wp_set_object_terms( $post_id, (string) $year, 'timeline-year' );
	wp_set_object_terms( $post_id, $decade, 'timeline-decade' );
}

/**
 * Admin list: sortable Year column (before the taxonomy columns), sorted
 * oldest-first by default so the list reads like the timeline.
 */
function stjo_timeline_columns( $columns ) {
	$out = array();
	foreach ( $columns as $key => $label ) {
		$out[ $key ] = $label;
		if ( 'title' === $key ) {
			$out['stjo_year'] = __( 'Year', 'stjo' );
		}
	}
	return $out;
}
add_filter( 'manage_timeline-event_posts_columns', 'stjo_timeline_columns' );

function stjo_timeline_column_content( $column, $post_id ) {
	if ( 'stjo_year' === $column ) {
		$year = absint( get_post_meta( $post_id, 'stjo_timeline_year', true ) );
		echo $year ? esc_html( $year ) : '<span aria-hidden="true">—</span><span class="screen-reader-text">' . esc_html__( 'No year set', 'stjo' ) . '</span>';
	}
}
add_action( 'manage_timeline-event_posts_custom_column', 'stjo_timeline_column_content', 10, 2 );

function stjo_timeline_sortable_columns( $columns ) {
	$columns['stjo_year'] = 'stjo_year';
	return $columns;
}
add_filter( 'manage_edit-timeline-event_sortable_columns', 'stjo_timeline_sortable_columns' );

function stjo_timeline_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || 'timeline-event' !== $query->get( 'post_type' ) ) {
		return;
	}
	$orderby = $query->get( 'orderby' );
	if ( 'stjo_year' === $orderby || '' === $orderby ) {
		$query->set( 'meta_key', 'stjo_timeline_year' );
		$query->set( 'orderby', array( 'meta_value_num' => $query->get( 'order' ) ? $query->get( 'order' ) : 'ASC', 'menu_order' => 'ASC' ) );
		if ( '' === $orderby ) {
			$query->set( 'order', 'ASC' );
		}
	}
}
add_action( 'pre_get_posts', 'stjo_timeline_admin_order' );

/**
 * Decade filter dropdown on the Timeline list table.
 */
function stjo_timeline_decade_filter() {
	global $typenow;
	if ( 'timeline-event' !== $typenow ) {
		return;
	}
	$selected = isset( $_GET['timeline-decade'] ) ? sanitize_key( $_GET['timeline-decade'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	wp_dropdown_categories( array(
		'show_option_all' => __( 'All decades', 'stjo' ),
		'taxonomy'        => 'timeline-decade',
		'name'            => 'timeline-decade',
		'value_field'     => 'slug',
		'selected'        => $selected,
		'hide_empty'      => true,
		'hide_if_empty'   => true,
	) );
}
add_action( 'restrict_manage_posts', 'stjo_timeline_decade_filter' );
