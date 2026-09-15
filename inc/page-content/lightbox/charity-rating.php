<?php
/**
 * Lightbox content: Charity Rating (opened from Accountability & Reports).
 * Verbatim from stjo.org/about/faq/charity-rating-bbb/. Image rules: the two
 * portrait photos sit in crop-to-fill media-texts under their headings, the
 * landscape photo sits full width above its heading; each live caption is the
 * section's lead line.
 *
 * @package stjo
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require __DIR__ . '/_lb-helpers.php';

echo $lb_h3( 'Why are you not rated by the Better Business Bureau (BBB)?' );
echo $lb_media_text( 'CharityRating-BBB1.jpg',
	$lb_sub( 'St. Joseph’s is fully accredited and meets all the academic standards set forth by the state of South Dakota.' )
	. $lb_p( 'Like many other nonprofit organizations, St. Joseph’s Indian School does not have a Better Business Bureau charity rating because a conscious decision was made to not submit information requested by the BBB’s Wise Giving Alliance. Perhaps the most important factor influencing our decision is a firm belief that the Council on Accreditation provides a more thorough evaluation of our organization’s credibility and thus, our value. This is achieved because the COA’s evaluation process is based on comparisons to similar programs across the nation, the effectiveness of our programs, and the impact felt by the individuals in the communities we serve.' ),
	35
);
?>
<!-- wp:paragraph -->
<p>Through the Council on Accreditation’s extensive audit process, we receive evaluation by a panel of experts in social services fields. The evaluation, including site visits and interviews with St. Joseph’s personnel and families served, assesses more than just finances. St. Joseph’s Indian School receives ratings in:</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>Ethical Practice</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Financial Management</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Governance</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Human Resources</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>Performance &amp; Quality Improvement</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Risk Prevention &amp; Management</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Best Practices in Child Care</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:paragraph -->
<p>All areas are then measured against national standards for service organizations. COA’s standards emphasize that St. Joseph’s Indian School’s services are: accessible, appropriate, culturally responsive, evidence-based, outcome-oriented and provided by a skilled and supported workforce.</p>
<!-- /wp:paragraph -->

<?php
echo $lb_gap();
echo $lb_h3( 'How are St. Joseph’s Indian School funds spent?' );
echo $lb_media_text( 'CharityRating-BBB2.jpg',
	$lb_sub( 'St. Joseph’s homes are operated just like a normal family home; students cook and eat together, do homework and chores and, of course, have lots of fun!' )
	. $lb_p( 'St. Joseph’s Indian School spends fundraising dollars to attract new donors to fund not only current programs, but future programs as well. Our ultimate goal is to decrease fundraising costs and increase the percentage of funds going towards programs: education, residential care, promotion and preservation of Lakota culture, and faith development.' )
	. $lb_p( 'See how much of your dollar goes directly to the Lakota children and review our <a href="/about/accountability-reports/annual-financial-report/">most recent financial information</a>.' ),
	35,
	true
);
?>
<!-- wp:paragraph -->
<p>We are dedicated to balancing our program needs with improving the efficiency of our fundraising; without effective fundraising, programs the Lakota children need would have to be cut. We strive to be good stewards of the gifts we are given and provide for the Lakota boys and girls in accordance with our mission statement:</p>
<!-- /wp:paragraph -->

<?php echo $lb_gap(); ?>
<!-- wp:quote -->
<blockquote class="wp-block-quote"><!-- wp:paragraph -->
<p>St. Joseph’s Indian School, an apostolate of the Congregation of the Priests of the Sacred Heart, partners with Native American children and families to educate for life — mind, body, heart and spirit.</p>
<!-- /wp:paragraph --></blockquote>
<!-- /wp:quote -->

<?php
echo $lb_gap();
echo $lb_photo( 'CharityRating-BBB3.jpg' );
echo $lb_h3( 'About the Council on Accreditation' );
echo $lb_sub( 'Beyond academics, St. Joseph’s students learn about their traditional Lakota (Sioux) culture by participating in our annual powwow and other cultural activities.' );
echo $lb_p( 'COA was founded in 1977 and maintains a rigorous assessment process designed to identify providers who have set high performance standards and made a commitment to deliver the highest quality services to children in need. <a href="https://www.social-current.org/impact-areas/coa-accreditation/">Learn more about the Council on Accreditation.</a>' );
echo $lb_p( 'Thank you on behalf of our Lakota (Sioux) students who have benefitted from your generous giving. W&oacute;phila t&#543;&aacute;&#331;ka — many thanks — for your generosity!' );
