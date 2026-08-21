<?php
/**
 * Title: Quote Band
 * Categories: quotes
 * Description: Structure: quote-band. A standalone centered quote on the cream band — nothing but the quote (attribution optional). Requested in internal QA (Blocks: "a block with only the quote on it").
 *
 * @package stjo
 */
?>
<!-- wp:group {"metadata":{"name":"Quote Band"},"align":"full","backgroundColor":"light","className":"stjo-quote-band","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-quote-band has-light-background-color has-background"><!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><!-- wp:paragraph -->
<p>&#8220;Because of you, a child woke up today with somewhere safe to call home.&#8221;</p>
<!-- /wp:paragraph --></blockquote>
<!-- /wp:quote -->

<!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->
