<?php
/**
 * Title: Related Content Cards (Auto)
 * Categories: cards
 * Description: Structure: related-content-cards auto. Eyebrow, heading, latest posts via Query Loop styled as info cards. Source: general-content.
 *
 * @package stjo
 */
?>
<!-- wp:group {"metadata":{"name":"Related Content Cards (Auto)"},"align":"full","backgroundColor":"light","className":"stjo-related-content-cards-auto","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-related-content-cards-auto has-light-background-color has-background"><!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow">Keep Reading</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Related Content Section</h2>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"var:preset|spacing|medium"} -->
<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:query {"queryId":2,"query":{"perPage":4,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false},"className":"stjo-related-query"} -->
<div class="wp-block-query stjo-related-query"><!-- wp:post-template {"layout":{"type":"grid","columnCount":4}} -->
<!-- wp:post-featured-image {"isLink":true,"height":"200px"} /-->

<!-- wp:group {"className":"stjo-info-card__body"} -->
<div class="wp-block-group stjo-info-card__body"><!-- wp:post-title {"isLink":true,"level":3} /-->

<!-- wp:post-excerpt {"excerptLength":18} /-->

<!-- wp:read-more {"content":"Learn More"} /--></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query -->

<!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->
