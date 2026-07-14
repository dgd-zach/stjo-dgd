<?php
/**
 * Search results template.
 *
 * @package stjo
 */

get_header();
?>
<div class="wp-block-group alignfull stjo-page-hero">
	<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
	<h1 class="wp-block-heading has-text-align-center">
		<?php printf( esc_html__( 'Search results for “%s”', 'stjo' ), esc_html( get_search_query() ) ); ?>
	</h1>
	<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
</div>
<hr class="wp-block-separator has-alpha-channel-opacity stjo-zigzag alignfull"/>
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
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Nothing found. Try another search.', 'stjo' ); ?></p>
	<?php endif; ?>
	<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
</div>
<?php
get_footer();
