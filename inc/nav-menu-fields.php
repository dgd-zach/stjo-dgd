<?php
/**
 * Appearance > Menus: promo card fields on top-level menu items.
 *
 * The panel intro blurb is the item's native Description field (enable it
 * under Screen Options). The promo card (image, heading, body, button) is
 * item meta edited right on the menu item, stored via the keys in
 * stjo_nav_promo_keys() (inc/nav-menu.php).
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fields UI, top-level items only.
 */
function stjo_nav_item_fields( $item_id, $item, $depth ) {
	if ( 0 !== (int) $depth ) {
		return;
	}
	$keys    = stjo_nav_promo_keys();
	$heading = get_post_meta( $item_id, $keys['heading'], true );
	$body    = get_post_meta( $item_id, $keys['body'], true );
	$img_id  = (int) get_post_meta( $item_id, $keys['image_id'], true );
	$label   = get_post_meta( $item_id, $keys['button_label'], true );
	$url     = get_post_meta( $item_id, $keys['button_url'], true );
	$thumb   = $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : '';
	wp_nonce_field( 'stjo_nav_promo', 'stjo_nav_promo_nonce_' . $item_id );
	?>
	<div class="stjo-nav-promo description-wide" style="margin: 8px 0 12px; padding: 10px 12px; background: #f6f7f7; border: 1px solid #dcdcde; border-radius: 4px;">
		<p style="margin: 0 0 8px;"><strong><?php esc_html_e( 'Mega menu panel', 'stjo' ); ?></strong><br>
			<span class="description"><?php esc_html_e( 'The intro blurb is this item\'s Description field (enable it in Screen Options). Fields below fill the promo card; leave the heading and image empty to hide the card.', 'stjo' ); ?></span>
		</p>
		<p class="description description-wide" style="margin: 0 0 8px;">
			<label><?php esc_html_e( 'Promo heading', 'stjo' ); ?><br>
				<input type="text" class="widefat" name="stjo_promo_heading[<?php echo (int) $item_id; ?>]" value="<?php echo esc_attr( $heading ); ?>">
			</label>
		</p>
		<p class="description description-wide" style="margin: 0 0 8px;">
			<label><?php esc_html_e( 'Promo body', 'stjo' ); ?><br>
				<textarea class="widefat" rows="2" name="stjo_promo_body[<?php echo (int) $item_id; ?>]"><?php echo esc_textarea( $body ); ?></textarea>
			</label>
		</p>
		<p class="description" style="margin: 0 0 8px;">
			<?php esc_html_e( 'Promo image', 'stjo' ); ?><br>
			<span class="stjo-promo-image-preview" style="display:block; margin: 4px 0;"><?php if ( $thumb ) : ?><img src="<?php echo esc_url( $thumb ); ?>" alt="" style="max-width: 120px; height: auto; border-radius: 4px;"><?php endif; ?></span>
			<input type="hidden" class="stjo-promo-image-id" name="stjo_promo_image_id[<?php echo (int) $item_id; ?>]" value="<?php echo $img_id ? (int) $img_id : ''; ?>">
			<button type="button" class="button stjo-promo-image-pick"><?php esc_html_e( 'Choose image', 'stjo' ); ?></button>
			<button type="button" class="button stjo-promo-image-remove" <?php echo $img_id ? '' : 'style="display:none;"'; ?>><?php esc_html_e( 'Remove', 'stjo' ); ?></button>
		</p>
		<p class="description" style="margin: 0 0 8px; display: inline-block; width: 48%;">
			<label><?php esc_html_e( 'Button label', 'stjo' ); ?><br>
				<input type="text" class="widefat" name="stjo_promo_button_label[<?php echo (int) $item_id; ?>]" value="<?php echo esc_attr( $label ); ?>">
			</label>
		</p>
		<p class="description" style="margin: 0 0 8px 2%; display: inline-block; width: 48%;">
			<label><?php esc_html_e( 'Button URL', 'stjo' ); ?><br>
				<input type="text" class="widefat" name="stjo_promo_button_url[<?php echo (int) $item_id; ?>]" value="<?php echo esc_attr( $url ); ?>" placeholder="/donate/">
			</label>
		</p>
	</div>
	<?php
}
add_action( 'wp_nav_menu_item_custom_fields', 'stjo_nav_item_fields', 10, 3 );

/**
 * Persist the fields. Fires per item on menu save; also fires during
 * programmatic menu writes (seeding), so bail unless our POST fields exist.
 */
function stjo_nav_item_fields_save( $menu_id, $item_id ) {
	if ( ! isset( $_POST[ 'stjo_nav_promo_nonce_' . $item_id ] )
		|| ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST[ 'stjo_nav_promo_nonce_' . $item_id ] ) ), 'stjo_nav_promo' ) ) {
		return;
	}
	$keys = stjo_nav_promo_keys();
	$map  = array(
		'stjo_promo_heading'      => array( $keys['heading'], 'sanitize_text_field' ),
		'stjo_promo_body'         => array( $keys['body'], 'sanitize_textarea_field' ),
		'stjo_promo_image_id'     => array( $keys['image_id'], 'absint' ),
		'stjo_promo_button_label' => array( $keys['button_label'], 'sanitize_text_field' ),
		'stjo_promo_button_url'   => array( $keys['button_url'], 'esc_url_raw' ),
	);
	foreach ( $map as $field => $spec ) {
		list( $meta_key, $sanitize ) = $spec;
		$value = isset( $_POST[ $field ][ $item_id ] ) ? call_user_func( $sanitize, wp_unslash( $_POST[ $field ][ $item_id ] ) ) : '';
		if ( '' === $value || 0 === $value ) {
			delete_post_meta( $item_id, $meta_key );
		} else {
			update_post_meta( $item_id, $meta_key, $value );
		}
	}
}
add_action( 'wp_update_nav_menu_item', 'stjo_nav_item_fields_save', 10, 2 );

/**
 * Media picker on the Menus screen.
 */
function stjo_nav_admin_assets( $hook ) {
	if ( 'nav-menus.php' !== $hook ) {
		return;
	}
	wp_enqueue_media();
	$js = <<<'JS'
jQuery(function ($) {
	var frame;
	$(document).on('click', '.stjo-promo-image-pick', function (e) {
		e.preventDefault();
		var wrap = $(this).closest('p');
		frame = wp.media({ title: 'Promo image', library: { type: 'image' }, multiple: false });
		frame.on('select', function () {
			var att = frame.state().get('selection').first().toJSON();
			var url = (att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url);
			wrap.find('.stjo-promo-image-id').val(att.id);
			wrap.find('.stjo-promo-image-preview').html('<img src="' + url + '" alt="" style="max-width:120px;height:auto;border-radius:4px;">');
			wrap.find('.stjo-promo-image-remove').show();
		});
		frame.open();
	});
	$(document).on('click', '.stjo-promo-image-remove', function (e) {
		e.preventDefault();
		var wrap = $(this).closest('p');
		wrap.find('.stjo-promo-image-id').val('');
		wrap.find('.stjo-promo-image-preview').empty();
		$(this).hide();
	});
});
JS;
	wp_add_inline_script( 'media-editor', $js );
}
add_action( 'admin_enqueue_scripts', 'stjo_nav_admin_assets' );
