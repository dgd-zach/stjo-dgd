<?php
/**
 * board-of-directors — a tertiary page under About (Miro sitemap board
 * uXjVHzJD47c, tertiary tier / #adf0c7, linked from the About page's
 * "Our Organization" card row).
 *
 * Split out of the temporary "BOD & strategic plan" holding page (#1226)
 * together with strategic-plan.php.
 *
 * Roster: headshots (from stjo.org/about/board-of-directors/ via the
 * seeded-image pipeline, pages-images.json) laid out with core blocks —
 * a group per governance tier holding one small group per member (image +
 * name). Pure wp:columns sized tiers unevenly and blew each portrait up
 * one-per-row on phones, so the tier group carries the .stjo-roster class
 * (sections.css) that flexes the cards to a uniform width and wraps them
 * centred. Per-person roles sit under the name where the tier heading does
 * not already state them.
 *
 * Note: the roster lists Jennifer Renner-Meyer as CEO while the strategic
 * plan quote on stjo.org/about/ credits her as Chief Operating Officer. Both
 * are carried verbatim from the client's own copy; worth confirming with them.
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

$zigzag = '<!-- wp:separator {"className":"alignfull"} --><hr class="wp-block-separator has-alpha-channel-opacity alignfull"/><!-- /wp:separator -->';

/** A centred governance-tier heading. */
$cat = function ( $title ) {
	return '<!-- wp:heading {"textAlign":"center","level":2,"fontSize":"medium"} -->'
		. '<h2 class="wp-block-heading has-text-align-center has-medium-font-size">' . $title . '</h2>'
		. '<!-- /wp:heading -->';
};

/** One member card: a headshot with the name (and optional role) beneath. */
$card = function ( $file, $name, $role = '' ) {
	$img = stjo_seeded_image( $file );
	$alt = $img['id'] ? (string) get_post_meta( $img['id'], '_wp_attachment_image_alt', true ) : $name;
	$fig = $img['id']
		? '<!-- wp:image {"id":' . (int) $img['id'] . ',"sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->'
			. '<figure class="wp-block-image size-full is-style-rounded"><img src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '" class="wp-image-' . (int) $img['id'] . '"/></figure>'
			. '<!-- /wp:image -->'
		: '';
	$meta = '<!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center"><strong>' . $name . '</strong>'
		. ( $role ? '<br>' . $role : '' )
		. '</p><!-- /wp:paragraph -->';
	return '<!-- wp:group -->' . "\n" . '<div class="wp-block-group">' . $fig . $meta . '</div>' . "\n" . '<!-- /wp:group -->';
};

/** A governance tier: heading, then its member cards in a centred grid. */
$tier = function ( $title, $cards ) use ( $cat, $sp ) {
	return $cat( $title ) . $sp( 'small' )
		. '<!-- wp:group {"className":"stjo-roster"} -->' . "\n"
		. '<div class="wp-block-group stjo-roster">' . implode( '', $cards ) . '</div>' . "\n"
		. '<!-- /wp:group -->';
};
?>
<!-- wp:group {"metadata":{"name":"Page Title Band"},"align":"full","textColor":"white","className":"stjo-page-title-band","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-page-title-band has-white-color has-text-color"><!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"align":"center","textColor":"yellow","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-yellow-color has-text-color">About Us</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":1,"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">Board of Directors</h1>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->

<?php echo $zigzag; ?>

<!-- wp:group {"metadata":{"name":"Governance"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">The ex-officio members, officers and directors who govern St. Joseph's Indian School.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Board Roster"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<?php
echo $tier( 'Ex-Officio Members', array(
	$card( 'FrVienNguyenSCJ.jpg', 'Fr. Vien Nguyen, SCJ' ),
	$card( 'DnDavidNagel.jpg', 'Dn. David Nagel, SCJ' ),
) );
echo $sp( 'medium' );

echo $tier( 'Leadership', array(
	$card( 'FrGregorySchillSCJ.jpg', 'Fr. Gregory Schill, SCJ', 'Chairperson' ),
	$card( 'DougKnust.jpg', 'Doug Knust', 'Vice-Chairperson' ),
) );
echo $sp( 'medium' );

echo $tier( 'Board Members', array(
	$card( 'Bridget-B-Martin.jpg', 'Bridget Martin' ),
	$card( 'TerryJohnson.jpg', 'Terry Johnson' ),
	$card( 'FrJackKurpsSCJ.jpg', 'Fr. Jack Kurps, SCJ' ),
	$card( 'EmmetKenney.jpg', 'Dr. Emmet M. Kenney Jr., MD' ),
	$card( 'LarryJandreau.jpg', 'Larry Jandreau' ),
	$card( 'Sr-Catherine-Bertrand-SSND.jpg', 'Sr. Catherine Bertrand, SSND' ),
	$card( 'MikeTyrell.jpg', 'Mike Tyrell' ),
) );
echo $sp( 'medium' );

echo $tier( 'Non-Voting Members', array(
	$card( 'JenniferRenner-Meyer.jpg', 'Jennifer Renner-Meyer', 'CEO' ),
	$card( 'KoryChristianson.jpg', 'Kory Christianson', 'Executive Director of Development, Secretary' ),
	$card( 'RobynKnecht.jpg', 'Robyn Knecht', 'Executive Director of Child Services' ),
) );
?>

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Keep Reading"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><?php echo $sp( 'medium' ); ?>

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-arrow-link"} -->
<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link wp-element-button" href="/about/strategic-plan/">Read the Strategic Plan</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->
