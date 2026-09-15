<?php
/**
 * our-children — "About Our Children" (About section).
 *
 * Layout follows the About Our Children wireframe on the sitemap board
 * (uXjVHzJD47c, frame 3458764674147056179), which is the General Content
 * template: title band, intro paragraph, "A future for Lakota children" as two
 * columns (Education and Services | Family and Culture), a Student Stories
 * media-text with a Read More link, then an Accountability row of two cards
 * (Student Bill of Rights, Protecting Students). The board's sticky says
 * "Copy from here: stjo.org/native-american-children/", so the copy is that
 * page's, redistributed into those slots: admissions facts and the student
 * profile list in the intro, what gifts provide under Education and Services,
 * the "there is hope" heritage passage under Family and Culture.
 *
 * The two Accountability cards point at the tertiary pages the sitemap places
 * under Accountability & Reports (seed-pages.php creates them as stubs); their
 * blurbs come from those live pages. The Student Stories blurb is the Your
 * Impact page's own card copy. The photo is the live page's (350px wide, the
 * only size the client has published). Header, breadcrumbs, generosity
 * pre-footer and footer are template parts, not part of this content.
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

/** Seeded photo as a media-text figure. */
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

/** Text info card (the wireframe's "Item / short description / Learn more"). */
$card = function ( $title, $text, $href, $cta ) {
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:group {"className":"stjo-info-card"} --><div class="wp-block-group stjo-info-card">'
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
?>
<!-- wp:group {"metadata":{"name":"Page Title Band"},"align":"full","textColor":"white","className":"stjo-page-title-band","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-page-title-band has-white-color has-text-color"><?php echo $sp( 'large' ); ?>

<!-- wp:paragraph {"align":"center","textColor":"yellow","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-yellow-color has-text-color">About</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":1,"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">About Our Children</h1>
<!-- /wp:heading -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">Students are not required to be Catholic to attend St. Joseph’s, though over half are.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Native American students fill our school’s 21 homes. Unfortunately, over 100 other American Indian youth are on our waiting list.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>To be admitted, children must be of Native American heritage, and be in grades one through 12. Admission is based on need, and many of our Lakota students have the following characteristics:</p>
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
<li>Native American children’s exposure to substance abuse and domestic violence is increasing. Approximately 55% of our students have been exposed to drug and alcohol use and 41% have witnessed domestic violence.</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"A Future for Lakota Children"},"layout":{"type":"constrained"},"anchor":"a-future-for-lakota-children"} -->
<div class="wp-block-group" id="a-future-for-lakota-children"><!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">A future for Lakota children</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Education and Services</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Tax-deductible gifts help our Lakota students receive:</p>
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
<li>Tools to help build confidence, boost self-esteem and improve cultural awareness</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>And MORE to help our students believe in a productive, possibility-filled future!</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>Thank you for your support.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Family and Culture</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>But there is hope … since 1927, St. Joseph’s Indian School has been working with Native American youth and their families to educate and support for life — mind, body, heart and spirit. For over 90 years, reaching out to American Indian youth on Indian reservations and beyond has remained our top priority.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>By supporting St. Joseph’s Indian School, you are helping Native American students in need regain pride in their Lakota (Sioux) heritage by learning the Lakota language, studying Native American culture and learning ways they can grow and prosper.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Student Stories"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><?php echo $sp( 'medium' ); ?>

<!-- wp:media-text {<?php echo $media_id( 'OurChildren1.jpg' ); ?>"mediaType":"image","className":"is-style-rounded"} -->
<div class="wp-block-media-text is-stacked-on-mobile is-style-rounded"><?php echo $figure( 'OurChildren1.jpg' ); ?><div class="wp-block-media-text__content"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Student Stories</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Meet the Native American students who call St. Joseph’s home, from elementary school through high school graduation.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-arrow-link"} -->
<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link wp-element-button" href="/student-stories/">Read More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:media-text -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Accountability"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"},"anchor":"accountability"} -->
<div class="wp-block-group alignfull has-light-background-color has-background" id="accountability"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Accountability</h2>
<!-- /wp:heading -->

<!-- wp:columns {"className":"stjo-related-cards"} -->
<div class="wp-block-columns stjo-related-cards">
<?php
echo $card(
	'Student Bill of Rights',
	'Equal treatment, the necessities of life, freedom of expression, protection from abuse, medical and dental care, religious freedom, education and recreation, and more: the rights every St. Joseph’s student is guaranteed.',
	'/about/accountability-reports/student-bill-of-rights/',
	'Read the Student Bill of Rights'
);
echo $card(
	'Protecting Students',
	'Inappropriate behavior is unequivocally not tolerated at St. Joseph’s Indian School. A zero-tolerance policy is strictly enforced throughout our programs.',
	'/about/accountability-reports/protecting-students/',
	'How We Protect Students'
);
?>
</div>
<!-- /wp:columns -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
