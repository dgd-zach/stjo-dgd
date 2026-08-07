/* Editor UI — no build step: plain JS + ServerSideRender preview. */
( function ( wp ) {
	var el = wp.element.createElement;
	var registerBlockType = wp.blocks.registerBlockType;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var PanelBody = wp.components.PanelBody;
	var SelectControl = wp.components.SelectControl;
	var TextControl = wp.components.TextControl;
	var ToggleControl = wp.components.ToggleControl;
	var useSelect = wp.data.useSelect;
	var decodeEntities = wp.htmlEntities.decodeEntities;
	var ServerSideRender = wp.serverSideRender;

	registerBlockType( 'stjo/stories-section', {
		edit: function ( props ) {
			var a = props.attributes;

			var terms = useSelect( function ( select ) {
				return select( 'core' ).getEntityRecords( 'taxonomy', 'story-category', {
					per_page: -1,
					orderby: 'name',
					order: 'asc',
					_fields: 'id,name,slug'
				} );
			}, [] );

			var options = [ { label: '— Select a category —', value: '' } ].concat(
				( terms || [] ).map( function ( t ) {
					return { label: decodeEntities( t.name ) + ' (' + t.slug + ')', value: t.slug };
				} )
			);

			return el(
				wp.element.Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: 'Section' },
						el( SelectControl, {
							label: 'Story category',
							value: a.category,
							options: options,
							onChange: function ( v ) { props.setAttributes( { category: v } ); }
						} ),
						el( TextControl, {
							label: 'Heading',
							help: 'Leave blank to use the category name.',
							value: a.title,
							onChange: function ( v ) { props.setAttributes( { title: v } ); }
						} ),
						el( ToggleControl, {
							label: 'Class Year filter',
							help: 'Show year pills (graduate sections).',
							checked: !! a.yearFilter,
							onChange: function ( v ) { props.setAttributes( { yearFilter: !! v } ); }
						} ),
						el( ToggleControl, {
							label: 'Cards open a lightbox',
							help: 'Off = cards link to the story (or its external URL).',
							checked: !! a.lightbox,
							onChange: function ( v ) { props.setAttributes( { lightbox: !! v } ); }
						} ),
						el( ToggleControl, {
							label: 'Tinted background',
							checked: !! a.tint,
							onChange: function ( v ) { props.setAttributes( { tint: !! v } ); }
						} ),
						el( ToggleControl, {
							label: 'Curated order',
							help: 'Order by each story’s Order attribute instead of date.',
							checked: !! a.curatedOrder,
							onChange: function ( v ) { props.setAttributes( { curatedOrder: !! v } ); }
						} )
					)
				),
				el(
					'div',
					useBlockProps(),
					a.category
						? el( ServerSideRender, { block: 'stjo/stories-section', attributes: a } )
						: el( 'p', { style: { padding: '1em', background: '#f0f0f0', textAlign: 'center' } }, 'Student Stories Section — choose a story category in the block settings.' )
				)
			);
		},
		save: function () { return null; }
	} );
} )( window.wp );
