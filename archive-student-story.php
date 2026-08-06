<?php
/**
 * Archive: Student Stories — built to the SJIS-Homepage Figma (5531:4852).
 *
 * Four story sections, one story-category each, each a swipeable carousel
 * of 6-card pages (assets/js/stories-carousel.js; without JS the pages
 * simply stack):
 *   Student Stories          general blog features (external-link cards)   year filter
 *   Eighth Grade Graduates   story-category: eighth-grade-graduates        year filter
 *   High School Graduates    story-category: high-school-graduates         year filter
 *   Alumni Stories           story-category: alumni (external-link cards)  no filter
 *
 * Year filters are server-side: pills link back to this archive with a
 * per-section ?y_<key> query arg (other sections' filters carry over) and a
 * #fragment so the reload lands back on the section. Clicking the active
 * pill clears it.
 *
 * @package stjo
 */

get_header();

/**
 * Stories for one section (all filters applied). Newest first by default;
 * pass 'menu_order' for hand-curated sequences (Alumni mirrors the order of
 * the client's alumni page via each post's Order attribute).
 */
function stjo_stories_query( $cat, $tag = '', $year = '', $orderby = 'date' ) {
	$tax = array( array( 'taxonomy' => 'story-category', 'field' => 'slug', 'terms' => $cat ) );
	if ( $tag ) {
		$tax[] = array( 'taxonomy' => 'story-tag', 'field' => 'slug', 'terms' => $tag );
	}
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
 * One story card (design-approved card markup). Stories carrying an
 * stjo_external_url meta (alumni imported from the blog) link out to it in
 * a new tab; $lightbox cards (Eighth Grade / High School) open the story in
 * a modal instead of navigating to the single view. Featured photos honor
 * the per-post Image Focus meta within the card's fixed-height crop.
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
 * One section band: heading, optional year pills, carousel of 6-card pages.
 *
 * @param array $args id, title, cat, tag, filter_key (empty = no filter),
 *                    tint (cream band), active (all active filters by key),
 *                    orderby ('date' default, or 'menu_order' for curated).
 */
function stjo_stories_section( $args ) {
	$key    = $args['filter_key'];
	$sort   = ! empty( $args['orderby'] ) ? $args['orderby'] : 'date';
	$year   = $key && ! empty( $args['active'][ $key ] ) ? $args['active'][ $key ] : '';
	$all    = stjo_stories_query( $args['cat'], $args['tag'], '', $sort );
	$posts  = $year ? stjo_stories_query( $args['cat'], $args['tag'], $year, $sort ) : $all;
	$pages  = array_chunk( $posts, 6 );
	$h_id   = $args['id'] . '-heading';

	// Pills come from the years present in the section's unfiltered set.
	$years = array();
	if ( $key && $all ) {
		$terms = wp_get_object_terms( wp_list_pluck( $all, 'ID' ), 'story-year' );
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $t ) {
				$years[ $t->slug ] = $t->name;
			}
			ksort( $years );
		}
	}

	// Base URL keeps every OTHER section's active filter.
	$base = get_post_type_archive_link( 'student-story' );
	$carry = array();
	foreach ( $args['active'] as $k => $v ) {
		if ( $v && $k !== $key ) {
			$carry[ 'y_' . $k ] = $v;
		}
	}
	?>
	<section id="<?php echo esc_attr( $args['id'] ); ?>" class="wp-block-group alignfull stjo-stories-band<?php echo $args['tint'] ? ' stjo-stories-band--tint' : ''; ?>" aria-labelledby="<?php echo esc_attr( $h_id ); ?>">
		<div class="stjo-filterbar stjo-stories-band__head">
			<h3 id="<?php echo esc_attr( $h_id ); ?>"><?php echo esc_html( $args['title'] ); ?></h3>
			<?php if ( $years ) : ?>
				<nav class="stjo-pills" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: section title */ __( 'Filter %s by year', 'stjo' ), $args['title'] ) ); ?>">
					<span class="stjo-filterbar__label"><?php esc_html_e( 'Filters:', 'stjo' ); ?></span>
					<?php foreach ( $years as $slug => $name ) : ?>
						<?php
						// (string): PHP int-ifies numeric array keys, so year
						// slugs come back from the $years map as integers.
						$is_active = (string) $slug === $year;
						// Active pill links back to the unfiltered section.
						$params = $is_active ? $carry : array_merge( $carry, array( 'y_' . $key => $slug ) );
						$url    = ( $params ? add_query_arg( $params, $base ) : $base ) . '#' . $args['id'];
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
			<div class="stjo-stories-carousel" data-stories-carousel data-carousel-label="<?php echo esc_attr( $args['title'] ); ?>">
				<div class="stjo-stories-carousel__viewport">
					<div class="stjo-stories-carousel__track">
						<?php foreach ( $pages as $page ) : ?>
							<div class="stjo-stories-carousel__page">
								<div class="stjo-stories-grid">
									<?php foreach ( $page as $p ) { stjo_story_card( $p, ! empty( $args['lightbox'] ) ); } ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</section>
	<?php
}

$stjo_active = array();
foreach ( array( 'students', 'eighth', 'high' ) as $stjo_k ) {
	$stjo_v = isset( $_GET[ 'y_' . $stjo_k ] ) ? sanitize_text_field( wp_unslash( $_GET[ 'y_' . $stjo_k ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$stjo_active[ $stjo_k ] = preg_match( '/^\d{4}$/', $stjo_v ) ? $stjo_v : '';
}

$stjo_reg = WP_Block_Patterns_Registry::get_instance();
?>

<div class="wp-block-group alignfull stjo-page-title-band has-white-color has-text-color">
	<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
	<p class="has-text-align-center is-style-eyebrow has-white-color has-text-color"><?php esc_html_e( 'About', 'stjo' ); ?></p>
	<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color"><?php post_type_archive_title(); ?></h1>
	<p class="has-text-align-center stjo-stories-hero__intro">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
	<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
</div>
<hr class="wp-block-separator has-alpha-channel-opacity alignfull"/>

<?php
// Intro band: the two-column intro pattern with this page's copy. The
// entry-content wrapper supplies the wrapper width + mobile gutters that
// in-flow content gets on regular pages.
$stjo_intro = $stjo_reg->get_registered( 'stjo/two-column-intro' );
if ( $stjo_intro ) {
	echo '<div class="entry-content">';
	echo do_blocks( str_replace( // phpcs:ignore WordPress.Security.EscapeOutput
		array(
			'Section Intro Title',
			'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
		),
		array(
			"Every gift you make writes a new chapter in a child's life.",
			'The children of St. Joseph&#8217;s Indian School come from communities that have faced generations of hardship, yet every day on our campus, we witness resilience, joy and an unquenchable spirit. Our student stories capture real moments: a child conquering fear in the classroom, a young dancer finding pride in their heritage, an alumnus returning to give back. These are the stories your support makes possible.',
		),
		$stjo_intro['content']
	) );
	echo '</div>';
}

// Sections are one story-category each (per PM: Student Stories = general
// blog features about students, distinct from the two graduates sections).
stjo_stories_section( array(
	'id'         => 'student-stories',
	// "(placeholders)": current cards are blog pulls pending the client's real picks.
	'title'      => __( 'Student Stories (placeholders)', 'stjo' ),
	'cat'        => 'student-stories',
	'tag'        => '',
	'filter_key' => 'students',
	'tint'       => false,
	'active'     => $stjo_active,
) );

stjo_stories_section( array(
	'id'         => 'eighth-grade-graduates',
	'title'      => __( 'Eighth Grade Graduates', 'stjo' ),
	'cat'        => 'eighth-grade-graduates',
	'tag'        => '',
	'filter_key' => 'eighth',
	'tint'       => true,
	'active'     => $stjo_active,
	// Graduate stories open in a lightbox instead of the single view.
	'lightbox'   => true,
) );

stjo_stories_section( array(
	'id'         => 'high-school-graduates',
	'title'      => __( 'High School Graduates', 'stjo' ),
	'cat'        => 'high-school-graduates',
	'tag'        => '',
	'filter_key' => 'high',
	'tint'       => false,
	'active'     => $stjo_active,
	'lightbox'   => true,
) );

stjo_stories_section( array(
	'id'         => 'alumni-stories',
	'title'      => __( 'Alumni Stories', 'stjo' ),
	'cat'        => 'alumni',
	'tag'        => '',
	'filter_key' => '',
	'tint'       => true,
	'active'     => $stjo_active,
	// Curated: mirrors the client's alumni page via each post's Order field.
	'orderby'    => 'menu_order',
) );

// DreamMaker CTA band, straight from the registered pattern.
$stjo_dm = $stjo_reg->get_registered( 'stjo/band-cover-cta' );
if ( $stjo_dm ) {
	echo do_blocks( $stjo_dm['content'] ); // phpcs:ignore WordPress.Security.EscapeOutput
}

// Continue Exploring: the cover-cards pattern re-labelled for this page.
$stjo_cards = $stjo_reg->get_registered( 'stjo/3-column-cover-cards' );
if ( $stjo_cards ) {
	echo do_blocks( str_replace( // phpcs:ignore WordPress.Security.EscapeOutput
		array(
			'>The Culture That <strong>Shapes Every Child</strong><',
			'>About Our Children<',
			'>Our Programs<',
			'>Meet Our Children<',
			'>Explore Our Programs<',
			'<!-- wp:heading {"textAlign":"center","level":2} -->',
		),
		array(
			'>Continue Exploring Our Story<',
			'>Lakota Culture<',
			'>Your Impact<',
			'>Explore the Lakota Culture<',
			'>See Your Impact<',
			'<!-- wp:paragraph {"align":"center","className":"is-style-eyebrow"} --><p class="has-text-align-center is-style-eyebrow">Learn More</p><!-- /wp:paragraph --><!-- wp:heading {"textAlign":"center","level":2} -->',
		),
		$stjo_cards['content']
	) );
}

get_footer();
