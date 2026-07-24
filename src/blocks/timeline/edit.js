/* Editor UI — no build step: plain JS + ServerSideRender preview (view.js does
 * not run in the canvas, so the preview shows every card stacked per decade). */
( function ( wp ) {
	var el = wp.element.createElement;
	var registerBlockType = wp.blocks.registerBlockType;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var ServerSideRender = wp.serverSideRender;

	registerBlockType( 'stjo/timeline', {
		edit: function ( props ) {
			return el(
				'div',
				useBlockProps(),
				el( ServerSideRender, { block: 'stjo/timeline', attributes: props.attributes } )
			);
		},
		save: function () {
			return null;
		}
	} );
} )( window.wp );
