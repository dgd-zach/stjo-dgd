<?php
/**
 * Blog archive: category pill filter + card grid on the posts page (/blog/).
 *
 * Server-side is the source of truth: the pills and pagination are real links
 * (/blog/?category=slug, /blog/page/N/?category=slug), so the archive works
 * with no JS. assets/js/blog-filter.js then upgrades those links to an in-page
 * fetch + swap (the same DOMParser pattern the stories carousel uses), so there
 * is no separate AJAX endpoint to keep in sync.
 *
 * Cards reuse .stjo-story-card and the two-up .stjo-story-grid; pagination
 * reuses the carousel-style .pagination (stjo_posts_pagination). See home.php.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter the main blog query by the ?category slug (so filtered URLs and their
 * pagination resolve server-side).
 */
function stjo_blog_pre_get_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_home() ) {
		return;
	}
	$cat = isset( $_GET['category'] ) ? sanitize_title( wp_unslash( $_GET['category'] ) ) : '';
	if ( $cat && 'all' !== $cat ) {
		$query->set( 'category_name', $cat );
	}
}
add_action( 'pre_get_posts', 'stjo_blog_pre_get_posts' );

/**
 * The active category slug for the current request ('' = All).
 */
function stjo_blog_active_category() {
	$cat = isset( $_GET['category'] ) ? sanitize_title( wp_unslash( $_GET['category'] ) ) : '';
	return ( 'all' === $cat ) ? '' : $cat;
}

/**
 * Categories to show as filter pills: non-empty, minus Uncategorized.
 */
function stjo_blog_categories() {
	return get_categories(
		array(
			'hide_empty' => true,
			'exclude'    => array( (int) get_option( 'default_category' ) ),
			'orderby'    => 'name',
		)
	);
}

/**
 * Display category for a post: the Yoast primary category if set, else the
 * first non-default category. Used as the single-post hero eyebrow.
 */
function stjo_post_primary_category( $post ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	$primary = (int) get_post_meta( $post->ID, '_yoast_wpseo_primary_category', true );
	if ( $primary ) {
		$term = get_term( $primary, 'category' );
		if ( $term && ! is_wp_error( $term ) ) {
			return $term->name;
		}
	}
	$cats = get_the_category( $post->ID );
	if ( ! $cats ) {
		return '';
	}
	$default = (int) get_option( 'default_category' );
	foreach ( $cats as $cat ) {
		if ( (int) $cat->term_id !== $default ) {
			return $cat->name;
		}
	}
	return $cats[0]->name;
}

/**
 * One blog post card — same markup/behaviour as the story/search cards
 * (whole card clickable via the stretched Read More link).
 */
function stjo_post_card( $post ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}
	$permalink = get_permalink( $post );
	?>
	<div class="wp-block-column">
		<article <?php post_class( 'stjo-story-card', $post ); ?>>
			<?php if ( has_post_thumbnail( $post ) ) : ?>
				<figure class="wp-block-image"><?php echo get_the_post_thumbnail( $post, 'medium_large' ); ?></figure>
			<?php endif; ?>
			<div class="stjo-story-card__body">
				<h3><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( get_the_title( $post ) ); ?></a></h3>
				<p><?php echo esc_html( wp_trim_words( get_the_excerpt( $post ), 24 ) ); ?></p>
				<a class="stjo-story-card__more" href="<?php echo esc_url( $permalink ); ?>"><?php esc_html_e( 'Read More', 'stjo' ); ?></a>
			</div>
		</article>
	</div>
	<?php
}
