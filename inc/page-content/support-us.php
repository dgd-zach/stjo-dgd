<?php
/**
 * support-us — the "Support Us" section landing.
 *
 * Layout follows the Support Us wireframe on the sitemap board (uXjVHzJD47c,
 * frame 3458764671874048270), the same shape as the built Your Impact page:
 * page hero, intro, then the Support Us "8 ways to give" band as the page's
 * main content, then a "Your Impact" card row. The band is the site-wide
 * pre-footer's block twin (inc/patterns/band-cards-tiles-cta.php, links from
 * theme-config give.*); footer.php sees `stjo-generosity` in this content and
 * skips the pre-footer so the band does not repeat below.
 *
 * Copy is stjo.org/help-native-americans/ (Ways to Give): its headline and
 * paragraphs are the intro, its tax-deductible list closes it. The Your Impact
 * cards reuse the Your Impact page's own card copy and photos; each links to
 * the thing it names (the DreamMaker card to the LO monthly form, per Zach).
 *
 * Header, breadcrumbs and footer are template parts, not part of this content.
 * Seed source only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sp = function ( $size ) {
	return '<!-- wp:spacer {"height":"var:preset|spacing|' . $size . '"} -->'
		. '<div style="height:var(--wp--preset--spacing--' . $size . ')" aria-hidden="true" class="wp-block-spacer"></div>'
		. '<!-- /wp:spacer -->';
};

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

$hero       = esc_url( stjo_asset( 'hero.png' ) ); // the shared page-hero photo (Your Impact, Youth Programs)
$dreammaker = esc_url( (string) stjo_config_get( 'give.dreammaker_url', 'https://give.stjo.org/site/Donation2?mfc_pref=T&idb=444651902&df_id=10023&10023.donation=form1' ) );
?>
<!-- wp:cover {"url":"<?php echo $hero; ?>","dimRatio":0,"isUserOverlayColor":true,"focalPoint":{"x":0.52,"y":0.29},"minHeight":450,"contentPosition":"bottom center","metadata":{"name":"Page Hero"},"align":"full","className":"stjo-page-hero"} -->
<div class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center stjo-page-hero" style="min-height:450px"><img class="wp-block-cover__image-background" alt="" src="<?php echo $hero; ?>" style="object-position:52% 29%" data-object-fit="cover" data-object-position="52% 29%"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"align":"full","className":"hero-content","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull hero-content"><!-- wp:paragraph {"align":"center","textColor":"white","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-white-color has-text-color">Ways to Give</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":1,"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">Support Us</h1>
<!-- /wp:heading --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->

<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Helping Native American children in need</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">St. Joseph’s Indian School has served Lakota (Sioux) children and families since 1927. Reaching out to American Indian youth on Indian reservations and beyond, remains St. Joseph’s Indian School’s mission.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Child poverty and abuse are serious issues on Indian reservations. By supporting St. Joseph’s Indian School, you are helping Native American children in need regain pride in the Lakota (Sioux) culture by learning the Lakota language, studying Native American culture and healing the broken family circle from which they come.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Lakota (Sioux) children in need escape extreme poverty and other issues when they attend St. Joseph’s Indian School.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">Tax-deductible gifts help Native American children in need receive:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>A safe, stable home away from reservation hardships</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Individual counseling and guidance</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Carefully planned curriculum based on Lakota (Sioux) culture and individual student needs</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Tools to help build confidence, boost self-esteem, improve cultural awareness</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>All of this and more to live a bright, productive, possibility-filled future</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:separator {"align":"full"} -->
<hr class="wp-block-separator alignfull has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<?php echo stjo_generosity_band_seed_markup(); // the synced Your Generosity Band (inline blocks only if the pattern is missing) ?>

<!-- wp:group {"metadata":{"name":"Your Impact"},"align":"full","className":"stjo-cards-band","layout":{"type":"constrained"},"anchor":"your-impact"} -->
<div class="wp-block-group alignfull stjo-cards-band" id="your-impact"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Your Impact</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<?php
echo $cover_card(
	'card.png', // the live DreamMakers graphic (NewDreamMakers.jpg) has its title burned in, so it would fight the card heading
	'Become a DreamMaker',
	'Monthly gifts give Lakota (Sioux) children steady support all year, at school, at home and in their culture. Your generosity helps them thrive.',
	$dreammaker,
	'Become a DreamMaker'
);
echo $cover_card(
	'card-2.png',
	'College Scholarship',
	'The Čhaŋkú Lúta Scholarship helps Native American students pursue higher education. In 2025-26, St. Joseph’s awarded a record $275,000 in scholarships.',
	'/youth-programs/#college-scholarship',
	'About the Scholarship'
);
echo $cover_card(
	'card-3.png',
	'Student Stories',
	'Meet the Native American students who call St. Joseph’s home, from elementary school through high school graduation.',
	'/student-stories/',
	'Read Student Stories'
);
?>
</div>
<!-- /wp:columns -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/your-impact/">Explore Your Impact</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
