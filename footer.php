<?php
/**
 * Footer — closes the main content area and renders the global site footer.
 *
 * @package stjo
 */
?>
</main><!-- #main -->

<?php
if ( locate_template( 'template-parts/global/pre-footer.php' ) ) {
	get_template_part( 'template-parts/global/pre-footer' );
}
?>

<?php get_template_part( 'template-parts/global/site-footer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
