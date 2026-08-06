/* "Image Focus" sidebar panel for student-story: the cover block's
 * drag-to-focus picker, bound to the stjo_story_image_focus meta ("X% Y%").
 * Positions the featured photo within the card's fixed-height crop (and the
 * story lightbox hero). No build step. Mirrors src/blocks/timeline/focus-panel.js. */
( function ( wp ) {
	var el = wp.element.createElement;
	var registerPlugin = wp.plugins && wp.plugins.registerPlugin;
	var editorPkg = ( wp.editor && wp.editor.PluginDocumentSettingPanel ) ? wp.editor : wp.editPost;
	var PluginDocumentSettingPanel = editorPkg && editorPkg.PluginDocumentSettingPanel;
	var FocalPointPicker = wp.components && wp.components.FocalPointPicker;
	var useSelect = wp.data.useSelect;
	var useDispatch = wp.data.useDispatch;

	if ( ! registerPlugin || ! PluginDocumentSettingPanel || ! FocalPointPicker ) {
		return;
	}

	function parseFocus( value ) {
		var parts = ( value || '' ).trim().split( /\s+/ );
		if ( 2 === parts.length && '%' === parts[ 0 ].slice( -1 ) && '%' === parts[ 1 ].slice( -1 ) ) {
			var x = parseFloat( parts[ 0 ] ) / 100;
			var y = parseFloat( parts[ 1 ] ) / 100;
			if ( isFinite( x ) && isFinite( y ) ) {
				return {
					x: Math.min( Math.max( x, 0 ), 1 ),
					y: Math.min( Math.max( y, 0 ), 1 )
				};
			}
		}
		return { x: 0.5, y: 0.5 };
	}

	function Panel() {
		var postType = useSelect( function ( select ) {
			return select( 'core/editor' ).getCurrentPostType();
		}, [] );
		var meta = useSelect( function ( select ) {
			return select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {};
		}, [] );
		var thumbId = useSelect( function ( select ) {
			return select( 'core/editor' ).getEditedPostAttribute( 'featured_media' );
		}, [] );
		var media = useSelect( function ( select ) {
			return thumbId ? select( 'core' ).getMedia( thumbId ) : null;
		}, [ thumbId ] );
		var editPost = useDispatch( 'core/editor' ).editPost;

		if ( 'student-story' !== postType ) {
			return null;
		}

		var body;
		if ( ! thumbId ) {
			body = el( 'p', {}, 'Set a featured image first, then drag the focus point here to position the photo within the card.' );
		} else if ( ! media ) {
			body = el( 'p', {}, 'Loading image…' );
		} else {
			var sizes = media.media_details && media.media_details.sizes;
			var url = ( sizes && sizes.large && sizes.large.source_url ) || media.source_url;
			body = el( FocalPointPicker, {
				url: url,
				value: parseFocus( meta.stjo_story_image_focus ),
				onChange: function ( v ) {
					editPost( { meta: { stjo_story_image_focus: Math.round( v.x * 100 ) + '% ' + Math.round( v.y * 100 ) + '%' } } );
				}
			} );
		}

		return el(
			PluginDocumentSettingPanel,
			{ name: 'stjo-story-focus', title: 'Image Focus' },
			body
		);
	}

	registerPlugin( 'stjo-story-focus', { render: Panel } );
} )( window.wp );
