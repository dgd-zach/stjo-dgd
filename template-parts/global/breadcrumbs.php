<?php
/**
 * Template part: breadcrumbs bar, under the header on every page except the
 * front page (header.php includes this between the header and <main>).
 *
 * Yoast renders the trail (CPT archives, hierarchy, taxonomy and 404 cases,
 * client-editable labels, BreadcrumbList schema); the theme owns the wrapper
 * markup and styling. Trail tweaks (for example the About crumb on Student
 * Stories) hook wpseo_breadcrumb_links, see inc/cpt-student-story.php. If
 * Yoast is ever deactivated, the fallback below keeps a basic
 * Home > ancestors > current trail.
 *
 * @package stjo
 */

if ( is_front_page() ) {
	return;
}

if ( function_exists( 'yoast_breadcrumb' ) ) {
	yoast_breadcrumb(
		'<nav class="stjo-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'stjo' ) . '"><div class="stjo-breadcrumbs__inner">',
		'</div></nav>'
	);
	return;
}

// Fallback trail without Yoast: Home > ancestors > current.
$stjo_crumb_current = '';
if ( is_singular() ) {
	$stjo_crumb_current = get_the_title();
} elseif ( is_post_type_archive() ) {
	$stjo_crumb_current = post_type_archive_title( '', false );
} elseif ( is_archive() ) {
	$stjo_crumb_current = get_the_archive_title();
} elseif ( is_search() ) {
	$stjo_crumb_current = __( 'Search results', 'stjo' );
} elseif ( is_404() ) {
	$stjo_crumb_current = __( 'Page not found', 'stjo' );
}
?>
<nav class="stjo-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'stjo' ); ?>">
	<div class="stjo-breadcrumbs__inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'stjo' ); ?></a>
		<?php if ( is_singular() ) : ?>
			<?php foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $stjo_crumb_id ) : ?>
				<span class="stjo-breadcrumbs__sep" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 18 18" fill="none" focusable="false"><path d="M7 4l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
				<a href="<?php echo esc_url( get_permalink( $stjo_crumb_id ) ); ?>"><?php echo esc_html( get_the_title( $stjo_crumb_id ) ); ?></a>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if ( $stjo_crumb_current ) : ?>
			<span class="stjo-breadcrumbs__sep" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 18 18" fill="none" focusable="false"><path d="M7 4l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
			<span class="breadcrumb_last" aria-current="page"><?php echo esc_html( $stjo_crumb_current ); ?></span>
		<?php endif; ?>
	</div>
</nav>
