/* Editor UI — no build step: plain JS + ServerSideRender preview. The card
   preview in the canvas is not clickable; the sidebar's Preview Lightbox
   button renders the prescribed lightbox template (hero, heading, content,
   actions) from the block's own fields in an editor modal. The block's
   style.css is enqueued in the admin document (inc/blocks.php) so that
   preview matches the frontend. */
( function ( wp ) {
	var el = wp.element.createElement;
	var useState = wp.element.useState;
	var registerBlockType = wp.blocks.registerBlockType;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var MediaUpload = wp.blockEditor.MediaUpload;
	var MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var PanelBody = wp.components.PanelBody;
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var ToggleControl = wp.components.ToggleControl;
	var Button = wp.components.Button;
	var Modal = wp.components.Modal;
	var ServerSideRender = wp.serverSideRender;

	function previewParagraphs( content ) {
		return content.split( /\n+/ ).filter( function ( line ) {
			return line.trim().length;
		} ).map( function ( line, i ) {
			return el( 'p', { key: i }, line.trim() );
		} );
	}

	registerBlockType( 'stjo/lightbox-card', {
		edit: function ( props ) {
			var a = props.attributes;
			var previewState = useState( false );
			var previewOpen = previewState[ 0 ];
			var setPreviewOpen = previewState[ 1 ];

			return el(
				wp.element.Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: 'Lightbox' },
						el( TextareaControl, {
							label: 'Content',
							help: 'Blank line = new paragraph. The card shows the first 20 words as its excerpt.',
							rows: 8,
							value: a.content,
							onChange: function ( v ) { props.setAttributes( { content: v } ); }
						} ),
						el( TextControl, {
							label: 'Link text',
							value: a.linkText,
							onChange: function ( v ) { props.setAttributes( { linkText: v } ); }
						} ),
						el( TextControl, {
							label: 'Link destination (URL)',
							value: a.linkUrl,
							onChange: function ( v ) { props.setAttributes( { linkUrl: v } ); }
						} ),
						el( ToggleControl, {
							label: 'Open link in a new tab',
							checked: !! a.linkNewTab,
							onChange: function ( v ) { props.setAttributes( { linkNewTab: !! v } ); }
						} ),
						el( Button, {
							variant: 'secondary',
							disabled: ! a.content,
							onClick: function () { setPreviewOpen( true ); }
						}, 'Preview Lightbox' )
					),
					el(
						PanelBody,
						{ title: 'Card' },
						el( TextControl, {
							label: 'Title',
							help: 'Used as the card heading and the lightbox heading.',
							value: a.title,
							onChange: function ( v ) { props.setAttributes( { title: v } ); }
						} ),
						el( TextControl, {
							label: 'Card link label',
							value: a.linkLabel,
							onChange: function ( v ) { props.setAttributes( { linkLabel: v } ); }
						} ),
						el( MediaUploadCheck, null, el( MediaUpload, {
							onSelect: function ( media ) {
								props.setAttributes( { mediaId: media.id, mediaUrl: media.url, mediaAlt: media.alt || '' } );
							},
							allowedTypes: [ 'image' ],
							value: a.mediaId,
							render: function ( obj ) {
								return el( Button, { variant: 'secondary', onClick: obj.open },
									a.mediaId ? 'Replace image' : 'Choose image' );
							}
						} ) ),
						a.mediaId ? el( Button, {
							variant: 'link',
							isDestructive: true,
							style: { marginLeft: '12px' },
							onClick: function () { props.setAttributes( { mediaId: 0, mediaUrl: '', mediaAlt: '' } ); }
						}, 'Remove image' ) : null,
						el( 'p', { style: { fontSize: '12px', color: '#757575', marginTop: '8px' } },
							'The image is the card photo and the lightbox hero.' )
					)
				),
				previewOpen ? el(
					Modal,
					{
						title: 'Lightbox preview',
						onRequestClose: function () { setPreviewOpen( false ); },
						className: 'stjo-lightbox-preview-modal',
						style: { maxWidth: '720px', width: '100%', padding: 0 }
					},
					el(
						'div',
						{ className: 'stjo-lightbox stjo-lightbox--preview' },
						a.mediaUrl ? el( 'figure', { className: 'stjo-lightbox__hero' },
							el( 'img', { src: a.mediaUrl, alt: a.mediaAlt || '' } ) ) : null,
						el(
							'div',
							{ className: 'stjo-lightbox__inner' },
							a.title ? el( 'h2', { className: 'stjo-lightbox__title' }, a.title ) : null,
							el( 'div', { className: 'stjo-lightbox__body' }, previewParagraphs( a.content || '' ) ),
							el(
								'div',
								{ className: 'stjo-lightbox__actions' },
								a.linkUrl ? el( 'span', { className: 'stjo-lightbox__cta' },
									a.linkText || 'Learn More' ) : null,
								el( 'button', {
									type: 'button',
									className: 'stjo-lightbox__dismiss',
									onClick: function () { setPreviewOpen( false ); }
								}, 'Close' )
							)
						)
					)
				) : null,
				el( 'div', useBlockProps(), el( ServerSideRender, { block: 'stjo/lightbox-card', attributes: a } ) )
			);
		},
		save: function () { return null; }
	} );
} )( window.wp );
