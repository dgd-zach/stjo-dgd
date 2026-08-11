<?php
/**
 * board-of-directors — a tertiary page under About (Miro sitemap board
 * uXjVHzJD47c, tertiary tier / #adf0c7, linked from the About page's
 * "Our Organization" card row).
 *
 * Split out of the temporary "BOD & strategic plan" holding page (#1226)
 * together with strategic-plan.php.
 *
 * Note: the roster lists Jennifer Renner-Meyer as CEO while the strategic
 * plan quote on stjo.org/about/ credits her as Chief Operating Officer. Both
 * are carried verbatim from the client's own copy; worth confirming with them.
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

/** One roster column: a heading and the names beneath it. */
$group = function ( $title, $names ) {
	$items = '';
	foreach ( $names as $n ) {
		$items .= '<!-- wp:list-item --><li>' . $n . '</li><!-- /wp:list-item -->';
	}
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:heading {"level":2,"fontSize":"medium"} -->'
		. '<h2 class="wp-block-heading has-medium-font-size">' . $title . '</h2>'
		. '<!-- /wp:heading -->'
		. '<!-- wp:list --><ul class="wp-block-list">' . $items . '</ul><!-- /wp:list -->'
		. '</div><!-- /wp:column -->';
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
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">Board of Directors</h1>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->

<?php echo $zigzag; ?>

<!-- wp:group {"metadata":{"name":"Governance"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">The ex-officio members, officers and directors who govern St. Joseph's Indian School.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Board Roster"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:columns -->
<div class="wp-block-columns">
<?php
echo $group( 'Ex-Officio Members', array( 'Fr. Vien Nguyen, SCJ', 'Dn. David Nagel, SCJ' ) );
echo $group( 'Leadership', array( 'Fr. Gregory Schill, SCJ, Chairperson', 'Doug Knust, Vice-Chairperson' ) );
echo $group(
	'Board Members',
	array(
		'Bridget Martin',
		'Terry Johnson',
		'Fr. Jack Kurps, SCJ',
		'Dr. Emmet M. Kenney Jr., MD',
		'Larry Jandreau',
		'Sr. Catherine Bertrand, SSND',
		'Mike Tyrell',
	)
);
echo $group(
	'Non-Voting Members',
	array(
		'Jennifer Renner-Meyer, CEO',
		'Kory Christianson, Executive Director of Development, Secretary',
		'Robyn Knecht, Executive Director of Child Services',
	)
);
?>
</div>
<!-- /wp:columns -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Keep Reading"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><?php echo $sp( 'medium' ); ?>

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons is-layout-flex is-content-justification-center"><!-- wp:button {"className":"is-style-arrow-link"} -->
<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link wp-element-button" href="/about/strategic-plan/">Read the Strategic Plan</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->
