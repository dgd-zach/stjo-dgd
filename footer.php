<?php
/**
 * Footer — closes the main content area and renders the global site footer.
 *
 * @package stjo
 */
?>
<?php
// Site-wide pre-footer band, inside <main> so the full-bleed overflow clip
// applies. The front page carries the band in its own content (per the
// design, the testimonial band sits between it and the footer there).
// A page whose own content already carries the band (Support Us: the sitemap
// puts the ways to give mid-page, above Your Impact) must not get it twice.
$stjo_content_has_band = is_singular()
	&& false !== strpos( (string) get_post_field( 'post_content', get_queried_object_id() ), 'stjo-generosity' );
if ( ! is_front_page() && ! $stjo_content_has_band && locate_template( 'template-parts/global/pre-footer.php' ) ) {
	get_template_part( 'template-parts/global/pre-footer' );
}
?>
</main><!-- #main -->

<?php get_template_part( 'template-parts/global/site-footer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
