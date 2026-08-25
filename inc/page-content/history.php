<?php
/**
 * Page content: Our History (/our-history/).
 *
 * Seed source only: the live content lives in the DB after seeding. Rendered
 * by inc/seed/seed-history.php, which ob_start()s this file and writes the
 * result to the page matched by slug.
 *
 * The two founders portraits are core/media-text blocks, and media-text stores
 * a numeric mediaId. Those IDs differ between local and staging, so they are
 * resolved from the running site's media library at seed time rather than
 * hard-coded. Everything else here is root-relative and portable as-is.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dehon    = stjo_seeded_image( 'history-fr-dehon.jpg' );
$hogebach = stjo_seeded_image( 'history-fr-hogebach-with-student.jpg' );
?>
<!-- wp:group {"metadata":{"name":"Page Title Band","categories":["stjo","stjo-heroes"],"patternName":"stjo/page-title-band"},"align":"full","className":"stjo-page-title-band","textColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-page-title-band has-white-color has-text-color"><!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"className":"is-style-eyebrow","style":{"typography":{"textAlign":"center"}},"textColor":"white"} -->
<p class="has-text-align-center is-style-eyebrow has-white-color has-text-color">About</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"}},"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">Our History</h1>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Title + Text","categories":["stjo","stjo-body"],"patternName":"stjo/title-text"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:spacer -->
<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading -->
<h2 class="wp-block-heading">A Century  of Service &amp; Love</h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph -->
<p>It is difficult to conceive, walking the grounds of St. Joseph’s Indian School, how many trials a small school like ours has endured since 1927.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>The blue-green stretch of Missouri River next to our campus offers no hint of the dust storms and grasshopper plagues of the Great Depression. The tall trees catching the morning sun don’t speak of the tornado of 1930. And, the happy laughter of children playing makes it hard to remember the crackle of fires or the bang of hammers rebuilding through the years.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:spacer -->
<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Two-Column Subsection (Image Left)","categories":["stjo-media"],"patternName":"stjo/two-column-subsection"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><!-- wp:spacer -->
<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:media-text {"align":"wide","mediaId":<?php echo (int) $dehon['id']; ?>,"mediaLink":"?attachment_id=<?php echo (int) $dehon['id']; ?>","linkDestination":"none","mediaType":"image","mediaWidth":30,"imageFill":false,"className":"is-style-rounded"} -->
<div class="wp-block-media-text alignwide is-stacked-on-mobile is-style-rounded" style="grid-template-columns:30% auto"><figure class="wp-block-media-text__media"><img src="<?php echo esc_url( $dehon['url'] ); ?>" alt="Studio portrait of Fr. Leo John Dehon in glasses and clerical dress" class="wp-image-<?php echo (int) $dehon['id']; ?> size-full"/></figure><div class="wp-block-media-text__content"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Fr. Leo John Dehon</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>Father Leo John Dehon, founder of the Priests of the Sacred Heart, believed in responding to God’s love by trying to meet the needs of those around us.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>March 14, 1843: Born in LaChapelle, France</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>December 19, 1868: Ordained as a priest, served as First Vatican Council clerk, then appointed to Saint Quentin Parish.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>June 28, 1878: Founded Congregation of the Priests of the Sacred Heart (SCJs)</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>August 12, 1925: Died in Brussels, Belgium.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Visit&nbsp;<a href="http://poshusa.org/who-we-are/learn-about-our-founder">poshusa.org</a>&nbsp;for more details.</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div></div>
<!-- /wp:media-text -->

<!-- wp:spacer -->
<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Two-Column Subsection (Image Right)","categories":["stjo-media"],"patternName":"stjo/two-column-subsection-reversed"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><!-- wp:spacer -->
<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:media-text {"mediaPosition":"right","mediaId":<?php echo (int) $hogebach['id']; ?>,"mediaLink":"/?attachment_id=<?php echo (int) $hogebach['id']; ?>","linkDestination":"none","mediaType":"image","mediaWidth":30,"imageFill":false,"className":"is-style-rounded"} -->
<div class="wp-block-media-text has-media-on-the-right is-stacked-on-mobile is-style-rounded" style="grid-template-columns:auto 30%"><div class="wp-block-media-text__content"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Fr. Henry Hogebach, SCJ</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>Born in 1890.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>&nbsp;In 1923, Fr. Henry Hogebach, SCJ, came to the US from Germany. His ministry led him to the Lower Brule Reservation in South Dakota.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>&nbsp;While starting a school was not a part of their original missionary plan, Fr. Hogebach felt called to seek permission to start a school closer to the Native American families they were serving. He was granted permission in 1927.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>&nbsp;Much of his time was spent raising funds to keep the school open; donations of clothing, shoes, medication and other supplies were greatly appreciated and well-used … just as they are today.</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div><figure class="wp-block-media-text__media"><img src="<?php echo esc_url( $hogebach['url'] ); ?>" alt="Fr. Henry Hogebach, SCJ standing with a young student on the front steps of the school" class="wp-image-<?php echo (int) $hogebach['id']; ?> size-full"/></figure></div>
<!-- /wp:media-text -->

<!-- wp:spacer -->
<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->

<!-- wp:spacer -->
<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:group {"metadata":{"name":"Milestones Timeline"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><!-- wp:spacer -->
<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:stjo/timeline /-->

<!-- wp:group {"metadata":{"name":"Today Outro"},"className":"stjo-timeline-outro","layout":{"type":"default"}} -->
<div class="wp-block-group stjo-timeline-outro"><!-- wp:paragraph {"className":"is-style-eyebrow","textColor":"brand-dark"} -->
<p class="is-style-eyebrow has-brand-dark-color has-text-color">Today</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textColor":"blue-900"} -->
<h2 class="wp-block-heading has-blue-900-color has-text-color">Lorem ipsum dolor sit amet consectetur</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Continue Exploring"},"align":"full","backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-white-background-color has-background"><!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"className":"is-style-eyebrow has-brand-dark-color has-text-color","style":{"typography":{"textAlign":"center"}},"textColor":"brand-dark"} -->
<p class="has-text-align-center is-style-eyebrow has-brand-dark-color has-text-color">Learn More</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"style":{"typography":{"textAlign":"center"}},"textColor":"brand-dark"} -->
<h2 class="wp-block-heading has-text-align-center has-brand-dark-color has-text-color">Continue Exploring Our Story</h2>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"var:preset|spacing|small"} -->
<div style="height:var(--wp--preset--spacing--small)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"url":"/wp-content/themes/stjo-dgd/assets/images/card-3.png","dimRatio":50,"overlayColor":"black","isUserOverlayColor":true,"minHeight":360,"className":"stjo-card"} -->
<div class="wp-block-cover stjo-card" style="min-height:360px"><img class="wp-block-cover__image-background" alt="" src="/wp-content/themes/stjo-dgd/assets/images/card-3.png" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":3,"textColor":"light"} -->
<h3 class="wp-block-heading has-light-color has-text-color">Our Mission</h3>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"textColor":"white","className":"is-style-arrow-link"} -->
<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link has-white-color has-text-color wp-element-button" href="/about/">See Our Mission</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"url":"/wp-content/themes/stjo-dgd/assets/images/card-2.png","dimRatio":50,"overlayColor":"black","isUserOverlayColor":true,"minHeight":360,"className":"stjo-card"} -->
<div class="wp-block-cover stjo-card" style="min-height:360px"><img class="wp-block-cover__image-background" alt="" src="/wp-content/themes/stjo-dgd/assets/images/card-2.png" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":3,"textColor":"light"} -->
<h3 class="wp-block-heading has-light-color has-text-color">Lakota Culture</h3>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"textColor":"white","className":"is-style-arrow-link"} -->
<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link has-white-color has-text-color wp-element-button" href="/lakota-culture/">Explore the Lakota Culture</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"url":"/wp-content/themes/stjo-dgd/assets/images/card-8.png","dimRatio":50,"overlayColor":"black","isUserOverlayColor":true,"minHeight":360,"className":"stjo-card"} -->
<div class="wp-block-cover stjo-card" style="min-height:360px"><img class="wp-block-cover__image-background" alt="" src="/wp-content/themes/stjo-dgd/assets/images/card-8.png" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":3,"textColor":"light"} -->
<h3 class="wp-block-heading has-light-color has-text-color">Your Impact</h3>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"textColor":"white","className":"is-style-arrow-link"} -->
<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link has-white-color has-text-color wp-element-button" href="/your-impact/">See Your Impact</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->
