<?php
/**
 * our-podcast: "Our Podcast" (About section).
 *
 * General Content template. Replaces the live page's accordion of past
 * seasons with a card band (stjo-cards-band), grouped Seasons 1-3, 4-6 and
 * 7-8 to keep each row at the band's 3-up card width. Intro copy, the
 * featured video and the season list are verbatim from
 * https://www.stjo.org/about/podcast/.
 *
 * Seed source only: edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

/** Cover card (photo, H3, hover-reveal blurb, arrow link), as on Your Impact. */
$cover_card = function ( $img, $title, $text, $href, $cta ) {
	// A seeded live photo (by filename) wins over the design's stock asset.
	$seeded = stjo_seeded_image( $img );
	$src    = esc_url( $seeded['id'] ? $seeded['url'] : stjo_asset( $img ) );
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:cover {"url":"' . $src . '","dimRatio":80,"overlayColor":"black","isUserOverlayColor":true,"minHeight":400,"className":"stjo-card"} -->'
		. '<div class="wp-block-cover stjo-card" style="min-height:400px">'
		. '<img class="wp-block-cover__image-background" alt="" src="' . $src . '" data-object-fit="cover"/>'
		. '<span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-80 has-background-dim"></span>'
		. '<div class="wp-block-cover__inner-container">'
		. '<!-- wp:heading {"level":3,"textColor":"light"} -->'
		. '<h3 class="wp-block-heading has-light-color has-text-color">' . $title . '</h3>'
		. '<!-- /wp:heading -->'
		. '<!-- wp:paragraph {"textColor":"light","className":"stjo-card__reveal"} -->'
		. '<p class="has-light-color has-text-color stjo-card__reveal">' . $text . '</p>'
		. '<!-- /wp:paragraph -->'
		. '<!-- wp:buttons --><div class="wp-block-buttons">'
		. '<!-- wp:button {"textColor":"white","className":"is-style-arrow-link"} -->'
		. '<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link has-white-color has-text-color wp-element-button" href="' . esc_url( $href ) . '">' . $cta . '</a></div>'
		. '<!-- /wp:button --></div><!-- /wp:buttons -->'
		. '</div></div>'
		. '<!-- /wp:cover -->'
		. '</div><!-- /wp:column -->';
};

echo $title_band( 'About', 'Our Podcast' );
?>
<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">Hóčhoka – A Podcast by St. Joseph’s Indian School</p>
<!-- /wp:paragraph -->

<!-- wp:embed {"url":"https://www.youtube.com/watch?v=rcmTYET7E-M","type":"video","providerNameSlug":"youtube","responsive":true,"className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
<figure class="wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">
https://www.youtube.com/watch?v=rcmTYET7E-M
</div></figure>
<!-- /wp:embed -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph -->
<p>St. Joseph’s Indian School is a national leader in advancing Native American lives. To reach a new audience beyond our campus walls, we have published a captivating video podcast series called <strong>Hóčhoka</strong> — <em>the Center</em> — that delves into the heart of Native American education, culture and community.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Each episode of <strong>Hóčhoka</strong> features engaging conversations with students, alumni, staff and more. It highlights Native American perspectives and provides a platform for voices and stories too often underrepresented in mainstream media. Further, it fosters a sense of community and belonging while displaying the strong bonds and support systems within St. Joseph’s Indian School.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>If you want to learn more about Native American education and culture, the <strong>Hóčhoka</strong> podcast is worth a listen!</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a href="https://www.youtube.com/@stjo1927/podcasts"><strong>Watch the Podcast right now on our YouTube channel for a glimpse into this special multimedia platform.</strong></a> While you are there, click that “Subscribe” button to never miss an episode of <strong>Hóčhoka</strong> and all the other great videos we share regularly.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Philámayaye</strong> — <em>thank you</em> — for joining St. Joseph’s Indian School as we unpack topics, ideas and conversations surrounding Native American culture and communities today.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Archived Seasons"},"align":"full","backgroundColor":"light","className":"stjo-cards-band","layout":{"type":"constrained"},"anchor":"archived-seasons"} -->
<div class="wp-block-group alignfull stjo-cards-band has-light-background-color has-background" id="archived-seasons"><?php echo $sp( 'large' ); ?>

<!-- wp:paragraph {"align":"center","textColor":"brand-dark","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-brand-dark-color has-text-color">Catch Up</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Archived Seasons</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Every season of Hóčhoka is on our YouTube channel. Pick a season to start watching.</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns">
<?php
echo $cover_card(
	'podcast-season-1.jpg',
	'Season 1',
	'15 episodes. Watch the full season on YouTube.',
	'https://www.youtube.com/playlist?list=PLBkGQb4TQK3pYYc4aOYtmezxOMJyUau_8',
	'Watch Season 1'
);
echo $cover_card(
	'podcast-season-2.jpg',
	'Season 2',
	'16 episodes. Watch the full season on YouTube.',
	'https://www.youtube.com/playlist?list=PLBkGQb4TQK3r9sjElZQG_LPRT9kp2fScI',
	'Watch Season 2'
);
echo $cover_card(
	'podcast-season-3.jpg',
	'Season 3',
	'16 episodes. Watch the full season on YouTube.',
	'https://www.youtube.com/playlist?list=PLBkGQb4TQK3o4v8pQOdNZCgpIcWigEPPv',
	'Watch Season 3'
);
?>
</div>
<!-- /wp:columns -->

<!-- wp:columns -->
<div class="wp-block-columns">
<?php
echo $cover_card(
	'podcast-season-4.jpg',
	'Season 4',
	'14 episodes. Watch the full season on YouTube.',
	'https://www.youtube.com/playlist?list=PLBkGQb4TQK3rvOQladMX2CPk6hfGU5qQI',
	'Watch Season 4'
);
echo $cover_card(
	'podcast-season-5.jpg',
	'Season 5',
	'17 episodes. Watch the full season on YouTube.',
	'https://www.youtube.com/playlist?list=PLBkGQb4TQK3p5HljnnCgxcw15B7JGLchn',
	'Watch Season 5'
);
echo $cover_card(
	'podcast-season-6.jpg',
	'Season 6',
	'17 episodes. Watch the full season on YouTube.',
	'https://www.youtube.com/playlist?list=PLBkGQb4TQK3rCFPjAONBbGvkoQX5YssKz',
	'Watch Season 6'
);
?>
</div>
<!-- /wp:columns -->

<!-- wp:columns -->
<div class="wp-block-columns">
<?php
echo $cover_card(
	'podcast-season-7.jpg',
	'Season 7',
	'19 episodes. Watch the full season on YouTube.',
	'https://www.youtube.com/playlist?list=PLBkGQb4TQK3o0vh6rpL4G_r9LCXT9VsDd',
	'Watch Season 7'
);
echo $cover_card(
	'podcast-season-8.jpg',
	'Season 8',
	'18 episodes. Watch the full season on YouTube.',
	'https://www.youtube.com/playlist?list=PLBkGQb4TQK3qQcwm7HiRgsh0MzdwNC5cc',
	'Watch Season 8'
);
?>
</div>
<!-- /wp:columns -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="https://www.youtube.com/@stjo1927/podcasts">Subscribe on YouTube</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
