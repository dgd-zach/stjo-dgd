<?php
/**
 * education-cultural-awareness — "Education & Cultural Awareness" (Youth
 * Programs section).
 *
 * Layout follows the Education, Culture & Awareness wireframe on the sitemap
 * board (uXjVHzJD47c, frame 3458764671962869885): title band, intro
 * paragraph, YouTube video, an "Education" media-text (image left), a
 * "Culture & Awareness" media-text (image right), then an "Education & Lakota
 * Culture" card row: Lakota Language, Religious Education, Cultural Trip.
 * The sitemap folds two stjo.org pages into this one, so the copy is
 * programs/native-american-education/ (with its video and classroom photo)
 * and programs/native-american-cultural-awareness/, verbatim, split across
 * the two media-texts with the longer runs continuing beneath each. The
 * Culture photo is the hub's approved Cultural Awareness card image.
 *
 * Cards: Lakota Language is the Lakota Culture page (the board's sticky says
 * the two are one page); Religious Education and Cultural Trip are the
 * tertiary pages the sitemap hangs under this one (seed-pages.php creates
 * them as stubs) with blurbs from their live pages. Hannah's sticky asks for
 * the "Lakota Word Wednesday" YouTube series to be linked here; the sitemap's
 * Lakota Language sticky carries that playlist URL, so it rides the Lakota
 * Language card. Aktá and Inípi keep their accents per the house rule.
 *
 * Header, breadcrumbs, generosity pre-footer and footer are template parts.
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

$figure = function ( $file ) {
	$img = stjo_seeded_image( $file );
	$alt = $img['id'] ? (string) get_post_meta( $img['id'], '_wp_attachment_image_alt', true ) : '';
	$cls = $img['id'] ? ' class="wp-image-' . (int) $img['id'] . ' size-full"' : '';
	return '<figure class="wp-block-media-text__media"><img src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '"' . $cls . '/></figure>';
};
$media_id = function ( $file ) {
	$img = stjo_seeded_image( $file );
	return $img['id'] ? '"mediaId":' . (int) $img['id'] . ',' : '';
};

/**
 * Text info card (the wireframe's "Item / short description / Learn more").
 * $extra is an optional second arrow link (label, href).
 */
$card = function ( $title, $text, $href, $cta, $extra = null ) {
	$links = '<!-- wp:button {"textColor":"blue-900","className":"is-style-arrow-link"} -->'
		. '<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link has-blue-900-color has-text-color wp-element-button" href="' . esc_url( $href ) . '">' . $cta . '</a></div>'
		. '<!-- /wp:button -->';
	if ( $extra ) {
		$links .= '<!-- wp:button {"textColor":"blue-900","className":"is-style-arrow-link"} -->'
			. '<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link has-blue-900-color has-text-color wp-element-button" href="' . esc_url( $extra[1] ) . '">' . $extra[0] . '</a></div>'
			. '<!-- /wp:button -->';
	}
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:group {"className":"stjo-info-card"} --><div class="wp-block-group stjo-info-card">'
		. '<!-- wp:group {"className":"stjo-info-card__body"} --><div class="wp-block-group stjo-info-card__body">'
		. '<!-- wp:heading {"level":3,"textColor":"blue-900"} -->'
		. '<h3 class="wp-block-heading has-blue-900-color has-text-color">' . $title . '</h3>'
		. '<!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>' . $text . '</p><!-- /wp:paragraph -->'
		. '<!-- wp:buttons --><div class="wp-block-buttons">' . $links . '</div><!-- /wp:buttons -->'
		. '</div><!-- /wp:group -->'
		. '</div><!-- /wp:group -->'
		. '</div><!-- /wp:column -->';
};

$powwow = '/lakota-culture/powwow-dance/attend-a-powwow/';
?>
<!-- wp:group {"metadata":{"name":"Page Title Band"},"align":"full","textColor":"white","className":"stjo-page-title-band","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-page-title-band has-white-color has-text-color"><?php echo $sp( 'large' ); ?>

<!-- wp:paragraph {"align":"center","textColor":"yellow","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-yellow-color has-text-color">Youth Programs</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":1,"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">Education &amp; Cultural Awareness</h1>
<!-- /wp:heading -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">St. Joseph’s Indian School’s education philosophy is student-centered. Through a variety of instructional methods, we strive to develop a love for learning; instill habits of mind and heart; and promote culture and faith.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Educating the Native American children in the classroom is a core part of St. Joseph’s mission – partnering with Native American children and families to educate for life — <em>mind, body, heart and spirit</em>.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Preserving and sharing the Native American culture and tradition is a core part of our mission at St. Joseph’s Indian School. In addition to having our students learn about their Native American culture in our Native American Studies classes, the <a href="https://aktalakota.stjo.org/">Akt&aacute; Lakota Museum &amp; Cultural Center</a> is located on campus for students and visitors alike to broaden their knowledge of the Sioux culture, more specifically known by their dialects — Lakota, Dakota and Nakota.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Video"},"align":"full","backgroundColor":"light","className":"stjo-heading-video","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-heading-video has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:embed {"url":"https://www.youtube.com/watch?v=Y_HrPhnkCeM","type":"video","providerNameSlug":"youtube","responsive":true,"className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
<figure class="wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">
https://www.youtube.com/watch?v=Y_HrPhnkCeM
</div></figure>
<!-- /wp:embed -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Education"},"align":"full","layout":{"type":"constrained"},"anchor":"education"} -->
<div class="wp-block-group alignfull" id="education"><?php echo $sp( 'large' ); ?>

<!-- wp:media-text {<?php echo $media_id( 'qualityEducation2.jpg' ); ?>"mediaType":"image","className":"is-style-rounded"} -->
<div class="wp-block-media-text is-stacked-on-mobile is-style-rounded"><?php echo $figure( 'qualityEducation2.jpg' ); ?><div class="wp-block-media-text__content"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Education</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Children attending St. Joseph’s benefit from individualized care plans that follow nationally approved and accredited techniques. Friends who support St. Joseph’s help provide for every aspect of each child’s physical, emotional, spiritual and educational needs.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Our Native American Education Program splits our 200+ students into two groups — elementary and high school.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Children in elementary grades one through eight attend classes at St. Joseph’s Elementary School on campus. Children benefit from small class sizes (approximately 12 students per classroom) and one-on-one attention. Technology is carefully integrated into the learning environment!</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:media-text -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:group {"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><!-- wp:paragraph -->
<p>After eighth grade, students can enroll in St. Joseph’s High School Program. With capacity to care for 50 Lakota (Sioux) high school students, St. Joseph’s partners with Chamberlain High School to give our students a multitude of opportunities to participate in sports, fine arts and extra-curricular activities. In addition, they still live on St. Joseph’s campus with our specially trained houseparents.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>According to the South Dakota Department of Education, just 52% of Native American students completed high school on-time during the 2024-25 school year. In contrast, St. Joseph’s Indian School sees a graduation rate of approximately 96%. This alarming difference solidifies the importance of education starting in elementary years. Children at St. Joseph’s are encouraged to be passionate about their education through special awards, recognition and fun activities.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Integrating the children’s Native American heritage into their classroom experience also plays an important role in their healing and growth. Native American Studies is a required course for all St. Joseph’s students; in this course, children find themselves immersed in culture by learning the Lakota language, traditional stories and more.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Additionally, Native American beliefs and philosophies are incorporated into core education classes like math, science and language arts through careful lesson planning and the WoLakota program. Embracing diversity and cultural understanding lends a new perspective, which can be beneficial to students and staff of any background.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Culture and Awareness"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"},"anchor":"culture-awareness"} -->
<div class="wp-block-group alignfull has-light-background-color has-background" id="culture-awareness"><?php echo $sp( 'large' ); ?>

<!-- wp:media-text {"mediaPosition":"right","mediaType":"image","className":"is-style-rounded"} -->
<div class="wp-block-media-text has-media-on-the-right is-stacked-on-mobile is-style-rounded"><div class="wp-block-media-text__content"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Culture &amp; Awareness</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">St. Joseph’s Indian School’s cultural philosophy is to celebrate and embrace our students Native American heritage through the study and practice of language, ceremony, dance and other activities.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a href="<?php echo esc_url( $powwow ); ?>">Our annual powwow</a>, held each September, gives visitors the unique opportunity to learn about our students’ Native American culture and traditions.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Appreciation of the children’s cultural heritage is infused in our daily routine both at school and in the homes. St. Joseph’s Native American Studies classes focus on Lakota language, culture and traditions. We have cultural experts on staff who teach about the Native American culture and also regularly invite Lakota (Sioux) elders and advisors to assist with ceremonies like In&iacute;pi, a purification rite, on campus. A variety of educational opportunities are provided for both students and staff alike.</p>
<!-- /wp:paragraph --></div><figure class="wp-block-media-text__media"><img src="/wp-content/uploads/2026/08/Youth-Programs-Cultural-Awareness.jpg" alt="Youth Programs Cultural Awareness"/></figure></div>
<!-- /wp:media-text -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:group {"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><!-- wp:paragraph -->
<p>In addition to Native American Studies classes, classroom teachers strive to include the Lakota language and culturally appropriate material in the regular curriculum. Each morning begins with prayer, singing of the flag song, weather announcements and the “Word for the Week” — all in Lakota. An additional cultural activity takes place one Wednesday each month.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Furthermore, St. Joseph’s Indian School participates in the WoLakota Project. This project is an educational program that provides an opportunity for cultural understanding and exchange in the classroom. With education and the support of a mentor, all teachers are given the tools to connect with their Native American students culturally, not just through a lesson, but in how a lesson is taught. Sharmel, St. Joseph’s Principal explains, “This program provides a cultural thread in the classroom. With 100% of our students being Native American, it’s particularly important at St. Joseph’s that we do everything we can to help students embrace their heritage.”</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">Students have the opportunity to take part in numerous cultural activities:</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>A dance club teaches traditional Native American dance and songs</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Powwow competitions, including <a href="<?php echo esc_url( $powwow ); ?>">St. Joseph’s annual powwow</a> held on campus each year</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Traditional drum group</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Lakota language competitions, like the Lakota Nation Invitational</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Hand games</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Sweat lodge ceremonies (also known as In&iacute;pi)</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Cultural trips</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>And much, much more!</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Education and Lakota Culture"},"align":"full","layout":{"type":"constrained"},"anchor":"education-lakota-culture"} -->
<div class="wp-block-group alignfull" id="education-lakota-culture"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Education &amp; Lakota Culture</h2>
<!-- /wp:heading -->

<!-- wp:columns {"className":"stjo-related-cards"} -->
<div class="wp-block-columns stjo-related-cards">
<?php
echo $card(
	'Lakota Language',
	'Preserving and sharing the Lakota culture is a core part of our mission at St. Joseph’s Indian School and traditional Lakota language is a vital part of that effort.',
	'/lakota-culture/lakota-language/',
	'Explore Lakota Language',
	array( 'Watch Lakota Word Wednesday', 'https://www.youtube.com/playlist?list=PLBkGQb4TQK3pl0AEzYvVOsKBuLZl4xqdF' )
);
echo $card(
	'Religious Education',
	'Though St. Joseph’s Indian School is affiliated with the Catholic Church through the Priests of the Sacred Heart, we welcome children of all faiths, recognizing the dignity of each human person created in God’s image.',
	'/youth-programs/education-cultural-awareness/religious-education/',
	'Explore Religious Education'
);
echo $card(
	'Cultural Trip',
	'St. Joseph’s incoming eighth-grade class participates in a week-long cultural trip to Native American sites of cultural, spiritual and historical significance in South Dakota, North Dakota, Montana and Wyoming.',
	'/youth-programs/education-cultural-awareness/cultural-trip/',
	'Explore the Cultural Trip'
);
?>
</div>
<!-- /wp:columns -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
