<?php
/**
 * Title: Page Hero
 * Description: Structure: page-hero. Interior page hero: image, eyebrow, page title, bottom-aligned. Source: section-landing-general.
 *
 * @package stjo
 */
?>
<!-- wp:cover {"metadata":{"name":"Page Hero"},"url":"<?php echo esc_url( stjo_asset( 'image-2.png' ) ); ?>","dimRatio":50,"overlayColor":"blue-900","isUserOverlayColor":true,"minHeight":400,"contentPosition":"bottom center","align":"full","className":"stjo-page-hero"} -->
<div class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center stjo-page-hero" style="min-height:400px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( stjo_asset( 'image-2.png' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-blue-900-background-color has-background-dim-50 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"align":"center","textColor":"white","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-white-color has-text-color">Optional Eyebrow</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":1,"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">Page Heading</h1>
<!-- /wp:heading --></div></div>
<!-- /wp:cover -->
