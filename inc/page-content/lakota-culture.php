<?php
/**
 * lakota-culture — the "Lakota Culture" section landing page.
 *
 * Structure follows the Lakota Culture frame on the sitemap/wireframe Miro
 * board (uXjVHzJD47c, frame 3458764671870816913): title band, intro, then a
 * 3x2 grid of culture sub-page cards. Copy is lifted verbatim from
 * stjo.org/native-american-culture/. The header, breadcrumbs, generosity
 * pre-footer, and footer are template parts and are not part of this content.
 *
 * The six cards match the six Lakota Culture children in the primary nav.
 * Oceti Sakowin and the Seven Lakota Rites exist on stjo.org but have no page
 * in this sitemap, so they run inline as media & text above the grid. Both are
 * dead ends here: on stjo.org each teaser links to a fuller, sourced account
 * (Black Elk citations on the Rites page) that the rebuild has nowhere to put.
 * If those pages are never built, the client should know this page is now the
 * site's whole treatment of both subjects.
 *
 * The Seven Lakota Rites photo is an inípi lodge (one of the seven rites),
 * from stjo.org's own Inipi page. It replaces a photo of Our Lady of the Sioux
 * Chapel, which paired a Catholic chapel with the seven traditional Lakota
 * ceremonies — a conflation the client's own copy avoids.
 *
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

$zigzag = '<!-- wp:separator {"align":"full"} --><hr class="wp-block-separator alignfull has-alpha-channel-opacity"/><!-- /wp:separator -->';

/**
 * Image-topped info card: photo, H3, short description, arrow link. Link text
 * is unique per card so the grid reads unambiguously out of context.
 */
$card = function ( $img, $alt, $title, $text, $href, $cta ) {
	$src = esc_url( stjo_asset( $img ) );
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:group {"className":"stjo-info-card"} --><div class="wp-block-group stjo-info-card">'
		. '<!-- wp:image {"sizeSlug":"large","className":"stjo-info-card__image"} -->'
		. '<figure class="wp-block-image size-large stjo-info-card__image"><img src="' . $src . '" alt="' . esc_attr( $alt ) . '"/></figure>'
		. '<!-- /wp:image -->'
		. '<!-- wp:group {"className":"stjo-info-card__body"} --><div class="wp-block-group stjo-info-card__body">'
		. '<!-- wp:heading {"level":3,"textColor":"blue-900"} -->'
		. '<h3 class="wp-block-heading has-blue-900-color has-text-color">' . $title . '</h3>'
		. '<!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>' . $text . '</p><!-- /wp:paragraph -->'
		. '<!-- wp:buttons --><div class="wp-block-buttons">'
		. '<!-- wp:button {"textColor":"blue-900","className":"is-style-arrow-link"} -->'
		. '<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link has-blue-900-color has-text-color wp-element-button" href="' . esc_url( $href ) . '">' . $cta . '</a></div>'
		. '<!-- /wp:button --></div><!-- /wp:buttons -->'
		. '</div><!-- /wp:group -->'
		. '</div><!-- /wp:group -->'
		. '</div><!-- /wp:column -->';
};

// ── Page title band ────────────────────────────────────────────────────────
// H1 is the hero headline from stjo.org/native-american-culture/.
?>
<!-- wp:group {"metadata":{"name":"Page Title Band"},"align":"full","textColor":"white","className":"stjo-page-title-band","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-page-title-band has-white-color has-text-color"><!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"align":"center","textColor":"yellow","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-yellow-color has-text-color">Lakota Culture</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":1,"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">Native American (Lakota) Culture</h1>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->

<?php echo $zigzag; ?>

<!-- wp:group {"metadata":{"name":"Culture Intro"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph -->
<p>Culture is defined as the established beliefs, social norms, customs and traditions of a group of people. The same is true for Native American culture. Factors like geography, history and generations of spirituality, stories and traditions also shape the culture of any given tribe or people. Native Americans are no exception.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Here at St. Joseph's Indian School, we have had the privilege of working with Native American families and communities since 1927. In 1991, the Akt&aacute; Lakota Museum &amp; Cultural Center was established on our campus to honor and preserve the historical artifacts and contemporary art that tell the story of the Lakota (Sioux) people of the Northern Plains.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Native American culture is sometimes thought of as a thing of the past. However, contemporary powwows, art and language revitalization efforts tell a different story.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>This section introduces historical information about Native American culture — specifically the Lakota — as well as ways this rich culture is being lived and shared today.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Oceti Sakowin"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><?php echo $sp( 'medium' ); ?>

<!-- wp:media-text {"mediaType":"image","className":"is-style-rounded"} -->
<div class="wp-block-media-text is-stacked-on-mobile is-style-rounded"><figure class="wp-block-media-text__media"><img src="<?php echo esc_url( stjo_asset( 'columns.png' ) ); ?>" alt="Open sky over the Northern Plains"/></figure><div class="wp-block-media-text__content"><!-- wp:heading -->
<h2 class="wp-block-heading">Oceti Sakowin — Seven Council Fires</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The proper name for the people commonly known as the Sioux is Oceti Sakowin, meaning Seven Council Fires. What is known today as the Great Sioux Nation is made up of the bands and dialects of the Seven Council Fires or Oceti Sakowin, in Lakota.</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:media-text -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Seven Lakota Rites"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><!-- wp:media-text {"mediaPosition":"right","mediaType":"image","className":"is-style-rounded"} -->
<div class="wp-block-media-text has-media-on-the-right is-stacked-on-mobile is-style-rounded"><div class="wp-block-media-text__content"><!-- wp:heading -->
<h2 class="wp-block-heading">Seven Lakota Rites</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>As legend states, long ago, the Sacred White Buffalo Calf Woman came to Earth and gave the Lakota people a Sacred Pipe and a small round stone. These gifts would be used for the Seven Lakota Rites.</p>
<!-- /wp:paragraph --></div><figure class="wp-block-media-text__media"><img src="<?php echo esc_url( stjo_asset( 'inipi-lodge.jpg' ) ); ?>" alt="A covered in&iacute;pi lodge standing in a grassy clearing at the edge of the trees"/></figure></div>
<!-- /wp:media-text -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Learn More About Lakota Culture"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:paragraph {"align":"center","textColor":"brand-dark","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-brand-dark-color has-text-color">Explore</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Learn More About Lakota Culture</h2>
<!-- /wp:heading -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:columns {"className":"stjo-related-cards"} -->
<div class="wp-block-columns stjo-related-cards">
<?php
echo $card(
	'card-4.png',
	'Students in powwow regalia standing together on the campus lawn',
	'Beliefs &amp; Traditions',
	'What a person believes shapes the way they live their lives, make decisions and plan for the future. Traditions and customs are the backbone of any culture.',
	'/lakota-culture/beliefs-traditions/',
	'Explore Beliefs &amp; Traditions'
);
echo $card(
	'card-2.png',
	'A line of dancers in jingle dress regalia waiting to enter the powwow arena',
	'Powwow &amp; Dance',
	'A wa&#269;h&iacute;pi — powwow — is a Native American gathering focused on dance, song and celebration. Powwows celebrate the connections to tradition and spirituality, to the Earth and to one another in a social, personal and spiritual meeting.',
	'/lakota-culture/powwow-dance/',
	'Explore Powwow &amp; Dance'
);
echo $card(
	'card-6.png',
	'A student smiling on a playground swing',
	'Lakota Legends',
	'Lakota history was passed from generation to generation through the beautiful art of storytelling. Several legends are still remembered and passed down today.',
	'/lakota-culture/lakota-legends/',
	'Read the Legends'
);
?>
</div>
<!-- /wp:columns -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:columns {"className":"stjo-related-cards"} -->
<div class="wp-block-columns stjo-related-cards">
<?php
echo $card(
	'card-5.png',
	'Students working on laptops at their desks in a classroom',
	'Lakota Language',
	'Preserving and sharing the Lakota culture is a core part of our mission at St. Joseph&#8217;s Indian School and traditional Lakota language is a vital part of that effort. Like many other indigenous languages around the world, Lakota is in danger of being permanently lost.',
	'/lakota-culture/lakota-language/',
	'Learn the Language'
);
echo $card(
	'hero.png',
	'Two students standing arm in arm on the grass outside',
	'Important Animals',
	'There are animals that are considered relatives to the Lakota people. Among them are the winged, four-legged and reptile nation.',
	'/lakota-culture/important-animals/',
	'Meet the Animals'
);
echo $card(
	'card-3.png',
	'Three students reading together at a table in the campus library',
	'Native American Books',
	'Cultural education is important for students and staff at St. Joseph&#8217;s. Check out some of our favorite Native American books and authors.',
	'/lakota-culture/native-american-books/',
	'Browse the Books'
);
?>
</div>
<!-- /wp:columns -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:cover {"metadata":{"name":"Aktá Lakota Museum CTA"},"url":"<?php echo esc_url( stjo_asset( 'cover.png' ) ); ?>","dimRatio":70,"overlayColor":"blue-900","isUserOverlayColor":true,"minHeight":418,"align":"full","className":"stjo-dreammaker","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull stjo-dreammaker" style="min-height:418px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( stjo_asset( 'cover.png' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-blue-900-background-color has-background-dim-70 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":2,"textColor":"white","fontSize":"xxl"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color has-xxl-font-size">Akt&aacute; Lakota Museum &amp; Cultural Center</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color">Established on our campus in 1991 to honor and preserve the historical artifacts and contemporary art that tell the story of the Lakota (Sioux) people of the Northern Plains.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill","textColor":"brand-dark","backgroundColor":"white"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link has-brand-dark-color has-text-color has-white-background-color has-background wp-element-button" href="https://aktalakota.stjo.org/">Visit the Museum</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->

<?php echo $zigzag; ?>
