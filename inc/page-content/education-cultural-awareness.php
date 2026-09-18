<?php
/**
 * education-cultural-awareness: "Education & Cultural Awareness" (Youth
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
 * the two media-texts with the longer runs continuing beneath each.
 *
 * 2026-09-17 design pass. The page was 957 words of near-solid prose with two
 * photos, so the wireframe's slots stayed but the long runs were redistributed
 * and nothing was rewritten: every sentence of the client's copy is still on
 * the page, re-grouped only.
 *
 *   - The museum paragraph moved out of the intro and now opens Culture &
 *     Awareness beside a photo of students at the Akt&aacute; Lakota Museum, which is
 *     what the sentence is about. The intro keeps the education philosophy.
 *   - The four numbers already in the copy (200+ students, ~12 per classroom,
 *     the state's 52% on-time graduation rate, St. Joseph's 96%) are a count-up
 *     stats band on the sand ground, each labelled with its own verbatim
 *     sentence, the same shape as the Annual Financial Report band. 52% and
 *     96% sit side by side because the copy's point is the contrast.
 *   - The Native American Studies / WoLakota runs became one "in the classroom"
 *     band, and Sharmel's quote inside the WoLakota paragraph is promoted to a
 *     pull quote (her title carries into the citation, as on the Strategic Plan
 *     page).
 *   - The eight cultural activities run three columns wide, in their original
 *     order, so they scan instead of trailing down one narrow rail.
 *   - The three cards gained photos. "Watch Lakota Word Wednesday" left the
 *     Lakota Language card and is now the row's CTA: an info card stretches one
 *     link across the whole tile, so a second link inside it fought the first.
 *
 * Cards: Lakota Language is the Lakota Culture page (the board's sticky says
 * the two are one page); Religious Education and Cultural Trip are the
 * tertiary pages the sitemap hangs under this one (seed-pages.php creates
 * them as stubs) with blurbs from their live pages. Hannah's sticky asks for
 * the "Lakota Word Wednesday" YouTube series to be linked here; the sitemap's
 * Lakota Language sticky carries that playlist URL. Akt&aacute; and In&iacute;pi keep
 * their accents per the house rule.
 *
 * Header, breadcrumbs, generosity pre-footer and footer are template parts.
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
 * Media Library lookup for a photo by filename: the seed-tagged copy first
 * (pages the seeder imported), then any attachment with that exact file, so
 * IDs resolve per environment rather than being baked into the markup.
 * Private to this file (inc/page-content/_helpers.php is shared).
 *
 * @param string $file Filename, e.g. 'youth-programs-akta-lakota-museum.jpg'.
 * @return array{id:int,url:string}
 */
$photo = function ( $file ) {
	$seeded = function_exists( 'stjo_seeded_image' ) ? stjo_seeded_image( $file ) : array( 'id' => 0, 'url' => '' );
	if ( ! empty( $seeded['id'] ) ) {
		return array( 'id' => (int) $seeded['id'], 'url' => (string) $seeded['url'] );
	}
	$id = function_exists( 'stjo_seed_find_attachment_by_filename' ) ? stjo_seed_find_attachment_by_filename( $file ) : 0;
	if ( $id ) {
		return array( 'id' => (int) $id, 'url' => (string) wp_make_link_relative( (string) wp_get_attachment_url( $id ) ) );
	}
	return array( 'id' => 0, 'url' => (string) stjo_asset( $file ) );
};

/** Media & Text: photo on one side, blocks on the other. $side is left|right. */
$media_text = function ( $file, $alt, $body, $side = 'left' ) use ( $photo ) {
	$img   = $photo( $file );
	$cls   = $img['id'] ? ' class="wp-image-' . (int) $img['id'] . ' size-full"' : '';
	$attrs = ( $img['id'] ? '"mediaId":' . (int) $img['id'] . ',' : '' )
		. ( 'right' === $side ? '"mediaPosition":"right",' : '' )
		. '"mediaType":"image","className":"is-style-rounded"';
	$figure  = '<figure class="wp-block-media-text__media"><img src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '"' . $cls . '/></figure>';
	$content = '<div class="wp-block-media-text__content">' . $body . '</div>';
	return '<!-- wp:media-text {' . $attrs . '} -->' . "\n"
		. '<div class="wp-block-media-text' . ( 'right' === $side ? ' has-media-on-the-right' : '' ) . ' is-stacked-on-mobile is-style-rounded">'
		. ( 'right' === $side ? $content . $figure : $figure . $content )
		. '</div>' . "\n"
		. '<!-- /wp:media-text -->' . "\n";
};

/** One count-up figure over the sentence it comes from. */
$stat = function ( $value, $label ) {
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:stjo/stat-figure {"value":"' . esc_attr( $value ) . '"} /-->'
		. '<!-- wp:paragraph {"align":"center","className":"stjo-stat__label"} -->'
		. '<p class="has-text-align-center stjo-stat__label">' . $label . '</p>'
		. '<!-- /wp:paragraph -->'
		. '</div><!-- /wp:column -->';
};

/**
 * Image-topped white info card: photo, H3, blurb, one arrow link. One link per
 * card: the card stretches it across the whole tile (sections.css).
 */
$card = function ( $file, $alt, $title, $text, $href, $cta ) use ( $photo ) {
	$src = esc_url( $photo( $file )['url'] );
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

/** One column of the cultural-activities list, in the copy's original order. */
$list_column = function ( array $items ) {
	$out = '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:list --><ul class="wp-block-list">';
	foreach ( $items as $item ) {
		$out .= '<!-- wp:list-item --><li>' . $item . '</li><!-- /wp:list-item -->';
	}
	return $out . '</ul><!-- /wp:list --></div><!-- /wp:column -->';
};

$powwow   = '/lakota-culture/powwow-dance/attend-a-powwow/';
$playlist = 'https://www.youtube.com/playlist?list=PLBkGQb4TQK3pl0AEzYvVOsKBuLZl4xqdF';
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

<?php
echo $media_text(
	'qualityEducation2.jpg',
	'A student smiling at her desk beside a classroom computer',
	'<!-- wp:heading {"level":2} -->' . "\n"
	. '<h2 class="wp-block-heading">Education</h2>' . "\n"
	. '<!-- /wp:heading -->' . "\n\n"
	. '<!-- wp:paragraph -->' . "\n"
	. '<p>Children attending St. Joseph’s benefit from individualized care plans that follow nationally approved and accredited techniques. Friends who support St. Joseph’s help provide for every aspect of each child’s physical, emotional, spiritual and educational needs.</p>' . "\n"
	. '<!-- /wp:paragraph -->' . "\n\n"
	. '<!-- wp:paragraph -->' . "\n"
	. '<p>Children in elementary grades one through eight attend classes at St. Joseph’s Elementary School on campus. Technology is carefully integrated into the learning environment!</p>' . "\n"
	. '<!-- /wp:paragraph -->',
	'left'
);
echo "\n" . $sp( 'medium' ) . "\n\n";
echo $media_text(
	'2018-Seniors.jpg',
	'St. Joseph’s graduates in caps and gowns gathered on the campus lawn',
	'<!-- wp:paragraph -->' . "\n"
	. '<p>After eighth grade, students can enroll in St. Joseph’s High School Program. With capacity to care for 50 Lakota (Sioux) high school students, St. Joseph’s partners with Chamberlain High School to give our students a multitude of opportunities to participate in sports, fine arts and extra-curricular activities. In addition, they still live on St. Joseph’s campus with our specially trained houseparents.</p>' . "\n"
	. '<!-- /wp:paragraph -->',
	'right'
);
?>

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Education by the Numbers"},"align":"full","backgroundColor":"light","className":"stjo-stats","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-stats has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:columns {"className":"stjo-stats__row"} -->
<div class="wp-block-columns stjo-stats__row"><?php
echo $stat( '200+', 'Our Native American Education Program splits our 200+ students into two groups — elementary and high school.' );
echo $stat( '12', 'Children benefit from small class sizes (approximately 12 students per classroom) and one-on-one attention.' );
echo $stat( '52%', 'According to the South Dakota Department of Education, just 52% of Native American students completed high school on-time during the 2024-25 school year.' );
echo $stat( '96%', 'In contrast, St. Joseph’s Indian School sees a graduation rate of approximately 96%.' );
?></div>
<!-- /wp:columns -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:group {"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">This alarming difference solidifies the importance of education starting in elementary years. Children at St. Joseph’s are encouraged to be passionate about their education through special awards, recognition and fun activities.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Culture and Awareness"},"align":"full","layout":{"type":"constrained"},"anchor":"culture-awareness"} -->
<div class="wp-block-group alignfull" id="culture-awareness"><?php echo $sp( 'large' ); ?>

<?php
echo $media_text(
	'youth-programs-akta-lakota-museum.jpg',
	'Two students leaning over a display case at the Akt&aacute; Lakota Museum',
	'<!-- wp:heading {"level":2} -->' . "\n"
	. '<h2 class="wp-block-heading">Culture &amp; Awareness</h2>' . "\n"
	. '<!-- /wp:heading -->' . "\n\n"
	. '<!-- wp:paragraph {"className":"stjo-subhead"} -->' . "\n"
	. '<p class="stjo-subhead">St. Joseph’s Indian School’s cultural philosophy is to celebrate and embrace our students Native American heritage through the study and practice of language, ceremony, dance and other activities.</p>' . "\n"
	. '<!-- /wp:paragraph -->' . "\n\n"
	. '<!-- wp:paragraph -->' . "\n"
	. '<p>Preserving and sharing the Native American culture and tradition is a core part of our mission at St. Joseph’s Indian School. In addition to having our students learn about their Native American culture in our Native American Studies classes, the <a href="https://aktalakota.stjo.org/">Akt&aacute; Lakota Museum &amp; Cultural Center</a> is located on campus for students and visitors alike to broaden their knowledge of the Sioux culture, more specifically known by their dialects — Lakota, Dakota and Nakota.</p>' . "\n"
	. '<!-- /wp:paragraph -->',
	'left'
);
echo "\n" . $sp( 'medium' ) . "\n\n";
echo $media_text(
	'Youth-Programs-Cultural-Awareness.jpg',
	'A student dancing in jingle dress regalia at the powwow',
	'<!-- wp:paragraph -->' . "\n"
	. '<p><a href="' . esc_url( $powwow ) . '">Our annual powwow</a>, held each September, gives visitors the unique opportunity to learn about our students’ Native American culture and traditions.</p>' . "\n"
	. '<!-- /wp:paragraph -->' . "\n\n"
	. '<!-- wp:paragraph -->' . "\n"
	. '<p>Appreciation of the children’s cultural heritage is infused in our daily routine both at school and in the homes. St. Joseph’s Native American Studies classes focus on Lakota language, culture and traditions. We have cultural experts on staff who teach about the Native American culture and also regularly invite Lakota (Sioux) elders and advisors to assist with ceremonies like In&iacute;pi, a purification rite, on campus. A variety of educational opportunities are provided for both students and staff alike.</p>' . "\n"
	. '<!-- /wp:paragraph -->',
	'right'
);
?>

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Culture in the Classroom"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<?php
echo $media_text(
	'youth-programs-education-cultural.jpg',
	'A student holding up a bundle of freshly harvested sweetgrass in the campus garden',
	'<!-- wp:paragraph -->' . "\n"
	. '<p>Integrating the children’s Native American heritage into their classroom experience also plays an important role in their healing and growth. Native American Studies is a required course for all St. Joseph’s students; in this course, children find themselves immersed in culture by learning the Lakota language, traditional stories and more.</p>' . "\n"
	. '<!-- /wp:paragraph -->' . "\n\n"
	. '<!-- wp:paragraph -->' . "\n"
	. '<p>In addition to Native American Studies classes, classroom teachers strive to include the Lakota language and culturally appropriate material in the regular curriculum. Each morning begins with prayer, singing of the flag song, weather announcements and the “Word for the Week” — all in Lakota. An additional cultural activity takes place one Wednesday each month.</p>' . "\n"
	. '<!-- /wp:paragraph -->',
	'left'
);
?>

<?php echo $sp( 'medium' ); ?>

<!-- wp:group {"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><!-- wp:paragraph -->
<p>Additionally, Native American beliefs and philosophies are incorporated into core education classes like math, science and language arts through careful lesson planning and the WoLakota program. Embracing diversity and cultural understanding lends a new perspective, which can be beneficial to students and staff of any background.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Furthermore, St. Joseph’s Indian School participates in the WoLakota Project. This project is an educational program that provides an opportunity for cultural understanding and exchange in the classroom. With education and the support of a mentor, all teachers are given the tools to connect with their Native American students culturally, not just through a lesson, but in how a lesson is taught.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php echo $sp( 'large' ); ?>

<!-- wp:pullquote {"align":"wide","className":"stjo-pull-quote"} -->
<figure class="wp-block-pullquote alignwide stjo-pull-quote"><blockquote><p>&#8220;This program provides a cultural thread in the classroom. With 100% of our students being Native American, it’s particularly important at St. Joseph’s that we do everything we can to help students embrace their heritage.&#8221;</p><cite>Sharmel, St. Joseph’s Principal</cite></blockquote></figure>
<!-- /wp:pullquote -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Cultural Activities"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><?php echo $sp( 'large' ); ?>

<!-- wp:paragraph {"align":"center","className":"stjo-subhead"} -->
<p class="has-text-align-center stjo-subhead">Students have the opportunity to take part in numerous cultural activities:</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:columns -->
<div class="wp-block-columns"><?php
echo $list_column( array(
	'A dance club teaches traditional Native American dance and songs',
	'Powwow competitions, including <a href="' . esc_url( $powwow ) . '">St. Joseph’s annual powwow</a> held on campus each year',
) );
echo $list_column( array(
	'Traditional drum group',
	'Lakota language competitions, like the Lakota Nation Invitational',
	'Hand games',
) );
echo $list_column( array(
	'Sweat lodge ceremonies (also known as In&iacute;pi)',
	'Cultural trips',
	'And much, much more!',
) );
?></div>
<!-- /wp:columns -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:image {"metadata":{"name":"Powwow Photo Band"},"align":"full","sizeSlug":"full","className":"stjo-full-width-image"} -->
<figure class="wp-block-image alignfull size-full stjo-full-width-image"><img src="<?php echo esc_url( $photo( 'card-4.png' )['url'] ); ?>" alt="Students in powwow regalia standing together on the campus lawn"/></figure>
<!-- /wp:image -->

<!-- wp:group {"metadata":{"name":"Education and Lakota Culture"},"align":"full","backgroundColor":"light","className":"stjo-cards-band","layout":{"type":"constrained"},"anchor":"education-lakota-culture"} -->
<div class="wp-block-group alignfull stjo-cards-band has-light-background-color has-background" id="education-lakota-culture"><?php echo $sp( 'large' ); ?>

<!-- wp:paragraph {"align":"center","textColor":"brand-dark","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-brand-dark-color has-text-color">Explore</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Education &amp; Lakota Culture</h2>
<!-- /wp:heading -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:columns {"className":"stjo-related-cards"} -->
<div class="wp-block-columns stjo-related-cards">
<?php
echo $card(
	'card-5.png',
	'Students working on laptops at their desks in a classroom',
	'Lakota Language',
	'Preserving and sharing the Lakota culture is a core part of our mission at St. Joseph’s Indian School and traditional Lakota language is a vital part of that effort.',
	'/lakota-culture/lakota-language/',
	'Explore Lakota Language'
);
echo $card(
	'prayers-priest-blessing-students.jpg',
	'A priest in purple vestments blessing students during Mass in the chapel',
	'Religious Education',
	'Though St. Joseph’s Indian School is affiliated with the Catholic Church through the Priests of the Sacred Heart, we welcome children of all faiths, recognizing the dignity of each human person created in God’s image.',
	'/youth-programs/education-cultural-awareness/religious-education/',
	'Explore Religious Education'
);
echo $card(
	'card.png',
	'A student leaning over her seat on the bus, smiling',
	'Cultural Trip',
	'St. Joseph’s incoming eighth-grade class participates in a week-long cultural trip to Native American sites of cultural, spiritual and historical significance in South Dakota, North Dakota, Montana and Wyoming.',
	'/youth-programs/education-cultural-awareness/cultural-trip/',
	'Explore the Cultural Trip'
);
?>
</div>
<!-- /wp:columns -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $playlist ); ?>">Watch Lakota Word Wednesday</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
