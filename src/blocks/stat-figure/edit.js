/* Editor UI — no build step: plain JS, no JSX. The canvas edits the number in
   place with RichText and wears .stjo-stat__figure, which sections.css loads
   into the editor too (functions.php > stjo_editor_styles), so what you type
   already looks like the frontend. The count-up itself only runs on the front
   end, so the editor shows the final value — which is what you want to read
   while writing it. */
( function ( wp ) {
	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var registerBlockType = wp.blocks.registerBlockType;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var RichText = wp.blockEditor.RichText;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var BlockControls = wp.blockEditor.BlockControls;
	var AlignmentControl = wp.blockEditor.AlignmentControl;
	var PanelBody = wp.components.PanelBody;
	var RangeControl = wp.components.RangeControl;

	var DURATION_HELP =
		'How long the number takes to finish counting. The slowdown is ' +
		'exponential, so a longer duration also means more of the final ' +
		'numbers tick past one at a time instead of being skipped.';

	registerBlockType( 'stjo/stat-figure', {
		edit: function ( props ) {
			var attrs = props.attributes;
			var classes = 'stjo-stat__figure';
			if ( attrs.textAlign ) {
				classes += ' has-text-align-' + attrs.textAlign;
			}
			var blockProps = useBlockProps( { className: classes } );

			return el(
				Fragment,
				{},
				el(
					BlockControls,
					{ group: 'block' },
					el( AlignmentControl, {
						value: attrs.textAlign,
						onChange: function ( next ) {
							props.setAttributes( { textAlign: next || '' } );
						}
					} )
				),
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: 'Count-up animation' },
						el( RangeControl, {
							label: 'Duration (ms)',
							value: attrs.duration,
							onChange: function ( next ) {
								props.setAttributes( { duration: next ? parseInt( next, 10 ) : 4000 } );
							},
							min: 500,
							max: 10000,
							step: 100,
							help: DURATION_HELP,
							__nextHasNoMarginBottom: true,
							__next40pxDefaultSize: true
						} )
					)
				),
				el( RichText, Object.assign( {}, blockProps, {
					tagName: 'p',
					value: attrs.value,
					// Plain text only: the value is parsed as a number on the
					// front end, so inline markup inside it would break counting.
					allowedFormats: [],
					disableLineBreaks: true,
					onChange: function ( next ) {
						props.setAttributes( { value: next } );
					},
					'aria-label': 'Stat figure',
					placeholder: '22,610'
				} ) )
			);
		},

		// Dynamic block: markup comes from render.php.
		save: function () {
			return null;
		}
	} );
}( window.wp ) );
