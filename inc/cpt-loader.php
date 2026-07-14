<?php
/**
 * Auto-load CPT registrations. Each content type from the project's
 * content_model gets its own inc/cpt-{slug}.php file; this loader picks up
 * whatever exists so adding a CPT never requires touching functions.php.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

foreach ( glob( get_template_directory() . '/inc/cpt-*.php' ) as $cpt_file ) {
	if ( basename( $cpt_file ) === 'cpt-loader.php' ) {
		continue;
	}
	require_once $cpt_file;
}
