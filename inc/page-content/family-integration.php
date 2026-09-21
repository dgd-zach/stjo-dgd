<?php
/**
 * family-integration — "Family Integration" (Youth Programs).
 *
 * Built from stjo.org/programs/native-american-family-life/.
 *
 * Seed source only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

$img_c = function ( $file, $align = 'center', $class = 'is-style-rounded' ) {
	$i = stjo_seeded_image( $file );
	if ( ! $i['id'] ) {
		return '';
	}
	$alt = esc_attr( (string) get_post_meta( $i['id'], '_wp_attachment_image_alt', true ) );
	return '<!-- wp:image {"id":' . (int) $i['id'] . ',"sizeSlug":"large","linkDestination":"none","align":"' . $align . '","className":"' . $class . '"} -->' . "\n"
		. '<figure class="wp-block-image align' . $align . ' size-large ' . $class . '"><img src="' . esc_url( $i['url'] ) . '" alt="' . $alt . '" class="wp-image-' . (int) $i['id'] . '"/></figure>' . "\n"
		. '<!-- /wp:image -->';
};

echo $title_band( 'Youth Programs', 'Family Integration' );
?>
<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:quote {"className":"is-style-plain"} -->
<blockquote class="wp-block-quote is-style-plain"><!-- wp:paragraph -->
<p>And while I stood there I saw more than I can tell and understood more than I saw; for I was seeing in a sacred manner the shapes of all things in the spirit, and the shape of all shapes as they must live together like one being. And I saw that the sacred hoop of my people was one of many hoops that made one circle, wide as daylight and as starlight, and in the center grew one mighty flowering tree to shelter all the children of one mother and one father. And I saw that it was holy.</p>
<!-- /wp:paragraph --><cite>Black Elk, Lakota (1863-1950)</cite></blockquote>
<!-- /wp:quote -->

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">St. Joseph’s Indian School recognizes and understands the importance of family within the Lakota (Sioux) culture and the reality that Native American family connections can be challenging in a residential school where children live away from home.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Family and Organizational Integration is an essential part in our new strategic plan, <a href="/about/strategic-plan/">Vision 2025 — More than Possible</a>. The effort is part of the Mission Integration Department, which also includes Religious Studies, Native American Studies, Alumni and the Artists Residency. Each of these areas is integral to animating St. Joseph’s mission across the organization.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Student success in St. Joseph’s residential setting depends on supporting stronger relationships with our students’ families. Our efforts to strengthen family integration include going out and listening to the families we work with, connecting them with resources at St. Joseph’s and in their communities, while at the same time taking a fresh, family-focused look at everything we do.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<?php echo $img_c( 'familyIntegration1.jpg' ); ?>

<!-- wp:group {"metadata":{"name":"Family Integration Detail"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Integrating Families</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Families are crucial to helping their students learn and grow. For more than 10 years, houseparents and counselors have worked to build relationships with families in a variety of ways. Houseparents send regular newsletters to parents and guardians about things going on in campus homes. Parents and guardians are invited to join their students for meals or share a talent or skill.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Since Vision 2025 was implemented in August 2016, we take additional care to engage Native American families through yearly visits, effective listening, needs identification and strategizing ways to meet needs by connecting families to available resources at St. Joseph’s and in their local communities. The individual who leads this effort is our Family Integration Coordinator. An enrolled member of a South Dakota tribe, she regularly shares the compliments and concerns of parents with staff across the organization to improve the way we serve our students and their families. In addition, she works side-by-side with our Alumni Liaison and Family Service Counselors to address immediate family needs such as requests for food or clothing, attendance at funerals and more.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Adding Organizational Channels</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>St. Joseph’s staff is intentional about reaching out to our students’ families throughout the year. Our grade school teachers and houseparents make ongoing contact via phone, email and postal mail, and take every opportunity to have face-to-face contact when possible. At the beginning of this past school year, school staff recorded introductions of themselves and sent them to families on DVDs.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>The start-of-the-school-year picnic includes family photography sessions, games and other activities that can make this back-to-school-away-from-home day more enjoyable and family-focused.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Our Alumni Liaison invites conversations with families through the Bookmobile, meet-and-greets in the communities where our families live and on-campus events and visits.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Because families are such an integral part of a child’s healing and growth, St. Joseph’s also relies on members of our Parent Advisory Council to offer us insight and feedback on programs, services and challenges. The council members advise us in crucial areas like high school education, alumni programs, grief counseling and issues our students face, such as abandonment and racism. They gather at least twice yearly to review policies and offer suggestions. Their perspective is priceless as we work to ensure holistic care and individual attention for each child in our care.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Improving Staff Understanding</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We understand the importance of having our staff understand the Native American family values of the students in our care. This spring, a “family awareness survey” will gauge staff’s current understanding. In the fall, specially created family awareness training will be provided to all staff. This training will become a part of future new-staff orientation, and the education will include tested ways of communicating with families and supporting their desire to raise successful children.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>We enjoy working with our students’ families and look forward to ways we can continue strengthening our relationship with them each and every day.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Youth Programs CTA"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"textColor":"blue-900","className":"is-style-arrow-link"} -->
<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link has-blue-900-color has-text-color wp-element-button" href="/youth-programs/">Explore our Native American Youth Programs</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
