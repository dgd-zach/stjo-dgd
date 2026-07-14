<?php
/**
 * Template part: breadcrumbs. Appears only when the page sits 3+
 * levels deep (per the project spec) and never on the front page.
 *
 * @package stjo
 */

if ( is_front_page() || ! is_singular() ) {
	return;
}
$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
$depth     = count( $ancestors ) + 1;
$min_depth = apply_filters( 'stjo_breadcrumbs_min_depth', 3 );
if ( $depth < $min_depth ) {
	return;
}
?>
<nav class="stjo-breadcrumbs alignfull" aria-label="<?php esc_attr_e( 'Breadcrumb', 'stjo' ); ?>">
	<div class="stjo-breadcrumbs__inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'stjo' ); ?></a>
		<span class="sep" aria-hidden="true">&rsaquo;</span>
		<?php foreach ( $ancestors as $crumb_id ) : ?>
			<a href="<?php echo esc_url( get_permalink( $crumb_id ) ); ?>"><?php echo esc_html( get_the_title( $crumb_id ) ); ?></a>
			<span class="sep" aria-hidden="true">&rsaquo;</span>
		<?php endforeach; ?>
		<span aria-current="page"><?php the_title(); ?></span>
	</div>
</nav>
