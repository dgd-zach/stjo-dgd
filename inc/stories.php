<?php
/**
 * Student-story section rendering — shared by the stjo/stories-section block
 * (src/blocks/stories-section) so the Student Stories archive can live as an
 * editable Page instead of a hardcoded archive template.
 *
 * A section is one story-category rendered as a swipeable carousel of 6-card
 * pages (assets/js/stories-carousel.js; without JS the pages just stack), with
 * an optional Class Year filter (grad sections). Filtering is server-side:
 * pills link back to the same page with a ?y_<category-slug> query arg (other
 * sections' filters carry over) and a #stories-<category-slug> fragment; the
 * carousel JS upgrades that to an AJAX section swap.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Stories for one section. Newest first by default; 'menu_order' gives a
 * hand-curated sequence (Alumni mirrors the client's alumni page order).
 */
function stjo_stories_query( $cat, $year = '', $orderby = 'date' ) {
	$tax = array( array( 'taxonomy' => 'story-category', 'field' => 'slug', 'terms' => $cat ) );
	if ( $year ) {
		$tax[] = array( 'taxonomy' => 'story-year', 'field' => 'slug', 'terms' => $year );
	}
	return get_posts( array(
		'post_type'      => 'student-story',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'tax_query'      => $tax,
		'orderby'        => 'menu_order' === $orderby ? array( 'menu_order' => 'ASC', 'date' => 'DESC' ) : 'date',
		'order'          => 'DESC',
	) );
}

/**
 * One story card. Stories with an stjo_external_url meta (blog/alumni) link
 * out in a new tab; $lightbox cards (grads) open the story in a modal; the
 * rest link to the local single. Featured photos honor the per-post Image
 * Focus meta within the card's fixed-height crop.
 */
function stjo_story_card( $post, $lightbox = false ) {
	$external = get_post_meta( $post->ID, 'stjo_external_url', true );
	$focus    = get_post_meta( $post->ID, 'stjo_story_image_focus', true );
	$img_attr = $focus && 'center center' !== $focus ? array( 'style' => 'object-position:' . $focus ) : array();
	?>
	<article <?php post_class( 'stjo-story-card', $post ); ?>>
		<?php if ( has_post_thumbnail( $post ) ) : ?>
			<figure class="wp-block-image"><?php echo get_the_post_thumbnail( $post, 'medium_large', $img_attr ); ?></figure>
		<?php endif; ?>
		<div class="stjo-story-card__body">
			<h3><?php echo esc_html( get_the_title( $post ) ); ?></h3>
			<p><?php echo esc_html( wp_trim_words( get_the_excerpt( $post ), 36 ) ); // hard cap: the design's placeholder excerpt is 36 words ?></p>
			<?php if ( $external ) : ?>
				<a class="stjo-story-card__more" href="<?php echo esc_url( $external ); ?>" target="_blank" rel="noopener external">
					<?php esc_html_e( 'Read More', 'stjo' ); ?>
					<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: story title */ __( 'about %s (opens in a new tab)', 'stjo' ), get_the_title( $post ) ) ); ?></span>
				</a>
			<?php elseif ( $lightbox ) : ?>
				<button type="button" class="stjo-story-card__more" data-stjo-lightbox>
					<?php esc_html_e( 'Read More', 'stjo' ); ?>
					<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: story title */ __( 'about %s', 'stjo' ), get_the_title( $post ) ) ); ?></span>
				</button>
				<template data-stjo-lightbox-template>
					<?php if ( has_post_thumbnail( $post ) ) : ?>
						<figure class="stjo-lightbox__hero"><?php echo get_the_post_thumbnail( $post, 'large', $img_attr ); ?></figure>
					<?php endif; ?>
					<div class="stjo-lightbox__inner">
						<h2 class="stjo-lightbox__title"><?php echo esc_html( get_the_title( $post ) ); ?></h2>
						<div class="stjo-lightbox__body">
							<?php
							// Same chain as the lightbox-card block: not the_content,
							// which would re-enter the page's filter pass.
							$stjo_story_html = do_blocks( $post->post_content );
							$stjo_story_html = wptexturize( $stjo_story_html );
							$stjo_story_html = wpautop( $stjo_story_html );
							$stjo_story_html = shortcode_unautop( $stjo_story_html );
							echo do_shortcode( $stjo_story_html ); // phpcs:ignore WordPress.Security.EscapeOutput -- editor-authored post content.
							?>
						</div>
						<div class="stjo-lightbox__actions">
							<button type="button" class="stjo-lightbox__dismiss" data-stjo-lightbox-close><?php esc_html_e( 'Close', 'stjo' ); ?></button>
						</div>
					</div>
				</template>
			<?php else : ?>
				<a class="stjo-story-card__more" href="<?php echo esc_url( get_permalink( $post ) ); ?>">
					<?php esc_html_e( 'Read More', 'stjo' ); ?>
					<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: story title */ __( 'about %s', 'stjo' ), get_the_title( $post ) ) ); ?></span>
				</a>
			<?php endif; ?>
		</div>
	</article>
	<?php
}

/**
 * Render one story-category section band and return its HTML.
 *
 * @param array $attrs {
 *     @type string $category     story-category slug (required).
 *     @type string $title        heading (defaults to the term name).
 *     @type bool   $yearFilter   show Class Year pills (grad sections).
 *     @type bool   $tint         cream band background.
 *     @type bool   $curatedOrder order by the post Order attribute (menu_order).
 *     @type bool   $lightbox     cards open the story in a lightbox.
 * }
 * @return string
 */
function stjo_render_stories_section( $attrs ) {
	$cat = isset( $attrs['category'] ) ? sanitize_title( $attrs['category'] ) : '';
	if ( '' === $cat ) {
		return '';
	}

	$title = isset( $attrs['title'] ) ? trim( $attrs['title'] ) : '';
	if ( '' === $title ) {
		$term  = get_term_by( 'slug', $cat, 'story-category' );
		$title = $term ? $term->name : ucwords( str_replace( '-', ' ', $cat ) );
	}

	$year_filter = ! empty( $attrs['yearFilter'] );
	$tint        = ! empty( $attrs['tint'] );
	$lightbox    = ! empty( $attrs['lightbox'] );
	$orderby     = ! empty( $attrs['curatedOrder'] ) ? 'menu_order' : 'date';

	$section_id = 'stories-' . $cat;
	$param_key  = 'y_' . $cat;
	$h_id       = $section_id . '-heading';

	// Every y_* request param, sanitized to a 4-digit year (or ''). Used to
	// carry other sections' active filters through this section's pill links.
	$active = array();
	foreach ( $_GET as $k => $v ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( is_string( $k ) && 0 === strpos( $k, 'y_' ) ) {
			$val          = sanitize_text_field( wp_unslash( $v ) );
			$active[ $k ] = preg_match( '/^\d{4}$/', $val ) ? $val : '';
		}
	}
	$year = $year_filter && ! empty( $active[ $param_key ] ) ? $active[ $param_key ] : '';

	$all   = stjo_stories_query( $cat, '', $orderby );
	$posts = $year ? stjo_stories_query( $cat, $year, $orderby ) : $all;
	$pages = array_chunk( $posts, 6 );

	// Pills come from the years present in the section's unfiltered set.
	$years = array();
	if ( $year_filter && $all ) {
		$terms = wp_get_object_terms( wp_list_pluck( $all, 'ID' ), 'story-year' );
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $t ) {
				$years[ $t->slug ] = $t->name;
			}
			ksort( $years );
		}
	}

	// Pills link back to the page they're on; carry every OTHER section's filter.
	$base  = get_permalink();
	if ( ! $base ) {
		$base = home_url( '/' );
	}
	$carry = array();
	foreach ( $active as $k => $v ) {
		if ( $v && $k !== $param_key ) {
			$carry[ $k ] = $v;
		}
	}

	ob_start();
	?>
	<section id="<?php echo esc_attr( $section_id ); ?>" class="wp-block-group alignfull stjo-stories-band<?php echo $tint ? ' stjo-stories-band--tint' : ''; ?>" aria-labelledby="<?php echo esc_attr( $h_id ); ?>">
		<div class="stjo-filterbar stjo-stories-band__head">
			<h2 id="<?php echo esc_attr( $h_id ); ?>"><?php echo esc_html( $title ); ?></h2>
			<?php if ( $years ) : ?>
				<nav class="stjo-pills" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: section title */ __( 'Filter %s by year', 'stjo' ), $title ) ); ?>">
					<span class="stjo-filterbar__label"><?php esc_html_e( 'Filters:', 'stjo' ); ?></span>
					<?php foreach ( $years as $slug => $name ) : ?>
						<?php
						// (string): PHP int-ifies numeric array keys, so year slugs
						// come back from the $years map as integers.
						$is_active = (string) $slug === $year;
						$params    = $is_active ? $carry : array_merge( $carry, array( $param_key => (string) $slug ) );
						$url       = ( $params ? add_query_arg( $params, $base ) : $base ) . '#' . $section_id;
						?>
						<a href="<?php echo esc_url( $url ); ?>"<?php echo $is_active ? ' aria-current="true"' : ''; ?>><?php echo esc_html( $name ); ?></a>
					<?php endforeach; ?>
				</nav>
			<?php endif; ?>
		</div>

		<?php if ( ! $pages ) : ?>
			<p class="stjo-stories-band__empty">
				<?php echo $year ? esc_html( sprintf( /* translators: %s: year */ __( 'No stories from %s yet.', 'stjo' ), $year ) ) : esc_html__( 'No stories yet, check back soon.', 'stjo' ); ?>
			</p>
		<?php else : ?>
			<div class="stjo-stories-carousel" data-stories-carousel data-carousel-label="<?php echo esc_attr( $title ); ?>">
				<div class="stjo-stories-carousel__viewport">
					<div class="stjo-stories-carousel__track">
						<?php foreach ( $pages as $page ) : ?>
							<div class="stjo-stories-carousel__page">
								<div class="stjo-stories-grid">
									<?php foreach ( $page as $p ) { stjo_story_card( $p, $lightbox ); } ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</section>
	<?php
	return ob_get_clean();
}
