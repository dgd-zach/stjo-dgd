<?php
/**
 * lakota-language — "Lakota Language" (Lakota Culture section).
 *
 * Built from the live culture page stjo.org/native-american-culture/lakota-language/.
 * Its two long accordions ride in "Links that open Lightboxes" cards whose
 * bodies are lightbox-content pages (lightbox/lakota-vs-sioux.php,
 * lightbox/revitalize-lakota-language.php). The third accordion ("At St.
 * Joseph's Indian School") is short, so it sits inline at the end rather than
 * behind a modal.
 *
 * Seed source only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

$img_block = function ( $file, $class = 'is-style-rounded' ) {
	$i = stjo_seeded_image( $file );
	if ( ! $i['id'] ) {
		return '';
	}
	$alt = esc_attr( (string) get_post_meta( $i['id'], '_wp_attachment_image_alt', true ) );
	return '<!-- wp:image {"id":' . (int) $i['id'] . ',"sizeSlug":"large","linkDestination":"none","align":"center","className":"' . $class . '"} -->' . "\n"
		. '<figure class="wp-block-image aligncenter size-large ' . $class . '"><img src="' . esc_url( $i['url'] ) . '" alt="' . $alt . '" class="wp-image-' . (int) $i['id'] . '"/></figure>' . "\n"
		. '<!-- /wp:image -->';
};

echo $title_band( 'Lakota Culture', 'Lakota Language' );
?>
<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">Preserving and sharing the Lakota culture is a core part of our mission at St. Joseph&#8217;s Indian School and traditional Lakota language is a vital part of that effort. Like many other indigenous languages around the world, Lakota is in danger of being permanently lost.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>According to the Lakota Language Consortium, Lakota is one of only eight Native American languages with over 5,000 speakers. However, fluent speakers are aging, making it difficult to continue to teach younger generations.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Across South Dakota, schools, pre-schools, daycare centers and other organizations are working to help revitalize and save the Lakota language. Thanks to these efforts, more language resources are available for learning Lakota than ever before.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ) . $img_block( 'Lakota-Language-Map.jpg' ) . $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Learn More"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Explore the Lakota Language</h2>
<!-- /wp:heading -->

<?php
echo $card_rows( array(
	$lightbox_card(
		'What&#8217;s the Difference between Lakota and Sioux?',
		'Oglala, Sicangu, Mnicoujou, Siha Sapa: how the Sioux bands and their dialects (Lakota, Dakota and Nakota) relate, and where each was traditionally spoken.',
		'lakota-vs-sioux',
		'How the dialects relate'
	),
	$lightbox_card(
		'Joining the Fight to Revitalize the Lakota Language',
		'How St. Joseph&#8217;s and the Lakota Language Consortium are giving students the tools to become fluent, plus everyday Lakota words to learn.',
		'revitalize-lakota-language',
		'Read the full story'
	),
), 2 );
echo $sp( 'large' );
?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"At St. Joseph's"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><?php echo $sp( 'large' ); ?>

<?php
$photo = stjo_seeded_image( 'lakotaLanguage1.jpg' );
$palt  = esc_attr( (string) get_post_meta( (int) $photo['id'], '_wp_attachment_image_alt', true ) );
$attrs = serialize_block_attributes( array( 'mediaId' => (int) $photo['id'], 'mediaType' => 'image', 'mediaWidth' => 45, 'imageFill' => true ) );
?>
<!-- wp:media-text <?php echo $attrs; ?> -->
<div class="wp-block-media-text is-stacked-on-mobile is-image-fill-element" style="grid-template-columns:45% auto"><figure class="wp-block-media-text__media"><img src="<?php echo esc_url( $photo['url'] ); ?>" alt="<?php echo $palt; ?>" class="wp-image-<?php echo (int) $photo['id']; ?> size-full" style="object-position:50% 50%"/></figure><div class="wp-block-media-text__content">
<!-- wp:heading -->
<h2 class="wp-block-heading">At St. Joseph&#8217;s Indian School</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Using curriculum from the Lakota Language Consortium, St. Joseph&#8217;s students receive language instruction during their <a href="https://www.stjo.org/programs/lakota-language/">Native American Studies classes</a> twice weekly. <a href="http://blog.stjo.org/st-josephs-language-efforts-earn-recognition-from-lakota-language-consortium/">Their hard work has earned recognition</a> from the Lakota Language Consortium multiple times!</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:media-text -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
