<?php
/**
 * privacy-policy — "Privacy Policy" (footer utility page).
 *
 * Replaces WordPress's placeholder privacy text with the client's own policy,
 * verbatim from stjo.org/privacy-policy/. Links follow the live page: the
 * detailed online policy PDF stays the live site's file until it is uploaded
 * here (flagged), Luminate forms keep their LO URLs (donations use the
 * configured one-time form), "download a digital product" pointed at
 * stjo.org/free which has no home in this sitemap (left as plain text,
 * flagged), and "email updates" goes to the footer sign-up on this site.
 *
 * Seed source only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

$pdf   = 'https://www.stjo.org/wp-content/Media/PDFs/PrivacyPolicy/online-privacy-policy.pdf';
$store = 'https://give.stjo.org/site/Ecommerce?store_id=4303&s_src=GiftStore_Privacy-Policy-Pg';
$prefs = 'https://give.stjo.org/site/SPageNavigator/wp_communication_preferences.html';

echo $title_band( 'St. Joseph’s Indian School', 'Privacy Policy' );
?>
<!-- wp:group {"metadata":{"name":"Privacy Policy"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">As an organization serving Lakota (Sioux) children in an educational and residential setting, privacy is very important to us at St. Joseph’s Indian School. We also respect your right to privacy and are happy to share the following policy with you.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a href="<?php echo esc_url( $pdf ); ?>">View St. Joseph’s Indian School’s Detailed Online Privacy and Security Policy.</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Friends who <a href="<?php echo esc_url( $store ); ?>">place orders</a>, <a href="<?php echo $give_once; ?>">make donations</a>, send eCards, download a digital product or <a href="#footer-newsletter">request our email updates</a> are added to our mailing list. From this information, we track transactions you make so we can better meet your needs. Please contact us at <a href="tel:18003412235">1-800-341-2235</a> or <a href="mailto:saintjosephs@stjo.org">saintjosephs@stjo.org</a> if you have questions.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Occasionally, St. Joseph’s shares names and postal addresses with other respected organizations, as is standard practice for most nonprofits. You can also <a href="https://www.dmachoice.org/">visit the DMAchoice to have your information removed from all lists</a>. Mobile information will not be shared with third parties/affiliates for their marketing/promotional purposes. All the above categories exclude text messaging originator opt in data and consent; this information will not be shared with any third parties. Once a user has received a text message from St. Joseph’s Indian School, they can expect to receive donor care messages, links to student/campus news and/or fundraising appeals. Message frequency will vary, and data rates may apply. Users can opt out of SMS/MMS messaging at any time by replying ‘STOP’ to any text message or by calling 1-800-341-2235.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>We make select portions of our postal mailing list available to carefully screened organizations. However, you always have the option to be removed from our rental list. To keep your information private, please email us at <a href="mailto:saintjosephs@stjo.org">saintjosephs@stjo.org</a>, fill out our online <a href="<?php echo esc_url( $prefs ); ?>">Communication Preferences Form</a> or call 1-800-341-2235.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Because our friends are a special part of our thiy&oacute;&scaron;paye — extended family — we do occasionally receive birth dates from various sources. This information is used so the Lakota children at St. Joseph’s are able to send you birthday greetings. If you prefer to be excluded from this program, please email <a href="mailto:saintjosephs@stjo.org">saintjosephs@stjo.org</a> or call us toll-free at 1-800-341-2235.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">General Web Site Policies</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We DO NOT sell or share email addresses. Email addresses are added to our files through several means:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>Making an online donation</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Sending an eCard</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Asking to receive email updates</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Downloading a St. Joseph’s digital product</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Providing email information to St. Joseph’s Indian School via postal mail or telephone</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Entering St. Joseph’s online raffles</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Completing St. Joseph’s online surveys</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Purchasing items from <a href="<?php echo esc_url( $store ); ?>">St. Joseph’s online gift store</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Purchasing items from the <a href="https://aktalakota.stjo.org/">Akt&aacute; Lakota Museum &amp; Cultural Center</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>We do not randomly collect information on our website. We gather general information regarding pages visited, time spent on each page and resources accessed. Additionally, we enter information volunteered by visitors such as survey information and sweepstake entries into our system for tracking and corresponding purposes. We do not sell or share email addresses.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Friends who donate online are opted in to receive email correspondence. If you decide to opt out, you will not receive any solicitation email from us; however, you may receive customer service emails regarding your gifts, information changes, etc.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Also, if you are opted in to our email program, you may receive regular emails from the <a href="https://aktalakota.stjo.org/">Akt&aacute; Lakota Museum &amp; Cultural Center</a>, which is an outreach program supported by and located on the campus of St. Joseph’s Indian School.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>In dedication to serving the Lakota children to the best of our ability, we are also more than happy to honor all your reasonable wishes and instructions. We encourage friends to contact us via phone, postal mail or email at any time with concerns, changes, questions or comments.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>For further information on your rights as a consumer and how to remove your name from all mailing lists, please visit <a href="https://www.dmachoice.org/">the DMAchoice website</a>.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a href="<?php echo esc_url( $pdf ); ?>">For detailed information regarding specific online policy and security issues, please view our online privacy policy now!</a></p>
<!-- /wp:paragraph -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
