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
	var ComboboxControl = wp.components.ComboboxControl;
	var useSelect = wp.data.useSelect;
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

			// Published pages for the content-page picker, plus the rendered
			// content of the chosen one so Preview Lightbox can show the real
			// thing rather than a placeholder.
			var pageData = useSelect( function ( select ) {
				// Only pages filed under the "Lightbox Content" page category
				// are offered, so the picker lists purpose-made content instead
				// of the whole page tree. page-category is the theme's
				// pages-only taxonomy (inc/blocks.php).
				var terms = select( 'core' ).getEntityRecords( 'taxonomy', 'page-category', { slug: 'lightbox-content', _fields: 'id' } );
				var termId = terms && terms.length ? terms[ 0 ].id : 0;
				return {
					pages: termId ? select( 'core' ).getEntityRecords( 'postType', 'page', {
						per_page: 100,
						status: 'publish',
						'page-category': [ termId ],
						orderby: 'title',
						order: 'asc',
						_fields: 'id,title'
					} ) : [],
					// Resolved regardless of category, so an already-chosen page
					// keeps previewing even if someone recategorises it.
					chosen: a.contentPageId
						? select( 'core' ).getEntityRecord( 'postType', 'page', a.contentPageId )
						: null,
					// The chosen page's featured image, for the preview's hero —
					// same fallback the frontend uses when the block has no
					// image of its own (render.php).
					chosenMedia: ( function () {
						var rec = a.contentPageId ? select( 'core' ).getEntityRecord( 'postType', 'page', a.contentPageId ) : null;
						return rec && rec.featured_media ? select( 'core' ).getMedia( rec.featured_media ) : null;
					} )()
				};
			}, [ a.contentPageId ] );
			var pageOptions = ( pageData.pages || [] ).map( function ( pg ) {
				return { value: String( pg.id ), label: pg.title.rendered || '(no title)' };
			} );
			var pageHtml = pageData.chosen && pageData.chosen.content ? pageData.chosen.content.rendered : '';
			var heroUrl = a.mediaUrl || ( pageData.chosenMedia && pageData.chosenMedia.source_url ) || '';
			var heroAlt = a.mediaUrl ? ( a.mediaAlt || '' ) : ( ( pageData.chosenMedia && pageData.chosenMedia.alt_text ) || '' );
			var hasBody = !! ( a.contentPageId ? pageHtml : a.content );

			return el(
				wp.element.Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: 'Link' },
						el( TextControl, {
							label: 'Title',
							help: 'If using a "Card" style, this is used as the card heading and the lightbox heading.',
							value: a.title,
							onChange: function ( v ) { props.setAttributes( { title: v } ); }
						} ),
						el( TextControl, {
							label: 'Link label',
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
							'The image is the card photo and the lightbox hero. With no image here, the lightbox falls back to the content page\u2019s featured image.' )
					),
					el(
						PanelBody,
						{ title: 'Lightbox' },
						el( ComboboxControl, {
							className: 'stjo-lightbox-page-picker',
							label: 'Content page',
							help: 'Lists published pages in the \u201cLightbox Content\u201d page category. The lightbox shows the chosen page\u2019s content \u2014 headings, images and all \u2014 and editing the page updates the lightbox. Overrides the Content field below.',
							value: a.contentPageId ? String( a.contentPageId ) : '',
							options: pageOptions,
							onChange: function ( v ) {
								props.setAttributes( { contentPageId: v ? parseInt( v, 10 ) : 0 } );
							},
							__nextHasNoMarginBottom: true
						} ),
						el( TextareaControl, {
							label: 'Content',
							help: a.contentPageId
								? 'Not used while a content page is chosen (except as the card excerpt).'
								: 'Blank line = new paragraph. The card shows the first 20 words as its excerpt.',
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
							disabled: ! hasBody,
							onClick: function () { setPreviewOpen( true ); }
						}, 'Preview Lightbox' )
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
						heroUrl ? el( 'figure', { className: 'stjo-lightbox__hero' },
							el( 'img', { src: heroUrl, alt: heroAlt } ) ) : null,
						el(
							'div',
							{ className: 'stjo-lightbox__inner' },
							a.title ? el( 'h2', { className: 'stjo-lightbox__title' }, a.title ) : null,
							a.contentPageId
								? el( 'div', { className: 'stjo-lightbox__body', dangerouslySetInnerHTML: { __html: pageHtml } } )
								: el( 'div', { className: 'stjo-lightbox__body' }, previewParagraphs( a.content || '' ) ),
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
