<?php
/**
 * attend-a-powwow — "Attend a Powwow", the tertiary page linked from
 * Powwow & Dance (Miro sitemap board uXjVHzJD47c, tertiary tier / #adf0c7).
 *
 * Structure follows the Attend a Powwow frame (3458764674155130169): title
 * band, intro, Schedule at a Glance as three day columns, What to Expect,
 * Plan Your Visit, then a link back up to Powwow & Dance.
 *
 * Copy verbatim from
 * stjo.org/programs/native-american-cultural-awareness/attend-our-powwow/.
 * The dates below are that page's live 2026 schedule and will need updating
 * each year; the schedule itself carries the site's own "(subject to change)".
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

/** One day of the schedule: an H3 and a definition-style list of times. */
$day = function ( $label, $rows ) {
	$items = '';
	foreach ( $rows as $r ) {
		$items .= '<!-- wp:list-item --><li><strong>' . $r[0] . '</strong><br>' . $r[1] . '</li><!-- /wp:list-item -->';
	}
	return '<!-- wp:column --><div class="wp-block-column">'
		. '<!-- wp:heading {"level":3,"fontSize":"medium"} -->'
		. '<h3 class="wp-block-heading has-medium-font-size">' . $label . '</h3>'
		. '<!-- /wp:heading -->'
		. '<!-- wp:list {"className":"stjo-schedule"} --><ul class="wp-block-list stjo-schedule">' . $items . '</ul><!-- /wp:list -->'
		. '</div><!-- /wp:column -->';
};

$thursday = array(
	array( '8:00 am – 4:00 pm', 'Guest Registration (Recreation Center)' ),
	array( '9:00 am – 3:00 pm', 'Coffee Vendor — &#8220;Coffee Cantina&#8221; (Rec Center)' ),
	array( '10:00 am – 12:00 pm', 'Equine Therapy Center Open House' ),
	array( '1:00 pm – 3:00 pm', 'Circle of Culture: Alumni Teaching Traditions (Recreation Center)' ),
);

$friday = array(
	array( '8:00 am – 4:00 pm', 'Guest Registration (Recreation Center)' ),
	array( '8:00 am – 5:00 pm', 'Self-Tour Our Lady of the Sioux Chapel' ),
	array( '9:00 am – 11:15 am', 'Cultural Activities' ),
	array( '9:00 am – 3:00 pm', 'Coffee Vendor — &#8220;Coffee Cantina&#8221; (Rec Center)' ),
	array( '11:00 am – 1:30 pm', 'Food Trucks (Wisdom Circle)' ),
	array( '1:00 pm – 3:00 pm', 'School &amp; Health Center Open House' ),
	array( '3:30 pm – 4:15 pm', 'Cultural Performance' ),
	array( '4:30 pm – 6:30 pm', 'Alumni Thiy&oacute;&scaron;paye Social' ),
);

$saturday = array(
	array( '8:00 am – 4:00 pm', 'Self-tour Our Lady of the Sioux Chapel' ),
	array( '8:30 am – 11:00 am', 'Guest Registration (Recreation Center)' ),
	array( '9:00 am – 11:00 am', 'Student Homes Open House' ),
	array( '10:00 am – 4:30 pm', 'Concession Stand' ),
	array( '11:45 am', 'Powwow Grounds Blessing' ),
	array( '12:00 pm', 'Grand Entry &amp; Powwow' ),
	array( '5:00 pm', 'Mass' ),
	array( '5:30 pm', 'Complimentary Meal &amp; Powwow Prizes' ),
);

$expect = array(
	'Everyone is welcome. Attend one day or enjoy the full weekend.',
	'Powwow dance competitions',
	'Traditional Lakota foods',
	'Guided tours of school, health center and homes',
	'Cultural performances and activities',
	'Equine Therapy Center experience – Meet the horses',
);

$plan = array(
	'Pre-register online or by calling 855-777-3433',
	'Register upon campus arrival at the Recreation Center',
	'Wear comfortable shoes; bring a jacket',
	'Lawn chairs or blankets encouraged',
	'No pets',
	'No smoking',
	'No camping on campus',
);

$list = function ( $items ) {
	$out = '';
	foreach ( $items as $i ) {
		$out .= '<!-- wp:list-item --><li>' . $i . '</li><!-- /wp:list-item -->';
	}
	return '<!-- wp:list --><ul class="wp-block-list">' . $out . '</ul><!-- /wp:list -->';
};
?>
<!-- wp:group {"metadata":{"name":"Page Title Band"},"align":"full","textColor":"white","className":"stjo-page-title-band","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-page-title-band has-white-color has-text-color"><!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"align":"center","textColor":"yellow","className":"is-style-eyebrow"} -->
<p class="has-text-align-center is-style-eyebrow has-yellow-color has-text-color">Powwow &amp; Dance</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":1,"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">50th Annual Powwow &amp; Cultural Celebration</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","textColor":"light"} -->
<p class="has-text-align-center has-light-color has-text-color">September 17-19, 2026&nbsp;&nbsp;|&nbsp;&nbsp;Chamberlain, South Dakota</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->

<?php echo $zigzag; ?>

<!-- wp:group {"metadata":{"name":"Powwow Intro"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Celebrate 50 years of honoring Lakota (Sioux) culture, tradition and community at St. Joseph's Indian School's largest event of the year.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="https://give.stjo.org/site/SPageNavigator/wp_powwow_registration.html">Register to Attend</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Schedule at a Glance"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Schedule at a Glance</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">(subject to change)</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?>

<!-- wp:columns -->
<div class="wp-block-columns">
<?php
echo $day( 'Thursday, Sept. 17', $thursday );
echo $day( 'Friday, Sept. 18', $friday );
echo $day( 'Saturday, Sept. 19', $saturday );
?>
</div>
<!-- /wp:columns -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"What to Expect"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><?php echo $sp( 'large' ); ?>

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">What to Expect</h2>
<!-- /wp:heading -->

<?php echo $list( $expect ); ?></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Plan Your Visit</h2>
<!-- /wp:heading -->

<?php echo $list( $plan ); ?></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Learn More about Powwow & Dance"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background"><?php echo $sp( 'large' ); ?>

<!-- wp:media-text {"mediaPosition":"right","mediaType":"image","className":"is-style-rounded"} -->
<div class="wp-block-media-text has-media-on-the-right is-stacked-on-mobile is-style-rounded"><div class="wp-block-media-text__content"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Learn More about Powwow &amp; Dance</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Registered contestants participate in the dancing until the &#8220;intertribal dance&#8221; is announced. At this time, all visitors attending the powwow take part. At a powwow, there are no spectators, everyone is considered a participant.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-arrow-link"} -->
<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link wp-element-button" href="/lakota-culture/powwow-dance/">See the Dance Styles</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div><figure class="wp-block-media-text__media"><img src="<?php echo esc_url( stjo_asset( 'mens-grass-dance.jpg' ) ); ?>" alt="A grass dancer in orange and yellow fringed regalia turns mid-step on the powwow grounds"/></figure></div>
<!-- /wp:media-text -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
