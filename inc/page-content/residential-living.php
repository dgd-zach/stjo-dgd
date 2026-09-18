<?php
/**
 * residential-living: "Residential Living" (Youth Programs section).
 *
 * Layout follows the Residential Living wireframe on the sitemap board
 * (uXjVHzJD47c, frame 3458764671959689702): title band, intro, YouTube video,
 * "Our Programs" (elementary and high school), a "Houseparents And Pets In
 * Homes program" section with a lightbox holding the rest of that page, a
 * Residential Living FAQ accordion, then an "Other Programs" card row. The
 * board's sticky says "Include copy from:
 * stjo.org/programs/residential-living/ & .../hapi-homes-program/", so those
 * two pages supply the copy verbatim; the video is the live Residential Living
 * page's embed. FAQ answers are lifted verbatim from stjo.org/about/faq/ (the
 * three questions about who lives here and what they do). Other Programs
 * reuses the Youth Programs hub's card copy and photos.
 *
 * Design pass (Sept 2026), copy unchanged, only regrouped:
 *
 * - The intro led with the 44-word residential philosophy in subhead type,
 *   which read as a wall. The short orienting sentence ("two Residential
 *   Living Programs on campus") is the subhead now and the philosophy is the
 *   body paragraph under it. Same two sentences, lighter first screen.
 * - "Our Programs" was two dense text columns, one of them photo-free. Each
 *   program is now a media & text subsection (photo beside the opening
 *   paragraphs, image left then image right) with the longer run continuing in
 *   a 768px group beneath, the same shape as Education & Cultural Awareness.
 * - "There are no dorms at St. Joseph's." is promoted out of the elementary
 *   paragraph into a pull quote on its own cream band. It is the page's
 *   thesis and both programs restate it. The rest of that paragraph reads
 *   cleanly without it, so no sentence is lost or duplicated.
 * - The HAPI section keeps its photo and lightbox trigger; the research
 *   paragraph moved below the media & text so the copy is not fighting a
 *   744x300 banner photo for height.
 * - Bands now alternate blue / white / cream all the way down, and no two
 *   text-only bands sit next to each other.
 *
 * Photos are Media Library only. The high school photo swapped from
 * 2018-Seniors.jpg (350px wide, upscaled badly in a media & text column) to
 * 10-Seniors.jpg (840px). Flag that swap with the client. The elementary and
 * Other Programs photos are the ones the Youth Programs hub (page 308) already
 * uses, so the section reads continuously from hub to page.
 *
 * Header, breadcrumbs, generosity pre-footer and footer are template parts,
 * not part of this content.
 *
 * Seed source only. Edit live content in the WP editor after seeding.
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

/**
 * Library lookup for a photo by filename. Seeded images carry
 * `_stjo_seed_source`; the Sept 2026 QA uploads do not, so fall back to the
 * attached-file name. $alt overrides the library alt when the stored value is
 * a title rather than a description.
 */
$img_of = function ( $file, $alt = '' ) {
	$img = function_exists( 'stjo_seeded_image' ) ? stjo_seeded_image( $file ) : array( 'id' => 0, 'url' => '' );
	$id  = (int) $img['id'];
	if ( ! $id && function_exists( 'stjo_seed_find_attachment_by_filename' ) ) {
		$id = (int) stjo_seed_find_attachment_by_filename( $file );
	}
	$url = $id ? (string) wp_make_link_relative( (string) wp_get_attachment_url( $id ) ) : (string) $img['url'];
	if ( '' === $alt && $id ) {
		$alt = (string) get_post_meta( $id, '_wp_attachment_image_alt', true );
	}
	return array(
		'id'  => $id,
		'url' => $url,
		'alt' => $alt,
	);
};

/** Media & text figure, as core serializes it. */
$figure = function ( $file, $alt = '' ) use ( $img_of ) {
	$img = $img_of( $file, $alt );
	$cls = $img['id'] ? ' class="wp-image-' . (int) $img['id'] . ' size-full"' : '';
	return '<figure class="wp-block-media-text__media"><img src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $img['alt'] ) . '"' . $cls . '/></figure>';
};

/** `"mediaId":N,` for the media & text attributes, empty when unresolved. */
$media_id = function ( $file ) use ( $img_of ) {
	$img = $img_of( $file );
	return $img['id'] ? '"mediaId":' . (int) $img['id'] . ',' : '';
};

/** Image-topped info card: photo, H3, blurb, arrow link (unique link text per card). */
$card = function ( $src, $alt, $title, $text, $href, $cta ) {
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:group {"className":"stjo-info-card"} --><div class="wp-block-group stjo-info-card">'
		. '<!-- wp:image {"sizeSlug":"large","className":"stjo-info-card__image"} -->'
		. '<figure class="wp-block-image size-large stjo-info-card__image"><img src="' . esc_url( $src ) . '" alt="' . esc_attr( $alt ) . '"/></figure>'
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

/** Yoast FAQ block styled as an accordion (faq-accordion.js upgrades it). */
$faq = function ( $items ) {
	$questions = array();
	$sections  = '';
	foreach ( $items as $i => $item ) {
		$id          = 'faq-residential-' . ( $i + 1 );
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
	// serialize_block_attributes() writes the JSON the editor would, so the
	// block round-trips instead of being flagged on reopen.
	$attrs = serialize_block_attributes( array( 'questions' => $questions, 'className' => 'is-style-accordion' ) );
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
		'How are students selected to attend St. Joseph&#8217;s?',
		'The children attending St. Joseph&#8217;s are here because their families want them to be here. Parents or guardians complete an application process with our admissions staff, which includes personal interviews with the student and their family.',
	),
	array(
		'Do you offer sports and extracurricular activities?',
		'Yes, St. Joseph&#8217;s offers all the same sports as a typical junior high school, including football, volleyball, basketball and track. Younger students can participate in introductory wrestling and gymnastics. Students of all ages can participate in archery through the National Archery in the Schools program (NASP). St. Joseph&#8217;s high school students attend Chamberlain High School, and can participate in all the activities offered there.',
	),
);

// Photos, with alt written here when the library value is a title, not a
// description. Elementary: the hub's own Residential Living photo. High
// school: 10-Seniors.jpg, big enough for a media & text column.
$elementary_photo = 'youth-programs-residential-living.jpg';
$elementary_alt   = ''; // library alt already describes the scene
$highschool_photo = '10-Seniors.jpg';
$highschool_alt   = 'High school students standing together outside Our Lady of the Sioux Chapel';
$hapi_photo       = '20210628-HapiHomesHeader.jpg';
$hapi_alt         = 'A student hugging a small terrier on the campus lawn';

// The HAPI lightbox trigger. Its body is the HAPI Homes content page (the dogs
// and the rest of stjo.org/hapi-homes-program/), maintained as a page.
$hapi_attrs = array(
	'title'     => 'Houseparents and Pets In Homes',
	'linkLabel' => 'Meet the HAPI Homes dogs',
	'className' => 'is-style-arrow-link',
);
$hapi_page  = function_exists( 'stjo_seed_find_page' ) ? stjo_seed_find_page( 'hapi-homes' ) : null;
if ( $hapi_page ) {
	$hapi_attrs['contentPageId'] = (int) $hapi_page->ID;
}
?>
<!-- wp:group {"metadata":{"name":"Page Title Band"},"align":"full","textColor":"white","className":"stjo-page-title-band","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-page-title-band has-white-color has-text-color"><?php echo $sp( 'large' ); ?>

<!-- wp:paragraph {"align":"center","textColor":"yellow","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-yellow-color has-text-color">Youth Programs</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":1,"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">Residential Living</h1>
<!-- /wp:heading -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">St. Joseph’s Indian School has two Residential Living Programs on campus — the elementary program and the high school program. Both are tailored to meet the needs of the Lakota (Sioux) students in our care.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>St. Joseph’s Indian School’s residential philosophy is to develop collaborative relationships with students and families using a strength-based, developmental approach that teaches life skills and fosters lifelong learning through the values of belonging, mastery, independence and generosity.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Video"},"align":"full","backgroundColor":"light","className":"stjo-heading-video","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-heading-video has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:embed {"url":"https://www.youtube.com/watch?v=irnK7sgmMGs","type":"video","providerNameSlug":"youtube","responsive":true,"className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
<figure class="wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">
https://www.youtube.com/watch?v=irnK7sgmMGs
</div></figure>
<!-- /wp:embed -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Our Programs"},"align":"full","layout":{"type":"constrained"},"anchor":"our-programs"} -->
<div class="wp-block-group alignfull" id="our-programs"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Our Programs</h2>
<!-- /wp:heading -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:media-text {<?php echo $media_id( $elementary_photo ); ?>"mediaType":"image","className":"is-style-rounded"} -->
<div class="wp-block-media-text is-stacked-on-mobile is-style-rounded"><?php echo $figure( $elementary_photo, $elementary_alt ); ?><div class="wp-block-media-text__content"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Elementary Program</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>St. Joseph’s Indian School provides a nationally-accredited home-away-from-home for Native American children in grades one through 12. Native American families bring youngsters to St. Joseph’s knowing their children will be safe, loved and cared for in every way.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Some children may only spend a year or two at St. Joseph’s. However, for others, St. Joseph’s may be home seven days a week for years. Thanks to gifts from friends around the globe, St. Joseph’s has been lending a helping hand since 1927.</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:media-text -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:group {"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><!-- wp:paragraph -->
<p>Children live in one of our campus homes with two specially-trained houseparents. They live and play together as any family would. The boys and girls learn life skills from personal health and home finances to communication and teamwork.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Beyond these life skills, children learn how to embrace their culture and spirituality in everyday living. Family prayers, Lakota traditions and more are routine parts of daily life.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:media-text {"mediaPosition":"right",<?php echo $media_id( $highschool_photo ); ?>"mediaType":"image","className":"is-style-rounded"} -->
<div class="wp-block-media-text has-media-on-the-right is-stacked-on-mobile is-style-rounded"><div class="wp-block-media-text__content"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">High School Program</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Less than 50% of South Dakota’s Native American students graduate from high school, according to the Native American Student Achievement Advisory Council.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>To begin addressing this problem, St. Joseph’s began testing a high school initiative in 1976. Today, a collaborative partnership exists between St. Joseph’s Indian School and Chamberlain High School.</p>
<!-- /wp:paragraph --></div><?php echo $figure( $highschool_photo, $highschool_alt ); ?></div>
<!-- /wp:media-text -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:group {"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><!-- wp:paragraph -->
<p>After eighth grade graduation, St. Joseph’s students may choose to pursue our high school program. Young adults complete an application and interview process to ensure they are open to accepting the opportunities and guidance they will be given through St. Joseph’s High School Program. Once accepted, students live on St. Joseph’s campus in residential homes — not dorms. Each home is run by two specially-trained houseparents. Just like St. Joseph’s younger children, our high school students are expected to be participants in family living.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>There are responsibilities from preparing a meal to completing homework independently. Students have the freedom they need to grow, learn and thrive; however, they also abide by rules and expectations intended to protect them.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Unlike elementary age children, high school students do not attend school on campus. Instead, St. Joseph’s high school students attend the local public high school. We work closely with Chamberlain High School to ensure each of our high school students has the same opportunity to thrive in a public school setting.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>High school homes are extremely busy with students working part-time jobs after school, participating in extra-curricular activities and preparing for higher education.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"No Dorms Quote"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:group {"layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group"><!-- wp:pullquote {"className":"stjo-pull-quote"} -->
<figure class="wp-block-pullquote stjo-pull-quote"><blockquote><p>There are no dorms at St. Joseph’s.</p></blockquote></figure>
<!-- /wp:pullquote --></div>
<!-- /wp:group -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Houseparents and Pets In Homes"},"align":"full","layout":{"type":"constrained"},"anchor":"hapi-homes"} -->
<div class="wp-block-group alignfull" id="hapi-homes"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Houseparents And Pets In Homes program</h2>
<!-- /wp:heading -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:media-text {<?php echo $media_id( $hapi_photo ); ?>"mediaType":"image","className":"is-style-rounded"} -->
<div class="wp-block-media-text is-stacked-on-mobile is-style-rounded"><?php echo $figure( $hapi_photo, $hapi_alt ); ?><div class="wp-block-media-text__content"><!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">Homes are always happier when one of the members of the family walks on four legs, right? We sure think so!</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>St. Joseph’s is proud to have the Houseparents and Pets In (HAPI) Homes program on campus. The program launched in 2017 with three dogs. We are happy to say the program gets a little “HAPI-er” every year, and keeps expanding over time.</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:media-text -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:group {"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><!-- wp:paragraph -->
<p>Research shows dogs support psychological growth while increasing social skills and self-esteem in children. They provide emotional support and may decrease anxiety, which in turn has the potential to increase overall academic achievement. HAPI Homes also teaches students the responsibility that goes into caring for an animal.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"layout":{"type":"flex","justifyContent":"left"}} -->
<div class="wp-block-group"><!-- wp:stjo/lightbox-card <?php echo serialize_block_attributes( $hapi_attrs ); ?> /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Residential Living FAQ"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"},"anchor":"faq"} -->
<div class="wp-block-group alignfull has-light-background-color has-background" id="faq"><?php echo $sp( 'large' ); ?>

<!-- wp:paragraph {"align":"center","textColor":"brand-dark","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-brand-dark-color has-text-color">Questions</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Residential Living FAQ</h2>
<!-- /wp:heading -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:group {"layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group"><?php echo $faq( $faq_items ); ?></div>
<!-- /wp:group -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Other Programs"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Other Programs</h2>
<!-- /wp:heading -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:columns {"className":"stjo-related-cards"} -->
<div class="wp-block-columns stjo-related-cards">
<?php
echo $card(
	'/wp-content/uploads/2026/09/youth-programs-education-cultural.jpg',
	'A student holds a bundle of freshly harvested sweetgrass overhead in the campus garden',
	'Education &amp; Cultural Awareness',
	'From elementary school through high school, our Native American students grow up on campus with the steady support of live in houseparents. Alongside academics, they carry their Lakota culture forward through powwows, drum group, hand games and daily traditions woven into everyday life at St. Joseph’s.',
	'/youth-programs/education-cultural-awareness/',
	'Explore Education &amp; Cultural Awareness'
);
echo $card(
	'/wp-content/uploads/2026/09/youth-programs-family-integration.jpg',
	'A student hugs a family member in the chapel before a decorated altar',
	'Family Integration',
	'St. Joseph’s stays closely connected to every student’s family, through personal visits, calls, letters and regular check ins throughout the year. Our staff listens, identifies needs and connects families to helpful resources, both on campus and in their home communities.',
	'/youth-programs/family-integration/',
	'Explore Family Integration'
);
echo $card(
	'/wp-content/uploads/2026/09/youth-programs-student-health.jpg',
	'A nurse checks a student’s ankle in an exam room at the campus health center',
	'Student Health',
	'Five full-time nurses and a physician on campus, four days a week, cover our students’ physical health, with the local hospital close by for anything more serious. Every student also has a master’s level mental health counselor, working one on one with them and their family.',
	'/youth-programs/student-health/',
	'Explore Student Health'
);
?>
</div>
<!-- /wp:columns -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
