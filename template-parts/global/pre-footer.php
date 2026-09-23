<?php
/**
 * Template part: pre-footer — the site-wide "Your Generosity" band on every
 * inner page. The band itself lives in generosity-band.php (which renders the
 * synced Your Generosity Band pattern), so the stjo/generosity-band block can
 * render the same thing inside content.
 *
 * The zigzag transition into the band is part of the pattern now (a Separator
 * block editors control there), so nothing is added here; a separator in this
 * file would draw the ribbon twice.
 *
 * @package stjo
 */
?>
<?php get_template_part( 'template-parts/global/generosity-band' ); ?>
