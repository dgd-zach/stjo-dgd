<?php
/**
 * our-children: "About Our Children" (About section).
 *
 * Same slots as the About Our Children wireframe on the sitemap board
 * (uXjVHzJD47c, frame 3458764674147056179) and the same copy, from
 * stjo.org/native-american-children/, redesigned in 2026-09 because the page
 * read as four walls of text with one small photo. Nothing was written and no
 * number was invented: every sentence that was on the page is still on it, and
 * the only string added anywhere is the H2 "Our Lakota Students", a label made
 * from the copy's own phrase ("many of our Lakota students...") so the
 * demographics band has a heading. Flag it with the client.
 *
 * What moved, and why:
 *
 * - The admissions facts split. "Students are not required to be Catholic..."
 *   and "To be admitted..." open the page beside a photo; the two counting
 *   sentences ("21 homes", "over 100... on our waiting list") became the two
 *   Stat Figures that close the demographics band, each keeping its whole
 *   sentence as the label (the idiom annual-financial-report.php uses).
 * - "But there is hope ... mind, body, heart and spirit." was the first
 *   sentence of the Family and Culture column. It is now the full-bleed photo
 *   band between the hardship list and the hope sections, which is the turn
 *   the page makes anyway. Its paragraph keeps the rest of its sentences.
 * - Family and Culture now runs before Education and Services, so "there is
 *   hope" lands on the heritage passage rather than on a gift list.
 * - The two Accountability cards are unchanged apart from photos.
 *
 * Bands alternate white / light and no two text-only bands sit together:
 * title (blue), intro + photo (white), demographics + stats (light), hope
 * photo band, A future for Lakota children (white, two media & text),
 * Student Stories (light), Accountability cards (white).
 *
 * Photos are Media Library only. OurChildren1.jpg, the live page's own photo,
 * is published at 350px wide and went soft in a half-width column, so the
 * 2026/09 campus set is used instead; alt text comes from the library except
 * on seasonal-ourChildren.jpg, which has none there.
 *
 * The two Accountability cards point at the tertiary pages the sitemap places
 * under Accountability & Reports (seed-pages.php creates them as stubs); their
 * blurbs come from those live pages. The Student Stories blurb is the Your
 * Impact page's own card copy. Header, breadcrumbs, generosity pre-footer and
 * footer are template parts, not part of this content.
 *
 * Seed source only. Edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require __DIR__ . '/_helpers.php'; // $sp, $title_band, $card, $card_rows

/**
 * Media Library photo by filename: id, root-relative URL and the library's own
 * alt. The 2026/09 campus uploads carry _stjo_qa_source rather than
 * _stjo_seed_source, so stjo_seeded_image() cannot see them; matching on the
 * filename resolves the same photo on local and staging without an ID in the
 * markup. Falls back to stjo_seeded_image() for the seeded set.
 */
$photo = function ( $file ) {
	$id = function_exists( 'stjo_seed_find_attachment_by_filename' ) ? (int) stjo_seed_find_attachment_by_filename( $file ) : 0;
	if ( ! $id ) {
		$seeded = stjo_seeded_image( $file );
		$id     = (int) $seeded['id'];
	}
	if ( ! $id ) {
		return array( 'id' => 0, 'url' => '', 'alt' => '' );
	}
	return array(
		'id'  => $id,
		'url' => (string) wp_make_link_relative( (string) wp_get_attachment_url( $id ) ),
		'alt' => (string) get_post_meta( $id, '_wp_attachment_image_alt', true ),
	);
};

/**
 * Media & text subsection. $side is 'left' or 'right' (the photo's side);
 * $body is the already-serialized blocks for the text column. Serializes the
 * way core does: content div first when the media is on the right.
 */
$media_text = function ( $file, $side, $body, $alt_override = '' ) use ( $photo ) {
	$img    = $photo( $file );
	$alt    = $alt_override ? $alt_override : $img['alt'];
	$figure = '<figure class="wp-block-media-text__media"><img src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '"'
		. ( $img['id'] ? ' class="wp-image-' . (int) $img['id'] . ' size-full"' : '' ) . '/></figure>';
	$content = '<div class="wp-block-media-text__content">' . $body . '</div>';
	$attrs   = ( 'right' === $side ? '"mediaPosition":"right",' : '' )
		. ( $img['id'] ? '"mediaId":' . (int) $img['id'] . ',' : '' )
		. '"mediaType":"image","className":"is-style-rounded"';
	$classes = 'wp-block-media-text' . ( 'right' === $side ? ' has-media-on-the-right' : '' ) . ' is-stacked-on-mobile is-style-rounded';
	return '<!-- wp:media-text {' . $attrs . '} -->' . "\n"
		. '<div class="' . $classes . '">' . ( 'right' === $side ? $content . $figure : $figure . $content ) . '</div>' . "\n"
		. '<!-- /wp:media-text -->' . "\n";
};

/** One impact figure over its label, as a column (annual-financial-report idiom). */
$stat = function ( $value, $label ) {
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:stjo/stat-figure {"value":"' . esc_attr( $value ) . '"} /-->'
		. '<!-- wp:paragraph {"align":"center","className":"stjo-stat__label"} -->'
		. '<p class="has-text-align-center stjo-stat__label">' . $label . '</p>'
		. '<!-- /wp:paragraph -->'
		. '</div><!-- /wp:column -->';
};

echo $title_band( 'About', 'About Our Children' );

// The hope band's photo: the only one on the page with no alt in the library.
$hope     = $photo( 'seasonal-ourChildren.jpg' );
$hope_alt = 'Two students sitting on the grass outside, painting a clay flower pot together';
$hope_att = serialize_block_attributes( array_filter( array(
	'url'                => $hope['url'],
	'id'                 => (int) $hope['id'],
	'alt'                => $hope_alt,
	'dimRatio'           => 80,
	'overlayColor'       => 'blue-900',
	'isUserOverlayColor' => true,
	'focalPoint'         => array( 'x' => 0.34, 'y' => 0.5 ),
	'minHeight'          => 418,
	'metadata'           => array( 'name' => 'There Is Hope' ),
	'align'              => 'full',
	'layout'             => array( 'type' => 'constrained' ),
) ) );
?>
<!-- wp:group {"metadata":{"name":"Intro"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><?php echo $sp( 'medium' ); ?>

<?php
echo $media_text(
	'youth-programs-rising-eagle-day-camp.jpg',
	'right',
	'<!-- wp:paragraph {"className":"stjo-subhead"} -->' . "\n"
	. '<p class="stjo-subhead">Students are not required to be Catholic to attend St. Joseph&#8217;s, though over half are.</p>' . "\n"
	. '<!-- /wp:paragraph -->' . "\n\n"
	. '<!-- wp:paragraph -->' . "\n"
	. '<p>To be admitted, children must be of Native American heritage, and be in grades one through 12.</p>' . "\n"
	. '<!-- /wp:paragraph -->'
);
?>

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Our Lakota Students"},"align":"full","backgroundColor":"light","className":"stjo-stats","layout":{"type":"constrained"},"anchor":"our-lakota-students"} -->
<div class="wp-block-group alignfull stjo-stats has-light-background-color has-background" id="our-lakota-students"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Our Lakota Students</h2>
<!-- /wp:heading -->

<!-- wp:group {"layout":{"type":"constrained","contentSize":"860px"}} -->
<div class="wp-block-group"><!-- wp:paragraph -->
<p>Admission is based on need, and many of our Lakota students have the following characteristics:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>Approximately 23.2% of our American Indian students are enrolled in the Lower Brule Tribe, 16.6% in the Crow Creek Tribe, and 22.7% in the Rosebud Indian Tribe.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Many of our Native American students come from very poor families. The median household income on the Lower Brule Reservation is $20,263 and $13,750 on the Crow Creek Reservation.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Approximately 58.8% of our Lakota students are Catholic, 16.1% are Episcopal, 8.1% practice traditional Lakota spirituality and 14.7% have no religious affiliation.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Suicide is an epidemic for Native American youth. In fact, for several years, suicide has been the second leading cause of death (behind unintentional injuries) and the suicide rate for Indian youth aged 15-24 has been at or near four times the national average.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Native American children&#8217;s exposure to substance abuse and domestic violence is increasing. Approximately 55% of our students have been exposed to drug and alcohol use and 41% have witnessed domestic violence.</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:group {"layout":{"type":"constrained","contentSize":"820px"}} -->
<div class="wp-block-group"><!-- wp:columns {"className":"stjo-stats__row"} -->
<div class="wp-block-columns stjo-stats__row"><?php
echo $stat( '21', 'Native American students fill our school&#8217;s 21 homes.' );
echo $stat( '100+', 'Unfortunately, over 100 other American Indian youth are on our waiting list.' );
?></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:cover <?php echo $hope_att; ?> -->
<div class="wp-block-cover alignfull" style="min-height:418px"><img class="wp-block-cover__image-background<?php echo $hope['id'] ? ' wp-image-' . (int) $hope['id'] : ''; ?>" alt="<?php echo esc_attr( $hope_alt ); ?>" src="<?php echo esc_url( $hope['url'] ); ?>" style="object-position:34% 50%" data-object-fit="cover" data-object-position="34% 50%"/><span aria-hidden="true" class="wp-block-cover__background has-blue-900-background-color has-background-dim-80 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"align":"center","textColor":"white","fontSize":"large"} -->
<p class="has-text-align-center has-white-color has-text-color has-large-font-size">But there is hope &hellip; since 1927, St. Joseph&#8217;s Indian School has been working with Native American youth and their families to educate and support for life &mdash; mind, body, heart and spirit.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->

<!-- wp:group {"metadata":{"name":"A Future for Lakota Children"},"align":"full","layout":{"type":"constrained"},"anchor":"a-future-for-lakota-children"} -->
<div class="wp-block-group alignfull" id="a-future-for-lakota-children"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">A future for Lakota children</h2>
<!-- /wp:heading -->

<?php echo $sp( 'medium' ); ?>

<?php
echo $media_text(
	'youth-programs-residential-living.jpg',
	'left',
	'<!-- wp:heading {"level":3} -->' . "\n"
	. '<h3 class="wp-block-heading">Family and Culture</h3>' . "\n"
	. '<!-- /wp:heading -->' . "\n\n"
	. '<!-- wp:paragraph -->' . "\n"
	. '<p>For over 90 years, reaching out to American Indian youth on Indian reservations and beyond has remained our top priority.</p>' . "\n"
	. '<!-- /wp:paragraph -->' . "\n\n"
	. '<!-- wp:paragraph -->' . "\n"
	. '<p>By supporting St. Joseph&#8217;s Indian School, you are helping Native American students in need regain pride in their Lakota (Sioux) heritage by learning the Lakota language, studying Native American culture and learning ways they can grow and prosper.</p>' . "\n"
	. '<!-- /wp:paragraph -->'
);

echo $sp( 'medium' ) . "\n\n";

echo $media_text(
	'youth-programs-education-cultural.jpg',
	'right',
	'<!-- wp:heading {"level":3} -->' . "\n"
	. '<h3 class="wp-block-heading">Education and Services</h3>' . "\n"
	. '<!-- /wp:heading -->' . "\n\n"
	. '<!-- wp:paragraph -->' . "\n"
	. '<p>Tax-deductible gifts help our Lakota students receive:</p>' . "\n"
	. '<!-- /wp:paragraph -->' . "\n\n"
	. '<!-- wp:list -->' . "\n"
	. '<ul class="wp-block-list"><!-- wp:list-item -->' . "\n"
	. '<li>A safe, stable home away from reservation hardships</li>' . "\n"
	. '<!-- /wp:list-item -->' . "\n\n"
	. '<!-- wp:list-item -->' . "\n"
	. '<li>Individual counseling and guidance</li>' . "\n"
	. '<!-- /wp:list-item -->' . "\n\n"
	. '<!-- wp:list-item -->' . "\n"
	. '<li>Carefully planned curriculum based on Lakota (Sioux) culture and individual student needs</li>' . "\n"
	. '<!-- /wp:list-item -->' . "\n\n"
	. '<!-- wp:list-item -->' . "\n"
	. '<li>Tools to help build confidence, boost self-esteem and improve cultural awareness</li>' . "\n"
	. '<!-- /wp:list-item -->' . "\n\n"
	. '<!-- wp:list-item -->' . "\n"
	. '<li>And MORE to help our students believe in a productive, possibility-filled future!</li>' . "\n"
	. '<!-- /wp:list-item --></ul>' . "\n"
	. '<!-- /wp:list -->' . "\n\n"
	. '<!-- wp:paragraph -->' . "\n"
	. '<p>Thank you for your support.</p>' . "\n"
	. '<!-- /wp:paragraph -->'
);
?>

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Student Stories"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"},"anchor":"student-stories"} -->
<div class="wp-block-group alignfull has-light-background-color has-background" id="student-stories"><?php echo $sp( 'large' ); ?>

<?php
echo $media_text(
	'youth-programs-college-scholarships.jpg',
	'left',
	'<!-- wp:heading {"level":2} -->' . "\n"
	. '<h2 class="wp-block-heading">Student Stories</h2>' . "\n"
	. '<!-- /wp:heading -->' . "\n\n"
	. '<!-- wp:paragraph -->' . "\n"
	. '<p>Meet the Native American students who call St. Joseph&#8217;s home, from elementary school through high school graduation.</p>' . "\n"
	. '<!-- /wp:paragraph -->' . "\n\n"
	. '<!-- wp:buttons -->' . "\n"
	. '<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-arrow-link"} -->' . "\n"
	. '<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link wp-element-button" href="/student-stories/">Read Student Stories</a></div>' . "\n"
	. '<!-- /wp:button --></div>' . "\n"
	. '<!-- /wp:buttons -->'
);
?>

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Accountability"},"align":"full","layout":{"type":"constrained"},"anchor":"accountability"} -->
<div class="wp-block-group alignfull" id="accountability"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Accountability</h2>
<!-- /wp:heading -->

<?php echo $sp( 'medium' ); ?>

<?php
$bill    = $photo( 'powwow-young-women-regalia.jpg' );
$protect = $photo( 'youth-programs-student-health.jpg' );
echo $card_rows(
	array(
		$card(
			'Student Bill of Rights',
			'Equal treatment, the necessities of life, freedom of expression, protection from abuse, medical and dental care, religious freedom, education and recreation, and more: the rights every St. Joseph&#8217;s student is guaranteed.',
			'/about/accountability-reports/student-bill-of-rights/',
			'Read the Student Bill of Rights',
			$bill['url'],
			$bill['alt']
		),
		$card(
			'Protecting Students',
			'Inappropriate behavior is unequivocally not tolerated at St. Joseph&#8217;s Indian School. A zero-tolerance policy is strictly enforced throughout our programs.',
			'/about/accountability-reports/protecting-students/',
			'How We Protect Students',
			$protect['url'],
			$protect['alt']
		),
	),
	2
);
?>
<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
