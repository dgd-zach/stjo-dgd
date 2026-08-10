<?php
/**
 * Default page template.
 *
 * @package stjo
 */

get_header();

while ( have_posts() ) :
	the_post();
	$stjo_is_stub = stjo_page_is_stub();
	?>
	<article <?php post_class( $stjo_is_stub ? 'stjo-stub' : '' ); ?>>
		<?php
		// Not built out yet → give it the default branded hero (built pages
		// carry their own hero in content, so they're left alone).
		if ( $stjo_is_stub ) {
			stjo_page_hero();
		}
		?>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
