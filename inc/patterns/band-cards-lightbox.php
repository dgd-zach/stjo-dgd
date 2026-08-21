<?php
/**
 * Title: Cards That Open Lightboxes
 * Categories: cards
 * Description: Centered heading + subhead over lightbox cards (stjo/lightbox-card): a pair of text-only Sublink tiles and a trio of image cards. Each card opens a prescribed lightbox (hero, heading, content, optional link) built from its own fields; the card excerpt is auto-abbreviated from the lightbox content.
 *
 * @package stjo
 */

$stjo_lb_demo_sublink = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\nSed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.';
$stjo_lb_demo_card    = 'Daily language classes ensure the sacred words of their ancestors live on in the next generation. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\n\nUt enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium.';
?>
<!-- wp:group {"metadata":{"name":"Cards That Open Lightboxes"},"align":"full","className":"stjo-lightbox-band","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-lightbox-band"><!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Cards that open lightboxes</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Subhead</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"className":"stjo-lightbox-band__sublinks"} -->
<div class="wp-block-columns stjo-lightbox-band__sublinks"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:stjo/lightbox-card {"className":"is-style-text","title":"Sublink","content":"<?php echo $stjo_lb_demo_sublink; ?>"} /--></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:stjo/lightbox-card {"className":"is-style-text","title":"Sublink","content":"<?php echo $stjo_lb_demo_sublink; ?>","backgroundColor":"blue-900","textColor":"white"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:spacer {"height":"var:preset|spacing|medium"} -->
<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:stjo/lightbox-card {"title":"Lightbox Link","content":"<?php echo $stjo_lb_demo_card; ?>","mediaUrl":"<?php echo esc_url( stjo_asset( 'card.png' ) ); ?>"} /--></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:stjo/lightbox-card {"title":"Lightbox Link","content":"<?php echo $stjo_lb_demo_card; ?>","mediaUrl":"<?php echo esc_url( stjo_asset( 'card-2.png' ) ); ?>"} /--></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:stjo/lightbox-card {"title":"Lightbox Link","content":"<?php echo $stjo_lb_demo_card; ?>","mediaUrl":"<?php echo esc_url( stjo_asset( 'card-3.png' ) ); ?>"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->
