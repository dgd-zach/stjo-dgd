<?php
/**
 * strategic-plan — a tertiary page under About (Miro sitemap board
 * uXjVHzJD47c, tertiary tier / #adf0c7, linked from the About page's
 * "Our Organization" card row).
 *
 * Split out of the temporary "BOD & strategic plan" holding page (#1226)
 * together with board-of-directors.php. Copy is verbatim from stjo.org/about/.
 *
 * Seed source only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sp = function ( $size ) {
	return '<!-- wp:spacer {"height":"var:preset|spacing|' . $size . '"} -->'
		. '<div style="height:var(--wp--preset--spacing--' . $size . ')" aria-hidden="true" class="wp-block-spacer"></div>'
		. '<!-- /wp:spacer -->';
};

$zigzag = '<!-- wp:separator {"className":"alignfull"} --><hr class="wp-block-separator has-alpha-channel-opacity alignfull"/><!-- /wp:separator -->';

/** One limb of the tree framework. */
$limb = function ( $title, $text ) {
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:heading {"level":3,"textColor":"brand-dark","fontSize":"medium"} -->'
		. '<h3 class="wp-block-heading has-brand-dark-color has-text-color has-medium-font-size">' . $title . '</h3>'
		. '<!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>' . $text . '</p><!-- /wp:paragraph -->'
		. '</div><!-- /wp:column -->';
};

/** One of the five priorities, as a native details accordion. */
$priority = function ( $title, $text ) {
	return '<!-- wp:details --><details class="wp-block-details"><summary>' . $title . '</summary>'
		. '<!-- wp:paragraph --><p>' . $text . '</p><!-- /wp:paragraph -->'
		. '</details><!-- /wp:details -->';
};
?>
<!-- wp:group {"metadata":{"name":"Page Title Band"},"align":"full","textColor":"white","className":"stjo-page-title-band","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-page-title-band has-white-color has-text-color"><!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"align":"center","textColor":"yellow","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-yellow-color has-text-color">About Us</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":1,"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">St. Joseph's Indian School Strategic Plan 2023</h1>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->

<?php echo $zigzag; ?>

<!-- wp:group {"metadata":{"name":"Looking Ahead"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Since 1927, St. Joseph's Indian School has been a place of hope, learning and care for Native American children and their families. Looking ahead, we see St. Joseph's as more than a school. It will be a national model defining what holistic care and Native-serving education can be for generations to come.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:pullquote {"align":"wide","className":"stjo-pull-quote"} -->
<figure class="wp-block-pullquote alignwide stjo-pull-quote"><blockquote><p>&#8220;This plan reflects both the strength of our foundation and the responsibility we carry for the future. We are building on nearly a century of mission-driven work while continuing to grow and evolve to meet the changing needs of Native American children and families.&#8221;</p><cite>Jennifer Renner-Meyer, Chief Operating Officer</cite></blockquote></figure>
<!-- /wp:pullquote -->

<!-- wp:group {"metadata":{"name":"Strategic Framework"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Strategic Framework</h2>
<!-- /wp:heading -->

<!-- wp:group {"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">In Native American culture and Catholic spirituality, the tree is a sacred symbol of life. Using this metaphor, our framework shows how our roots, trunk, branches and fruits work together to sustain and strengthen our work.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:columns -->
<div class="wp-block-columns">
<?php
echo $limb( 'Roots — Who We Are', 'What grounds us and connects us to our purpose.' );
echo $limb( 'Trunk — How We Stand', 'What holds us up and allows us to carry out our mission each day.' );
echo $limb( 'Branches — Why We Grow', 'Where our daily efforts reach outward to shape lives, build trust and help define the future of care and education.' );
echo $limb( 'Fruit — What We Produce', 'The harvest we see when our roots are strong, our trunk is steady and our branches reach with purpose.' );
?>
</div>
<!-- /wp:columns -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Five Key Priorities"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Five Key Priorities</h2>
<!-- /wp:heading -->

<!-- wp:group {"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">While working within this framework, we have five key priorities:</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:group {"className":"stjo-accordion","layout":{"type":"constrained","contentSize":"860px"}} -->
<div class="wp-block-group stjo-accordion">
<?php
echo $priority(
	'1: Broaden Access &amp; Lifelong Connection',
	'We&#8217;re expanding opportunities for Native American children and families to join our community — and stay connected for life. By strengthening partnerships with tribes and local communities, we ensure every open enrollment spot is filled with mission-fit students and build a thriving network of alumni who return as mentors, volunteers and leaders. This includes ongoing healing and reconciliation efforts that honor past experiences and deepen trust across generations.'
);
echo $priority(
	'2: Cultivate a Workforce for the Future',
	'We&#8217;re investing in the people who make our mission possible. That means attracting qualified, mission aligned candidates and creating clear pathways for growth so every staff member — from new hires to seasoned supervisors — has the tools to lead with purpose. A strengthened development continuum ensures our team is prepared, supported and inspired for the work ahead.'
);
echo $priority(
	'3: Strengthen Influence',
	'We&#8217;re elevating St. Joseph&#8217;s role as a leader in holistic care, philanthropy and Native American serving education. By sharing student success more frequently and engaging in meaningful collaboration with tribal partners, elders and community leaders, we contribute insight that strengthens impact nationwide. Our voice grows stronger as our relationships deepen.'
);
echo $priority(
	'4: Build a Culture of Unity &amp; Clarity',
	'We&#8217;re aligning how we work so our entire organization moves forward as one team. Streamlined systems and shared tools reduce duplication, improve communication and ensure consistent representation of St. Joseph&#8217;s with families, donors and partners. Clear processes help every department work together with confidence and purpose.'
);
echo $priority(
	'5: Ensure Long Term Sustainability',
	'We&#8217;re safeguarding the future of St. Joseph&#8217;s through responsible stewardship of our financial, operational and environmental resources. Guided by Catholic values and Native American ways of living in balance with creation, we&#8217;re reducing our carbon footprint, strengthening donor sustainability and growing long term giving. These efforts ensure we can serve children and families for generations to come.'
);
?>
</div>
<!-- /wp:group -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-arrow-link"} -->
<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link wp-element-button" href="/about/board-of-directors/">Meet the Board of Directors</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
