<?php
/**
 * cultural-trip — "Cultural Trip" (Education & Cultural Awareness).
 *
 * Built from the live page
 * stjo.org/programs/native-american-cultural-awareness/cultural-trip/. The
 * one-line caption under the banner image is a photo caption, not body copy,
 * so it is left out.
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

echo $title_band( 'Education &amp; Cultural Awareness', 'Cultural Trip' );
?>
<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">A cultural trip with a lasting impact!</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>After months of preparation, St. Joseph&#8217;s incoming eighth-grade class embarks on a week-long cultural trip each spring.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>The journey takes them to Native American sites of cultural, spiritual and historical significance in South Dakota, North Dakota, Montana and Wyoming. What they learn helps prepare them for their future.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ) . $img_c( 'Cultural-Trip-1.jpg', 'center' ) . $sp( 'medium' ); ?>

<!-- wp:paragraph -->
<p>&#8220;The cultural trip is &mdash; at its core &mdash; a learning opportunity for the students and staff participating on the trip. During the week, we travel over 1400 miles, camp and hike in four different states and get the chance to see many historical sites that are culturally significant to the Lakota people,&#8221; explained Nathan, St. Joseph&#8217;s 4-6 Grade Residential Coordinator.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Now in its ninth year, the trip follows the same course annually and was carefully planned to help students get the most out of each site. During the school year, students learn about what makes each point on the map noteworthy. At each stopping point during the trip, students learn about the significance of that particular site in Native American culture.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Bear Butte"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php
$photo = stjo_seeded_image( 'Cultural-Trip-2.jpg' );
$palt  = esc_attr( (string) get_post_meta( (int) $photo['id'], '_wp_attachment_image_alt', true ) );
$attrs = serialize_block_attributes( array( 'mediaId' => (int) $photo['id'], 'mediaType' => 'image', 'mediaWidth' => 40, 'mediaPosition' => 'right', 'verticalAlignment' => 'center' ) );
?>
<!-- wp:media-text <?php echo $attrs; ?> -->
<div class="wp-block-media-text has-media-on-the-right is-stacked-on-mobile is-vertically-aligned-center" style="grid-template-columns:auto 40%"><div class="wp-block-media-text__content">
<!-- wp:paragraph -->
<p>Bear Butte and Black Elk Peak, for example, are important spots in the Lakota rite of Hanbleceya &mdash; Crying for a Vision. Here, students make tobacco ties and offer prayers to the Great Spirit.</p>
<!-- /wp:paragraph --></div><figure class="wp-block-media-text__media"><img src="<?php echo esc_url( $photo['url'] ); ?>" alt="<?php echo $palt; ?>" class="wp-image-<?php echo (int) $photo['id']; ?> size-large"/></figure></div>
<!-- /wp:media-text -->
</div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Leadership and stops"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph -->
<p>In addition to visiting significant spots around the tri-state area, there is another objective to the trip that is just as important!</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>&#8220;We like to encourage the students to start thinking about what it means to be a leader and what it takes to live their lives with integrity,&#8221; explained Nathan. &#8220;Each day is concluded around the campfire, where the students get a chance to process and reflect on the experiences of the day.&#8221;</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Separated into two different groups &mdash; boys and girls &mdash; each group sets out from St. Joseph&#8217;s the same day with the same itinerary, but follow it in opposite directions.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>The stops include:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>Mato Tipila &mdash; more commonly known as Devil&#8217;s Tower &mdash; in Wyoming.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Greasy Grass, the site of the Battle of Little Big Horn in Montana.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Ft. Robinson where Chief Crazy Horse was killed.</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>After each stop, students spend quiet time making journal entries about what they see and learn from these cultural, historical and spiritual sites.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Back at St. Joseph&#8217;s Indian School, the boys&#8217; and girls&#8217; groups convene once again. After a prayer service, they spend the last two days of school preparing and sharing a power point presentation about their trip.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Thank you"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:paragraph {"align":"center","className":"stjo-subhead"} -->
<p class="has-text-align-center stjo-subhead">Thank you for giving us the opportunity to teach our Lakota students about their culture. We couldn&#8217;t do what we do without you!</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
