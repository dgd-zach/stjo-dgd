<?php
/**
 * Title: Page Hero
 * Categories: heroes
 * Description: Structure: page-hero. Interior page hero: image, eyebrow, page title, bottom-aligned. Source: section-landing-general.
 *
 * dimRatio is 0 on purpose — the cover applies no overlay at all. The scrim is
 * drawn on the .hero-content group instead (see sections.css), so it sizes
 * itself to the eyebrow + heading rather than to a percentage of the hero, and
 * stays the right height when the text wraps to another line on a phone.
 *
 * The image is resolved through stjo_asset() rather than hard-coded: the map
 * points hero.png at /wp-content/uploads/2026/07/hero.jpg, so the output URL is
 * identical while staying portable between environments.
 *
 * @package stjo
 */
?>
<!-- wp:cover {"url":"<?php echo esc_url( stjo_asset( 'hero.png' ) ); ?>","dimRatio":0,"isUserOverlayColor":true,"focalPoint":{"x":0.5,"y":0.29},"minHeight":450,"contentPosition":"bottom center","metadata":{"name":"Page Hero"},"align":"full","className":"stjo-page-hero"} -->
<div class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center stjo-page-hero" style="min-height:450px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( stjo_asset( 'hero.png' ) ); ?>" style="object-position:50% 29%" data-object-fit="cover" data-object-position="50% 29%"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"className":"hero-content","layout":{"type":"constrained"}} -->
<div class="wp-block-group hero-content"><!-- wp:paragraph {"className":"is-style-eyebrow has-white-color has-text-color","style":{"typography":{"textAlign":"center"}},"textColor":"white"} -->
<p class="has-text-align-center is-style-eyebrow has-white-color has-text-color">Optional Eyebrow</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"}},"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">Section Landing Page Heading</h1>
<!-- /wp:heading --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->
