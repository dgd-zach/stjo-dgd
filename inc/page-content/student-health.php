<?php
/**
 * student-health — "Student Health" (Youth Programs).
 *
 * Built from the live page stjo.org/programs/native-american-student-health-care/.
 *
 * Seed source only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

echo $title_band( 'Youth Programs', 'Student Health' );
?>
<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">American Indians and Alaska Natives born today have a life expectancy that is 4.4 years less than the U.S. all races population (73.7 years to 78.1 years, respectively). American Indians and Alaska Natives continue to die at higher rates than other Americans in many categories, including chronic liver disease and cirrhosis, diabetes mellitus, unintentional injuries, assault/homicide, intentional self-harm/suicide and chronic lower respiratory diseases.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Source: U.S. Department of Health and Human Services, Indian Health Service (March 2016).</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>St. Joseph&#8217;s Indian School&#8217;s Health &amp; Family Services Center, located on St. Joseph&#8217;s campus, is open daily and has &#8216;on-call&#8217; nurse availability after hours and on weekends to fulfill the Native American health care needs of our students.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Healthcare providers are available each morning during the week to see to the needs of our Native American students and staff members.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Our Native American students are referred to the health center by a houseparent or school staff member whenever there is an injury or illness that needs attention. Barring something serious like an x-ray, St. Joseph&#8217;s nurses and contracted providers are able to see to all the Native American students&#8217; healthcare needs.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>A range of illnesses and injuries are cared for at the health center &mdash; some of St. Joseph&#8217;s most common visits are for colds, flu, allergies, asthma and athletic injuries. Students stay at the health center during the school day when they are too sick to attend school.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"More Health Center Details"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' );
$ph = stjo_seeded_image( 'physicalHealth2.jpg' );
$phalt = esc_attr( (string) get_post_meta( (int) $ph['id'], '_wp_attachment_image_alt', true ) );
$phattrs = serialize_block_attributes( array( 'mediaId' => (int) $ph['id'], 'mediaType' => 'image', 'mediaWidth' => 30, 'mediaPosition' => 'right', 'verticalAlignment' => 'center' ) );
?>

<!-- wp:media-text <?php echo $phattrs; ?> -->
<div class="wp-block-media-text has-media-on-the-right is-stacked-on-mobile is-vertically-aligned-center" style="grid-template-columns:auto 30%"><div class="wp-block-media-text__content">
<!-- wp:paragraph -->
<p>All staff who work directly with the Native American children &mdash; like teachers, houseparents and counselors &mdash; are also trained to deal with minor health problems and are certified in basic First Aid and CPR.</p>
<!-- /wp:paragraph --></div><figure class="wp-block-media-text__media"><img src="<?php echo esc_url( $ph['url'] ); ?>" alt="<?php echo $phalt; ?>" class="wp-image-<?php echo (int) $ph['id']; ?> size-large"/></figure></div>
<!-- /wp:media-text -->

<!-- wp:paragraph -->
<p>Nurses at the health center also evaluate our students&#8217; eyesight and arrange for eye appointments as needed. Annual dental appointments are also scheduled by the health center staff. Any time students need to see a specialist, it&#8217;s arranged through the health center!</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>On average, St. Joseph&#8217;s students make 2,700 visits to the health center each school year!</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Thank you for walking alongside us as we provide holistic care for the Native American children in our care.</p>
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
