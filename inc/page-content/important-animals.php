<?php
/**
 * important-animals — "Important Animals" (Lakota Culture section).
 *
 * General Content template. Intro + five "Links that open Lightboxes" cards
 * (Card - With image style), one per sacred animal, each opening a
 * lightbox-content page (inc/page-content/lightbox/<animal>.php). The card
 * hero is the first image from that animal's stjo.org subpage, pulled through
 * the seeded-image pipeline; the same image doubles as the lightbox hero.
 * The turtle subpage has no image on stjo.org, so its card carries no hero.
 * Intro and blurbs verbatim from
 * stjo.org/native-american-culture/important-animals/.
 *
 * Seed source only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

/** One animal: an image lightbox-card whose body is the animal's page. */
$animal_card = function ( $title, $blurb, $slug, $img_file = '', $img_alt = '' ) use ( $page_id ) {
	$attrs = array(
		'title'     => $title,
		'linkLabel' => 'Explore',
		'content'   => $blurb,
		'className' => 'is-style-image',
	);
	$id = $page_id( $slug );
	if ( $id ) {
		$attrs['contentPageId'] = $id;
	}
	if ( $img_file ) {
		$img = stjo_seeded_image( $img_file );
		if ( $img['id'] ) {
			$attrs['mediaId']  = (int) $img['id'];
			$attrs['mediaUrl'] = $img['url'];
			$attrs['mediaAlt'] = $img_alt ? $img_alt : (string) get_post_meta( $img['id'], '_wp_attachment_image_alt', true );
		}
	}
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:stjo/lightbox-card ' . serialize_block_attributes( $attrs ) . ' /-->'
		. '</div><!-- /wp:column -->';
};

echo $title_band( 'Lakota Culture', 'Important Animals' );
?>
<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">Indigenous cultures, such as the Lakota (Sioux), carry precious knowledge about the world around us, including spiritual relationships with animals. They are considered our relatives, among our winged, four-legged and reptile nation.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>In an attempt to explain the importance behind sacred animals, we have compiled cultural information about them. The following are five sacred animals to the Lakota.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"The Animals"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:paragraph {"align":"center","textColor":"brand-dark","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-brand-dark-color has-text-color">Explore</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Five Sacred Animals</h2>
<!-- /wp:heading -->

<?php
echo $card_rows( array(
	$animal_card( 'The Buffalo', 'The tȟatȟáŋka — buffalo — is held in high regard by the Lakota people. It is respected as a symbol of the divine because, for Native Americans, the buffalo was a “banquet” for the people.', 'buffalo', 'Buffalo1.jpg' ),
	$animal_card( 'The Eagle', 'The waŋblí — eagle — is an important winged symbol for the Native American people. The eagle is the strongest and bravest of all birds.', 'eagle', 'Eagle1.jpg' ),
	$animal_card( 'The Dog', 'The šúŋka — dog — has long played an important role in Lakota society and culture. The Lakota (Sioux) relied heavily on dogs for a variety of tasks.', 'dog', '2020-06-Dog.jpg' ),
	$animal_card( 'The Horse', 'To the Lakota, a šúŋkawakȟáŋ — horse — is a relative. A four-legged friend and companion that provided transportation, friendship and pride.', 'horse', '2020-06Horse.jpg' ),
	$animal_card( 'The Turtle', 'The khéya — turtle — is present in a multitude of stories, legends and observations of the Lakota people. The spirit of the turtle represents the guardian of life, longevity and fortitude.', 'turtle' ),
), 3 );
?>

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
