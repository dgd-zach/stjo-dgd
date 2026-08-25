<?php
/**
 * Single: Student Story.
 *
 * Uses the page-hero pattern (stjo_page_hero) with the story's featured image
 * as a full-bleed cover behind a scrim, matching single.php for blog posts. A
 * story with no featured image falls back to the flat blue band, which the
 * helper handles.
 *
 * Not currently reachable from the site: student stories link out to the
 * existing external blog. Kept in sync with single.php so it is correct if that
 * changes.
 *
 * header.php already opens <main id="main">, so this template must not open its
 * own or the page carries two main landmarks.
 *
 * @package stjo
 */

get_header();

while ( have_posts() ) :
	the_post();
	stjo_page_hero(
		get_post(),
		array(
			'eyebrow' => get_post_type_object( get_post_type() )->labels->singular_name,
			'cover'   => get_post_thumbnail_id(),
		)
	);
	?>
	<article <?php post_class(); ?>>
		<div class="entry-content">
			<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
			<?php // The featured image is the hero cover now, so it is not repeated here. ?>
			<?php the_content(); ?>
			<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
