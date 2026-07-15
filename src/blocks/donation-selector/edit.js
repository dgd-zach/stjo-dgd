/* Editor UI — no build step: plain JS + ServerSideRender preview. */
( function ( wp ) {
	var el = wp.element.createElement;
	var registerBlockType = wp.blocks.registerBlockType;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var PanelBody = wp.components.PanelBody;
	var TextControl = wp.components.TextControl;
	var ServerSideRender = wp.serverSideRender;

	registerBlockType( 'stjo/donation-selector', {
		edit: function ( props ) {
			var a = props.attributes;
			return el(
				wp.element.Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: 'Luminate Online' },
						el( TextControl, {
							label: 'LO form ID (df_id)',
							value: a.loFormId,
							onChange: function ( v ) { props.setAttributes( { loFormId: v } ); }
						} ),
						el( TextControl, {
							label: 'Donation base URL',
							value: a.baseUrl,
							onChange: function ( v ) { props.setAttributes( { baseUrl: v } ); }
						} ),
						el( TextControl, {
							label: 'Default amount',
							value: a.defaultAmount,
							onChange: function ( v ) { props.setAttributes( { defaultAmount: v } ); }
						} )
					)
				),
				el( 'div', useBlockProps(), el( ServerSideRender, { block: 'stjo/donation-selector', attributes: a } ) )
			);
		},
		save: function () { return null; }
	} );
} )( window.wp );
