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
if ( ! is_front_page() && locate_template( 'template-parts/global/pre-footer.php' ) ) {
	get_template_part( 'template-parts/global/pre-footer' );
}
?>
</main><!-- #main -->

<?php get_template_part( 'template-parts/global/site-footer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
