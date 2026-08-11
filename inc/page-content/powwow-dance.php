<?php
/**
 * powwow-dance — "Powwow & Dance", a secondary page under Lakota Culture.
 *
 * Structure follows the Powwow & Dance frame on the Miro sitemap board
 * (uXjVHzJD47c, frame 3458764671976970432): title band, intro, an Attend a
 * Powwow promo, Women's then Men's dance styles as alternating media & text,
 * and a three-item Powwow Etiquette FAQ. (The "Prayer Details" labels on that
 * frame are leftovers from the Prayers wireframe it was duplicated from.)
 *
 * Per the board's third tier, Attend a Powwow is the one tertiary page linked
 * from here. Copy is verbatim from stjo.org/native-american-culture/powwow/
 * and its three child pages, trimmed to two paragraphs per dance style.
 *
 * Photographs are St. Joseph's own, pulled from the live site's
 * /wp-content/Media/Images/Page/ tree and imported into the Media Library.
 * They are small originals (350x233 and 233x350) — ask the client for
 * higher-resolution masters before launch.
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
 * One dance style: native Media & Text, image side alternating down the page.
 * $side is 'left' or 'right' and refers to where the photo sits.
 */
$style_block = function ( $side, $img, $alt, $title, $paras, $media_width = 50 ) {
	$src   = esc_url( stjo_asset( $img ) );
	$body  = '<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . $title . '</h3><!-- /wp:heading -->';
	foreach ( $paras as $p ) {
		$body .= '<!-- wp:paragraph --><p>' . $p . '</p><!-- /wp:paragraph -->';
	}
	$media = '<figure class="wp-block-media-text__media"><img src="' . $src . '" alt="' . esc_attr( $alt ) . '"/></figure>';
	$inner = 'right' === $side
		? '<div class="wp-block-media-text__content">' . $body . '</div>' . $media
		: $media . '<div class="wp-block-media-text__content">' . $body . '</div>';
	// Portrait originals are only 233px wide; at the default 50% media cell they
	// upscale 2.5x and tower over their own text. A narrower cell fixes both.
	$w     = (int) $media_width;
	$mw    = 50 === $w ? '' : ',"mediaWidth":' . $w;
	$grid  = 50 === $w
		? ''
		: ' style="grid-template-columns:' . ( 'right' === $side ? 'auto ' . $w . '%' : $w . '% auto' ) . '"';
	$attrs = 'right' === $side
		? '{"mediaPosition":"right","mediaType":"image"' . $mw . ',"className":"is-style-rounded"}'
		: '{"mediaType":"image"' . $mw . ',"className":"is-style-rounded"}';
	$cls   = 'right' === $side
		? 'wp-block-media-text has-media-on-the-right is-stacked-on-mobile is-style-rounded'
		: 'wp-block-media-text is-stacked-on-mobile is-style-rounded';
	return '<!-- wp:media-text ' . $attrs . ' -->'
		. '<div class="' . $cls . '"' . $grid . '>' . $inner . '</div>'
		. '<!-- /wp:media-text -->';
};

// Yoast FAQ block styled as an accordion, matching the site's other FAQs.
$faq = function ( $items ) {
	$questions = array();
	$sections  = '';
	foreach ( $items as $i => $item ) {
		$id          = 'faq-powwow-' . ( $i + 1 );
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

// Verbatim from stjo.org/native-american-culture/powwow/powwow-etiquette/.
$etiquette = array(
	array(
		'Listen',
		'Powwows can be very busy events with lots of action going on at once, which is why one of the first expectations of powwow etiquette is to listen carefully. The Master of Ceremonies may announce it&#8217;s time to stand and remove your hat for an honor song. Please follow the request just as you would for the National Anthem. The Master of Ceremonies will also tell you who is to dance and whether the audience may participate. Remember to allow elders to go first and listen carefully whenever they speak.',
	),
	array(
		'Show Respect',
		'Items carried or worn by dancers should be called outfits or regalia. Dancers dress to honor the spiritual connection they have with nature and Wak&#341;&#225;&#331; T&#341;&#225;&#331;ka — Great Spirit. Outfits can sometimes take years to put together. Each piece is intricate; they can be costly and may be family heirlooms. Please do not refer to these cherished possessions as a &#8220;costume.&#8221; Spectators should also show respect for dancers by asking permission before taking a photo or touching any part of their regalia.',
	),
	array(
		'Participate',
		'Most Native Americans are glad to share their culture with those who are genuinely interested. A good way to learn is to find a friendly participant and ask questions. Pay attention to the Master of Ceremonies and to what people around you are doing, and you will have no problems enjoying the powwow!',
	),
);

?>
<!-- wp:group {"metadata":{"name":"Page Title Band"},"align":"full","textColor":"white","className":"stjo-page-title-band","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-page-title-band has-white-color has-text-color"><!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"align":"center","textColor":"yellow","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-yellow-color has-text-color">Lakota Culture</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":1,"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">Wa&#269;h&iacute;pi — powwow — a Native American tradition</h1>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->

<?php echo $zigzag; ?>

<!-- wp:group {"metadata":{"name":"Powwow Intro"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph -->
<p>A wa&#269;h&iacute;pi — powwow — is a Native American gathering focused on dance, song and family celebration. Powwows celebrate the connections to tradition and spirituality, to the Earth and to one another in a social, personal and spiritual meeting.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Powwows began mainly as religious ceremonies to gain wisdom from and give thanks to Wak&#341;&#225;&#331; T&#341;&#225;&#331;ka — Great Spirit. Though many of today's powwows have evolved into social and contest-oriented dances, religious and ceremonial dances are still performed.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Dancers in colorful regalia gracefully move around the circle, with the drum beat directing their movements. The tradition is passed from one generation to the next.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Attend a Powwow"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:media-text {"mediaType":"image","mediaWidth":32,"className":"is-style-rounded"} -->
<div class="wp-block-media-text is-stacked-on-mobile is-style-rounded" style="grid-template-columns:32% auto"><figure class="wp-block-media-text__media"><img src="<?php echo esc_url( stjo_asset( 'powwow-girl-purple-regalia.jpg' ) ); ?>" alt="A young dancer in a purple shawl marked with a beaded cross moves across the grass at the powwow"/></figure><div class="wp-block-media-text__content"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Attend a Powwow</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Each year, St. Joseph's hosts an annual weekend of Lakota culture on our Chamberlain, South Dakota campus.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>All people (including non-Indian people) are welcome to St. Joseph's Indian School's annual powwow. It is a valuable and fascinating cultural experience for those unfamiliar with the rich traditions of our Lakota brothers and sisters.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-arrow-link"} -->
<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link wp-element-button" href="/lakota-culture/powwow-dance/attend-a-powwow/">Plan Your Powwow Visit</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:media-text -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Women's Dance Styles"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Women's Dance Styles</h2>
<!-- /wp:heading -->

<!-- wp:group {"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Women began participating in the dance circle around 1953. Before that time, they were not permitted in the dance arena and stood in the background — usually behind the drums — and sang. Today, women have three styles of dance and regalia.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php echo $sp( 'medium' ); ?>

<?php
echo $style_block(
	'left',
	'womens-traditional-dance.jpg',
	'A young woman in deep red traditional regalia holds a folded shawl over her arm, standing before a bed of flowers',
	'Traditional Dance',
	array(
		'Native American women are regarded as the life-giving force that nurtures the next generation of youth. Traditional dancers wear long, beautiful buckskin dresses or trade cloth adorned with meaningful designs made from beads, animal teeth, quillwork, shells and ribbon.',
		'During the honor beats — the stronger, louder and slower beats heard in the song — they lift their feather fan to show their pride and appreciation for the Creator&#8217;s blessings.',
	)
);
echo $style_block(
	'right',
	'womens-fancy-shawl-dance.jpg',
	'A fancy shawl dancer spreads a bright pink fringed shawl wide like wings mid-dance',
	'Fancy Shawl Dance',
	array(
		'The first impression people often have of the women&#8217;s fancy dancers is that of butterflies. Dancers wear decorated shawls that complement a satin dress and knee-high beaded moccasins or decorated leggings.',
		'The faster pace of the drum challenges dancers to keep in time with the beat while coordinating their fancy footwork and graceful movements.',
	)
);
echo $style_block(
	'left',
	'womens-jingle-dress-dance.jpg',
	'A young jingle dress dancer in blue and teal regalia stands with her hands on her hips at the powwow',
	'Jingle Dress Dance',
	array(
		'The Jingle Dress Dance came by way of a holy man&#8217;s vision. In his dream, four girls wearing dresses adorned with tiny cones — which made a very distinct sound — danced for the healing of a sick little girl. Upon his awakening, he instructed his wife to make dresses and found girls to dance. The sick girl was healed!',
		'This dance style is very popular among young female dancers. You can hear them coming from a distance as their many metal cones make a unique jingle sound!',
	)
,
	32
);
?>

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Men's Dance Styles"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Men's Dance Styles</h2>
<!-- /wp:heading -->

<!-- wp:group {"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Long ago, the Lakota (Sioux) people would dance to celebrate the coming of spring and their relationship with the Earth. Dance was a form of prayer thanking Wak&#341;&#225;&#331; T&#341;&#225;&#331;ka — Great Spirit — for another year of life.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php echo $sp( 'medium' ); ?>

<?php
echo $style_block(
	'left',
	'mens-traditional-dance.jpg',
	'A traditional dancer in a bone breastplate and white feather headdress dances before a seated crowd',
	'Traditional Dance',
	array(
		'The men&#8217;s Traditional Dance provides an image of past warriors who would return from hunting or battle and tell their story through dance. The feathers worn by the dancers are arranged in a single bustle and worn on their lower back, symbolic of a dancer&#8217;s relationship with nature and connection to the Great Spirit.',
		'Some regalia takes years to complete; some is handed down through generations of a family and may be over 100 years old. Songs for this dance are sung at a slower pace as the words reflect the honor traditional dancers feel when asked to protect the people.',
	)
);
echo $style_block(
	'right',
	'mens-fancy-dance.jpg',
	'A fancy dancer in green, blue and white feather bustles steps out with his arms extended',
	'Fancy Dance',
	array(
		'This contemporary dance style is FAST, exciting and full of color! The Fancy Dance was introduced during the reservation era when tribes from the southern plains conducted large gatherings for spectators who wanted to witness a war dance.',
		'The dancers carry twirling spinners as they hop, jump, skip and perform acrobatic movements throughout the dance. The best dancers are able to keep in time to the extremely fast drumbeat and stop on the last beat of the drum.',
	)
);
echo $style_block(
	'left',
	'mens-grass-dance.jpg',
	'A grass dancer in orange and yellow fringed regalia turns mid-step on the powwow grounds',
	'Grass Dance',
	array(
		'The Grass Dance, or Omaha Dance, was originally a ceremonial dance to celebrate the people&#8217;s relationship with Mother Earth. The regalia is unique because it has almost no feathers; it consists of a shirt and trousers with colorful fringe attached.',
		'Today, the Grass Dance is very popular among younger dancers. The dancers try to emulate the movement of the grass blowing in the breezes of the Great Plains as they sway from side to side.',
	)
);
?>

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Powwow Etiquette FAQ"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><?php echo $sp( 'large' ); ?>

<!-- wp:paragraph {"align":"center","textColor":"brand-dark","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-brand-dark-color has-text-color">Before You Go</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Powwow Etiquette</h2>
<!-- /wp:heading -->

<!-- wp:group {"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Powwow etiquette at its most basic is simply the use of common courtesy and respect for others.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:group {"layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group"><?php echo $faq( $etiquette ); ?>

<?php echo $sp( 'medium' ); ?>

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons is-layout-flex is-content-justification-center"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="https://give.stjo.org/site/SPageNavigator/wp_powwow_booklet.html">Download the Free Powwow Booklet</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<?php echo $zigzag; ?>
