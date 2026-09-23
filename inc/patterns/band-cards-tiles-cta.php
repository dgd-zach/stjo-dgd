<?php
/**
 * Title: Your Generosity Band (seed source)
 * Categories: ctas
 * Inserter: no
 * Description: Seed source for the synced "Your Generosity Band" pattern. Editors use the synced pattern (Appearance > Design > Patterns), not this file.
 *
 * This file is what stjo_generosity_band_ensure_pattern() turns into the
 * synced wp_block pattern the site actually shows (inc/generosity-band.php),
 * and the fallback the pre-footer renders until that pattern exists. It is
 * kept out of the inserter so the team sees one "Your Generosity Band", the
 * synced one, whose edits land on every page.
 *
 * Every card, tile, heading and link is a native block, so wording, photos,
 * icons and destinations are edited in place. Defaults come from
 * theme-config.json (give.*) and the theme's card art. The wrapper group
 * carries templateLock contentOnly plus a move/remove lock: the Pattern
 * editor offers the content (text, images, links) but not the structure;
 * Modify lifts it when the layout itself must change. The wrapper keeps the
 * stjo-generosity class, which footer.php also reads to skip the automatic
 * band when a page carries these blocks inline.
 *
 * The icon tiles are a Group (stjo-give-tile) holding an Image, a Heading
 * whose inline link is stretched over the whole tile (sections.css), and a
 * Paragraph; the template version is a single <a>. Both shapes share the
 * tile CSS and icon-mask.js.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$stjo_give = function ( $key, $fallback ) {
	return (string) stjo_config_get( 'give.' . $key, $fallback );
};
$stjo_give_once     = $stjo_give( 'once_url', 'https://give.stjo.org/site/Donation2?df_id=6740&6740.donation=form1' );
$stjo_give_monthly  = $stjo_give( 'monthly_url', $stjo_give_once );
$stjo_give_daf      = $stjo_give( 'daf_url', $stjo_give_once );
$stjo_give_memorial = $stjo_give( 'memorial_url', $stjo_give_once );
$stjo_give_planned  = $stjo_give( 'planned_url', 'https://plannedgiving.stjo.org/' );
$stjo_give_ira      = $stjo_give( 'ira_url', 'https://plannedgiving.stjo.org/give-from-your-ira' );
$stjo_give_shop     = $stjo_give( 'shop_url', 'https://give.stjo.org/site/SPageNavigator/Giftstore_Home.html&s_src=GiftStore_Main_Navigation' );
$stjo_give_wishlist = $stjo_give( 'wishlists_url', '/support-us/wishlists-gift-cards/' );

// Root-relative image URLs so a local to staging push cannot drag a hostname along.
$stjo_img = function ( $file ) {
	return esc_url( wp_make_link_relative( stjo_asset( $file ) ) );
};
// Off-site destinations open in a new tab (house rule); the block attrs must
// carry the same target/rel as the anchor or the editor flags the block.
$stjo_ext_attr = function ( $url ) {
	return function_exists( 'stjo_external_link_attrs' ) ? stjo_external_link_attrs( $url ) : '';
};
$stjo_ext_json = function ( $url ) {
	return ( function_exists( 'stjo_is_external_url' ) && stjo_is_external_url( $url ) ) ? ',"linkTarget":"_blank","rel":"noopener noreferrer"' : '';
};

/** Photo card: Cover with heading, hover line and arrow link. */
$stjo_card = function ( $file, $title, $text, $href, $cta ) use ( $stjo_img, $stjo_ext_attr, $stjo_ext_json ) {
	$src = $stjo_img( $file );
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:cover {"url":"' . $src . '","dimRatio":80,"overlayColor":"black","isUserOverlayColor":true,"minHeight":340,"className":"stjo-card"} -->'
		. '<div class="wp-block-cover stjo-card" style="min-height:340px">'
		. '<img class="wp-block-cover__image-background" alt="" src="' . $src . '" data-object-fit="cover"/>'
		. '<span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-80 has-background-dim"></span>'
		. '<div class="wp-block-cover__inner-container">'
		. '<!-- wp:heading {"level":3,"textColor":"light"} -->'
		. '<h3 class="wp-block-heading has-light-color has-text-color">' . $title . '</h3>'
		. '<!-- /wp:heading -->'
		. '<!-- wp:paragraph {"textColor":"light","className":"stjo-card__reveal"} -->'
		. '<p class="has-light-color has-text-color stjo-card__reveal">' . $text . '</p>'
		. '<!-- /wp:paragraph -->'
		. '<!-- wp:buttons --><div class="wp-block-buttons">'
		. '<!-- wp:button {"textColor":"white","className":"is-style-arrow-link"' . $stjo_ext_json( $href ) . '} -->'
		. '<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link has-white-color has-text-color wp-element-button" href="' . esc_url( $href ) . '"' . $stjo_ext_attr( $href ) . '>' . $cta . '</a></div>'
		. '<!-- /wp:button --></div><!-- /wp:buttons -->'
		. '</div></div>'
		. '<!-- /wp:cover -->'
		. '</div><!-- /wp:column -->' . "\n";
};

/** Icon tile: Group with an Image, a linked Heading and a Paragraph. */
$stjo_tile = function ( $icon, $title, $sub, $href ) use ( $stjo_img, $stjo_ext_attr ) {
	$src = $stjo_img( $icon );
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:group {"className":"stjo-give-tile","layout":{"type":"constrained"}} -->'
		. '<div class="wp-block-group stjo-give-tile">'
		. '<!-- wp:image {"width":"56px","height":"56px","sizeSlug":"full","className":"stjo-give-tile__icon"} -->'
		. '<figure class="wp-block-image size-full is-resized stjo-give-tile__icon"><img src="' . $src . '" alt="" style="width:56px;height:56px"/></figure>'
		. '<!-- /wp:image -->'
		. '<!-- wp:heading {"level":3,"className":"stjo-give-tile__title"} -->'
		. '<h3 class="wp-block-heading stjo-give-tile__title"><a href="' . esc_url( $href ) . '"' . $stjo_ext_attr( $href ) . '>' . $title . '</a></h3>'
		. '<!-- /wp:heading -->'
		. '<!-- wp:paragraph {"className":"stjo-give-tile__sub"} -->'
		. '<p class="stjo-give-tile__sub">' . $sub . '</p>'
		. '<!-- /wp:paragraph -->'
		. '</div>'
		. '<!-- /wp:group -->'
		. '</div><!-- /wp:column -->' . "\n";
};
?>
<!-- wp:group {"metadata":{"name":"Your Generosity Band"},"align":"full","className":"stjo-generosity","layout":{"type":"constrained"},"templateLock":"contentOnly","lock":{"move":true,"remove":true}} -->
<div class="wp-block-group alignfull stjo-generosity"><!-- wp:spacer {"height":"var:preset|spacing|medium"} -->
<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:heading {"textAlign":"center","level":2,"textColor":"white"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color">Your Generosity <strong>Changes Everything</strong></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","textColor":"light"} -->
<p class="has-text-align-center has-light-color has-text-color">Every gift, no matter the size or how you give, brings hope, culture and opportunity to a Lakota child at St. Joseph's.</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"var:preset|spacing|small"} -->
<div style="height:var(--wp--preset--spacing--small)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns {"align":"wide","className":"stjo-give-row"} -->
<div class="wp-block-columns alignwide stjo-give-row">
<?php
echo $stjo_card( 'card-4.png', 'Monthly giving', 'Be a DreamMaker with a recurring gift.', $stjo_give_monthly, 'Give Monthly Now' );
echo $stjo_card( 'card-5.png', 'One-Time Gift', 'Make an immediate impact today.', $stjo_give_once, 'Give Now' );
echo $stjo_card( 'card-6.png', 'Donor Advised Fund', 'Give through your DAF account.', $stjo_give_daf, 'Give Now' );
echo $stjo_card( 'card-7.png', 'Memorial Gift', 'Honor a loved one\'s memory.', $stjo_give_memorial, 'Give Now' );
?>
</div>
<!-- /wp:columns -->

<!-- wp:columns {"align":"wide","className":"stjo-give-row"} -->
<div class="wp-block-columns alignwide stjo-give-row">
<?php
echo $stjo_tile( 'article-person.png', 'Estate Legacy Giving', 'Leave a lasting legacy.', $stjo_give_planned );
echo $stjo_tile( 'savings.png', 'Individual Retirement Account', 'Give a tax-smart IRA gift.', $stjo_give_ira );
echo $stjo_tile( 'local-mall.png', 'Our Shop', 'Shop Lakota crafts.', $stjo_give_shop );
echo $stjo_tile( 'redeem.png', 'Wishlists &amp; Gift Cards', 'Give specific items students need.', $stjo_give_wishlist );
?>
</div>
<!-- /wp:columns -->

<!-- wp:spacer {"height":"var:preset|spacing|medium"} -->
<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"yellow","textColor":"blue-900","fontSize":"medium"<?php echo $stjo_ext_json( $stjo_give_once ); ?>} -->
<?php // WP 7.1 serialises a preset fontSize on a button as BOTH has-<size>-font-size and has-custom-font-size on the anchor; leaving either out flags the block in the editor. ?>
<div class="wp-block-button"><a class="wp-block-button__link has-blue-900-color has-yellow-background-color has-text-color has-background has-medium-font-size has-custom-font-size wp-element-button" href="<?php echo esc_url( $stjo_give_once ); ?>"<?php echo $stjo_ext_attr( $stjo_give_once ); ?>>Donate Now</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:spacer {"height":"var:preset|spacing|medium"} -->
<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->
