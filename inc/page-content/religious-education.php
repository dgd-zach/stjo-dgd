<?php
/**
 * religious-education — "Religious Education" (Education & Cultural Awareness).
 *
 * Built from the live page stjo.org/programs/religious-education/. The live
 * page's opening line is a video caption, not body copy, so it is left out.
 *
 * Seed source only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

echo $title_band( 'Education &amp; Cultural Awareness', 'Religious Education' );
?>
<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">Though St. Joseph&#8217;s Indian School is affiliated with the Catholic Church through the Priests of the Sacred Heart, we welcome children of all faiths, recognizing the dignity of each human person created in God&#8217;s image. Students are not required to be Catholic, and we respect each child&#8217;s individual family beliefs. Families record their wishes in their consent packet each year, and the Director of Mission Integration follows up to confirm those wishes and provide further information when sacramental preparation is requested.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>We provide spiritual education through Religious Studies classes for all students in grades one through 12. When families request, we work with them to prepare their children for Baptism, First Reconciliation, Communion and Confirmation. In adherence to our mission, we seek to <a href="/youth-programs/education-cultural-awareness/">educate the Lakota children</a> for life &mdash; mind, body, heart and spirit.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Children participate in Mass on Sundays and prayer services at various times throughout the year. They participate in choir and as altar servers, readers, blessing ministers and ushers. Each month, a &#8220;Lakota Mass&#8221; takes place, with appropriate cultural elements &mdash; St. Joseph&#8217;s drum group offers songs, dancers in regalia lead the entrance and closing procession, and selected prayers and hymns are said in Lakota.</p>
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
