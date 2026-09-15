<?php
/**
 * lakota-legends — "Lakota Legends" (Lakota Culture section).
 *
 * General Content template. The sitemap board hangs five tertiary pages off
 * this one (Dreamcatcher, Lakota Pipe, Devils Tower, Iktómi, The Great Race);
 * each is a card linking to its page (seed-pages.php creates them as stubs).
 * The live page's sixth story, the Seven Lakota Rites, already lives here as
 * a lightbox-content page (seven-lakota-rites), so it opens in a lightbox
 * from an arrow link under the grid (never mixed into the info-card row). Intro and blurbs are verbatim from
 * stjo.org/native-american-culture/lakota-legends/.
 *
 * Seed source only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

$base = '/lakota-culture/lakota-legends/';

echo $title_band( 'Lakota Culture', 'Lakota Legends' );
?>
<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">While Native American culture has struggled to survive through centuries of displacement and assimilation, the stories and legends passed on from generation to generation still persevere.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>This is perhaps due to their common, timeless message of peace and harmony with nature and all living things.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Years ago, there were thousands of tribes, clans and people of various beliefs and customs living in tipis and other dwellings. People were hunting, fishing and farming, only taking what was needed and making the most of every animal killed or plant harvested.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>While cultures and customs varied, all Native American beliefs were rooted in a common belief that the universe was bound together by the spirits within all natural life of plants, animals, humans, water, air and earth.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Lakota history was passed from generation to generation through the beautiful art of storytelling. Elders shared tales with young ones to preserve the culture and ensure the continuation of a people.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"The Legends"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"},"anchor":"legends"} -->
<div class="wp-block-group alignfull has-light-background-color has-background" id="legends"><?php echo $sp( 'large' ); ?>

<!-- wp:paragraph {"align":"center","textColor":"brand-dark","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-brand-dark-color has-text-color">Explore</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">The Legends</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">The following are stories told by Native American tribes of the Northern Plains.</p>
<!-- /wp:paragraph -->

<?php
echo $card_rows( array(
	$card( 'Dreamcatcher', 'Native Americans of the Great Plains believe the air is filled with both good and bad dreams.', $base . 'dreamcatcher/', 'Read more about the Dreamcatcher' ),
	$card( 'Origin of the Lakota Pipe', 'In this legend, two men encounter a beautiful woman with a strong message and important gift for the Lakota people.', $base . 'lakota-pipe/', 'Read more about the Origin of the Lakota Pipe' ),
	$card( 'Devils Tower', 'Devils Tower National Monument is an astounding geologic feature protruding out of the prairie surrounding the Black Hills. It is considered sacred by people of the Northern Plains.', $base . 'devils-tower/', 'Read more about the Devils Tower' ),
	$card( 'Ikt&oacute;mi', 'The legend of Ikt&oacute;mi comes from the Plains, Southwestern and Western Native American groups. Ikt&oacute;mi has spider-like characteristics and features.', $base . 'iktomi/', 'Read more about the Legend of Ikt&oacute;mi' ),
	$card( 'The Great Race', 'The legend of the Great Race tells the story of how humans became the most powerful beings on Earth.', $base . 'the-great-race/', 'Read more about The Great Race' ),
) );
echo $sp( 'medium' );
// The sixth story already lives here as a lightbox-content page, so it opens
// in a lightbox from an arrow link rather than joining the info-card grid
// (the two card kinds are styled differently and must not share a row).
$rites = array( 'title' => 'The Seven Lakota Rites', 'linkLabel' => 'Read more about the Seven Lakota Rites', 'className' => 'is-style-arrow-link' );
if ( $page_id( 'seven-lakota-rites' ) ) {
	$rites['contentPageId'] = $page_id( 'seven-lakota-rites' );
}
?>

<!-- wp:group {"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center">The Seven Lakota Rites</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">The legend of the Seven Lakota Rites tells the story of the White Buffalo Calf Women gave gifts to the Lakota for what would be their sacred ceremonies.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-group"><!-- wp:stjo/lightbox-card <?php echo serialize_block_attributes( $rites ); ?> /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
