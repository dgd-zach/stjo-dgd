/* "Image Focus" sidebar panel for timeline-event: the cover block's
 * drag-to-focus picker, bound to the stjo_timeline_image_focus meta
 * ("X% Y%", legacy keyword pairs still parse). No build step. */
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

	var KEYWORD = { left: 0, top: 0, center: 0.5, right: 1, bottom: 1 };

	function parseFocus( value ) {
		var parts = ( value || '' ).trim().split( /\s+/ );
		if ( 2 === parts.length ) {
			var x = '%' === parts[ 0 ].slice( -1 ) ? parseFloat( parts[ 0 ] ) / 100 : KEYWORD[ parts[ 0 ] ];
			var y = '%' === parts[ 1 ].slice( -1 ) ? parseFloat( parts[ 1 ] ) / 100 : KEYWORD[ parts[ 1 ] ];
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

		if ( 'timeline-event' !== postType ) {
			return null;
		}

		var body;
		if ( ! thumbId ) {
			body = el( 'p', {}, 'Set a featured image first, then drag the focus point here to choose which part of the photo stays in view.' );
		} else if ( ! media ) {
			body = el( 'p', {}, 'Loading image…' );
		} else {
			var sizes = media.media_details && media.media_details.sizes;
			var url = ( sizes && sizes.large && sizes.large.source_url ) || media.source_url;
			body = el( FocalPointPicker, {
				url: url,
				value: parseFocus( meta.stjo_timeline_image_focus ),
				onChange: function ( v ) {
					editPost( { meta: { stjo_timeline_image_focus: Math.round( v.x * 100 ) + '% ' + Math.round( v.y * 100 ) + '%' } } );
				}
			} );
		}

		return el(
			PluginDocumentSettingPanel,
			{ name: 'stjo-timeline-focus', title: 'Image Focus' },
			body
		);
	}

	registerPlugin( 'stjo-timeline-focus', { render: Panel } );
} )( window.wp );
