/**
 * Site Settings controls: show each field's "Leave blank to..." hint only
 * while the field is empty, positioned directly under the field.
 *
 * The hint markup ships inside the control's description (see
 * stjo_customize_blank_hint in inc/customizer.php). WordPress renders a
 * description above the input for text-type controls, so we move the hint
 * below the field, then toggle it against the setting's live value.
 */
( function ( wp, $ ) {
	'use strict';
	if ( ! wp || ! wp.customize || ! $ ) {
		return;
	}
	wp.customize.bind( 'ready', function () {
		wp.customize.control.each( function ( control ) {
			if ( ! control.setting || ! control.container ) {
				return;
			}
			var hint = control.container.find( '.stjo-blank-hint' );
			if ( ! hint.length ) {
				return;
			}
			var field = control.container
				.find( 'input[type="text"], input[type="url"], input[type="email"], textarea' )
				.first();
			if ( field.length ) {
				field.after( hint );
			}
			var sync = function ( value ) {
				var empty = null === value || undefined === value || '' === String( value ).replace( /^\s+|\s+$/g, '' );
				hint.toggle( empty );
			};
			sync( control.setting.get() );
			control.setting.bind( sync );
		} );
	} );
} )( window.wp, window.jQuery );
