<?php
/**
 * stjo-block-training: "STJO Block Training" (staff-only reference page).
 *
 * Teaches the Group block's Align + Layout settings with LIVE examples: every
 * colored box on the page is a real block inside a real Group, so the width
 * you see is the width that setting produces. Written in plain language for
 * the St. Joseph's content team. Widths quoted are the theme.json tiers
 * (content 1146px, wide 1280px, full = viewport); phones collapse everything
 * to the screen width with a small gutter.
 *
 * Seed source only: edit live content in the WP editor after seeding. Kept
 * noindex via Yoast meta so it never leaks into search if it reaches prod.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

/**
 * A visible child box: a Group with a background and one centered line of
 * text. $align '' | 'wide' | 'full' sets the box's own Align, so it also
 * demonstrates children asking for their own width.
 */
$box = function ( $text, $bg = 'yellow', $color = 'blue-900', $align = '' ) {
	$attrs = array();
	if ( $align ) {
		$attrs['align'] = $align;
	}
	$attrs['backgroundColor'] = $bg;
	$attrs['textColor']       = $color;
	$attrs['layout']          = array( 'type' => 'constrained' );
	$class = 'wp-block-group' . ( $align ? ' align' . $align : '' ) . ' has-' . $color . '-color has-' . $bg . '-background-color has-text-color has-background';
	return '<!-- wp:group ' . wp_json_encode( $attrs ) . ' -->' . "\n"
		. '<div class="' . $class . '"><!-- wp:paragraph {"align":"center"} -->' . "\n"
		. '<p class="has-text-align-center">' . $text . '</p>' . "\n"
		. '<!-- /wp:paragraph --></div>' . "\n"
		. '<!-- /wp:group -->' . "\n\n";
};

/** Centered eyebrow + H2 + one-line explainer, used at the top of each band. */
$band_head = function ( $eyebrow, $h2, $text ) {
	return '<!-- wp:paragraph {"align":"center","textColor":"brand-dark","className":"is-style-eyebrow"} -->' . "\n"
		. '<p class="has-text-align-center is-style-eyebrow has-brand-dark-color has-text-color">' . $eyebrow . '</p>' . "\n"
		. '<!-- /wp:paragraph -->' . "\n\n"
		. '<!-- wp:heading {"textAlign":"center","level":2} -->' . "\n"
		. '<h2 class="wp-block-heading has-text-align-center">' . $h2 . '</h2>' . "\n"
		. '<!-- /wp:heading -->' . "\n\n"
		. '<!-- wp:paragraph {"align":"center"} -->' . "\n"
		. '<p class="has-text-align-center">' . $text . '</p>' . "\n"
		. '<!-- /wp:paragraph -->' . "\n\n";
};

echo $title_band( 'Staff Training', 'Group Block Layouts' );
?>
<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"className":"stjo-subhead"} -->
<p class="stjo-subhead">One rule explains almost everything: a Group's <strong>Layout</strong> setting controls the blocks <em>inside</em> it. The Group's own width is set by its <strong>Align</strong> button.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>This page is built from real Groups. Every colored box is a block sitting inside a Group, so the width you see is exactly what that setting produces. The widths quoted are for a desktop screen. On a phone, everything fills the screen with a small gutter on each side.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Three widths exist on this site: <strong>content</strong> (1146px, the normal page column), <strong>wide</strong> (1280px) and <strong>full</strong> (edge to edge). Everything below is about choosing one of those for a Group, and one of those for the blocks inside it.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Example: the three widths"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"},"anchor":"three-widths"} -->
<div class="wp-block-group alignfull has-light-background-color has-background" id="three-widths"><?php echo $sp( 'large' ); ?>

<?php
echo $band_head( 'Example 1', 'The three widths', 'This whole band is a Group set to Align: Full with the toggle on. The three boxes inside it each ask for a different width using their own Align button.' );
echo $box( 'Default. No Align set. I stop at the content width, 1146px.' );
echo $box( 'Align: Wide width. I stretch to 1280px.', 'blue-700', 'white', 'wide' );
echo $box( 'Align: Full width. I run edge to edge inside my band.', 'blue-900', 'white', 'full' );
?>

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Example: full band, toggle on"},"align":"full","backgroundColor":"light","layout":{"type":"constrained"},"anchor":"full-toggle-on"} -->
<div class="wp-block-group alignfull has-light-background-color has-background" id="full-toggle-on"><?php echo $sp( 'large' ); ?>

<?php
echo $band_head( 'Example 2', 'Full band, toggle ON', 'Align: Full width. Layout: "Inner blocks use content width" is ON. The background runs edge to edge, but the box stops at 1146px and centers. This is the standard band on the site.' );
echo $box( 'Toggle ON: I stop at 1146px, lined up with the rest of the page.' );
?>

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Example: full band, toggle off"},"align":"full","backgroundColor":"blue-200","layout":{"type":"default"},"anchor":"full-toggle-off"} -->
<div class="wp-block-group alignfull has-blue-200-background-color has-background" id="full-toggle-off"><?php echo $sp( 'large' ); ?>

<?php
echo $band_head( 'Example 3', 'Full band, toggle OFF', 'Same Group, but "Inner blocks use content width" is OFF. Now nothing holds the blocks inside, so the box fills the whole band. Use this only when you want edge-to-edge content, such as a photo strip.' );
echo $box( 'Toggle OFF: I fill the whole band.' );
?>

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Wide groups intro"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><?php echo $sp( 'large' ); ?>

<?php echo $band_head( 'Example 4', 'Wide Group, toggle OFF and ON', 'These two Groups are set to Align: Wide width, so each one is 1280px wide. The only difference between them is the toggle.' ); ?>

</div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Example: wide, toggle off"},"align":"wide","backgroundColor":"light","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide has-light-background-color has-background"><?php echo $sp( 'small' ); ?>

<?php echo $box( 'Wide Group, toggle OFF: I fill my Group, so I am wide too.' ); ?>

<?php echo $sp( 'small' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Example: wide, toggle on"},"align":"wide","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-light-background-color has-background"><?php echo $sp( 'small' ); ?>

<?php echo $box( 'Wide Group, toggle ON: I stop at 1146px even though my Group is wider.' ); ?>

<?php echo $sp( 'small' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Wide groups note"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Good to know: switching either of these Groups from Wide to Full changes only the Group's background. The box inside keeps following the toggle, exactly as it does here.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Example: custom content width"},"align":"full","backgroundColor":"light","layout":{"type":"constrained","contentSize":"768px"},"anchor":"custom-width"} -->
<div class="wp-block-group alignfull has-light-background-color has-background" id="custom-width"><?php echo $sp( 'large' ); ?>

<?php
echo $band_head( 'Example 5', 'A custom content width', 'Toggle ON, and the Content width field set to 768px. Everything inside this band, including this heading and paragraph, stops at 768px. Use it for reading-heavy text so lines stay short.' );
echo $box( 'Content width 768px: I stop here.' );
?>

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Example: row layout"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><?php echo $sp( 'large' ); ?>

<?php echo $band_head( 'Example 6', 'Row, Stack and Grid', 'When Layout is set to Row, Stack or Grid, the Group lines its blocks up side by side or in a grid instead. There is no content width in these modes. Use them for small clusters, like a row of buttons or logos.' ); ?>

<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
<div class="wp-block-group"><?php
echo $box( 'Row item 1' );
echo $box( 'Row item 2' );
echo $box( 'Row item 3' );
?></div>
<!-- /wp:group -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"How to set it"},"align":"full","backgroundColor":"light","layout":{"type":"constrained","contentSize":"860px"},"anchor":"how-to"} -->
<div class="wp-block-group alignfull has-light-background-color has-background" id="how-to"><?php echo $sp( 'large' ); ?>

<?php echo $band_head( 'Step by step', 'How to set a Group up', 'The same five steps cover every example above.' ); ?>

<!-- wp:list {"ordered":true} -->
<ol class="wp-block-list"><!-- wp:list-item -->
<li><strong>Select the Group.</strong> Clicking usually selects the block inside it, so open <strong>List View</strong> (the three-line icon in the top-left toolbar) and click the Group there.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Set the Group's own width</strong> with the <strong>Align</strong> button in the block toolbar: None, Wide width or Full width.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Open the Layout panel</strong> in the Settings sidebar (the panel icon at the top right; make sure the Block tab is selected).</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Turn "Inner blocks use content width" ON</strong> to hold the blocks inside at page width. Leave it OFF only when the content should fill the Group edge to edge.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Optional: set a Content width</strong> in the field that appears when the toggle is on. Type a value such as 768px for narrow reading text, or leave it blank for the site default. Justification only matters for blocks narrower than that width.</li>
<!-- /wp:list-item --></ol>
<!-- /wp:list -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Common mix-ups"},"layout":{"type":"constrained","contentSize":"860px"},"anchor":"mix-ups"} -->
<div class="wp-block-group" id="mix-ups"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Common mix-ups</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><strong>The toggle seems to do nothing.</strong> In a normal-width Group it changes nothing, because the Group is already at the content width. It only shows in a Full or Wide Group, or with a custom Content width.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>A Full block that is not full.</strong> Full means "as wide as my Group". Only a Group set to Full reaches the screen edge, so put the Full block inside a Full Group.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Content looks slightly narrower in a colored band.</strong> Bands with a background color get their own side padding. That is normal.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>A 768px width that vanishes on phones.</strong> Inside Columns, a custom Content width is released once the columns stack, so text fills the column. That is on purpose.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>The editor looks a little different from the site.</strong> The editor canvas is close but not exact. When a width matters, check the live page.</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Cheat sheet"},"align":"full","backgroundColor":"light","layout":{"type":"constrained","contentSize":"860px"},"anchor":"cheat-sheet"} -->
<div class="wp-block-group alignfull has-light-background-color has-background" id="cheat-sheet"><?php echo $sp( 'large' ); ?>

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Cheat sheet</h2>
<!-- /wp:heading -->

<!-- wp:table -->
<figure class="wp-block-table"><table class="has-fixed-layout"><thead><tr><th>You want</th><th>Set the Group to</th></tr></thead><tbody><tr><td>A colored band with content lined up with the page</td><td>Align: Full width. Toggle ON.</td></tr><tr><td>A band whose content runs edge to edge</td><td>Align: Full width. Toggle OFF.</td></tr><tr><td>A row of cards a little wider than the text</td><td>Align: Wide width. Toggle OFF. Or keep the band Full and set the cards row to Wide.</td></tr><tr><td>Short, easy-to-read lines of text</td><td>Toggle ON. Content width 768px.</td></tr><tr><td>Buttons or logos side by side</td><td>Layout: Row.</td></tr></tbody></table></figure>
<!-- /wp:table -->

<!-- wp:paragraph {"align":"center","className":"is-style-subhead"} -->
<p class="has-text-align-center is-style-subhead">This page is for staff and is hidden from search engines.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
