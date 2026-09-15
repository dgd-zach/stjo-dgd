<?php
/**
 * careers — "Careers" (footer page, kept under About so the footer's
 * /about/careers/ link resolves).
 *
 * Copy and photo are verbatim from stjo.org/about/career-opportunities/.
 * The live call to action goes to the sjiskids.org careers portal; it does
 * here too. Seed source only — edit live content in the WP editor after
 * seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

$img = stjo_seeded_image( 'Catholic-Schools-Week-2018.jpg' );
$alt = $img['id'] ? (string) get_post_meta( $img['id'], '_wp_attachment_image_alt', true ) : '';

echo $title_band( 'About', 'Careers' );
?>
<!-- wp:group {"metadata":{"name":"Careers"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><?php echo $sp( 'medium' ); ?>

<!-- wp:media-text {<?php echo $img['id'] ? '"mediaId":' . (int) $img['id'] . ',' : ''; ?>"mediaType":"image","className":"is-style-rounded"} -->
<div class="wp-block-media-text is-stacked-on-mobile is-style-rounded"><figure class="wp-block-media-text__media"><img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $alt ); ?>"<?php echo $img['id'] ? ' class="wp-image-' . (int) $img['id'] . ' size-full"' : ''; ?>/></figure><div class="wp-block-media-text__content"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">St. Joseph’s career opportunities</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">Employment at St. Joseph’s is more than a job — it is life’s great work!</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>From houseparenting to teaching, there is always work to be done at St. Joseph’s Indian School!</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="https://sjiskids.org/careers/">See Current Openings</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:media-text -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Who We Look For"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Joining our team</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Joining our team requires commitment to excellence, a positive attitude and respect for the Lakota (Sioux) culture. We seek individuals with the following qualities:</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>Extreme dedication</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Enormous capacity to share and care</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Team player for betterment of each child</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Participation and appreciation of Lakota culture and spiritual development</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Sense of mission in helping others less fortunate</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Ability to motivate young people to think, respond and learn</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Good stamina</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>A preference for order</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-arrow-link"} -->
<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link wp-element-button" href="https://sjiskids.org/careers/">Learn more about current career opportunities today!</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
