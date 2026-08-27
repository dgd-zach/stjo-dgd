<?php
/**
 * Title: Hero Carousel
 * Categories: heroes
 * Description: Structure: carousel of full-width cover slides (Campaign, Event, DreamMaker). Apply per-slide imagery and copy in the editor; carousel.js adds controls.
 *
 * Any group carrying the is-style-carousel class behaves this way: each
 * direct-child Cover block is one slide. The class rides this pattern's
 * markup — it is no longer a pickable block style (removed 2026-08-27), so
 * new carousels start from this pattern. Campaign and Event photos are placeholder crops
 * pending clean asset exports from the design.
 *
 * Readability + mobile framing (automatic, no editor setup needed):
 * - carousel.js adds a guaranteed-contrast scrim to every slide (light scrim
 *   under dark text, dark under light), anchored to the text's column side,
 *   flat on phones. Editor-set overlays stack on top as art direction.
 * - Set each slide's FOCAL POINT on the subject. On desktop that governs the
 *   vertical crop; object-position keeps the point in frame as the band
 *   reflows. Slides without one default to 50% 30% on phones. The
 *   focus-sm-{top,bottom,left,...} and focus-sm-{X}-{Y} classes still work for
 *   anything already set that way (carousel.js parses the digits).
 * - The two "on phones" sliders are the discoverable route, and work like the
 *   desktop one: 0 is centred, the number is how far the photo moves. Note the
 *   axes swap on a phone — a tall narrow band crops these landscape photos by
 *   height, so sideways is free and up/down is the axis that leaves a strip
 *   bare.
 * - HORIZONTALLY the focal point does nothing on desktop, and no setting will
 *   change that: every hero photo is narrower than the band (2.37:1 to 2.78:1
 *   against 2.9:1 at 1440px and wider above), so object-fit: cover fits them
 *   by width, leaving no horizontal overflow for object-position to pan into.
 *   Use "Move image sideways" in the Slide framing panel instead. The number
 *   is literally how far the photo moves, as a percent of the band's width;
 *   negative is left. Slide 1 sits right at -11%, which centres the boy in the
 *   empty column.
 * - Moving the photo leaves that much of the band bare on the far side, which
 *   is the side the text and scrim sit on, so the scrim hides it (the cover is
 *   painted the scrim's own colour so the two agree). Add Image zoom only if a
 *   strip still shows. See inc/hero-slide-controls.php.
 *
 * @package stjo
 */
?>
<!-- wp:group {"metadata":{"name":"Home Hero Carousel"},"align":"full","className":"is-style-carousel stjo-hero-carousel","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull is-style-carousel stjo-hero-carousel"><!-- wp:cover {"url":"/wp-content/uploads/2026/07/2026-06-Foodfund_adjusted.jpg","id":423,"alt":"A boy smiling at lunchtime","dimRatio":60,"isUserOverlayColor":true,"focalPoint":{"x":1,"y":0.1},"minHeight":492,"minHeightUnit":"px","customGradient":"linear-gradient(90deg,rgba(255,255,255,0) 38%,rgba(255,255,255,0.95) 60%)","sizeSlug":"full","align":"full","className":"stjo-hero-slide","layout":{"type":"default"}} -->
<div class="wp-block-cover alignfull stjo-hero-slide" style="min-height:492px"><img class="wp-block-cover__image-background wp-image-423 size-full" alt="A boy smiling at lunchtime" src="/wp-content/uploads/2026/07/2026-06-Foodfund_adjusted.jpg" style="object-position:100% 10%" data-object-fit="cover" data-object-position="100% 10%"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-60 has-background-dim wp-block-cover__gradient-background has-background-gradient" style="background:linear-gradient(90deg,rgba(255,255,255,0) 38%,rgba(255,255,255,0.95) 60%)"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"align":"full"} -->
<div class="wp-block-group alignfull"><!-- wp:columns {"verticalAlignment":null} -->
<div class="wp-block-columns"><!-- wp:column {"width":"%","style":{"css":"max-width: calc(100% - 440px);"},"layout":{"type":"default"}} -->
<div class="wp-block-column has-custom-css"></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"440px"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:440px"><!-- wp:heading {"style":{"typography":{"textAlign":"center"}},"textColor":"brand-dark"} -->
<h2 class="wp-block-heading has-text-align-center has-brand-dark-color has-text-color">Your Gift Today<br>is <strong>Triple Matched</strong></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"is-style-default","style":{"typography":{"textAlign":"center"}},"textColor":"brand-dark"} -->
<p class="has-text-align-center is-style-default has-brand-dark-color has-text-color">to provide healthy meals to hungry Native American children</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"yellow","textColor":"blue-900","className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link has-blue-900-color has-yellow-background-color has-text-color has-background wp-element-button" href="/donate/">Give Monthly</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->

<!-- wp:cover {"url":"/wp-content/uploads/2026/07/SlideEvent.jpg","id":424,"alt":"A man playing a traditional flute","dimRatio":60,"isUserOverlayColor":true,"focalPoint":{"x":0.29,"y":0.33},"minHeight":492,"customGradient":"linear-gradient(90deg,rgba(255,255,255,0) 38%,rgba(255,255,255,0.94) 60%)","sizeSlug":"full","align":"full","className":"stjo-hero-slide","layout":{"type":"default"}} -->
<div class="wp-block-cover alignfull stjo-hero-slide" style="min-height:492px"><img class="wp-block-cover__image-background wp-image-424 size-full" alt="A man playing a traditional flute" src="/wp-content/uploads/2026/07/SlideEvent.jpg" style="object-position:29% 33%" data-object-fit="cover" data-object-position="29% 33%"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-60 has-background-dim wp-block-cover__gradient-background has-background-gradient" style="background:linear-gradient(90deg,rgba(255,255,255,0) 38%,rgba(255,255,255,0.94) 60%)"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"align":"full"} -->
<div class="wp-block-group alignfull"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"","style":{"css":"max-width: calc(100% - 330px);"}} -->
<div class="wp-block-column has-custom-css"></div>
<!-- /wp:column -->

<!-- wp:column {"width":"330px"} -->
<div class="wp-block-column" style="flex-basis:330px"><!-- wp:paragraph {"className":"is-style-eyebrow","style":{"typography":{"textAlign":"center"}},"textColor":"brand-dark"} -->
<p class="has-text-align-center is-style-eyebrow has-brand-dark-color has-text-color">50th Annual</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"style":{"typography":{"textAlign":"center"}},"textColor":"brand-dark"} -->
<h2 class="wp-block-heading has-text-align-center has-brand-dark-color has-text-color">Powwow &amp; Cultural Celebration</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"brand-dark"} -->
<p class="has-text-align-center has-brand-dark-color has-text-color"><strong>September 17–19, 2026</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"brand-dark"} -->
<p class="has-text-align-center has-brand-dark-color has-text-color">Celebrate 50 years of honoring Lakota (Sioux) culture, tradition and community at St. Joseph’s Indian School’s largest event of the year.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="/lakota-culture/powwow-dance/">Register to Attend</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->

<!-- wp:cover {"url":"/wp-content/uploads/2026/07/cover.jpg","dimRatio":0,"isUserOverlayColor":true,"focalPoint":{"x":0.5,"y":1},"minHeight":492,"minHeightUnit":"px","align":"full","className":"stjo-hero-slide stjo-hero-slide\u002d\u002ddark"} -->
<div class="wp-block-cover alignfull stjo-hero-slide stjo-hero-slide--dark" style="min-height:492px"><img class="wp-block-cover__image-background" alt="" src="/wp-content/uploads/2026/07/cover.jpg" style="object-position:50% 100%" data-object-fit="cover" data-object-position="50% 100%"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"verticalAlignment":"center","width":"420px"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:420px"><!-- wp:heading {"style":{"typography":{"textAlign":"center"}},"textColor":"white"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color">Become a <strong>DreamMaker</strong></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color">The DreamMakers are a special group of friends who give automatic monthly gifts to ensure the Lakota (Sioux) children are provided for year-round… <strong>and will help provide dreams for the future.</strong></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"brand-dark","className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link has-brand-dark-color has-white-background-color has-text-color has-background wp-element-button" href="/donate/">Give Monthly</a></div>
<!-- /wp:button -->

<!-- wp:button {"textColor":"white","className":"is-style-outline","style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}}}} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-white-color has-text-color has-link-color wp-element-button" href="/your-impact/become-a-dreammaker/">Learn More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"width":""} -->
<div class="wp-block-column"></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->