/* Editor UI — no build step: plain JS + ServerSideRender preview. */
( function ( wp ) {
	var el = wp.element.createElement;
	var registerBlockType = wp.blocks.registerBlockType;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var PanelBody = wp.components.PanelBody;
	var TextControl = wp.components.TextControl;
	var SelectControl = wp.components.SelectControl;
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
							label: 'Form ID (df_id)',
							help: 'Used for one-time gifts, and for monthly unless overridden below.',
							value: a.loFormId,
							onChange: function ( v ) { props.setAttributes( { loFormId: v } ); }
						} ),
						el( TextControl, {
							label: 'Monthly form ID (df_id)',
							help: 'Use only if monthly uses a different form ID than above.',
							value: a.loFormIdMonthly,
							onChange: function ( v ) { props.setAttributes( { loFormIdMonthly: v } ); }
						} ),
						el( SelectControl, {
							label: 'Default frequency',
							value: a.defaultFrequency,
							options: [
								{ label: 'Give Monthly', value: 'monthly' },
								{ label: 'Give Once', value: 'once' }
							],
							onChange: function ( v ) { props.setAttributes( { defaultFrequency: v } ); }
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
