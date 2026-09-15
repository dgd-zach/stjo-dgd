<?php
/**
 * beliefs-traditions — "Beliefs & Traditions" (Lakota Culture section).
 *
 * General Content template. The sitemap board hangs nine tertiary pages off
 * this one (Seven Lakota Values, Four Directions, Star Quilt, Medicine Wheel,
 * Seasons and Moon Calendar, Quillwork & Beadwork, The Winter Count, The
 * Morning Star, Tipi), so each is a card. Intro and card blurbs are verbatim
 * from stjo.org/native-american-culture/native-american-beliefs/ (Tipi's from
 * its own page). Cards link to the child pages; seed-pages.php creates the
 * unbuilt ones as stubs.
 *
 * Seed source only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

$base = '/lakota-culture/beliefs-traditions/';

echo $title_band( 'Lakota Culture', 'Beliefs &amp; Traditions' );
?>
<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">Native American beliefs and values, as in any culture, help shape life-changing decisions and plans for the future.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>In this section, you will find an overview and resources pertaining to Native American beliefs and traditions, and more specifically the Lakota (Sioux) culture. As with any culture and language, practices are constantly evolving as people and families change along with the world around them. Although similarities are present, the exact meaning behind different Native American beliefs and traditions varies among tribes, clans and individuals. What one group believes or practices might be similar to another, but have differences.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>At St. Joseph’s Indian School, our students and their families hold a variety of Native American beliefs and religious affiliations — they come from many backgrounds. <a href="/youth-programs/education-cultural-awareness/religious-education/">We welcome children of all faiths</a>, recognizing the dignity of each human person created in God’s image. We respect each child’s individual family beliefs and do not require students to be Catholic or practice traditional Lakota spirituality. Families record their wishes in their consent packet each year when their children are enrolled at St. Joseph’s. The Director of Mission Integration and Family Service Counselors are in regular contact with families to ensure we are honoring families’ wishes for each child’s education — academically, spiritually and culturally.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Beliefs and Traditions"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"},"anchor":"explore"} -->
<div class="wp-block-group alignfull has-light-background-color has-background" id="explore"><?php echo $sp( 'large' ); ?>

<!-- wp:paragraph {"align":"center","textColor":"brand-dark","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-brand-dark-color has-text-color">Explore</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Native American Beliefs and Traditions</h2>
<!-- /wp:heading -->

<?php
echo $card_rows( array(
	$card( 'Seven Lakota Values', 'There are seven highly regarded values to the Lakota, which include generosity, kinship, fortitude, wisdom, prayer, respect and compassion.', $base . 'seven-lakota-values/', 'Read more about the Seven Lakota Values' ),
	$card( 'Four Directions', 'When Lakota pray, or do anything sacred, they see the world as having four directions. From these four directions — west, north, east and south — come the four winds. Each direction is also identified by a specific color. The shape of the cross in the center of the circle symbolizes all directions.', $base . 'four-directions/', 'Read more about the Four Directions' ),
	$card( 'Star Quilt', 'The image of the star quilt is a reminder of the importance of generosity in Lakota culture and is one of the most valued gifts one can receive.', $base . 'star-quilt/', 'Read more about the Star Quilt' ),
	$card( 'Medicine Wheel', 'The Medicine Wheel is a sacred symbol used by the indigenous Plains tribes to represent all knowledge of the universe. The Medicine Wheel is a symbol of hope — a movement toward healing for those who seek it.', $base . 'medicine-wheel/', 'Read more about the Medicine Wheel' ),
	$card( 'Seasons and Moon Calendar', 'Native Americans treasure nature and earth. The people’s close connection to nature is seen in their calendars, which can be explained and described by the seasons and moons.', $base . 'seasons-moon-calendar/', 'Read more about the Seasons and Moon Calendar' ),
	$card( 'Lakota Quillwork &amp; Beadwork', 'Quillwork has long been a significant part of the Lakota heritage, as well as being the forerunner of beadwork. Different sized quills were used for different things.', $base . 'quillwork-beadwork/', 'Read more about Lakota Quillwork &amp; Beadwork' ),
	$card( 'The Winter Count', 'For generations, the Lakota documented historical events and the passing of time with pictures and symbols on a buffalo or deer hide.', $base . 'winter-count/', 'Read more about The Winter Count' ),
	$card( 'The Morning Star', 'Just before the sun rises, there is a star standing alone, shining brightly in the east that announced the coming of the sun — a new day.', $base . 'morning-star/', 'Read more about The Morning Star' ),
	$card( 'The Th&iacute;pi', 'The th&iacute;pi — tipi — was looked upon by nomadic hunters as “a good mother” who sheltered and protected her children in a secure and comfortable place.', $base . 'tipi/', 'Read more about the Th&iacute;pi' ),
) );
echo $sp( 'large' );
?></div>
<!-- /wp:group -->
