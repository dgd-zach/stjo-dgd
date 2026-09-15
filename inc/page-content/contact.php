<?php
/**
 * contact — "Contact Us" (footer page).
 *
 * Copy is verbatim from stjo.org/contact/, arranged as: ways to reach us
 * (phone, mail, email, LiveChat) with the quick links the live page lists,
 * the Planned Giving mailing address and its links, the Form 1095-C request,
 * directions from I-90, and Visit Chamberlain. Links follow the live page:
 * Luminate forms keep their LO URLs, Employment Opportunities goes to the
 * Careers page, Annual Powwow to Attend a Powwow. "Print shipping labels" is
 * the live site's PDF until it is uploaded here and "Tours" has no page in
 * this sitemap (both flagged). "General Questions" had no destination on the
 * live page; it points at the school's email. The LiveChat line stays because
 * the CXone widget (inc/chatbot.php) is the yellow bubble it describes.
 *
 * Seed source only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

echo $title_band( 'St. Joseph’s Indian School', 'Contact Us' );
?>
<!-- wp:group {"metadata":{"name":"Contact Us By"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Contact us by:</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Phone or Mail</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><a href="tel:18003412235">1-800-341-2235</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>St. Joseph’s Indian School<br>P.O. Box 326<br>Chamberlain, SD 57326</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a href="https://www.stjo.org/wp-content/Media/PDFs/WaysToGive/shippinglabel.pdf">Print shipping labels</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">LiveChat</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>M-Th: 8AM-4:30PM / Friday: 8AM-3PM (CT)</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Click the Yellow Speech Bubble to Start Chat</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Online</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><a href="https://give.stjo.org/site/SPageNavigator/wp_prayer_request.html?s_src=prayer_nav">Prayer Request</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="mailto:saintjosephs@stjo.org">General Questions</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="https://give.stjo.org/site/SPageNavigator/wp_communication_preferences.html">Communication Preferences</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="/about/careers/">Employment Opportunities</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Planned Giving"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"},"anchor":"planned-giving"} -->
<div class="wp-block-group alignfull has-light-background-color has-background" id="planned-giving"><?php echo $sp( 'large' ); ?>

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Planned Giving Mailing Address:</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><a href="tel:18005849200">1-800-584-9200</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>St. Joseph’s Indian School<br>P.O. Box 100<br>Chamberlain, SD 57325-0100</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Planned Giving:</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><a href="https://plannedgiving.stjo.org/charitable-gift-annuities">Annuity Information</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="https://plannedgiving.stjo.org/wills-and-living-trusts">Will Information</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="https://plannedgiving.stjo.org/memorials-and-tribute-gifts">Memorial Gifts</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="https://plannedgiving.stjo.org/appreciated-securities">Securities Information</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Form 1095-C"},"layout":{"type":"constrained","contentSize":"768px"},"anchor":"form-1095-c"} -->
<div class="wp-block-group" id="form-1095-c"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Request an Employer Provided Health Insurance Form (Form 1095-C)</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>To request proof of employer provided health insurance (Form 1095-C) call <a href="tel:16052343249">605-234-3249</a> or email <a href="mailto:pay@stjo.org">pay@stjo.org</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Mail your request to:<br>St. Joseph’s Indian School<br>ATTN: Payroll<br>PO BOX 776<br>Chamberlain SD 57325</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Directions"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"},"anchor":"directions"} -->
<div class="wp-block-group alignfull has-light-background-color has-background" id="directions"><?php echo $sp( 'large' ); ?>

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Directions from Interstate 90 to St. Joseph’s Indian School campus</h2>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-arrow-link"} -->
<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link wp-element-button" href="https://www.google.com/maps/dir//1301+N+Main+St,+Chamberlain,+SD+57325">Chamberlain Map</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">From Interstate 90:</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Take exit 263. Proceed north two miles — passing through downtown Chamberlain. St. Joseph’s Indian School’s entrance is located on the left (north) side of the road.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Turn into campus and continue north to the four-way stop. Turn left. The Akt&aacute; Lakota Museum &amp; Cultural Center will be located in front of you on your left side.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Park at your convenience and enjoy the museum! Campus and museum tours are available upon request at the receptionist’s desk.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Visit Chamberlain"},"layout":{"type":"constrained"},"anchor":"visit"} -->
<div class="wp-block-group" id="visit"><?php echo $sp( 'large' ); ?>

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Visit Chamberlain, South Dakota</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Along with St. Joseph’s Indian School, many Native American youth call Chamberlain, South Dakota and the surrounding Indian reservations home!</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>While visiting St. Joseph’s Indian School and the <a href="https://aktalakota.stjo.org/">Akt&aacute; Lakota Museum &amp; Cultural Center</a>, you can also enjoy a variety of activities in the Chamberlain, South Dakota area.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>From fishing, kayaking and boating on the Missouri River to hunting and touring the rural landscape, there’s something for everyone to enjoy.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Sites and Locations of Interest:</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><a href="https://www.chamberlainsd.com/hotels-resorts">Lodging</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Tours</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="/lakota-culture/powwow-dance/attend-a-powwow/">Annual Powwow</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="https://aktalakota.stjo.org/">Akt&aacute; Lakota Museum &amp; Cultural Center</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>For more information on the Chamberlain area, visit:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><a href="https://www.chamberlainsd.com/">chamberlainsd.com</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="https://www.travelsouthdakota.com/">travelsouthdakota.com</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
