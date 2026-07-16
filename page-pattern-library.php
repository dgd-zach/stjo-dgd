<?php
/**
 * Template Name: Pattern Library
 *
 * Living style guide: renders every block pattern registered in the theme
 * category, grouped under content-type headings (see
 * stjo_pattern_content_categories()), straight from the pattern registry so
 * it can never go stale. Hovering or focusing a pattern reveals an overlay
 * with its title, description, and an Isolate link; clicking anywhere else
 * on the pattern copies its block markup to the clipboard for pasting into
 * the editor (assets/js/pattern-library.js). Append ?pattern=<slug> to
 * render a single pattern in isolation. Noindexed and excluded from search.
 *
 * @package stjo
 */

get_header();

$stjo_patterns = array_values( array_filter(
	WP_Block_Patterns_Registry::get_instance()->get_all_registered(),
	function ( $pattern ) {
		return in_array( 'stjo', $pattern['categories'] ?? array(), true );
	}
) );

$stjo_cats = stjo_pattern_content_categories();

// Group patterns by their first content-type category, in category order.
$stjo_groups = array_fill_keys( array_keys( $stjo_cats ), array() );
$stjo_groups['stjo-misc'] = array();
foreach ( $stjo_patterns as $pattern ) {
	$content_cats = array_intersect( $pattern['categories'], array_keys( $stjo_cats ) );
	$bucket       = $content_cats ? reset( $content_cats ) : 'stjo-misc';
	$stjo_groups[ $bucket ][] = $pattern;
}
$stjo_cats['stjo-misc'] = __( 'Other', 'stjo' );

$stjo_only = isset( $_GET['pattern'] ) ? sanitize_title( wp_unslash( $_GET['pattern'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

/**
 * Anchor slug for a registered pattern name (e.g. "stjo/page-hero" -> "page-hero").
 */
function stjo_pattern_anchor( $name ) {
	return sanitize_title( str_replace( '/', '-', preg_replace( '#^[^/]+/#', '', $name ) ) );
}

/**
 * One pattern section: render + hover overlay + raw markup for copying.
 */
function stjo_pattern_library_item( $pattern, $stjo_only ) {
	$anchor = stjo_pattern_anchor( $pattern['name'] );
	?>
	<section id="<?php echo esc_attr( $anchor ); ?>" class="stjo-plib__item" data-plib-item data-plib-title="<?php echo esc_attr( $pattern['title'] ); ?>">
		<div class="entry-content stjo-plib__render">
			<?php echo do_blocks( $pattern['content'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<div class="stjo-plib__overlay">
			<div class="stjo-plib__overlay-inner">
				<h3 class="stjo-plib__overlay-title"><?php echo esc_html( $pattern['title'] ); ?></h3>
				<?php if ( ! empty( $pattern['description'] ) ) : ?>
					<p class="stjo-plib__overlay-desc"><?php echo esc_html( $pattern['description'] ); ?></p>
				<?php endif; ?>
				<p class="stjo-plib__overlay-hint" data-plib-hint><?php esc_html_e( 'Click anywhere to copy the block markup', 'stjo' ); ?></p>
				<div class="stjo-plib__overlay-actions">
					<?php if ( $stjo_only ) : ?>
						<a class="stjo-plib__overlay-btn" href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'Back to all', 'stjo' ); ?></a>
					<?php else : ?>
						<a class="stjo-plib__overlay-btn" href="<?php echo esc_url( add_query_arg( 'pattern', $anchor, get_permalink() ) ); ?>"><?php esc_html_e( 'Isolate', 'stjo' ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<script type="text/plain" class="stjo-plib__markup"><?php echo $pattern['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></script>
	</section>
	<?php
}
?>

<?php if ( ! $stjo_only ) : ?>
<div class="stjo-plib__head">
	<div class="stjo-plib__head-inner">
		<p class="is-style-eyebrow"><?php echo esc_html( wp_get_theme()->get( 'Name' ) ); ?></p>
		<h1><?php esc_html_e( 'Pattern Library', 'stjo' ); ?></h1>
		<p class="stjo-plib__count">
			<?php
			/* translators: %d: number of registered patterns */
			printf( esc_html__( '%d registered patterns, rendered live from the pattern registry. Click a pattern to copy its markup.', 'stjo' ), count( $stjo_patterns ) );
			?>
		</p>
		<nav class="stjo-plib__toc" aria-label="<?php esc_attr_e( 'Patterns on this page', 'stjo' ); ?>">
			<?php foreach ( $stjo_groups as $cat_slug => $group ) : ?>
				<?php if ( ! $group ) { continue; } ?>
				<div class="stjo-plib__toc-group">
					<p class="stjo-plib__toc-label"><?php echo esc_html( $stjo_cats[ $cat_slug ] ); ?></p>
					<ul>
						<?php foreach ( $group as $pattern ) : ?>
							<li><a href="#<?php echo esc_attr( stjo_pattern_anchor( $pattern['name'] ) ); ?>"><?php echo esc_html( $pattern['title'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</nav>
	</div>
</div>
<?php endif; ?>

<div aria-live="polite" class="screen-reader-text" data-plib-live></div>

<?php foreach ( $stjo_groups as $cat_slug => $group ) : ?>
	<?php
	if ( ! $group ) {
		continue;
	}
	if ( $stjo_only ) {
		foreach ( $group as $pattern ) {
			if ( stjo_pattern_anchor( $pattern['name'] ) === $stjo_only ) {
				stjo_pattern_library_item( $pattern, $stjo_only );
			}
		}
		continue;
	}
	?>
	<h2 class="stjo-plib__cat" id="cat-<?php echo esc_attr( $cat_slug ); ?>"><?php echo esc_html( $stjo_cats[ $cat_slug ] ); ?></h2>
	<?php foreach ( $group as $pattern ) : ?>
		<?php stjo_pattern_library_item( $pattern, '' ); ?>
	<?php endforeach; ?>
<?php endforeach; ?>

<?php
get_footer();
