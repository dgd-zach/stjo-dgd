<?php
/**
 * Search form (used by get_search_form() on the results page and 404).
 *
 * Shares the header search modal's look: input + fill button in a centered
 * row (see .search-modal__* / .stjo-search-form in main.css). Not wrapped in a
 * <dialog> since this one lives inline in the page.
 *
 * @package stjo
 */

$stjo_search_id = 'stjo-search-field-' . wp_unique_id();
?>
<form class="stjo-search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $stjo_search_id ); ?>"><?php esc_html_e( 'Search this site', 'stjo' ); ?></label>
	<input
		id="<?php echo esc_attr( $stjo_search_id ); ?>"
		class="stjo-search-form__input"
		type="search"
		name="s"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php esc_attr_e( 'Search', 'stjo' ); ?>"
		required
	>
	<div class="wp-block-button is-style-fill stjo-search-form__submit">
		<button class="wp-block-button__link wp-element-button" type="submit"><?php esc_html_e( 'Search', 'stjo' ); ?></button>
	</div>
</form>
