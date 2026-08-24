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
			if ( 'core/cover' !== props.name ) {
				return el( BlockEdit, props );
			}

			var a = props.attributes;
			var set = props.setAttributes;

			// Framing is a carousel-slide idea (it lines a subject up with the
			// text column beside it), so it stays scoped to slides. The scrim is
			// useful on any cover with text over a photo, so it is offered on
			// all of them — but a slide has it by default and a standalone cover
			// has to be switched on, which is what flips the toggle's meaning.
			var slide = isSlide( props );
			var scrimOn = slide ? ( 'off' !== a.stjoScrim ) : ( 'on' === a.stjoScrim );


			return el(
				Fragment,
				{},
				el( BlockEdit, props ),
				el(
					InspectorControls,
					{},
					slide && el(
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

						el( RangeControl, {
							label: 'Move image sideways on phones (%)',
							value: a.stjoShiftSmX || 0,
							onChange: function ( v ) { set( { stjoShiftSmX: 'number' === typeof v ? v : 0 } ); },
							min: -50, max: 50, step: 1,
							help: 'Free to use, and cannot leave a gap: a phone crop has spare width inside the photo, so this pans through it and stops at the edge.',
							__nextHasNoMarginBottom: true,
							__next40pxDefaultSize: true
						} ),

						el( RangeControl, {
							label: 'Move image up or down on phones (%)',
							value: a.stjoShiftSmY || 0,
							onChange: function ( v ) { set( { stjoShiftSmY: 'number' === typeof v ? v : 0 } ); },
							min: -50, max: 50, step: 1,
							help: 'Negative moves it up. Unlike sideways there is no spare height on a phone, so this leaves that much of the band bare at the opposite edge — add phone zoom above to make room.',
							__nextHasNoMarginBottom: true,
							__next40pxDefaultSize: true
						} )
					),

					el(
						PanelBody,
						{ title: slide ? 'Slide scrim' : 'Contrast scrim', initialOpen: false },

						el( ToggleControl, {
							label: 'Automatic contrast scrim',
							checked: scrimOn,
							onChange: function ( on ) {
								set( { stjoScrim: slide ? ( on ? 'auto' : 'off' ) : ( on ? 'on' : 'auto' ) } );
							},
							help: slide
								? 'Desktop only. On: a scrim is added behind the text, light under dark copy and dark under light, anchored to the text’s side. Turn off if the photo already carries the text there. Phones keep their own setting below.'
								: 'Off by default on covers outside the carousel. On: a scrim is added behind the text, light under dark copy and dark under light, anchored to the text’s column (flat if there are no columns). Guarantees the text stays readable whatever photo is swapped in.',
							__nextHasNoMarginBottom: true
						} ),

						( slide || scrimOn ) && el( RangeControl, {
							label: 'Scrim strength on phones (%)',
							value: 'number' === typeof a.stjoScrimSm ? a.stjoScrimSm : 50,
							onChange: function ( v ) { set( { stjoScrimSm: 'number' === typeof v ? v : undefined } ); },
							min: 0, max: 100, step: 5,
							help: 'Independent of the toggle above: on phones the columns stack and the text sits over the middle of the photo, so it usually still needs a scrim even when desktop does not. 0 removes it.',
							__nextHasNoMarginBottom: true,
							__next40pxDefaultSize: true
						} ),

						( slide || scrimOn ) && 'number' === typeof a.stjoScrimSm && el( Button, {
							variant: 'tertiary',
							onClick: function () { set( { stjoScrimSm: undefined } ); }
						}, 'Reset phone scrim to default' )
					)
				)
			);
		};
	}, 'withHeroSlideControls' );

	addFilter( 'editor.BlockEdit', 'stjo/hero-slide-controls', withHeroSlideControls );

	/**
	 * Show the sideways move in the editor canvas too.
	 *
	 * The frontend gets --stjo-slide-x from the render_block filter in
	 * inc/hero-slide-controls.php, but the editor never runs render_block — it
	 * builds the cover from JS — so without this the slider looked inert until
	 * you previewed. main.css is already loaded into the canvas via
	 * add_editor_style(), so all that is missing is the class and the custom
	 * properties; put those on the block wrapper and the same rules apply.
	 */
	var withHeroSlideStyles = createHigherOrderComponent( function ( BlockListBlock ) {
		return function ( props ) {
			if ( 'core/cover' !== props.name || ! isSlide( props ) ) {
				return el( BlockListBlock, props );
			}
			var a = props.attributes || {};
			var shift = 'number' === typeof a.stjoShiftX ? a.stjoShiftX : 0;
			var smX = 'number' === typeof a.stjoShiftSmX ? a.stjoShiftSmX : 0;
			var smY = 'number' === typeof a.stjoShiftSmY ? a.stjoShiftSmY : 0;
			var inRange = function ( n ) { return n >= -100 && n <= 100; };
			if ( ! inRange( shift ) || ! inRange( smX ) || ! inRange( smY ) ) {
				return el( BlockListBlock, props );
			}
			if ( ! shift && ! smX && ! smY ) {
				return el( BlockListBlock, props );
			}
			var zoom = ( 'number' === typeof a.stjoZoom && a.stjoZoom > 1 && a.stjoZoom <= 3 ) ? a.stjoZoom : 1;
			// Only the vertical half of the focal point survives a shift — the
			// slider owns the horizontal axis, same as on the frontend.
			var focalY = ( a.focalPoint && 'number' === typeof a.focalPoint.y ) ? a.focalPoint.y * 100 : 50;
			var existing = props.wrapperProps || {};

			var added = [];
			if ( shift ) { added.push( 'has-slide-shift' ); }
			if ( smX || smY ) { added.push( 'has-shift-sm' ); }

			return el( BlockListBlock, Object.assign( {}, props, {
				className: [ props.className ].concat( added ).filter( Boolean ).join( ' ' ),
				wrapperProps: Object.assign( {}, existing, {
					style: Object.assign( {}, existing.style, {
						'--stjo-slide-x': shift + '%',
						'--stjo-slide-sm-ox': ( 50 - smX ) + '%',
						'--stjo-slide-sm-y': smY + '%',
						'--stjo-slide-zoom': zoom,
						'--stjo-slide-zoom-sm': ( 'number' === typeof a.stjoZoomSm && a.stjoZoomSm > 1 ) ? a.stjoZoomSm : zoom,
						'--stjo-focus-y': focalY + '%'
					} )
				} )
			} ) );
		};
	}, 'withHeroSlideStyles' );

	addFilter( 'editor.BlockListBlock', 'stjo/hero-slide-styles', withHeroSlideStyles );
}( window.wp ) );
