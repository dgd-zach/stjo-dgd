/* Editor UI — no build step. The band is rendered server-side from the shared
   template part, so the canvas shows exactly what the front end prints. There
   is nothing to type into: copy and destinations live in theme-config.json. */
( function ( wp ) {
	var el = wp.element.createElement;
	var registerBlockType = wp.blocks.registerBlockType;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var PanelBody = wp.components.PanelBody;
	var ServerSideRender = wp.serverSideRender;

	registerBlockType( 'stjo/generosity-band', {
		edit: function ( props ) {
			return el(
				wp.element.Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: 'Your Generosity Band' },
						el( 'p', { style: { fontSize: '12px', color: '#757575', margin: 0 } },
							'This band is shared by every page. Its cards, tiles, labels and giving links come from the theme configuration (theme-config.json), so a change there updates it everywhere, including the footer band on inner pages.' )
					)
				),
				el( 'div', useBlockProps(), el( ServerSideRender, { block: 'stjo/generosity-band', attributes: props.attributes } ) )
			);
		},
		save: function () { return null; }
	} );
} )( window.wp );
