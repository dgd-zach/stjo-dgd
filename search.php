<?php
/**
 * Search results template.
 *
 * Uses the shared page hero (stjo_page_hero) so results are framed like every
 * other interior page: blue title band, eyebrow, H1, zigzag ribbon. The hand
 * built band this replaced carried .stjo-page-hero, which has no background of
 * its own, so the heading rendered on bare white.
 *
 * @package stjo
 */

get_header();

stjo_page_hero(
	null,
	array(
		'eyebrow' => __( 'Search', 'stjo' ),
		/* translators: %s: the search term. Raw query: stjo_page_hero() escapes the title. */
		'title'   => sprintf( __( 'Search results for “%s”', 'stjo' ), get_search_query( false ) ),
	)
);
?>
<div class="entry-content">
	<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
	<?php get_search_form(); ?>
	<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
	<?php if ( have_posts() ) : ?>
		<div class="wp-block-columns stjo-story-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<div class="wp-block-column">
					<article <?php post_class( 'stjo-story-card' ); ?>>
						<div class="stjo-story-card__body">
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
							<a class="stjo-story-card__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'stjo' ); ?></a>
						</div>
					</article>
				</div>
			<?php endwhile; ?>
		</div>
		<?php stjo_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Nothing found. Try another search.', 'stjo' ); ?></p>
	<?php endif; ?>
	<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
</div>
<?php
get_footer();
