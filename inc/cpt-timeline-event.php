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
		// No admin column: it's synced from the Year field, and the sortable
		// "Year" meta column already shows the value (a duplicate column read
		// as confusing).
		'show_admin_column'  => false,
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

	// End of a span, e.g. a building project running 1927-1930. Absent or 0
	// means a single year. There is no separate "is a range" flag: a valid end
	// year IS the range, so the two cannot contradict each other.
	register_post_meta( 'timeline-event', 'stjo_timeline_end_year', array(
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

/**
 * How a year or year span should read.
 *
 * Returns both forms because they differ: sighted readers want the en dash
 * ("1927-1930" with a proper en dash), screen readers want a word, since a
 * bare dash is either announced as punctuation or swallowed entirely.
 *
 * @param int $start Start year.
 * @param int $end   End year, 0 for a single year.
 * @return array{display: string, spoken: string, is_range: bool}
 */
function stjo_timeline_year_label( $start, $end = 0 ) {
	$start = absint( $start );
	$end   = absint( $end );
	if ( ! $start ) {
		return array( 'display' => '', 'spoken' => '', 'is_range' => false );
	}
	// An end year at or before the start is not a span; the save handler drops
	// those, but seeds and REST writes can still get here.
	if ( $end > $start ) {
		return array(
			'display'  => $start . "\u{2013}" . $end,
			/* translators: 1: start year, 2: end year */
			'spoken'   => sprintf( __( '%1$s to %2$s', 'stjo' ), $start, $end ),
			'is_range' => true,
		);
	}
	return array( 'display' => (string) $start, 'spoken' => (string) $start, 'is_range' => false );
}

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
		(string) filemtime( get_template_directory() . '/src/blocks/timeline/focus-panel.js' ), // mtime version: STJO_VERSION let stale copies stick in the editor
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
	$year     = absint( get_post_meta( $post->ID, 'stjo_timeline_year', true ) );
	$end_year = absint( get_post_meta( $post->ID, 'stjo_timeline_end_year', true ) );
	$is_range = $end_year > $year;
	$layout   = get_post_meta( $post->ID, 'stjo_timeline_image_layout', true );
	$layout   = stjo_timeline_sanitize_layout( $layout ? $layout : 'horizontal' );
	wp_nonce_field( 'stjo_timeline_details', 'stjo_timeline_details_nonce' );
	?>
	<p>
		<label for="stjo-timeline-year">
			<strong id="stjo-timeline-year-label"><?php
				echo esc_html( $is_range ? __( 'Start Year', 'stjo' ) : __( 'Year', 'stjo' ) );
			?></strong>
		</label><br>
		<input type="number" id="stjo-timeline-year" name="stjo_timeline_year"
			value="<?php echo esc_attr( $year ? $year : '' ); ?>"
			min="1900" max="2100" step="1" style="width:100%"
			placeholder="<?php esc_attr_e( 'e.g. 1927', 'stjo' ); ?>">
	</p>
	<p>
		<label>
			<input type="checkbox" id="stjo-timeline-has-range" name="stjo_timeline_has_range"
				value="1" <?php checked( $is_range ); ?>>
			<?php esc_html_e( 'Add a range', 'stjo' ); ?>
		</label>
	</p>
	<p id="stjo-timeline-end-year-row"<?php echo $is_range ? '' : ' hidden'; ?>>
		<label for="stjo-timeline-end-year"><strong><?php esc_html_e( 'End Year', 'stjo' ); ?></strong></label><br>
		<input type="number" id="stjo-timeline-end-year" name="stjo_timeline_end_year"
			value="<?php echo esc_attr( $end_year ? $end_year : '' ); ?>"
			min="1900" max="2100" step="1" style="width:100%"
			placeholder="<?php esc_attr_e( 'e.g. 1930', 'stjo' ); ?>">
	</p>
	<p class="description"><?php esc_html_e( 'A range shows as "1927-1930" on the card and its chip. The decade group and sort order always come from the start year, so a span crossing decades still files under the one it began in.', 'stjo' ); ?></p>
	<p class="description"><?php esc_html_e( 'Events sort oldest first; use Order (Attributes) to break ties within the same year.', 'stjo' ); ?></p>
	<script>
	/* Progressive: with JS off both fields render and both still save, so the
	   feature degrades to "fill in End Year to make it a range". */
	( function () {
		var box   = document.getElementById( 'stjo-timeline-has-range' );
		var row   = document.getElementById( 'stjo-timeline-end-year-row' );
		var label = document.getElementById( 'stjo-timeline-year-label' );
		var start = document.getElementById( 'stjo-timeline-year' );
		var end   = document.getElementById( 'stjo-timeline-end-year' );
		if ( ! box || ! row || ! label || ! start || ! end ) { return; }

		var LABEL_SINGLE = <?php echo wp_json_encode( __( 'Year', 'stjo' ) ); ?>;
		var LABEL_RANGE  = <?php echo wp_json_encode( __( 'Start Year', 'stjo' ) ); ?>;

		function sync() {
			var on = box.checked;
			row.hidden = ! on;
			label.textContent = on ? LABEL_RANGE : LABEL_SINGLE;
			// Let the browser reject an end year that is not after the start,
			// so the mistake is caught in the form instead of silently dropped
			// by the save handler.
			end.min = start.value ? ( parseInt( start.value, 10 ) + 1 ) : 1900;
			if ( on ) { end.setAttribute( 'required', 'required' ); }
			else { end.removeAttribute( 'required' ); }
		}
		box.addEventListener( 'change', function () {
			sync();
			if ( box.checked ) { end.focus(); }
		} );
		start.addEventListener( 'input', sync );
		sync();
	}() );
	</script>
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

	// The checkbox is UI only — nothing stores it. A range exists when there is
	// an end year later than the start, which means the two can never disagree.
	// Unticking therefore has to clear the value, or a hidden end year would
	// keep rendering a span.
	$wants_range = ! empty( $_POST['stjo_timeline_has_range'] );
	$end_year    = isset( $_POST['stjo_timeline_end_year'] ) ? absint( $_POST['stjo_timeline_end_year'] ) : 0;
	if ( $wants_range && $year && $end_year > $year ) {
		update_post_meta( $post_id, 'stjo_timeline_end_year', $end_year );
	} else {
		delete_post_meta( $post_id, 'stjo_timeline_end_year' );
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
	// Start year only, deliberately. A span that crosses a decade boundary
	// (1928-1935) files under the decade it began in and carries its start year
	// as the year term, so an event never appears in two decade groups.
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
			$out['stjo_year'] = __( 'Year', 'stjo' ); // shows a span as "1927-1930"; sorts on the start year
		}
	}
	return $out;
}
add_filter( 'manage_timeline-event_posts_columns', 'stjo_timeline_columns' );

function stjo_timeline_column_content( $column, $post_id ) {
	if ( 'stjo_year' === $column ) {
		$label = stjo_timeline_year_label(
			get_post_meta( $post_id, 'stjo_timeline_year', true ),
			get_post_meta( $post_id, 'stjo_timeline_end_year', true )
		);
		if ( '' === $label['display'] ) {
			echo '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'No year set', 'stjo' ) . '</span>';
			return;
		}
		echo '<span aria-hidden="true">' . esc_html( $label['display'] ) . '</span>';
		echo '<span class="screen-reader-text">' . esc_html( $label['spoken'] ) . '</span>';
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
