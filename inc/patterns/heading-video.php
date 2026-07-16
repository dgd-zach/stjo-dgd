<?php
/**
 * Title: Heading + Video
 * Categories: media
 * Description: Structure: heading-video. Centered title over a rounded video placeholder. Source: section-landing-general + general-content.
 *
 * @package stjo
 */
?>
<!-- wp:group {"metadata":{"name":"Heading + Video"},"align":"full","backgroundColor":"light","className":"stjo-heading-video","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-heading-video has-light-background-color has-background"><!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Option Video Title</h2>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"var:preset|spacing|medium"} -->
<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:image {"sizeSlug":"large","align":"center","className":"is-style-rounded stjo-video"} -->
<figure class="wp-block-image aligncenter size-large is-style-rounded stjo-video"><img src="<?php echo esc_url( stjo_asset( 'video-placer.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->
