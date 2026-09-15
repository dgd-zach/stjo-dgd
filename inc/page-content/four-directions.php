<?php
/**
 * four-directions — "Four Directions" (tertiary page under Beliefs &
 * Traditions).
 *
 * General Content template. Copy is verbatim from stjo.org/native-american-
 * culture/native-american-beliefs/four-directions/: intro, the four
 * directions as four text columns with their credit line, then the Four
 * Directions Prayer. The prayer is long, so per Zach it opens in a lightbox
 * (lightbox-content page inc/page-content/lightbox/four-directions-prayer.php)
 * rather than running down the page. The live "Donate Now to help preserve
 * the Lakota culture!" line becomes the closing CTA band. The live page's
 * Four Directions symbol (210px) sits at its natural size above the columns.
 *
 * Seed source only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

$direction = function ( $title, $text ) {
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . $title . '</h3><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>' . $text . '</p><!-- /wp:paragraph -->'
		. '</div><!-- /wp:column -->';
};
$prayer_attrs = array( 'title' => 'Four Directions Prayer', 'linkLabel' => 'Read the Four Directions Prayer', 'className' => 'is-style-arrow-link' );
if ( $page_id( 'four-directions-prayer' ) ) {
	$prayer_attrs['contentPageId'] = $page_id( 'four-directions-prayer' );
}

echo $title_band( 'Beliefs &amp; Traditions', 'Four Directions' );
?>
<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">The Meaning of the Four Directions in Native American Culture</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>As part of the Lakota culture, when people pray or do anything sacred, they see the world as having Four Directions. From these Four Directions — west, north, east, south — come the four winds. The special meanings of each of the Four Directions are accompanied by specific colors, and the shape of the cross symbolizes all directions. Like many Native American beliefs and traditions, specific details regarding colors associated with directions varies.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"The Four Directions"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"},"anchor":"directions"} -->
<div class="wp-block-group alignfull has-light-background-color has-background" id="directions"><?php echo $sp( 'large' ); ?>

<?php
$sym = stjo_seeded_image( 'four-directions.png' );
if ( $sym['id'] ) :
?>
<!-- wp:image {"id":<?php echo (int) $sym['id']; ?>,"width":"210px","sizeSlug":"full","linkDestination":"none","align":"center"} -->
<figure class="wp-block-image aligncenter size-full is-resized"><img src="<?php echo esc_url( $sym['url'] ); ?>" alt="<?php echo esc_attr( (string) get_post_meta( $sym['id'], '_wp_attachment_image_alt', true ) ); ?>" class="wp-image-<?php echo (int) $sym['id']; ?>" style="width:210px"/></figure>
<!-- /wp:image -->
<?php endif; ?>

<!-- wp:columns -->
<div class="wp-block-columns"><?php
echo $direction( 'West (Black)', 'To the west, the sun sets, and the day ends. For this reason, west signifies the end of life. As Black Elk says, “… toward the setting sun of his life.” The great Thunderbird lives in the west and sends thunder and rain from its direction. For this reason, the west is also the source of water: rain, lakes, streams and rivers. Nothing can live without water, so the west is vital.' );
echo $direction( 'North (Red)', 'North brings the cold, harsh winds of the winter season. These winds are cleansing. They cause the leaves to fall and the earth to rest under a blanket of snow. If someone has the ability to face these winds like the buffalo with its head into the storm, they have learned patience and endurance. Generally, this direction stands for hardships and discomfort. Therefore, north represents the trials people must endure and the cleansing they must undergo.' );
?></div>
<!-- /wp:columns -->

<!-- wp:columns -->
<div class="wp-block-columns"><?php
echo $direction( 'East (Yellow)', 'The direction from which the sun comes. Light dawns in the morning and spreads over the earth. This is the beginning of a new day. It is also the beginning of understanding because light helps us see things the way they really are. On a deeper level, east stands for the wisdom helping people live good lives. Traditional people rise in the morning to pray facing the dawn, asking God for wisdom and understanding.' );
echo $direction( 'South (White)', 'Because the southern sky is when the sun is at its highest, this direction stands for warmth and growing. The sun’s rays are powerful in drawing life from the earth. It is said the life of all things comes from the south. Also, warm and pleasant winds come from the south. When people pass into the spirit world, they travel the Milky Way’s path back to the south — returning from where they came.' );
?></div>
<!-- /wp:columns -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><em>Adapted from Lakota Life by Ron Zeilinger</em></p>
<!-- /wp:paragraph -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Four Directions Prayer"},"layout":{"type":"constrained","contentSize":"768px"},"anchor":"prayer"} -->
<div class="wp-block-group" id="prayer"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Four Directions Prayer</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">When the Lakota pray with the Sacred Pipe, they add three other directions: Sky, Earth and the Sacred Place Within.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-group"><!-- wp:stjo/lightbox-card <?php echo serialize_block_attributes( $prayer_attrs ); ?> /--></div>
<!-- /wp:group -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:cover {"url":"<?php echo esc_url( stjo_asset( 'card-2.png' ) ); ?>","dimRatio":70,"overlayColor":"blue-900","isUserOverlayColor":true,"minHeight":418,"metadata":{"name":"Donate CTA"},"align":"full","className":"stjo-dreammaker","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull stjo-dreammaker" style="min-height:418px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( stjo_asset( 'card-2.png' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-blue-900-background-color has-background-dim-70 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":2,"textColor":"white","fontSize":"xxl"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color has-xxl-font-size">Donate Now to help preserve the Lakota culture!</h2>
<!-- /wp:heading -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"brand-dark","className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link has-brand-dark-color has-white-background-color has-text-color has-background wp-element-button" href="<?php echo $give_once; ?>">Donate Now</a></div>
<!-- /wp:button -->

<!-- wp:button {"textColor":"white","className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-white-color has-text-color wp-element-button" href="/lakota-culture/beliefs-traditions/">All Beliefs &amp; Traditions</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->
