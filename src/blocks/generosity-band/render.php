<?php
/**
 * stjo/generosity-band — renders the shared "Your Generosity" band template
 * part inside content. Same markup the pre-footer prints on inner pages, so
 * a change to the band (a tile, a URL in theme-config) lands everywhere at
 * once. The block wrapper carries alignfull so the editor canvas breaks it
 * out like the front end does.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php get_template_part( 'template-parts/global/generosity-band' ); ?>
</div>
