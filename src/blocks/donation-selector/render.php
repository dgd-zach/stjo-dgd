<?php
/**
 * Donation selector — frequency switch + amount radios, deep-links to Luminate
 * Online on submit (view.js builds the URL; noscript falls back to the bare
 * form link). All options are REAL radio inputs styled as buttons.
 *
 * @package stjo
 */

$uid        = wp_unique_id( 'stjo-donsel-' );
$lo_form_id = $attributes['loFormId'] ?? '';
$base_url   = $attributes['baseUrl'] ?? '';
$amounts    = array_map( 'strval', (array) ( $attributes['amounts'] ?? array() ) );
$default    = (string) ( $attributes['defaultAmount'] ?? '' );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'stjo-donation-selector' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<form class="stjo-donation-selector__form"
		data-base-url="<?php echo esc_url( $base_url ); ?>"
		data-lo-form-id="<?php echo esc_attr( $lo_form_id ); ?>">

		<?php if ( ! empty( $attributes['eyebrow'] ) ) : ?>
			<p class="is-style-eyebrow"><?php echo esc_html( $attributes['eyebrow'] ); ?></p>
		<?php endif; ?>

		<fieldset class="stjo-donation-selector__switch">
			<legend class="screen-reader-text"><?php esc_html_e( 'Giving frequency', 'stjo' ); ?></legend>
			<span class="stjo-donation-selector__thumb" aria-hidden="true"></span>
			<label class="stjo-donation-selector__freq">
				<input type="radio" name="<?php echo esc_attr( $uid ); ?>-freq" value="monthly" checked>
				<span><?php esc_html_e( 'Give Monthly', 'stjo' ); ?></span>
			</label>
			<label class="stjo-donation-selector__freq">
				<input type="radio" name="<?php echo esc_attr( $uid ); ?>-freq" value="once">
				<span><?php esc_html_e( 'Give Once', 'stjo' ); ?></span>
			</label>
		</fieldset>

		<fieldset class="stjo-donation-selector__amounts">
			<legend class="screen-reader-text"><?php esc_html_e( 'Donation amount', 'stjo' ); ?></legend>
			<?php foreach ( $amounts as $amount ) : ?>
				<label class="stjo-donation-selector__amount">
					<input type="radio" name="<?php echo esc_attr( $uid ); ?>-amount"
						value="<?php echo esc_attr( $amount ); ?>" <?php checked( $amount, $default ); ?>>
					<span>$<?php echo esc_html( $amount ); ?></span>
				</label>
			<?php endforeach; ?>
		</fieldset>

		<label class="screen-reader-text" for="<?php echo esc_attr( $uid ); ?>-other"><?php esc_html_e( 'Other amount in dollars', 'stjo' ); ?></label>
		<input class="stjo-donation-selector__other" id="<?php echo esc_attr( $uid ); ?>-other"
			type="text" inputmode="decimal" autocomplete="off"
			placeholder="<?php esc_attr_e( '$Other Amount', 'stjo' ); ?>">

		<input type="hidden" name="lo_form_id" value="<?php echo esc_attr( $lo_form_id ); ?>">
		<input type="hidden" name="choices" value="">

		<div class="wp-block-button stjo-donation-selector__submit-wrap">
			<button type="submit" class="wp-block-button__link wp-element-button stjo-donation-selector__submit">
				<?php esc_html_e( 'Donate Now', 'stjo' ); ?>
			</button>
		</div>

		<?php if ( ! empty( $attributes['fineprint'] ) ) : ?>
			<p class="stjo-donation-selector__fineprint"><?php echo esc_html( $attributes['fineprint'] ); ?></p>
		<?php endif; ?>

		<noscript>
			<a href="<?php echo esc_url( add_query_arg( 'df_id', rawurlencode( $lo_form_id ), $base_url ) ); ?>"><?php esc_html_e( 'Donate on our secure giving page', 'stjo' ); ?></a>
		</noscript>
	</form>
</div>
