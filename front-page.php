<?php
/**
 * Front page template.
 *
 * The homepage content lives in a Page (block patterns assembled in the editor),
 * so this template simply renders that block content. Full-bleed section bands
 * break out via .alignfull; the layout cascade in main.css constrains their
 * inner content back to wrapper width.
 *
 * @package stjo
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<div class="entry-content entry-content--front">
		<?php the_content(); ?>
	</div>
	<?php
endwhile;

get_footer();
