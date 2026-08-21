/**
 * Hero slide controls — sidebar panel for the extra core/cover framing
 * attributes registered in inc/hero-slide-controls.php.
 *
 * No build step, so this is plain JS against wp.element.createElement.
 *
 * The panel only appears on covers that are actually carousel slides, so it
 * does not clutter the sidebar of every cover on the site. Framing that used to
 * need hand-typed classes (focus-sm-23-62, scrim-sm-40) now has real controls;
 * the classes still work for anything already set that way.
 */
( function ( wp ) {
	'use strict';

	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var addFilter = wp.hooks.addFilter;
	var createHigherOrderComponent = wp.compose.createHigherOrderComponent;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var PanelBody = wp.components.PanelBody;
	var RangeControl = wp.components.RangeControl;
	var ToggleControl = wp.components.ToggleControl;
	var Button = wp.components.Button;

	// A slide is a cover carrying the hero-slide class, or one sitting inside a
	// group with the Carousel style. The class check covers the pattern as
	// shipped; the fallback catches covers added to a carousel afterwards.
	function isSlide( props ) {
		var cls = props.attributes && props.attributes.className ? props.attributes.className : '';
		return cls.indexOf( 'stjo-hero-slide' ) !== -1 || cls.indexOf( 'is-style-carousel' ) !== -1;
	}

	var withHeroSlideControls = createHigherOrderComponent( function ( BlockEdit ) {
		return function ( props ) {
			if ( 'core/cover' !== props.name || ! isSlide( props ) ) {
				return el( BlockEdit, props );
			}

			var a = props.attributes;
			var set = props.setAttributes;

			// Phone focal point is either explicitly set (both axes) or off.
			var hasPhoneFocal = 'number' === typeof a.stjoFocalSmX && 'number' === typeof a.stjoFocalSmY;

			return el(
				Fragment,
				{},
				el( BlockEdit, props ),
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: 'Slide framing', initialOpen: false },

						el( RangeControl, {
							label: 'Move image sideways (%)',
							value: a.stjoShiftX || 0,
							onChange: function ( v ) { set( { stjoShiftX: 'number' === typeof v ? v : 0 } ); },
							min: -30,
							max: 30,
							step: 1,
							help: 'Slides the photo across the band. Negative moves it left. Desktop only — the focal point still handles phones. Whatever it moves leaves that much of the band bare on the far side, which is the side the text and scrim sit on, so it is normally hidden.',
							__nextHasNoMarginBottom: true,
							__next40pxDefaultSize: true
						} ),

						el( RangeControl, {
							label: 'Image zoom',
							value: a.stjoZoom || 1,
							onChange: function ( v ) { set( { stjoZoom: v || 1 } ); },
							min: 1,
							max: 2.5,
							step: 0.05,
							help: 'Scales the photo about its focal point. Also gives it spare width, so a sideways move leaves less of the band bare.',
							__nextHasNoMarginBottom: true,
							__next40pxDefaultSize: true
						} ),

						el( RangeControl, {
							label: 'Image zoom on phones',
							value: a.stjoZoomSm || 1,
							onChange: function ( v ) {
								// 1 means "same as desktop"; stored as 0 so the
								// renderer knows it was not set.
								set( { stjoZoomSm: ( ! v || v <= 1 ) ? 0 : v } );
							},
							min: 1,
							max: 2.5,
							step: 0.05,
							help: 'Phones crop far more aggressively than desktop. Zoom in here if the subject gets lost, or leave at 1 to use the desktop zoom.',
							__nextHasNoMarginBottom: true,
							__next40pxDefaultSize: true
						} ),

						el( ToggleControl, {
							label: 'Set a separate focal point for phones',
							checked: hasPhoneFocal,
							onChange: function ( on ) {
								set( on
									? { stjoFocalSmX: 50, stjoFocalSmY: 30 }
									: { stjoFocalSmX: undefined, stjoFocalSmY: undefined } );
							},
							help: 'Off uses the slide’s own focal point. Turn on when the desktop crop puts the subject off-frame on a phone.',
							__nextHasNoMarginBottom: true
						} ),

						hasPhoneFocal && el( RangeControl, {
							label: 'Phone focal point — horizontal (%)',
							value: a.stjoFocalSmX,
							onChange: function ( v ) { set( { stjoFocalSmX: 'number' === typeof v ? v : 50 } ); },
							min: 0, max: 100, step: 1,
							__nextHasNoMarginBottom: true,
							__next40pxDefaultSize: true
						} ),

						hasPhoneFocal && el( RangeControl, {
							label: 'Phone focal point — vertical (%)',
							value: a.stjoFocalSmY,
							onChange: function ( v ) { set( { stjoFocalSmY: 'number' === typeof v ? v : 30 } ); },
							min: 0, max: 100, step: 1,
							__nextHasNoMarginBottom: true,
							__next40pxDefaultSize: true
						} )
					),

					el(
						PanelBody,
						{ title: 'Slide scrim', initialOpen: false },

						el( ToggleControl, {
							label: 'Automatic contrast scrim',
							checked: 'off' !== a.stjoScrim,
							onChange: function ( on ) { set( { stjoScrim: on ? 'auto' : 'off' } ); },
							help: 'On: a scrim is added behind the text, light under dark copy and dark under light, anchored to the text’s side. Turn off if the photo already carries the text or you are handling the overlay yourself.',
							__nextHasNoMarginBottom: true
						} ),

						'off' !== a.stjoScrim && el( RangeControl, {
							label: 'Scrim strength on phones (%)',
							value: 'number' === typeof a.stjoScrimSm ? a.stjoScrimSm : 50,
							onChange: function ( v ) { set( { stjoScrimSm: 'number' === typeof v ? v : undefined } ); },
							min: 0, max: 100, step: 5,
							help: 'On phones the scrim goes flat across the whole slide, because the text sits over the middle of the photo rather than to one side. 0 removes it.',
							__nextHasNoMarginBottom: true,
							__next40pxDefaultSize: true
						} ),

						'off' !== a.stjoScrim && 'number' === typeof a.stjoScrimSm && el( Button, {
							variant: 'tertiary',
							onClick: function () { set( { stjoScrimSm: undefined } ); }
						}, 'Reset phone scrim to default' )
					)
				)
			);
		};
	}, 'withHeroSlideControls' );

	addFilter( 'editor.BlockEdit', 'stjo/hero-slide-controls', withHeroSlideControls );
}( window.wp ) );
