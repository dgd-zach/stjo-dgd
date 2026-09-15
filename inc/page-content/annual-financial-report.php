<?php
/**
 * annual-financial-report — "Annual Financial Report" (tertiary page under
 * Accountability & Reports).
 *
 * Copy is verbatim from stjo.org/about/faq/financial-report/: intro, then the
 * "Here is a look at your 2025 impact" list as a count-up stats band (Stat
 * Figure blocks on the light background: the sky photo the home band uses is
 * too dark at this band's height for the blue figures), the Donor Bill of Rights link and the donate call. The live
 * page shows "2025 Financials / Annual Report / Impact Summary" as headings
 * with no documents behind them, so nothing is linked there (flagged). The
 * Donor Bill of Rights PDF is the live site's file until it is uploaded here.
 * "Accomplishments you made possible" linked to the live success stories, so
 * it points at Student Stories.
 *
 * Seed source only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

/** One impact statistic: count-up figure over its label. */
$stat = function ( $value, $label ) {
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:stjo/stat-figure {"value":"' . esc_attr( $value ) . '"} /-->'
		. '<!-- wp:paragraph {"align":"center","className":"stjo-stat__label"} -->'
		. '<p class="has-text-align-center stjo-stat__label">' . $label . '</p>'
		. '<!-- /wp:paragraph -->'
		. '</div><!-- /wp:column -->';
};

echo $title_band( 'Accountability &amp; Reports', 'Annual Financial Report' );
?>
<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">Accomplishments you made possible</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Because of generous friends, St. Joseph’s Indian School serves Lakota (Sioux) children and their families, just as we have since 1927. Thanks to the generosity of many, admission and all services to Native American children and families remain free-of-charge.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"2025 Impact"},"align":"full","backgroundColor":"light","className":"stjo-stats","layout":{"type":"constrained"},"anchor":"impact"} -->
<div class="wp-block-group alignfull stjo-stats has-light-background-color has-background" id="impact"><?php echo $sp( 'medium' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Here is a look at your 2025 impact:</h2>
<!-- /wp:heading -->

<!-- wp:columns {"className":"stjo-stats__row"} -->
<div class="wp-block-columns stjo-stats__row"><?php
echo $stat( '98%', 'Private individuals continue to provide nearly all our support — 98% to be more exact.' );
echo $stat( '66%', 'For every dollar raised, 66% goes directly to the children attending St. Joseph’s Indian School, their families living in reservation communities, and toward cultural and faith development.' );
echo $stat( '119', 'students danced at powwow.' );
echo $stat( '24,274', 'dining hall meals served' );
?></div>
<!-- /wp:columns -->

<!-- wp:columns {"className":"stjo-stats__row"} -->
<div class="wp-block-columns stjo-stats__row"><?php
echo $stat( '22,610', 'books were distributed by the Bookmobile.' );
echo $stat( '3,707', 'campus health center visits.' );
echo $stat( '$247,800', 'was awarded in scholarships to students pursuing higher education.' );
?></div>
<!-- /wp:columns -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Read More"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'large' ); ?>

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-arrow-link"} -->
<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link wp-element-button" href="https://www.stjo.org/wp-content/Media/PDFs/WhyHelp/bill-of-rights.pdf">Read about our Donor Bill of Rights</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-arrow-link"} -->
<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link wp-element-button" href="/student-stories/">Accomplishments you made possible</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:cover {"url":"<?php echo esc_url( stjo_asset( 'cover.png' ) ); ?>","dimRatio":70,"overlayColor":"blue-900","isUserOverlayColor":true,"minHeight":418,"metadata":{"name":"Donate CTA"},"align":"full","className":"stjo-dreammaker","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull stjo-dreammaker" style="min-height:418px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( stjo_asset( 'cover.png' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-blue-900-background-color has-background-dim-70 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":2,"textColor":"white","fontSize":"xxl"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color has-xxl-font-size">Donate to the Lakota Children</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color">Thanks to the generosity of many, admission and all services to Native American children and families remain free-of-charge.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"brand-dark","className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link has-brand-dark-color has-white-background-color has-text-color has-background wp-element-button" href="<?php echo $give_once; ?>">Donate Now</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->
