<?php
/**
 * about — the "About" section landing page.
 *
 * Structure mirrors the trimmed arrangement Zach set in the editor on
 * 2026-08-10, which is the streamlined read of the About frame on the Miro
 * sitemap board (uXjVHzJD47c, frame 3458764674146521654): title band, org
 * intro, FAQ, Our Mission + sub-page cards, Our Organization cards, ways to
 * support. Bands alternate white / light down the page.
 *
 * Copy is verbatim from stjo.org/about/ and its child pages. The header,
 * breadcrumbs, generosity pre-footer, and footer are template parts and are
 * not part of this content.
 *
 * Deliberately NOT here: full Strategic Plan and Board of Directors sections,
 * and the long ways-to-give detail. Those live on their own tertiary pages
 * (/about/strategic-plan/ and /about/board-of-directors/), which the
 * "Our Organization" card row links to.
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

$zigzag = '<!-- wp:separator {"className":"alignfull"} --><hr class="wp-block-separator has-alpha-channel-opacity alignfull"/><!-- /wp:separator -->';

/**
 * Photo cover card (the home page's card-trio treatment). The background photo
 * is decorative — the H3 and description carry the meaning — so alt is empty.
 * The description sits in .stjo-card__reveal, which the theme collapses until
 * hover or focus-within; it stays in the a11y tree either way.
 */
$cover_card = function ( $img, $title, $text, $href, $cta ) {
	$src = esc_url( stjo_asset( $img ) );
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:cover {"url":"' . $src . '","dimRatio":40,"overlayColor":"black","isUserOverlayColor":true,"minHeight":400,"className":"stjo-card"} -->'
		. '<div class="wp-block-cover stjo-card" style="min-height:400px">'
		. '<img class="wp-block-cover__image-background" alt="" src="' . $src . '" data-object-fit="cover"/>'
		. '<span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-40 has-background-dim"></span>'
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

/** Image-topped white info card: photo, H3, short description, arrow link. */
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

// A Yoast FAQ block styled as an accordion, matching the site's other FAQ
// treatments. faq-accordion.js upgrades the saved schema-faq markup.
$faq = function ( $items ) {
	$questions = array();
	$sections  = '';
	foreach ( $items as $i => $item ) {
		$id          = 'faq-about-' . ( $i + 1 );
		$questions[] = array(
			'id'           => $id,
			'question'     => $item[0],
			'answer'       => $item[1],
			'jsonQuestion' => $item[0],
			'jsonAnswer'   => $item[1],
			'images'       => array(),
		);
		$sections .= '<div class="schema-faq-section" id="' . $id . '"><strong class="schema-faq-question">' . $item[0] . '</strong> <p class="schema-faq-answer">' . $item[1] . '</p> </div>';
	}
	$attrs = wp_json_encode(
		array(
			'questions' => $questions,
			'className' => 'is-style-accordion',
		)
	);
	return '<!-- wp:yoast/faq-block ' . $attrs . " -->\n"
		. '<div class="schema-faq wp-block-yoast-faq-block is-style-accordion">' . $sections . "</div>\n"
		. '<!-- /wp:yoast/faq-block -->';
};

// Verbatim from stjo.org/about/faq/.
$faq_items = array(
	array(
		'How many children attend your school?',
		'Our campus includes 20 homes that house over 200 Native American children. We educate boys and girls in grades one through eight in our elementary school on campus. Our older students attend grades 9-12 at Chamberlain High School.',
	),
	array(
		'Where is St. Joseph&#8217;s Indian School located?',
		'We are located in central South Dakota, where Interstate 90 crosses the Missouri River. Our community, Chamberlain, has a population of approximately 2,500 people.',
	),
	array(
		'What percentage of my donation goes towards the children&#8217;s needs?',
		'Of each dollar raised, 68 cents goes to the children in our care and for future planned program growth. Our annual financial report is available online.',
	),
);
?>
<!-- wp:group {"metadata":{"name":"Page Title Band"},"align":"full","textColor":"white","className":"stjo-page-title-band","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-page-title-band has-white-color has-text-color"><!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"align":"center","textColor":"yellow","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-yellow-color has-text-color">About Us</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":1,"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">Helping Native American Families Since 1927</h1>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->

<?php echo $zigzag; ?>

<!-- wp:group {"metadata":{"name":"About our Organization"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"38%"} -->
<div class="wp-block-column" style="flex-basis:38%"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">About our Organization</h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph -->
<p>St. Joseph's Indian School is a Native American school dedicated to improving the quality of life for Lakota (Sioux) children and families. As an apostolate of the Congregation of the Priests of the Sacred Heart, St. Joseph's mission is to educate Native American children and their families for life — <em>mind, body, heart and spirit</em>. This mission drives our organization to educate and provide housing for approximately 200 Lakota (Sioux) children each year.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Poverty is a serious issue in reservation communities with the potential to fuel other negative issues — violence, addiction, neglect and more. By supporting St. Joseph's Indian School, you are helping Native American children connected with in their culture by learning the Lakota language, studying Native American heritage and traditions and healing the effects of generations of poverty.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Our organization provides an opportunity for Lakota (Sioux) children to change the cycle of poverty, with an education and opportunity of a brighter future.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>All services and programs provided to students are free-of-charge, thanks to charitable contributions from generous supporters. Tax-deductible donations keep our doors open and provide approximately 200 Native American children safety, an education and love.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Frequently Asked Questions"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:paragraph {"align":"center","textColor":"brand-dark","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-brand-dark-color has-text-color">Common Questions</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Frequently Asked Questions</h2>
<!-- /wp:heading -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:group {"layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group"><?php echo $faq( $faq_items ); ?></div>
<!-- /wp:group -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Our Mission"},"align":"full","className":"stjo-cards-band","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-cards-band"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Our Mission</h2>
<!-- /wp:heading -->

<!-- wp:group {"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">St. Joseph's Indian School, an apostolate of the Congregation of the Priests of the Sacred Heart, partners with Native American children and families to educate for life — <em>mind, body, heart and spirit.</em></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:columns -->
<div class="wp-block-columns">
<?php
echo $cover_card(
	'card-7.png',
	'Our History',
	'It is difficult to conceive, walking the grounds of St. Joseph&#8217;s Indian School, how many trials a small school like ours has endured since 1927.',
	'/our-history/',
	'Learn more'
);
echo $cover_card(
	'card.png',
	'About our Children',
	'Students are not required to be Catholic to attend St. Joseph&#8217;s, though over half are. Native American students fill our school&#8217;s 21 homes.',
	'/about/our-children/',
	'Our Children'
);
echo $cover_card(
	'card-5.png',
	'Our Programs',
	'St. Joseph&#8217;s Indian School uses an approach called the Circle of Care while caring for every child who calls our campus home.',
	'/youth-programs/',
	'Our Programs'
);
?>
</div>
<!-- /wp:columns -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:columns -->
<div class="wp-block-columns">
<?php
echo $cover_card(
	'card-3.png',
	'Our Blog',
	'News, student milestones and stories from campus, published throughout the year by the staff at St. Joseph&#8217;s Indian School.',
	'/blog/',
	'Learn more'
);
echo $cover_card(
	'card-8.png',
	'Our Podcast',
	'A captivating video podcast series called H&oacute;&#269;hoka — <em>the Center</em> — that delves into the heart of Native American education, culture and community.',
	'/about/our-podcast/',
	'Learn more'
);
?>
</div>
<!-- /wp:columns -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Our Organization"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:paragraph {"align":"center","textColor":"brand-dark","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-brand-dark-color has-text-color">Who We Are</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Our Organization</h2>
<!-- /wp:heading -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:columns {"className":"stjo-related-cards"} -->
<div class="wp-block-columns stjo-related-cards">
<?php
echo $card(
	'card-4.png',
	'Students in powwow regalia standing together on the campus lawn',
	'Board of Directors',
	'The ex-officio members, officers and directors who govern St. Joseph&#8217;s Indian School.',
	'/about/board-of-directors/',
	'See the Board'
);
echo $card(
	'hero.png',
	'Two students standing arm in arm on the grass outside',
	'Strategic Plan',
	'Looking ahead, we see St. Joseph&#8217;s as more than a school. It will be a national model defining what holistic care and Native-serving education can be for generations to come.',
	'/about/strategic-plan/',
	'Read the Strategic Plan'
);
echo $card(
	'card-6.png',
	'A student smiling on a playground swing',
	'Accountability &amp; Reports',
	'For every dollar raised, 66% goes directly to the children attending St. Joseph&#8217;s Indian School, their families living in reservation communities, and toward cultural and faith development.',
	'/about/accountability-reports/',
	'View Our Reports'
);
?>
</div>
<!-- /wp:columns -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Many Ways to Support Our Mission"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><?php echo $sp( 'large' ); ?>

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Many Ways to Support Our Mission</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>There are many ways to support our mission and help Lakota (Sioux) children in need! Tax-deductible gifts help Native American children in need receive:</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>A safe, stable home away from reservation hardships.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Individual counseling and guidance.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Carefully planned curriculum based on Lakota (Sioux) culture and individual student needs.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Tools to help build confidence, boost self-esteem and improve cultural awareness.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>All of this and more to live a bright, productive and possibility-filled future.</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
