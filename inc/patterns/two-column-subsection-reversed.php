<?php
/**
 * Title: Two-Column Subsection (Image Right)
 * Description: Structure: two-column-subsection reversed. Text left, image right. Replaces two-column-image-text. Source: both frames.
 *
 * @package stjo
 */
?>
<!-- wp:group {"metadata":{"name":"Two-Column Subsection (Image Right)"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:spacer {"height":"var:preset|spacing|medium"} -->
<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Subsection Title</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">Optional Subhead</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="#">Primary CTA</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#">Secondary CTA</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-arrow-link"} -->
<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link wp-element-button" href="#">Learn More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"sizeSlug":"large","className":"is-style-rounded"} -->
<figure class="wp-block-image size-large is-style-rounded"><img src="<?php echo esc_url( stjo_asset( 'card-2.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:spacer {"height":"var:preset|spacing|medium"} -->
<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->
