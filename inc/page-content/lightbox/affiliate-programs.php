<?php
/**
 * Lightbox content: Affiliate Programs (opened from Wishlists, Gift Cards &
 * Affiliate Programs). Verbatim from
 * stjo.org/help-native-americans/other-ways-to-give/ (accordion 4).
 * Each program is one media-text block: brand logo (30% column, top-aligned)
 * beside its heading, description and an arrow-link button.
 *
 * @package stjo
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require __DIR__ . '/_lb-helpers.php';

$aff = function ( $file, $name, $desc, $href, $label ) {
	$img = stjo_seeded_image( $file );
	$id  = (int) $img['id'];
	$url = esc_url( $img['url'] );
	$alt = esc_attr( (string) get_post_meta( $id, '_wp_attachment_image_alt', true ) );
	$attrs = array( 'mediaId' => $id, 'mediaType' => 'image', 'mediaWidth' => 30, 'verticalAlignment' => 'top' );
	return '<!-- wp:media-text ' . serialize_block_attributes( $attrs ) . ' -->' . "\n"
		. '<div class="wp-block-media-text is-stacked-on-mobile is-vertically-aligned-top" style="grid-template-columns:30% auto">'
		. '<figure class="wp-block-media-text__media"><img src="' . $url . '" alt="' . $alt . '" class="wp-image-' . $id . ' size-full"/></figure>'
		. '<div class="wp-block-media-text__content">' . "\n"
		. '<!-- wp:heading {"level":3} -->' . "\n" . '<h3 class="wp-block-heading">' . $name . '</h3>' . "\n" . '<!-- /wp:heading -->' . "\n\n"
		. '<!-- wp:paragraph -->' . "\n" . '<p>' . $desc . '</p>' . "\n" . '<!-- /wp:paragraph -->' . "\n\n"
		. '<!-- wp:buttons -->' . "\n"
		. '<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-arrow-link"} -->' . "\n"
		. '<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $href ) . '">' . $label . '</a></div>' . "\n"
		. '<!-- /wp:button --></div>' . "\n" . '<!-- /wp:buttons -->'
		. '</div></div>' . "\n" . '<!-- /wp:media-text -->' . "\n";
};
?>
<!-- wp:paragraph -->
<p>You can make a difference for Lakota (Sioux) children in need just by completing daily activities. From scanning box tops to shopping at Wal-mart, you can help Native American youngsters every day.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>We are proud to be part of the following affiliate programs:</p>
<!-- /wp:paragraph -->

<?php
echo $lb_gap() . $aff(
	'boxtops4education.jpg',
	'Box Tops for Education',
	'Use the Box Tops app to scan your store receipt, find participating products and instantly add cash to St. Joseph&#8217;s Indian School&#8217;s earnings online. From playground equipment to technology to library books, St. Joseph&#8217;s can use this money to help teachers and students get the supplies they need.',
	'https://www.boxtops4education.com/',
	'Learn more about Box Tops for Education'
);
echo $lb_gap() . $aff(
	'iGive.jpg',
	'iGive.com',
	'The optional iGive Button is a simple web browser app, easy to install and uninstall. It automatically activates at participating stores. Shop normally (no special codes, no special anything) at any of the approximate 2,300 stores. The button is working in the background to let them know you&#8217;re helping when you shop.',
	'https://www.igive.com/welcome/lp16/cr64a.cfm',
	'Learn more about iGive'
);
echo $lb_gap() . $aff(
	'goodshopGive.jpg',
	'Goodshop',
	'Shop at your favorite stores through Goodshop. Once you&#8217;ve made your purchase with a participating store, Goodshop makes a donation in your honor to St. Joseph&#8217;s Indian School.',
	'https://www.goodsearch.com/nonprofit/st-josephs-indian-school-sjis.aspx',
	'Learn more about Goodshop'
);
echo $lb_gap() . $aff(
	'network4good.jpg',
	'Network for Good',
	'Network for Good&#8217;s nonprofit donor-advised fund uses the Internet and mobile technology to securely and efficiently distribute thousands of donations from donors to their favorite charities each year.',
	'https://www.networkforgood.com/charities-to-donate-to/',
	'Learn more about Network for Good'
);
echo $lb_gap() . $aff(
	'loaves4learning.jpg',
	'Loaves for Learning',
	'The Loaves 4 Learning program is open to eligible public and private K-12 schools throughout our distribution area. Collect eligible UPCs (universal product codes) and St. Joseph&#8217;s Indian School can earn as much as $10,000 per year that can be used for books, computers, sports, building repairs and more.',
	'http://www.loaves4learning.com/index.html',
	'Learn more about Loaves for Learning'
);
