<?php
/**
 * Template part: pre-footer — the zigzag divider into the site-wide "Your
 * Generosity" band, which lives in generosity-band.php so the
 * stjo/generosity-band block can render the same markup inside content.
 *
 * @package stjo
 */
?>
<!-- Zigzag transition into the band; every non-front page gets it for free. -->
<hr class="wp-block-separator has-alpha-channel-opacity alignfull" />

<?php get_template_part( 'template-parts/global/generosity-band' ); ?>
