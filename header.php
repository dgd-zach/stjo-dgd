<?php
/**
 * Header — opens the document and renders the global site header.
 *
 * @package stjo
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'stjo' ); ?></a>

<?php get_template_part( 'template-parts/global/site-header' ); ?>
<?php get_template_part( 'template-parts/global/breadcrumbs' ); ?>

<main id="main" class="site-main">
