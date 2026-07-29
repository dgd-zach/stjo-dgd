/* Editor UI — no build step: plain JS + ServerSideRender preview (view.js does
 * not run in the canvas, so the preview shows every card stacked per decade).
 * Inspector: the all-or-nothing "Image motion" toggle (imageMotion attribute)
 * that view.js reads on the front end. */
( function ( wp ) {
	var el = wp.element.createElement;
	var registerBlockType = wp.blocks.registerBlockType;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var PanelBody = wp.components.PanelBody;
	var ToggleControl = wp.components.ToggleControl;
	var ServerSideRender = wp.serverSideRender;

	registerBlockType( 'stjo/timeline', {
		edit: function ( props ) {
			return el(
				'div',
				useBlockProps(),
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: 'Timeline Settings' },
						el( ToggleControl, {
							label: 'Image motion',
							checked: false !== props.attributes.imageMotion,
							onChange: function ( v ) {
								props.setAttributes( { imageMotion: !! v } );
							},
							help: 'Applies to every card. Horizontal photos drift as the page scrolls, revealing the parts the crop hides (each event’s focus point still sets the side-to-side crop). Vertical photos start zoomed in and settle to full size. Turn off to keep all photos still.',
							__nextHasNoMarginBottom: true
						} )
					)
				),
				el( ServerSideRender, { block: 'stjo/timeline', attributes: props.attributes } )
			);
		},
		save: function () {
			return null;
		}
	} );
} )( window.wp );
