<?php
/**
 * 501c3 — "501(c)(3) Status" (footer page under About; also the Accountability
 * & Reports page's 501(c)(3) card).
 *
 * Copy and photo are verbatim from stjo.org/about/faq/501c3/. The two letters
 * are the live site's PDFs until they are uploaded here (flagged). Seed source
 * only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

$img = stjo_seeded_image( '501c3-1.jpg' );
$alt = $img['id'] ? (string) get_post_meta( $img['id'], '_wp_attachment_image_alt', true ) : '';

echo $title_band( 'About', '501(c)(3) Status' );
?>
<!-- wp:group {"metadata":{"name":"501(c)(3) Status"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><?php echo $sp( 'medium' ); ?>

<!-- wp:media-text {<?php echo $img['id'] ? '"mediaId":' . (int) $img['id'] . ',' : ''; ?>"mediaType":"image","className":"is-style-rounded"} -->
<div class="wp-block-media-text is-stacked-on-mobile is-style-rounded"><figure class="wp-block-media-text__media"><img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $alt ); ?>"<?php echo $img['id'] ? ' class="wp-image-' . (int) $img['id'] . ' size-full"' : ''; ?>/></figure><div class="wp-block-media-text__content"><!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">Your gifts help Lakota children receive a stable home and solid education.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>St. Joseph’s Indian School is a 501(c)(3) non-profit corporation as defined by the US IRS.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Our status is granted as part of a group exemption for members of the US Catholic Conference.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Simply, this means all gifts to St. Joseph’s Indian School are tax-deductible.</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>Group exemption number is #0928</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Federal tax ID # is 46-0235912</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="https://www.stjo.org/wp-content/Media/PDFs/501c3/group-ruling-20251103.pdf">St. Joseph’s 501(c)(3) letter</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="https://www.stjo.org/wp-content/Media/PDFs/501c3/directory.pdf">St. Joseph’s Official Catholic Directory letter</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div></div>
<!-- /wp:media-text -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"50% Limit Organizations"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">50% Limit Organizations</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>St. Joseph’s Indian School is a 50% limit organization based on the following two IRS requirements:</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>Churches and conventions or associations of churches.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Educational Organizations with a regular faculty and curriculum that normally have a regularly enrolled student body attending classes on site.</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>Visit the <a href="https://www.irs.gov/publications/p17">IRS website</a> for more information.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Thank You"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'large' ); ?>

<!-- wp:paragraph {"align":"center","className":"stjo-subhead"} -->
<p class="has-text-align-center stjo-subhead">Phil&aacute;mayaye — thank you — for supporting the Lakota (Sioux) children in need.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Please email us at <a href="mailto:saintjosephs@stjo.org">saintjosephs@stjo.org</a> or call <a href="tel:18003412235">1-800-341-2235</a> if you have additional questions or concerns about your contributions to St. Joseph’s Indian School.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
