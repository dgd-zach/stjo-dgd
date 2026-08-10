<?php
/**
 * Single blog post.
 *
 * Uses the page-hero pattern (stjo_page_hero): the featured image as a
 * full-bleed cover (blue band when there's no image), the post's category as
 * the eyebrow, and the title as the H1. (The student-story CPT has its own
 * single-student-story.php, so this only styles regular blog posts.)
 *
 * @package stjo
 */

get_header();

while ( have_posts() ) :
	the_post();
	stjo_page_hero(
		get_post(),
		array(
			'eyebrow' => stjo_post_primary_category( get_post() ),
			'cover'   => get_post_thumbnail_id(),
		)
	);
	?>
	<article <?php post_class(); ?>>
		<div class="entry-content">
			<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
			<?php the_content(); ?>
			<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
