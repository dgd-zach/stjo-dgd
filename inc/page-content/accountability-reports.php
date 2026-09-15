<?php
/**
 * accountability-reports — "Accountability & Reports" (About section).
 *
 * General Content template (no dedicated frame on the sitemap board). The
 * board hangs three tertiary pages off this one, so each is a card here:
 * Annual Financial Report, Student Bill of Rights, Protecting Students. The
 * live site spreads the rest of this subject over several FAQ sub-pages
 * (awards-and-accreditation, 501c3, charity-rating-bbb); that copy is long,
 * so per Zach it rides in "Links that open Lightboxes" cards whose bodies are
 * lightbox-content pages (inc/page-content/lightbox/*.php), keeping the page
 * itself light; 501(c)(3) status is a footer page of its own (/about/501c3/). Intro copy is verbatim from financial-report and
 * awards-and-accreditation. The footer's Awards link points at #awards here.
 *
 * Seed source only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

echo $title_band( 'About', 'Accountability &amp; Reports' );
?>
<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">Because of generous friends, St. Joseph’s Indian School serves Lakota (Sioux) children and their families, just as we have since 1927. Thanks to the generosity of many, admission and all services to Native American children and families remain free-of-charge.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>St. Joseph’s Indian School is accredited by the Council on Accreditation (COA), which sets rigorous, evidence-based standards for nonprofit governance and ethical best practices. Since 1995, our organization has elected to undergo COA accreditation. Each year, we submit documentation to demonstrate our adherence to ethical operational guidelines, and every four years, we undergo a comprehensive reaccreditation process, including detailed electronic submissions and on-site reviews.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>This ongoing accreditation reassures donors that we remain committed to the highest standards of service, safety and integrity in fulfilling our mission.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Awards and Accreditation"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"},"anchor":"awards"} -->
<div class="wp-block-group alignfull has-light-background-color has-background" id="awards"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Awards &amp; Accreditation</h2>
<!-- /wp:heading -->

<?php
// Lightbox cards only in this band (info cards live in the next one): the two
// card kinds are styled differently and must not share a row.
echo $card_rows( array(
	$lightbox_card( 'Awards', 'Top-Rated Charity by GreatNonprofits every year since 2017, the Platinum Transparency Seal from GuideStar by Candid and a Communicator Award of Excellence.', 'awards', 'See Our Awards' ),
	$lightbox_card( 'Accreditation', 'St. Joseph’s Indian School is accredited by the Council on Accreditation (COA), which sets rigorous, evidence-based standards for nonprofit governance and ethical best practices.', 'accreditation', 'About Our Accreditation' ),
	$lightbox_card( 'Memberships', 'A member school of the American Indian Catholic Schools Network of the University of Notre Dame and of the Coalition of Residential Excellence (CORE).', 'memberships', 'About Our Memberships' ),
	$lightbox_card( 'Charity Rating', 'Like many other nonprofit organizations, St. Joseph’s Indian School does not have a Better Business Bureau charity rating because a conscious decision was made to not submit information requested by the BBB’s Wise Giving Alliance.', 'charity-rating', 'Why We Are Not BBB Rated', 'is-style-text', true ),
), 2 );
echo $sp( 'large' );
?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Reports and Policies"},"align":"full","layout":{"type":"constrained"},"anchor":"reports"} -->
<div class="wp-block-group alignfull" id="reports"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Reports &amp; Policies</h2>
<!-- /wp:heading -->

<?php
echo $card_rows( array(
	$card( 'Annual Financial Report', 'For every dollar raised, 66% goes directly to the children attending St. Joseph’s Indian School, their families living in reservation communities, and toward cultural and faith development.', '/about/accountability-reports/annual-financial-report/', 'Read the Annual Financial Report' ),
	$card( '501(c)(3) Status', 'St. Joseph’s Indian School is a 501(c)(3) non-profit corporation as defined by the US IRS. Simply, this means all gifts to St. Joseph’s Indian School are tax-deductible.', '/about/501c3/', 'About Our 501(c)(3) Status' ),
	$card( 'Student Bill of Rights', 'Equal treatment, the necessities of life, freedom of expression, protection from abuse, medical and dental care, religious freedom, education and recreation, and more: the rights every St. Joseph’s student is guaranteed.', '/about/accountability-reports/student-bill-of-rights/', 'Read the Student Bill of Rights' ),
	$card( 'Protecting Students', 'Inappropriate behavior is unequivocally not tolerated at St. Joseph’s Indian School. A zero-tolerance policy is strictly enforced throughout our programs.', '/about/accountability-reports/protecting-students/', 'How We Protect Students' ),
), 2 );
echo $sp( 'large' );
?></div>
<!-- /wp:group -->
